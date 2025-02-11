<?php

namespace App\Http\Controllers\Report;

use App\Exports\ExportReportDaily;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DailyReportController extends Controller
{
    public function index()
    {
        return view('report.daily.index');
    }


    public function print(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $period = Carbon::parse($start_date)->translatedFormat('d F Y') . ' - ' . Carbon::parse($end_date)->translatedFormat('d F Y');

        $start_date = Carbon::parse($start_date)->startOfDay();
        $end_date = Carbon::parse($end_date)->endOfDay();

        $transactions = Transaction::with(['details.product'])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('status', 'completed')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->action == 'pdf') {
            $pdf = Pdf::loadView('report.daily.PrintPDF', ['transactions' => $transactions, 'period' => $period]);
            return $pdf->download('Laporan Penjualan Harian - Periode ' . $period . '.pdf');
        } else {
            return Excel::download(new ExportReportDaily($transactions, $period), 'Laporan Penjualan Harian - Periode ' . $period . '.xlsx');
        }
    }
}
