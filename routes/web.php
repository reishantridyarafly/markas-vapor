<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/register/kota', [App\Http\Controllers\Auth\RegisterController::class, 'getCity'])->name('register.get-city');

Route::get('/', [App\Http\Controllers\Frontend\BerandaController::class, 'index'])->name('beranda.index');
Route::get('/tentang', [App\Http\Controllers\Frontend\AboutController::class, 'index'])->name('about.index');
Route::get('/faq', [App\Http\Controllers\Frontend\FaqController::class, 'index'])->name('faq.index');

Route::get('/kontak', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('contact-message.index');
Route::post('/kontak', [App\Http\Controllers\Frontend\ContactController::class, 'store'])->name('contact-message.store');

Route::get('/belanja', [App\Http\Controllers\Frontend\ShopController::class, 'index'])->name('shop.index');
Route::get('/belanja/detail/{slug}', [App\Http\Controllers\Frontend\ShopController::class, 'detail'])->name('shop.detail');
Route::get('/belanja/pencarian/', [App\Http\Controllers\Frontend\ShopController::class, 'search'])->name('shop.search');
Route::get('/belanja/katalog/{slug}', [App\Http\Controllers\Frontend\ShopController::class, 'catalog'])->name('shop.catalog');

Route::post('/product-views', [App\Http\Controllers\Frontend\ProductViewController::class, 'store'])->name('productViews.store');

Route::get('/product/{productId}/recommendations', [App\Http\Controllers\Backend\ProductRecommendationController::class, 'showRelatedProducts'])->name('products.recommendations');

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/administrator', [App\Http\Controllers\Backend\AdministratorController::class, 'index'])->name('administrator.index');

    Route::get('/account/recommendations', [App\Http\Controllers\Backend\ProductRecommendationController::class, 'showPersonalizedRecommendations'])->name('account.recommendations');
    Route::get('/analytics/fp-growth', [App\Http\Controllers\Backend\ProductRecommendationController::class, 'adminAnalytics'])->name('admin.analytics');

    Route::get('/keranjang/jumlah', [App\Http\Controllers\Frontend\CartController::class, 'getCartItemCount'])->name('cart.count');
    Route::get('/keranjang/{id}', [App\Http\Controllers\Frontend\CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{id}', [App\Http\Controllers\Frontend\CartController::class, 'addCart'])->name('cart.addCart');
    Route::post('/keranjang/edit/{id}', [App\Http\Controllers\Frontend\CartController::class, 'updateCartItem'])->name('cart.updateCartItem');
    Route::delete('/keranjang/hapus/{id}', [App\Http\Controllers\Frontend\CartController::class, 'deleteCartItem'])->name('cart.deleteCartItem');

    Route::post('/pembayaran', [App\Http\Controllers\Frontend\CheckoutController::class, 'directCheckout'])->name('checkout.directCheckout');
    Route::post('/pembayaran/get-address-details/{id}', [App\Http\Controllers\Frontend\CheckoutController::class, 'getAddressDetails'])->name('checkout.get-address-details');
    Route::post('/pembayaran/check-ongkir', [App\Http\Controllers\Frontend\CheckoutController::class, 'checkOngkir'])->name('checkout.check-ongkir');
    Route::post('/pembayaran/store', [App\Http\Controllers\Frontend\CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/pembayaran/store/keranjang', [App\Http\Controllers\Frontend\CheckoutController::class, 'storeCart'])->name('checkout.storeCart');
    Route::post('/pembayaran/keranjang', [App\Http\Controllers\Frontend\CheckoutController::class, 'cartCheckout'])->name('checkout.cartCheckout');

    Route::get('/profile', [App\Http\Controllers\Backend\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/ubah/password', [App\Http\Controllers\Backend\ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::post('/profile/pengaturan/', [App\Http\Controllers\Backend\ProfileController::class, 'settingsProfile'])->name('profile.settings');
    Route::post('/profile/pengaturan/hapus-foto', [App\Http\Controllers\Backend\ProfileController::class, 'deletePhoto'])->name('profile.deletePhoto');
    Route::post('/profile/hapus/akun', [App\Http\Controllers\Backend\ProfileController::class, 'deleteAccount'])->name('profile.deleteAccount');

    Route::post('/alamat/kota', [App\Http\Controllers\Backend\AddressController::class, 'getCity'])->name('address.get-city');
    Route::get('/alamat', [App\Http\Controllers\Backend\AddressController::class, 'index'])->name('address.index');
    Route::get('/alamat/tambah', [App\Http\Controllers\Backend\AddressController::class, 'create'])->name('address.create');
    Route::post('/alamat', [App\Http\Controllers\Backend\AddressController::class, 'store'])->name('address.store');
    Route::get('/alamat/{id}/edit', [App\Http\Controllers\Backend\AddressController::class, 'edit'])->name('address.edit');
    Route::post('/alamat/{id}', [App\Http\Controllers\Backend\AddressController::class, 'update'])->name('address.update');
    Route::delete('/alamat/{id}', [App\Http\Controllers\Backend\AddressController::class, 'destroy'])->name('address.destroy');

    Route::post('/penilaian', [App\Http\Controllers\Frontend\RatingController::class, 'store'])->name('ratings.store');

    Route::get('/wishlist', [App\Http\Controllers\Frontend\WistlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [App\Http\Controllers\Frontend\WistlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\Frontend\WistlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::get('/transaksi', [App\Http\Controllers\Backend\TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transaksi/detail/{id}', [App\Http\Controllers\Backend\TransactionController::class, 'detail'])->name('transaction.detail');
    Route::post('/transaksi/selesai', [App\Http\Controllers\Backend\TransactionController::class, 'completed'])->name('transaction.completed');
});

Route::middleware(['auth', 'user-access:Pemilik,Administrator'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard.index');
});

Route::middleware(['auth', 'user-access:Pelanggan,Administrator'])->group(function () {
    Route::get('/pengembalian', [App\Http\Controllers\Backend\RefundController::class, 'index'])->name('refund.index');
    Route::post('/pengembalian/tolak', [App\Http\Controllers\Backend\RefundController::class, 'failed'])->name('refund.failed');
    Route::post('/pengembalian/proses', [App\Http\Controllers\Backend\RefundController::class, 'process'])->name('refund.process');
    Route::post('/pengembalian/selesai', [App\Http\Controllers\Backend\RefundController::class, 'completed'])->name('refund.completed');
    Route::delete('/pengembalian/{id}', [App\Http\Controllers\Backend\RefundController::class, 'destroy'])->name('refund.destroy');
    Route::get('/pengembalian/{id}/detail', [App\Http\Controllers\Backend\RefundController::class, 'detail'])->name('refund.detail');

    Route::post('/transaksi/pengembalian', [App\Http\Controllers\Backend\TransactionController::class, 'refund'])->name('transaction.refund');
});

Route::middleware(['auth', 'user-access:Administrator'])->group(function () {
    Route::get('/transaksi/tambah', [App\Http\Controllers\Backend\TransactionController::class, 'create'])->name('transaction.create');
    Route::get('/transaksi/get-product', [App\Http\Controllers\Backend\TransactionController::class, 'getProducts'])->name('transaction.getProducts');

    Route::get('/transaksi/{id}/edit', [App\Http\Controllers\Backend\TransactionController::class, 'edit'])->name('transaction.edit');
    Route::post('/transaksi', [App\Http\Controllers\Backend\TransactionController::class, 'store'])->name('transaction.store');
    Route::delete('/transaksi/{id}', [App\Http\Controllers\Backend\TransactionController::class, 'destroy'])->name('transaction.destroy');
    Route::post('/transaksi/tolak', [App\Http\Controllers\Backend\TransactionController::class, 'failed'])->name('transaction.failed');
    Route::post('/transaksi/proses', [App\Http\Controllers\Backend\TransactionController::class, 'process'])->name('transaction.process');
    Route::post('/transaksi/update/resi', [App\Http\Controllers\Backend\TransactionController::class, 'updateResi'])->name('transaction.updateResi');

    Route::get('/katalog', [App\Http\Controllers\Backend\CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/katalog/tambah', [App\Http\Controllers\Backend\CatalogController::class, 'create'])->name('catalog.create');
    Route::get('/katalog/{id}/edit', [App\Http\Controllers\Backend\CatalogController::class, 'edit'])->name('catalog.edit');
    Route::post('/katalog', [App\Http\Controllers\Backend\CatalogController::class, 'store'])->name('catalog.store');
    Route::delete('/katalog/{id}', [App\Http\Controllers\Backend\CatalogController::class, 'destroy'])->name('catalog.destroy');

    Route::get('/pesan-kontak', [App\Http\Controllers\Backend\ContactController::class, 'index'])->name('contact.index');
    Route::get('/pesan-kontak/{id}/detail', [App\Http\Controllers\Backend\ContactController::class, 'detail'])->name('contact.detail');
    Route::post('/pesan-kontak', [App\Http\Controllers\Backend\ContactController::class, 'store'])->name('contact.store');
    Route::delete('/pesan-kontak/{id}', [App\Http\Controllers\Backend\ContactController::class, 'destroy'])->name('contact.destroy');

    Route::get('/produk', [App\Http\Controllers\Backend\ProductController::class, 'index'])->name('product.index');
    Route::get('/produk/tambah', [App\Http\Controllers\Backend\ProductController::class, 'create'])->name('product.create');
    Route::post('/produk', [App\Http\Controllers\Backend\ProductController::class, 'store'])->name('product.store');
    Route::post('/produk/updateStatus', [App\Http\Controllers\Backend\ProductController::class, 'updateStatus'])->name('product.updateStatus');
    Route::get('/produk/{id}/edit', [App\Http\Controllers\Backend\ProductController::class, 'edit'])->name('product.edit');
    Route::post('/produk/{id}', [App\Http\Controllers\Backend\ProductController::class, 'update'])->name('product.update');
    Route::delete('/produk/{id}', [App\Http\Controllers\Backend\ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/rekening', [App\Http\Controllers\Backend\BankAccountController::class, 'index'])->name('account.index');
    Route::get('/rekening/tambah', [App\Http\Controllers\Backend\BankAccountController::class, 'create'])->name('account.create');
    Route::get('/rekening/{id}/edit', [App\Http\Controllers\Backend\BankAccountController::class, 'edit'])->name('account.edit');
    Route::post('/rekening', [App\Http\Controllers\Backend\BankAccountController::class, 'store'])->name('account.store');
    Route::delete('/rekening/{id}', [App\Http\Controllers\Backend\BankAccountController::class, 'destroy'])->name('account.destroy');

    Route::get('/pelanggan', [App\Http\Controllers\Backend\CustomersController::class, 'index'])->name('customers.index');
    Route::post('/pelanggan', [App\Http\Controllers\Backend\CustomersController::class, 'store'])->name('customers.store');
    Route::post('/pelanggan/updateStatus', [App\Http\Controllers\Backend\CustomersController::class, 'updateStatus'])->name('customers.updateStatus');
    Route::get('/pelanggan/{id}/edit', [App\Http\Controllers\Backend\CustomersController::class, 'edit'])->name('customers.edit');
    Route::delete('/pelanggan/{id}', [App\Http\Controllers\Backend\CustomersController::class, 'destroy'])->name('customers.destroy');

    Route::get('/penilaian', [App\Http\Controllers\Backend\RatingController::class, 'index'])->name('rating.index');
    Route::delete('/penilaian/{id}', [App\Http\Controllers\Backend\RatingController::class, 'destroy'])->name('rating.destroy');
});

Route::middleware(['auth', 'user-access:Pemilik'])->group(function () {
    Route::get('/pengguna', [App\Http\Controllers\Backend\UsersController::class, 'index'])->name('users.index');
    Route::post('/pengguna', [App\Http\Controllers\Backend\UsersController::class, 'store'])->name('users.store');
    Route::post('/pengguna/updateStatus', [App\Http\Controllers\Backend\UsersController::class, 'updateStatus'])->name('users.updateStatus');
    Route::get('/pengguna/{id}/edit', [App\Http\Controllers\Backend\UsersController::class, 'edit'])->name('users.edit');
    Route::delete('/pengguna/{id}', [App\Http\Controllers\Backend\UsersController::class, 'destroy'])->name('users.destroy');

    Route::get('/cetak-laporan/harian', [App\Http\Controllers\Report\DailyReportController::class, 'index'])->name('daily-report.index');
    Route::post('/cetak-laporan/harian/print', [App\Http\Controllers\Report\DailyReportController::class, 'print'])->name('daily-report.print');

    Route::get('/cetak-laporan/bulanan', [App\Http\Controllers\Report\MonthlyReportController::class, 'index'])->name('monthly-report.index');
    Route::post('/cetak-laporan/bulanan/print', [App\Http\Controllers\Report\MonthlyReportController::class, 'print'])->name('monthly-report.print');

    Route::get('/cetak-laporan/tahunan', [App\Http\Controllers\Report\YearlyReportController::class, 'index'])->name('yearly-report.index');
    Route::post('/cetak-laporan/tahunan/print', [App\Http\Controllers\Report\YearlyReportController::class, 'print'])->name('yearly-report.print');
});
