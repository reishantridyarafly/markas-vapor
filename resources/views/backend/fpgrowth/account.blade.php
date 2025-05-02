@extends('layouts.backend.main')
@section('title', 'Rekomendasi Produk Pengguna')
@section('content')
    <main class="nxl-container">
        <div class="nxl-content bg-light">
            <!-- Clean and minimal header -->
            <div class="page-header bg-white shadow-sm mb-4">
                <div class="container">
                    <div class="d-flex align-items-center py-3">
                        <h4 class="mb-0">Rekomendasi Produk</h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"
                                        class="text-decoration-none">Dashboard</a></li>
                                <li class="breadcrumb-item active">Rekomendasi</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="container pb-5">
                <!-- Informative header section -->
                <div class="text-center mb-5">
                    <h3 class="fw-bold mb-3">Produk Yang Mungkin Anda Suka</h3>
                    <p class="text-muted w-75 mx-auto">Rekomendasi khusus berdasarkan pola pembelian Anda. Kami menganalisis
                        riwayat transaksi untuk memberikan saran produk yang paling relevan.</p>
                </div>

                @if (count($recommendedProducts) > 0)
                    <div class="row g-4">
                        @foreach ($recommendedProducts as $recommendation)
                            <div class="col-sm-6 col-lg-3">
                                <div class="card h-100 border-0 rounded-3 shadow-hover">
                                    <div class="position-relative overflow-hidden">
                                        <img src="{{ asset('storage/' . $recommendation['product']->cover_photo) }}"
                                            class="card-img-top" alt="{{ $recommendation['product']->name }}"
                                            style="height: 200px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <div class="badge bg-gradient-primary rounded-pill shadow-sm"
                                                data-bs-toggle="tooltip" title="Tingkat kesesuaian dengan preferensi Anda">
                                                <i class="fas fa-thumbs-up me-1"></i>
                                                {{ number_format($recommendation['confidence'] * 100, 0) }}% Match
                                            </div>
                                        </div>
                                        <div class="card-img-overlay d-flex align-items-end p-0">
                                            <div class="w-100 bg-dark bg-opacity-50 p-3">
                                                <h6 class="card-title text-white mb-0 text-truncate">
                                                    {{ $recommendation['product']->name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            @if ($recommendation['product']->before_price)
                                                <div class="text-decoration-line-through text-muted small">
                                                    Rp
                                                    {{ number_format($recommendation['product']->before_price, 0, ',', '.') }}
                                                </div>
                                            @endif
                                            <div class="text-primary fw-bold h5 mb-0">
                                                Rp {{ number_format($recommendation['product']->after_price, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <p class="card-text text-muted small mb-4">
                                            {{ Str::limit($recommendation['product']->short_description, 80) }}
                                        </p>

                                        <div class="d-grid gap-2">
                                            <a href="{{ route('products.show', $recommendation['product']->slug) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-info-circle me-1"></i> Detail
                                            </a>

                                            @if ($recommendation['product']->stock > 0)
                                                <form action="{{ route('cart.quick-add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id"
                                                        value="{{ $recommendation['product']->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                                        <i class="fas fa-cart-plus me-1"></i> Beli
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-times-circle me-1"></i> Stok Habis
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-shopping-basket fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">Belum Ada Rekomendasi</h5>
                            <p class="text-muted mb-4">Lakukan beberapa pembelian agar kami dapat memberikan rekomendasi
                                yang
                                sesuai dengan selera Anda.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Mulai Berbelanja
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
