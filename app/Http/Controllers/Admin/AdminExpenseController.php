<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AdminExpenseController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $category  = $request->get('category');

        $query = Expense::whereBetween('expense_date', [$startDate, $endDate]);
        if ($category) {
            $query->where('category', $category);
        }

        $expenses     = $query->orderByDesc('expense_date')->get();
        $totalExpense = $expenses->sum('amount');

        // Monthly breakdown for chart (last 6 months)
        $monthlyData = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = Carbon::now()->subMonths($monthsAgo);
            return [
                'month'  => $date->format('M'),
                'amount' => Expense::whereYear('expense_date', $date->year)
                    ->whereMonth('expense_date', $date->month)
                    ->sum('amount'),
            ];
        });

        // Category breakdown
        $categoryBreakdown = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('admin.expenses.index', compact(
            'expenses', 'totalExpense', 'startDate', 'endDate',
            'category', 'monthlyData', 'categoryBreakdown'
        ));
    }

    public function exportExcel(Request $request, ExcelExportService $excelService)
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            'category'   => ['nullable', 'string', 'in:operasional,bahan,gaji,perawatan,lainnya'],
        ]);

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $category  = $request->get('category');

        $query = Expense::with('creator')->whereBetween('expense_date', [$startDate, $endDate]);
        if ($category) {
            $query->where('category', $category);
        }

        $expenses = $query->orderByDesc('expense_date')->get();

        $headers = [
            'No',
            'Tanggal Pengeluaran',
            'Kategori Biaya',
            'Keperluan / Judul Pengeluaran',
            'Nominal Biaya (AED)',
            'Dicatat Oleh',
            'Catatan Tambahan',
        ];

        $rows = [];
        foreach ($expenses as $idx => $exp) {
            $rows[] = [
                $idx + 1,
                $exp->expense_date ? $exp->expense_date->format('Y-m-d') : '-',
                strtoupper(Expense::categoryLabel($exp->category)),
                $exp->title,
                (float) $exp->amount,
                $exp->creator->name ?? 'Admin',
                $exp->notes ?? '-',
            ];
        }

        $columnTypes = [
            0 => 'int',
            1 => 'date',
            2 => 'center',
            3 => 'string',
            4 => 'currency',
            5 => 'string',
            6 => 'string',
        ];

        $filterDesc = ["Periode: {$startDate} s/d {$endDate}"];
        if ($category) {
            $filterDesc[] = "Kategori: " . Expense::categoryLabel($category);
        }
        $subtitle = implode(' | ', $filterDesc);

        $locale = app()->getLocale();
        if ($locale === 'ar') {
            $baseName = 'بيانات_المصروفات_التشغيلية';
            $sep = '_إلى_';
        } elseif ($locale === 'en') {
            $baseName = 'Operational_Expenses_Data';
            $sep = '_to_';
        } else {
            $baseName = 'Data_Pengeluaran';
            $sep = '_sd_';
        }
        $filename = "{$baseName}_{$startDate}{$sep}{$endDate}";

        return $excelService->download(
            filename: $filename,
            title: 'DATA BIAYA OPERASIONAL & PENGELUARAN',
            subtitle: $subtitle,
            headers: $headers,
            rows: $rows,
            columnTypes: $columnTypes,
            totalColumns: [4],
            currencyCode: 'AED',
            extraMetadata: ['Total Pengeluaran' => 'AED ' . number_format($expenses->sum('amount'), 2)]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'in:operasional,bahan,gaji,perawatan,lainnya'],
            'amount'       => ['required', 'numeric', 'min:1'],
            'expense_date' => ['required', 'date'],
            'notes'        => ['nullable', 'string', 'max:1000'],
        ]);

        Expense::create([
            'title'        => $request->title,
            'category'     => $request->category,
            'amount'       => $request->amount,
            'expense_date' => $request->expense_date,
            'notes'        => $request->notes,
            'created_by'   => Auth::id(),
        ]);

        return back()->with('success', __('Data pengeluaran berhasil ditambahkan!'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'in:operasional,bahan,gaji,perawatan,lainnya'],
            'amount'       => ['required', 'numeric', 'min:1'],
            'expense_date' => ['required', 'date'],
            'notes'        => ['nullable', 'string', 'max:1000'],
        ]);

        $expense->update($request->only('title', 'category', 'amount', 'expense_date', 'notes'));

        return back()->with('success', __('Data pengeluaran berhasil diperbarui!'));
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', __('Data pengeluaran berhasil dihapus.'));
    }
}
