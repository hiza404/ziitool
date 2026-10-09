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
        $apiKey = ! empty($options['api_key']) ? $options['api_key'] : config('services.gemini.key');
        $model = ! empty($options['model']) ? $options['model'] : config('services.gemini.model', 'gemini-2.0-flash');
        $mode = $options['mode'] ?? 'auto';

        if ($input instanceof UploadedFile) {
            $ext = strtolower($input->getClientOriginalExtension());
            if ($ext !== 'pdf') {
                return [
                    'success' => false,
                    'error' => 'Hệ thống chỉ hỗ trợ tải lên định dạng file PDF. Nếu bạn có file Word (.docx), vui lòng lưu sang file PDF (Save as PDF trong Word) hoặc dán trực tiếp nội dung đề thi vào ô văn bản.',
                ];
            }
        }

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
                Log::warning('Gemini AI parsing with '.$model.' failed, trying fallback: '.$e->getMessage());

                // Fallback attempt with gemini-1.5-flash if primary model was 2.0
                if ($model !== 'gemini-1.5-flash') {
                    try {
                        $aiResult = $this->parseWithGemini($input, $apiKey, 'gemini-1.5-flash');
                        if (! empty($aiResult['questions'])) {
                            return [
                                'success' => true,
                                'source' => 'gemini',
                                'model' => 'gemini-1.5-flash',
                                'total_questions' => count($aiResult['questions']),
                                'title' => $aiResult['title'] ?? 'Bài Thi Trắc Nghiệm (Tạo Bởi AI)',
                                'questions' => $aiResult['questions'],
                            ];
                        }
                    } catch (\Throwable $fallbackEx) {
                        Log::warning('Gemini 1.5 Flash fallback also failed: '.$fallbackEx->getMessage());
                    }
                }

                if ($mode === 'ai') {
                    return [
                        'success' => false,
                        'error' => 'Lỗi kết nối Gemini AI: '.$e->getMessage().'. Vui lòng kiểm tra lại kết nối mạng hoặc thử lại sau giây lát.',
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

        // Global answer map and explanations from answer table or solutions
        $answersMap = $this->extractAnswerTable($text);
        $explanationsMap = $this->extractExplanations($text);

        // Separate question text from answer table / solutions section to avoid treating solutions as questions
        $qText = $text;
        if (preg_match('/(?:GỢI\s*Ý\s*ĐÁP\s*ÁN|BẢNG\s*ĐÁP\s*ÁN|ĐÁP\s*ÁN\s*CHI\s*TIẾT|LỜI\s*GIẢI\s*CHI\s*TIẾT|HƯỚNG\s*DẪN\s*GIẢI)/iu', $text, $ansPos, PREG_OFFSET_CAPTURE)) {
            $qText = trim(substr($text, 0, $ansPos[0][1]));
        } elseif (preg_match('/(?:\n\s*(?:(?:Câu|C)\s*)?1[\.\)]\s+[A-D]\s*\n\s*(?:(?:Câu|C)\s*)?2[\.\)]\s+[A-D]\s*\n)/u', $text, $seqPos, PREG_OFFSET_CAPTURE)) {
            $qText = trim(substr($text, 0, $seqPos[0][1]));
        }

        // Check if the document has chapters (e.g., 'CHƯƠNG 1:', 'Phần I:', 'Phần 1:')
        $hasChapters = preg_match('/(?:^|\n)\s*(?:CHƯƠNG|PHẦN)\s+(?:\d+|[IVXLCDM]+)[:\s\.]+/iu', $qText);

        if ($hasChapters) {
            $segments = preg_split('/(?:^|\n)\s*((?:CHƯƠNG|PHẦN)\s+(?:\d+|[IVXLCDM]+)[:\s\.]+[^\n]+)/iu', $qText, -1, PREG_SPLIT_DELIM_CAPTURE);
            $allQuestions = [];
            $globalId = 1;

            for ($i = 1; $i < count($segments); $i += 2) {
                $chapHeading = trim($segments[$i]);
                $chapBody = $segments[$i + 1] ?? '';

                $chapQuestions = $this->parseTextSection($chapBody, $chapHeading, $globalId, $answersMap, $explanationsMap);
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
        $questions = $this->parseTextSection($qText, null, 1, $answersMap, $explanationsMap);

        return [
            'title' => $title,
            'questions' => $this->normalizeQuestions($questions),
        ];
    }

    /**
     * Parse a single text section/chapter for questions and answer tables.
     *
     * @param  array<int, string>  $answersMap
     * @param  array<int, string>  $explanationsMap
     * @return array<int, array<string, mixed>>
     */
    protected function parseTextSection(string $text, ?string $sectionPrefix = null, int $startId = 1, array $answersMap = [], array $explanationsMap = []): array
    {
        // 1. Check for answer key tables in this section if not already passed
        if (empty($answersMap)) {
            $answersMap = $this->extractAnswerTable($text);
        }
        if (empty($explanationsMap)) {
            $explanationsMap = $this->extractExplanations($text);
        }

        // 2. Separate question text from answer table text
        $qText = trim($text)."\n";
        if (preg_match('/(?:GỢI\s*Ý\s*ĐÁP\s*ÁN|BẢNG\s*ĐÁP\s*ÁN|ĐÁP\s*ÁN\s*CHI\s*TIẾT|LỜI\s*GIẢI\s*CHI\s*TIẾT)/iu', $text, $ansPos, PREG_OFFSET_CAPTURE)) {
            $qText = trim(substr($text, 0, $ansPos[0][1]))."\n";
        }

        $questions = [];
        $qPattern = '/(?:^|\n)\s*(?:\[bold\]\s*)*(?:(?:Câu|C)\s*(\d+)[\s*:\.-]+|(\d+)[\.\)]\s+)([\s\S]*?)(?=(?:\n\s*(?:\[bold\]\s*)*(?:(?:Câu|C)\s*\d+[\s*:\.-]+|\d+[\.\)]\s+)|\s*\Z))/iu';

        if (preg_match_all($qPattern, $qText, $matches, PREG_SET_ORDER)) {
            $currentId = $startId;
            foreach ($matches as $match) {
                $qNumber = ! empty($match[1]) ? (int) $match[1] : (! empty($match[2]) ? (int) $match[2] : $currentId);
                $content = trim($match[3]);

                // Extract options A, B, C, D (and E)
                // Note: negative lookbehind ensures mathematical constants like '+ C.' or '= C.' are not matched as option C
                $optPattern = '/(?:^|(?<![\+\-\=\/\*\(\^])[\s\t]+)(?:\[bold\]\s*)?([A-E])[\.\)]\s*([\s\S]*?)(?=(?<![\+\-\=\/\*\(\^])[\s\t]+(?:\[bold\]\s*)?[A-E][\.\)]|\n\s*(?:Đáp\s*án|Key|Đ\/a)\s*[:\.]|\Z)/u';

                $optMatches = [];
                preg_match_all($optPattern, $content, $optMatches, PREG_SET_ORDER);

                // Fallback for lowercase a), b), c), d) if fewer than 2 uppercase options found
                if (count($optMatches) < 2) {
                    $lowerOptPattern = '/(?:^|(?<![\+\-\=\/\*\(\^])[\s\t]+)(?:\[bold\]\s*)?([a-e])[\.\)]\s*([\s\S]*?)(?=(?<![\+\-\=\/\*\(\^])[\s\t]+(?:\[bold\]\s*)?[a-e][\.\)]|\n\s*(?:Đáp\s*án|Key|Đ\/a)\s*[:\.]|\Z)/u';
                    preg_match_all($lowerOptPattern, $content, $optMatches, PREG_SET_ORDER);
                }

                if (count($optMatches) >= 2) {
                    $firstOptPos = mb_strpos($content, $optMatches[0][0]);
                    $questionStem = $firstOptPos !== false ? trim(mb_substr($content, 0, $firstOptPos)) : $content;
                    $questionStem = str_replace('[bold]', '', $questionStem);
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

                        $optText = str_replace('[bold]', '', $optText);
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

                    $explanation = $explanationsMap[$qNumber] ?? "Đáp án chính xác là {$correct}.";

                    $prefix = $sectionPrefix ? "[$sectionPrefix] " : '';
                    $questions[] = [
                        'id' => $currentId,
                        'question' => "{$prefix}Câu {$qNumber}: {$questionStem}",
                        'options' => $options,
                        'correct' => $correct,
                        'explanation' => $explanation,
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

        // 1. Check for "GỢI Ý ĐÁP ÁN" or "BẢNG ĐÁP ÁN" blocks
        if (preg_match('/(?:GỢI\s*Ý\s*ĐÁP\s*ÁN|BẢNG\s*ĐÁP\s*ÁN|ĐÁP\s*ÁN\s*CHI\s*TIẾT|BẢNG\s*TRA\s*ĐÁP\s*ÁN)([\s\S]*?)(?:HƯỚNG\s*DẪN|LỜI\s*GIẢI|CHƯƠNG|\Z)/iu', $text, $ansBlock)) {
            $blockText = $ansBlock[1];

            // Match 'Câu 1 C' or 'Câu 1: C' or '1. C' or table format '1 \t C \t 11 \t D'
            if (preg_match_all('/(?:(?:Câu|C)\s*)?(\d+)[\s*:\.\|\t\n-]+(?:\[bold\]\s*)?([A-Da-d])\b/u', $blockText, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $qNum = (int) $m[1];
                    $ans = strtoupper($m[2]);
                    $answers[$qNum] = $ans;
                }
            }
        }

        // 2. Also check if explanations section contains "Câu X ... Đáp án / Chọn [A-D]"
        if (count($answers) < 5) {
            if (preg_match_all('/(?:^|\n)\s*(?:\[bold\]\s*)*(?:Câu|C)\s*(\d+)[\s\S]*?(?:Đáp\s*án|Chọn)\s*[:\.]?\s*([A-Da-d])\b/iu', $text, $solMatches, PREG_SET_ORDER)) {
                foreach ($solMatches as $sm) {
                    $qNum = (int) $sm[1];
                    if (! isset($answers[$qNum])) {
                        $answers[$qNum] = strtoupper($sm[2]);
                    }
                }
            }
        }

        // 3. Check for standalone sequence of answers at the bottom: e.g. 1. A \n 2. C \n 3. B ...
        if (count($answers) < 5) {
            if (preg_match_all('/(?:^|\n)\s*(?:(?:Câu|C)\s*)?(\d+)[\.\)]\s+([A-D])\s*(?=\n|$)/iu', $text, $seqMatches, PREG_SET_ORDER)) {
                if (count($seqMatches) >= 4) {
                    foreach ($seqMatches as $sm) {
                        $qNum = (int) $sm[1];
                        $answers[$qNum] = strtoupper($sm[2]);
                    }
                }
            }
        }

        return $answers;
    }

    /**
     * Extract step-by-step explanations from "LỜI GIẢI CHI TIẾT" or "HƯỚNG DẪN GIẢI".
     *
     * @return array<int, string>
     */
    protected function extractExplanations(string $text): array
    {
        $explanations = [];

        if (preg_match('/(?:LỜI\s*GIẢI\s*CHI\s*TIẾT|HƯỚNG\s*DẪN\s*GIẢI)([\s\S]*)/iu', $text, $solBlock)) {
            $blockText = $solBlock[1];
            $pattern = '/(?:^|\n)\s*(?:\[bold\]\s*)*(?:Câu|C)\s*(\d+)[\s\S]*?(?:\[bold\]\s*)*Lời\s*giải\s*\n([\s\S]*?)(?=(?:\n\s*(?:\[bold\]\s*)*(?:Câu|C)\s*\d+|\Z))/iu';
            if (preg_match_all($pattern, $blockText, $m, PREG_SET_ORDER)) {
                foreach ($m as $item) {
                    $qNum = (int) $item[1];
                    $exp = preg_replace('/\s+/', ' ', trim(str_replace('[bold]', '', $item[2])));
                    if (! empty($exp)) {
                        $explanations[$qNum] = $exp;
                    }
                }
            }
        }

        return $explanations;
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
                return $this->cleanDocumentWatermarks(implode("\n", $output));
            }

            // Fallback plain pdftotext
            exec('pdftotext '.escapeshellarg($path).' - 2>/dev/null', $output2, $code2);
            if ($code2 === 0 && ! empty($output2)) {
                return $this->cleanDocumentWatermarks(implode("\n", $output2));
            }
        }

        if ($ext === 'docx') {
            // 1. Try python3 extractor for MathType OLE equations
            $pythonText = $this->extractDocxWithPython($path);
            if (! empty($pythonText)) {
                return $this->cleanDocumentWatermarks($pythonText);
            }

            // 2. Fallback to ZipArchive pure PHP extraction
            $zip = new ZipArchive;
            if ($zip->open($path) === true) {
                $xml = $zip->getFromName('word/document.xml');
                $zip->close();
                if ($xml) {
                    $xml = preg_replace('/<w:tab(?:\s+[^>]*)?\/?>/i', "\t", $xml);
                    $xml = preg_replace('/<w:(?:br|cr)(?:\s+[^>]*)?\/?>/i', "\n", $xml);
                    $xml = preg_replace('/<\/w:tc>/i', "\t", $xml);
                    $xml = preg_replace('/<\/w:tr>/i', "\n", $xml);
                    $xml = preg_replace('/<\/w:p>/i', "\n", $xml);
                    $xml = preg_replace('/<w:b(?:\s+[^>]*)?\/>/i', ' [bold] ', $xml);
                    $xml = preg_replace('/<w:(?:color|highlight)(?:\s+[^>]*)?\/>/i', ' [bold] ', $xml);
                    $clean = strip_tags($xml);
                    $clean = html_entity_decode($clean, ENT_QUOTES, 'UTF-8');

                    return $this->cleanDocumentWatermarks($clean);
                }
            }
        }

        // Default text read
        $raw = File::get($path) ?: '';

        return $this->cleanDocumentWatermarks($raw);
    }

    /**
     * Extract DOCX text and MathType OLE formulas using python3 and olefile.
     */
    protected function extractDocxWithPython(string $path): ?string
    {
        $pythonScript = <<<'PY'
import sys, zipfile, io, re, html

try:
    import olefile
except ImportError:
    sys.exit(1)

path = sys.argv[1]
try:
    with zipfile.ZipFile(path) as zf:
        rels = {}
        try:
            rels_xml = zf.read("word/_rels/document.xml.rels").decode("utf-8", errors="ignore")
            rels = dict(re.findall(r'Id="(\w+)"[^>]*Target="([^"]+)"', rels_xml))
        except Exception:
            pass

        def get_formula(ole_bytes):
            try:
                ole = olefile.OleFileIO(io.BytesIO(ole_bytes))
                content = ole.openstream("Equation Native").read()
                hdr_len = int.from_bytes(content[:4], "little")
                mtef = content[hdr_len:]
                idx = mtef.find(b"MT Extra\x00")
                payload = mtef[idx + len(b"MT Extra\x00"):] if idx != -1 else mtef[50:]
                chars = re.findall(rb"([\x20-\x7e])\x00", payload)
                s = b"".join(chars).decode("latin1", errors="ignore")
                s = re.sub(r"^[A-E]+", "", s)
                return s.strip()
            except Exception:
                return ""

        cache = {}
        for rid, target in rels.items():
            if "embeddings/" in target:
                try:
                    cache[rid] = get_formula(zf.read("word/" + target))
                except Exception:
                    pass

        doc_xml = zf.read("word/document.xml").decode("utf-8", errors="ignore")

        def replace_obj(m):
            obj_xml = m.group(0)
            rid_m = re.search(r'<o:OLEObject[^>]+r:id="([^"]+)"', obj_xml)
            if rid_m and rid_m.group(1) in cache and cache[rid_m.group(1)]:
                return " " + cache[rid_m.group(1)] + " "
            return " "

        doc_xml = re.sub(r"<w:object[\s\S]*?<\/w:object>", replace_obj, doc_xml)
        doc_xml = re.sub(r"<w:tab(?:\s+[^>]*)?\/?>", "\t", doc_xml)
        doc_xml = re.sub(r"<w:(?:br|cr)(?:\s+[^>]*)?\/?>", "\n", doc_xml)
        doc_xml = re.sub(r"<\/w:tc>", "\t", doc_xml)
        doc_xml = re.sub(r"<\/w:tr>", "\n", doc_xml)
        doc_xml = re.sub(r"<\/w:p>", "\n", doc_xml)
        doc_xml = re.sub(r"<w:b(?:\s+[^>]*)?\/>", " [bold] ", doc_xml)
        text = html.unescape(re.sub(r"<[^>]+>", "", doc_xml))
        sys.stdout.write(text)
except Exception:
    sys.exit(2)
PY;

        $cmd = 'python3 -c '.escapeshellarg($pythonScript).' '.escapeshellarg($path).' 2>/dev/null';
        $output = null;
        $code = 0;
        exec($cmd, $output, $code);

        if ($code === 0 && ! empty($output)) {
            return implode("\n", $output);
        }

        return null;
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
        $str = preg_replace('/\n\s*(?:Circle|Mark|Read|Rewrite|PHẦN|CHƯƠNG|SECTION|Ghi\s*chú)[\s\S]*$/iu', '', $str);
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
