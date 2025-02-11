<?php

namespace App\Http\Controllers\Report;

use App\Exports\ExportReportMonthly;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyReportController extends Controller
{
    public function index()
    {
        return view('report.monthly.index');
    }


    public function print(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $start_date = Carbon::create($year, $month, 1)->startOfMonth();
        $end_date = Carbon::create($year, $month, 1)->endOfMonth();

        $period = $start_date->translatedFormat('F Y');

        $transactions = Transaction::with(['details.product'])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('status', 'completed')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->action == 'pdf') {
            $pdf = Pdf::loadView('report.monthly.PrintPDF', ['transactions' => $transactions, 'period' => $period]);
            return $pdf->download('Laporan Penjualan Bulanan - Periode ' . $period . '.pdf');
        } else {
            return Excel::download(new ExportReportMonthly($transactions, $period), 'Laporan Penjualan Bulanan - Periode ' . $period . '.xlsx');
        }
    }
}
