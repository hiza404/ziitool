<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiController extends Controller
{
    /**
     * API Documentation Page for Developers.
     */
    public function docs(): View
    {
        $seo = SeoService::getMetadata([
            'title' => 'Tài Liệu API Dành Cho Lập Trình Viên (MicroTools REST API)',
            'description' => 'Tích hợp các công cụ tiện ích mạnh mẽ vào ứng dụng của bạn qua REST API: Tính thuế TNCN, tính lãi kép, sinh mã băm, tạo mã QR tự động.',
        ]);

        return view('pages.api-docs', [
            'seo' => $seo,
        ]);
    }

    /**
     * List all available tools via API.
     */
    public function listTools(): JsonResponse
    {
        $tools = config('tools.list', []);
        $categories = config('tools.categories', []);

        return response()->json([
            'status' => 'success',
            'categories' => $categories,
            'tools_count' => count($tools),
            'tools' => array_values($tools),
        ]);
    }

    /**
     * Backend API: Tính Thuế TNCN Việt Nam chuẩn.
     */
    public function calculateTax(Request $request): JsonResponse
    {
        $income = floatval($request->input('income', 0));
        $type = $request->input('type', 'gross'); // 'gross' or 'net'
        $dependents = intval($request->input('dependents', 0));

        $selfDeduction = 11000000;
        $dependentDeduction = 4400000 * max(0, $dependents);

        $insurancePercent = [
            'bhxh' => 0.08,  // 8%
            'bhyt' => 0.015, // 1.5%
            'bhtn' => 0.01,  // 1%
        ];

        $totalInsuranceRate = array_sum($insurancePercent); // 10.5%
        $insurance = $income * $totalInsuranceRate;
        $incomeAfterInsurance = max(0, $income - $insurance);

        $taxableIncome = max(0, $incomeAfterInsurance - $selfDeduction - $dependentDeduction);

        // Biểu thuế lũy tiến từng phần 7 bậc
        $brackets = [
            ['limit' => 5000000, 'rate' => 0.05],
            ['limit' => 5000000, 'rate' => 0.10],
            ['limit' => 8000000, 'rate' => 0.15],
            ['limit' => 14000000, 'rate' => 0.20],
            ['limit' => 20000000, 'rate' => 0.25],
            ['limit' => 28000000, 'rate' => 0.30],
            ['limit' => PHP_FLOAT_MAX, 'rate' => 0.35],
        ];

        $tax = 0;
        $tempTaxable = $taxableIncome;
        $bracketDetails = [];

        foreach ($brackets as $i => $bracket) {
            if ($tempTaxable <= 0) {
                $bracketDetails[] = ['bracket' => $i + 1, 'rate' => $bracket['rate'] * 100, 'tax' => 0];

                continue;
            }
            $taxableInBracket = min($tempTaxable, $bracket['limit']);
            $taxInBracket = $taxableInBracket * $bracket['rate'];
            $tax += $taxInBracket;
            $tempTaxable -= $taxableInBracket;

            $bracketDetails[] = [
                'bracket' => $i + 1,
                'rate' => ($bracket['rate'] * 100).'%',
                'taxable_amount' => $taxableInBracket,
                'tax_amount' => $taxInBracket,
            ];
        }

        $takeHome = $income - $insurance - $tax;

        return response()->json([
            'status' => 'success',
            'data' => [
                'gross_income' => $income,
                'insurance' => [
                    'bhxh' => $income * 0.08,
                    'bhyt' => $income * 0.015,
                    'bhtn' => $income * 0.01,
                    'total' => $insurance,
                ],
                'deductions' => [
                    'self' => $selfDeduction,
                    'dependents' => $dependentDeduction,
                    'total' => $selfDeduction + $dependentDeduction,
                ],
                'taxable_income' => $taxableIncome,
                'tax' => $tax,
                'net_income' => $takeHome,
                'brackets' => $bracketDetails,
            ],
        ]);
    }

    /**
     * Backend API: Tính Lãi Kép Đầu Tư.
     */
    public function calculateInterest(Request $request): JsonResponse
    {
        $principal = floatval($request->input('principal', 10000000));
        $monthlyDeposit = floatval($request->input('monthly_deposit', 1000000));
        $annualRate = floatval($request->input('annual_rate', 8)) / 100;
        $years = intval($request->input('years', 5));

        $monthlyRate = $annualRate / 12;
        $totalMonths = $years * 12;

        $currentBalance = $principal;
        $totalDeposited = $principal;
        $timeline = [];

        for ($month = 1; $month <= $totalMonths; $month++) {
            $interest = $currentBalance * $monthlyRate;
            $currentBalance += $interest + $monthlyDeposit;
            $totalDeposited += $monthlyDeposit;

            if ($month % 12 === 0) {
                $year = $month / 12;
                $timeline[] = [
                    'year' => $year,
                    'total_balance' => round($currentBalance),
                    'total_deposited' => round($totalDeposited),
                    'total_interest' => round($currentBalance - $totalDeposited),
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'initial_principal' => $principal,
                'monthly_deposit' => $monthlyDeposit,
                'annual_rate_percent' => $annualRate * 100,
                'years' => $years,
                'final_balance' => round($currentBalance),
                'total_deposited' => round($totalDeposited),
                'total_profit' => round($currentBalance - $totalDeposited),
                'timeline' => $timeline,
            ],
        ]);
    }

    /**
     * Backend API: Tạo Mã Băm Hashing.
     */
    public function hash(Request $request): JsonResponse
    {
        $text = (string) $request->input('text', '');

        return response()->json([
            'status' => 'success',
            'data' => [
                'input' => $text,
                'md5' => md5($text),
                'sha1' => sha1($text),
                'sha256' => hash('sha256', $text),
                'sha512' => hash('sha512', $text),
                'base64_encoded' => base64_encode($text),
            ],
        ]);
    }
}
