@extends('layouts.backend.main')
@section('title', 'Rekomendasi Produk Pengguna')
@section('content')
    <main class="nxl-container">
        <div class="nxl-content bg-light">
            <!-- Clean and minimal header -->
            <div class="page-header bg-white shadow-sm mb-4">
                <div class="container">
                    <div class="d-flex align-items-center py-3">
                        <h4 class="mb-0 fw-bold text-primary">Rekomendasi Produk</h4>
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
                    <h3 class="fw-bold mb-3 text-primary">Produk Yang Mungkin Anda Suka</h3>
                    <p class="text-muted w-75 mx-auto">
                        <i class="fas fa-magic me-2"></i>
                        Rekomendasi khusus berdasarkan pola pembelian Anda. Kami menganalisis riwayat transaksi untuk
                        memberikan saran produk yang paling relevan.
                    </p>
                </div>

                @if (count($recommendedProducts) > 0)
                    <div class="row g-4">
                        @foreach ($recommendedProducts as $recommendation)
                            <div class="col-sm-6 col-lg-3">
                                <div class="card h-100 border-0 rounded-4 shadow-sm hover-scale transition-300">
                                    <div class="position-relative overflow-hidden">
                                        <img src="{{ asset('storage/uploads/cover/' . $recommendation['product']->cover_photo) }}"
                                            class="card-img-top" alt="{{ $recommendation['product']->name }}"
                                            style="height: 220px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <div class="badge bg-primary bg-gradient rounded-pill shadow"
                                                data-bs-toggle="tooltip" title="Tingkat kesesuaian dengan preferensi Anda">
                                                <i class="fas fa-star me-1"></i>
                                                {{ number_format($recommendation['confidence'] * 100, 0) }}% Match
                                            </div>
                                        </div>
                                        <div class="card-img-overlay d-flex align-items-end p-0">
                                            <div class="w-100 bg-dark bg-opacity-75 p-3 blur-effect">
                                                <h6 class="card-title text-white mb-0 text-truncate fw-semibold">
                                                    {{ $recommendation['product']->name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            @if ($recommendation['product']->before_price)
                                                <div class="text-decoration-line-through text-muted small mb-1">
                                                    <i class="fas fa-tag me-1"></i>Rp
                                                    {{ number_format($recommendation['product']->before_price, 0, ',', '.') }}
                                                </div>
                                            @endif
                                            <div class="text-primary fw-bold h5 mb-0">
                                                <i class="fas fa-coins me-1"></i>Rp
                                                {{ number_format($recommendation['product']->after_price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid">
                                            <a href="{{ route('shop.detail', $recommendation['product']->slug) }}"
                                                class="btn btn-primary btn-sm rounded-pill hover-lift">
                                                <i class="fas fa-eye me-1"></i> Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-box-open fa-4x text-primary opacity-50"></i>
                            </div>
                            <h5 class="fw-bold text-primary mb-3">Belum Ada Rekomendasi</h5>
                            <p class="text-muted mb-4 w-75 mx-auto">
                                Lakukan beberapa pembelian agar kami dapat memberikan rekomendasi yang sesuai dengan selera
                                Anda.
                            </p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4 hover-lift">
                                <i class="fas fa-shopping-cart me-2"></i>Mulai Berbelanja
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
