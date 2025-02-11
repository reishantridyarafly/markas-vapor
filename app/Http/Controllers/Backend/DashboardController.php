<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $catalog_count = Catalog::all()->count();
        $product_count = Product::all()->count();
        $rating_count = Rating::all()->count();
        $totalRevenue =  DB::table('transactions')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transactions.status', 'completed')
            ->sum('transaction_details.total_price');

        $total_transaction = Transaction::all()->count();
        $total_completed = Transaction::where('status', 'completed')->count();
        $total_process = Transaction::where('status', 'process')->count();
        $total_pending = Transaction::where('status', 'pending')->count();
        $total_failed = Transaction::where('status', 'failed')->count();
        $total_refund = Transaction::where('status', 'refund')->count();

        $transaction = Transaction::orderBy('transaction_date', 'desc')->take(10)->get();

        $monthlyRevenue = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->select(
                DB::raw('YEAR(transactions.transaction_date) as year'),
                DB::raw('MONTH(transactions.transaction_date) as month'),
                DB::raw('SUM(transaction_details.total_price) as total')
            )
            ->where('transactions.status', 'completed')
            ->groupBy(DB::raw('YEAR(transactions.transaction_date)'), DB::raw('MONTH(transactions.transaction_date)'))
            ->get();

        $topBrands = TransactionDetail::join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('catalog', 'products.catalog_id', '=', 'catalog.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->select('catalog.name', DB::raw('COUNT(transaction_details.id) as total_sales'))
            ->where('transactions.status', 'completed')
            ->groupBy('catalog.name')
            ->orderBy('total_sales', 'desc')
            ->take(10)
            ->get();

        $rating = Rating::with('user', 'product')->orderBy('created_at', 'desc')->take(10)->get();

        return view('backend.dashboard.index', compact([
            'catalog_count', 'product_count', 'rating_count', 'totalRevenue',
            'total_transaction', 'total_completed', 'total_process', 'total_pending',
            'total_failed', 'total_refund','transaction', 'monthlyRevenue', 'topBrands', 'rating'
        ]));
    }
}
