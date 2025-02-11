<?php

namespace App\Http\Controllers\Report;

use App\Exports\ExportReportYearly;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class YearlyReportController extends Controller
{
    public function index()
    {
        return view('report.yearly.index');
    }

    public function print(Request $request)
    {
        $year = $request->year;

        $period = $year;

        $transactions = Transaction::with(['details.product'])
            ->whereYear('created_at', $year)
            ->where('status', 'completed')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->action == 'pdf') {
            $pdf = Pdf::loadView('report.yearly.PrintPDF', ['transactions' => $transactions, 'period' => $period]);
            return $pdf->download('Laporan Penjualan Tahunan - Periode ' . $period . '.pdf');
        } else {
            return Excel::download(new ExportReportYearly($transactions, $period), 'Laporan Penjualan Tahunan - Periode ' . $period . '.xlsx');
        }
    }
}
