<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class QuizParserService
{
    /**
     * Parse questions from file or text.
     *
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public function parse(UploadedFile|string $input, array $options = []): array
    {
        $apiKey = $options['api_key'] ?? config('services.gemini.key');
        $model = $options['model'] ?? config('services.gemini.model', 'gemini-1.5-flash');
        $mode = $options['mode'] ?? 'auto';

        // 1. Try Gemini AI if requested or available
        $canUseAi = ! empty($apiKey) && ($mode === 'ai' || $mode === 'auto');

        if ($canUseAi) {
            try {
                $aiResult = $this->parseWithGemini($input, $apiKey, $model);
                if (! empty($aiResult['questions'])) {
                    return [
                        'success' => true,
                        'source' => 'gemini',
                        'model' => $model,
                        'total_questions' => count($aiResult['questions']),
                        'title' => $aiResult['title'] ?? 'Bài Thi Trắc Nghiệm (Tạo Bởi AI)',
                        'questions' => $aiResult['questions'],
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini AI parsing failed, falling back to local parser: '.$e->getMessage());
                if ($mode === 'ai') {
                    return [
                        'success' => false,
                        'error' => 'Lỗi kết nối Gemini AI: '.$e->getMessage().'. Vui lòng kiểm tra lại API Key hoặc đổi mô hình (Gemini 1.5 Flash).',
                    ];
                }
            }
        }

        // 2. Local fallback parser
        $localResult = $this->parseWithLocal($input);

        if (! empty($localResult['questions'])) {
            return [
                'success' => true,
                'source' => 'local',
                'total_questions' => count($localResult['questions']),
                'title' => $localResult['title'] ?? 'Bài Thi Trắc Nghiệm',
                'questions' => $localResult['questions'],
            ];
        }

        return [
            'success' => false,
            'error' => 'Không tìm thấy câu hỏi trắc nghiệm hợp lệ trong tài liệu. Vui lòng kiểm tra file có định dạng Câu 1, A, B, C, D hoặc sử dụng chế độ AI Gemini Pro.',
        ];
    }

    /**
     * Parse using Google Gemini API.
     *
     * @return array<string, mixed>
     */
    protected function parseWithGemini(UploadedFile|string $input, string $apiKey, string $model): array
    {
        $prompt = <<<'PROMPT'
Bạn là chuyên gia trích xuất và phân tích đề thi trắc nghiệm hàng đầu.
Nhiệm vụ: Phân tích tài liệu được cung cấp và trích xuất danh sách tất cả các câu hỏi trắc nghiệm kèm đáp án.

QUY TẮC NHẬN DIỆN ĐÁP ÁN ĐÚNG QUAN TRỌNG:
1. ĐẶC ĐIỂM ĐÁP ÁN TRONG TÀI LIỆU: Đáp án đúng thường được:
   - IN ĐẬM (Bold)
   - BÔI MÀU / HIGHLIGHT (màu vàng, đỏ, xanh lá, v.v.)
   - GẠCH CHÂN (Underline)
   - Đánh dấu sao (*) hoặc tích (✓)
2. BẢNG ĐÁP ÁN: Kiểm tra xem có bảng "GỢI Ý ĐÁP ÁN" / "ĐÁP ÁN" ở cuối trang, cuối chương hoặc cuối tài liệu hay không (ví dụ: Câu 1: C, Câu 2: B...). Hãy đối chiếu chính xác số thứ tự câu hỏi với đáp án trong bảng.
3. NẾU KHÔNG CÓ ĐÁP ÁN ĐÁNH DẤU: Hãy tự suy luận và giải để đưa ra đáp án chính xác nhất.
4. Mỗi câu hỏi hãy cung cấp giải thích ngắn gọn, súc tích vì sao đáp án đó đúng.
5. TUYỆT ĐỐI LOẠI BỎ CHÂN TRANG (FOOTER): Không lấy bất kỳ thông tin chân trang, watermark, thông tin người tải (Downloaded by...), email, số trang (Trang 1/10), tên website (Studocu...) vào câu hỏi hoặc đáp án.

ĐỊNH DẠNG ĐẦU RA BẮT BUỘC (JSON THUẦN TÚY):
Trả về duy nhất 1 JSON object với cấu trúc sau:
{
  "title": "Tên tiêu đề bài thi (ví dụ: Trắc nghiệm Tư tưởng Hồ Chí Minh)",
  "questions": [
    {
      "id": 1,
      "question": "Câu 1: Nội dung câu hỏi...",
      "options": {
        "A": "Nội dung lựa chọn A",
        "B": "Nội dung lựa chọn B",
        "C": "Nội dung lựa chọn C",
        "D": "Nội dung lựa chọn D"
      },
      "correct": "A",
      "explanation": "Giải thích ngắn gọn lý do đáp án A là chính xác."
    }
  ]
}
Chỉ trả về JSON hợp lệ, không bọc markdown ```json, không kèm giải thích ngoài JSON.
PROMPT;

        $parts = [];

        if ($input instanceof UploadedFile) {
            $mimeType = $input->getMimeType() ?: 'application/octet-stream';
            $extension = strtolower($input->getClientOriginalExtension());

            if ($extension === 'pdf') {
                $base64 = base64_encode(File::get($input->getRealPath()));
                $parts[] = [
                    'inlineData' => [
                        'mimeType' => 'application/pdf',
                        'data' => $base64,
                    ],
                ];
                $parts[] = ['text' => $prompt];
            } else {
                // Extract text from DOCX, TXT, etc.
                $extractedText = $this->extractTextFromFile($input);
                $parts[] = ['text' => "TÀI LIỆU CẦN TRÍCH XUẤT:\n\n".$extractedText];
                $parts[] = ['text' => $prompt];
            }
        } else {
            $parts[] = ['text' => "TÀI LIỆU CẦN TRÍCH XUẤT:\n\n".$input];
            $parts[] = ['text' => $prompt];
        }

        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $response = Http::timeout(90)->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'contents' => [
                [
                    'parts' => $parts,
                ],
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'temperature' => 0.2,
            ],
        ]);

        if (! $response->successful()) {
            $errData = $response->json();
            $msg = $errData['error']['message'] ?? ('HTTP '.$response->status());
            throw new \RuntimeException($msg);
        }

        $responseData = $response->json();
        $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';

        // Clean any accidental markdown fence
        $cleanJson = trim($rawText);
        $cleanJson = preg_replace('/^```(?:json)?\s*/i', '', $cleanJson);
        $cleanJson = preg_replace('/\s*```$/', '', $cleanJson);

        $decoded = json_decode($cleanJson, true);
        if (! is_array($decoded)) {
            throw new \RuntimeException('Gemini trả về dữ liệu không đúng định dạng JSON.');
        }

        // Support both direct list or object with questions key
        $questions = $decoded['questions'] ?? (isset($decoded[0]['question']) ? $decoded : []);
        $title = $decoded['title'] ?? 'Bài Thi Trắc Nghiệm';

        return [
            'title' => $title,
            'questions' => $this->normalizeQuestions($questions),
        ];
    }

    /**
     * Local regex and layout-based parser.
     *
     * @return array<string, mixed>
     */
    public function parseWithLocal(UploadedFile|string $input): array
    {
        $text = '';
        $title = 'Bài Thi Trắc Nghiệm';

        if ($input instanceof UploadedFile) {
            $text = $this->extractTextFromFile($input);
            $origName = pathinfo($input->getClientOriginalName(), PATHINFO_FILENAME);
            $title = 'Đề Thi: '.str_replace(['_', '-'], ' ', $origName);
        } else {
            $text = $this->cleanDocumentWatermarks($input);
        }

        if (empty(trim($text))) {
            return ['questions' => []];
        }

        // Check if the document has chapters (e.g., 'CHƯƠNG 1:', 'Phần 1:')
        $hasChapters = preg_match('/(?:^|\n)\s*(?:CHƯƠNG|PHẦN)\s+\d+[:\s]+/iu', $text);

        if ($hasChapters) {
            $segments = preg_split('/(?:^|\n)\s*((?:CHƯƠNG|PHẦN)\s+\d+[:\s]+[^\n]+)/iu', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
            $allQuestions = [];
            $globalId = 1;

            for ($i = 1; $i < count($segments); $i += 2) {
                $chapHeading = trim($segments[$i]);
                $chapBody = $segments[$i + 1] ?? '';

                $chapQuestions = $this->parseTextSection($chapBody, $chapHeading, $globalId);
                foreach ($chapQuestions as $q) {
                    $allQuestions[] = $q;
                    $globalId++;
                }
            }

            if (! empty($allQuestions)) {
                return [
                    'title' => $title,
                    'questions' => $this->normalizeQuestions($allQuestions),
                ];
            }
        }

        // Single section parsing
        $questions = $this->parseTextSection($text, null, 1);

        return [
            'title' => $title,
            'questions' => $this->normalizeQuestions($questions),
        ];
    }

    /**
     * Parse a single text section/chapter for questions and answer tables.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function parseTextSection(string $text, ?string $sectionPrefix = null, int $startId = 1): array
    {
        // 1. Check for answer key tables in this section
        $answersMap = $this->extractAnswerTable($text);

        // 2. Separate question text from answer table text
        $qText = trim($text)."\n";
        if (preg_match('/(?:GỢI\s*Ý\s*ĐÁP\s*ÁN|BẢNG\s*ĐÁP\s*ÁN)/iu', $text, $ansPos, PREG_OFFSET_CAPTURE)) {
            $qText = trim(substr($text, 0, $ansPos[0][1]))."\n";
        }

        $questions = [];
        $qPattern = '/(?:^|\n)\s*(?:Câu\s+(\d+)[\s*:\.-]+|(\d+)[\.\)]\s+)([\s\S]*?)(?=(?:\n\s*(?:Câu\s+\d+[\s*:\.-]+|\d+[\.\)]\s+)|\s*\Z))/u';

        if (preg_match_all($qPattern, $qText, $matches, PREG_SET_ORDER)) {
            $currentId = $startId;
            foreach ($matches as $match) {
                $qNumber = ! empty($match[1]) ? (int) $match[1] : (! empty($match[2]) ? (int) $match[2] : $currentId);
                $content = trim($match[3]);

                // Extract options A, B, C, D (and E)
                $optPattern = '/(?:^|\n|\s+)([A-E])[\.\)]\s*([\s\S]*?)(?=(?:^|\n|\s+)[A-E][\.\)]|\n\s*(?:Đáp\s*án|Key|Đ\/a)\s*[:\.]|\Z)/u';

                if (preg_match_all($optPattern, $content, $optMatches, PREG_SET_ORDER)) {
                    $firstOptPos = mb_strpos($content, $optMatches[0][0]);
                    $questionStem = $firstOptPos !== false ? trim(mb_substr($content, 0, $firstOptPos)) : $content;
                    $questionStem = $this->cleanTextSnippet($questionStem);

                    $options = [];
                    $boldOrMarkedCorrect = null;

                    foreach ($optMatches as $opt) {
                        $key = strtoupper(trim($opt[1]));
                        $optText = trim($opt[2]);

                        if (str_starts_with($optText, '*') || str_contains($optText, '✓') || str_contains($optText, '[x]')) {
                            $boldOrMarkedCorrect = $key;
                            $optText = trim(str_replace(['*', '✓', '[x]', '[X]'], '', $optText));
                        }

                        $optText = $this->cleanTextSnippet($optText);

                        $options[$key] = $optText;
                    }

                    // Determine correct answer
                    $correct = 'A';
                    if ($boldOrMarkedCorrect) {
                        $correct = $boldOrMarkedCorrect;
                    } elseif (isset($answersMap[$qNumber])) {
                        $correct = $answersMap[$qNumber];
                    } elseif (preg_match('/(?:Đáp\s*án|Key|Đ\/a)\s*[:\.]\s*([A-E])/iu', $content, $inlineAns)) {
                        $correct = strtoupper($inlineAns[1]);
                    }

                    $prefix = $sectionPrefix ? "[$sectionPrefix] " : '';
                    $questions[] = [
                        'id' => $currentId,
                        'question' => "{$prefix}Câu {$qNumber}: {$questionStem}",
                        'options' => $options,
                        'correct' => $correct,
                        'explanation' => "Đáp án chính xác là {$correct}.",
                    ];

                    $currentId++;
                }
            }
        }

        return $questions;
    }

    /**
     * Extract answer table from text like "GỢI Ý ĐÁP ÁN: Câu 1 C, Câu 2 B...".
     *
     * @return array<int, string>
     */
    protected function extractAnswerTable(string $text): array
    {
        $answers = [];

        // Check for "GỢI Ý ĐÁP ÁN" or "ĐÁP ÁN" blocks
        if (preg_match('/(?:GỢI\s*Ý\s*ĐÁP\s*ÁN|BẢNG\s*ĐÁP\s*ÁN|ĐÁP\s*ÁN)([\s\S]*?)(?:CHƯƠNG|\Z)/u', $text, $ansBlock)) {
            $blockText = $ansBlock[1];

            // Match 'Câu 1 C' or 'Câu 1: C' or '1. C'
            if (preg_match_all('/(?:Câu\s+)?(\d+)[\s*:\.-]+([A-D])\b/u', $blockText, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $qNum = (int) $m[1];
                    $ans = strtoupper($m[2]);
                    $answers[$qNum] = $ans;
                }
            }
        }

        return $answers;
    }

    /**
     * Extract text from uploaded file.
     */
    public function extractTextFromFile(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        if ($ext === 'pdf') {
            // Try pdftotext with layout preserving
            $output = null;
            $code = 0;
            exec('pdftotext -layout '.escapeshellarg($path).' - 2>/dev/null', $output, $code);
            if ($code === 0 && ! empty($output)) {
                return implode("\n", $output);
            }

            // Fallback plain pdftotext
            exec('pdftotext '.escapeshellarg($path).' - 2>/dev/null', $output2, $code2);
            if ($code2 === 0 && ! empty($output2)) {
                return implode("\n", $output2);
            }
        }

        if ($ext === 'docx') {
            $zip = new ZipArchive;
            if ($zip->open($path) === true) {
                $xml = $zip->getFromName('word/document.xml');
                $zip->close();
                if ($xml) {
                    // Replace <w:p> with newlines, mark <w:b/> with bold hint
                    $clean = preg_replace('/<w:b(?:\s+[^>]*)?\/>/i', ' [bold] ', $xml);
                    $clean = preg_replace('/<\/w:p>/i', "\n", $clean);
                    $clean = strip_tags($clean);

                    return html_entity_decode($clean, ENT_QUOTES, 'UTF-8');
                }
            }
        }

        // Default text read
        $raw = File::get($path) ?: '';

        return $this->cleanDocumentWatermarks($raw);
    }

    /**
     * Clean footers, headers, watermarks, emails, and page numbers from document text.
     */
    public function cleanDocumentWatermarks(string $text): string
    {
        $lines = explode("\n", str_replace(["\x0c", "\r"], ["\n", ''], $text));
        $cleanedLines = [];

        foreach ($lines as $line) {
            $trim = trim($line);

            // 1. Skip watermark lines from Studocu / document aggregators
            if (preg_match('/(?:lOMoARcPSD|Studocu|Downloaded\s+by|Downloaded\s+by\s+by|Scan\s+to\s+open)/iu', $trim)) {
                continue;
            }

            // 2. Skip email lines
            if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/u', $trim)) {
                continue;
            }

            // 3. Skip standalone watermark names from PDF footer
            if (preg_match('/^(?:Downloaded|Vuong|Khanh|Anh\s+Linh|Nguy\?n|Khanh\s+Linh\s+Vuong)\b/iu', $trim) && ! preg_match('/(?:Câu|CHƯƠNG|PHẦN|[A-E]\.)/u', $trim)) {
                continue;
            }

            // 4. Skip page number lines ("Trang 1 / 15", "Page 1 of 10", or single digits)
            if (preg_match('/^(?:(?:Trang|Page)\s+\d+(?:\s*(?:\/|of)\s*\d+)?|\d+\s*\/\s*\d+|\d+)$/iu', $trim)) {
                continue;
            }

            // 5. Skip website urls on own lines
            if (preg_match('/^(?:https?:\/\/|www\.)[^\s]+$/iu', $trim)) {
                continue;
            }

            $cleanedLines[] = $line;
        }

        return implode("\n", $cleanedLines);
    }

    /**
     * Clean an individual question stem or option string.
     */
    public function cleanTextSnippet(string $str): string
    {
        $str = preg_replace('/(?:Downloaded\s*(?:by)?|lOMoARcPSD|Studocu|Scan\s*to\s*open).*$/iu', '', $str);
        $str = preg_replace('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/u', '', $str);
        $str = preg_replace('/\([^\)]*@.*?\)/u', '', $str);
        $str = preg_replace('/(?:Trang|Page)\s+\d+(?:\s*(?:\/|of)\s*\d+)?/iu', '', $str);
        $str = preg_replace('/\b(?:Khanh|Vuong|Nguy\?n|Anh Linh)\b/iu', '', $str);
        $str = preg_replace('/\s+/u', ' ', trim($str));

        return $str;
    }

    /**
     * Normalize question objects to guarantee consistent structure.
     *
     * @param  array<int, mixed>  $questions
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeQuestions(array $questions): array
    {
        $normalized = [];
        $seq = 1;

        foreach ($questions as $q) {
            if (! is_array($q)) {
                continue;
            }

            $id = $q['id'] ?? $seq;
            $questionText = trim($q['question'] ?? '');
            if (empty($questionText)) {
                continue;
            }

            $rawOptions = $q['options'] ?? [];
            $options = [];
            if (is_array($rawOptions)) {
                foreach ($rawOptions as $k => $v) {
                    $optKey = strtoupper(trim((string) $k));
                    $options[$optKey] = trim((string) $v);
                }
            }

            // Ensure at least A, B options exist
            if (count($options) < 2) {
                continue;
            }

            $correct = strtoupper(trim((string) ($q['correct'] ?? 'A')));
            if (! isset($options[$correct])) {
                // If correct answer wasn't mapped directly, default to first option key
                $firstKey = array_key_first($options);
                $correct = $firstKey ?: 'A';
            }

            $normalized[] = [
                'id' => (int) $id,
                'question' => $questionText,
                'options' => $options,
                'correct' => $correct,
                'explanation' => trim((string) ($q['explanation'] ?? "Đáp án đúng là {$correct}.")),
            ];

            $seq++;
        }

        return $normalized;
    }

    /**
     * Return preloaded sample questions for instant testing.
     *
     * @return array<string, mixed>
     */
    public function getSampleExam(): array
    {
        $samplePath = resource_path('data/quiz_sample.json');
        if (File::exists($samplePath)) {
            $data = json_decode(File::get($samplePath), true);
            if (is_array($data) && ! empty($data['questions'])) {
                return [
                    'success' => true,
                    'title' => $data['title'] ?? '300 Câu Trắc Nghiệm Tư Tưởng Hồ Chí Minh (Đề Mẫu 40 Câu)',
                    'total_questions' => count($data['questions']),
                    'source' => 'sample',
                    'questions' => $data['questions'],
                ];
            }
        }

        return [
            'success' => false,
            'error' => 'File mẫu không khả dụng.',
        ];
    }
}
