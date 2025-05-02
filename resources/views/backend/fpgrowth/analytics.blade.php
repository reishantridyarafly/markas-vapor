@extends('layouts.backend.main')
@section('title', 'Analisis FP Growth')
@section('content')
    <main class="nxl-container">
        <!-- Clean modern header -->
        <div class="bg-white shadow-sm mb-4">
            <div class="container">
                <div class="d-flex align-items-center py-3">
                    <h4 class="mb-0 text-primary">@yield('title')</h4>
                    <nav aria-label="breadcrumb" class="ms-auto">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"
                                    class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item active">@yield('title')</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Analysis Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Transaksi</h6>
                            <h3 class="mb-0">{{ $limit }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Periode Analisis</h6>
                            <h3 class="mb-0">{{ $dateRange }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Min. Support</h6>
                            <h3 class="mb-0">{{ $minSupport }}%</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Min. Confidence</h6>
                            <h3 class="mb-0">{{ $minConfidence }}%</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-filter text-primary me-2"></i>
                        <h5 class="mb-0">Parameter Analisis</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.analytics') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-muted">Minimum Support (%)</label>
                            <input type="number" class="form-control" id="min_support" name="min_support"
                                value="{{ $minSupport }}" min="0.1" max="100" step="0.1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Minimum Confidence (%)</label>
                            <input type="number" class="form-control" id="min_confidence" name="min_confidence"
                                value="{{ $minConfidence }}" min="1" max="100" step="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Rentang Tanggal</label>
                            <input type="text" class="form-control date-range-picker" id="date_range" name="date_range"
                                value="{{ $dateRange }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-muted">Limit Data</label>
                            <input type="number" class="form-control" id="limit" name="limit"
                                value="{{ $limit }}" min="100" max="10000" step="100">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary float-end">
                                <i class="fas fa-sync-alt me-1"></i> Update Analisis
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Association Rules Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-network text-primary me-2"></i>
                            <h5 class="mb-0">Hasil Analisis Asosiasi</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="rules-table">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">#</th>
                                    <th class="py-3">Jika Membeli</th>
                                    <th class="py-3">Maka Membeli</th>
                                    <th class="py-3">Support (%)</th>
                                    <th class="py-3">Confidence (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($formattedRules as $index => $rule)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><span class="badge bg-light text-dark">{{ $rule['antecedent'] }}</span></td>
                                        <td><span class="badge bg-light text-dark">{{ $rule['consequent'] }}</span></td>
                                        <td><span class="text-primary">{{ $rule['support'] }}</span></td>
                                        <td><span class="text-success">{{ $rule['confidence'] }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Tidak ada aturan asosiasi yang ditemukan. Coba sesuaikan parameter analisis.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-percentage text-primary"></i>
                                <div class="ms-2">
                                    <h6 class="mb-0">Support</h6>
                                    <small class="text-muted">Frekuensi kemunculan kombinasi produk dalam transaksi</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-line text-success"></i>
                                <div class="ms-2">
                                    <h6 class="mb-0">Confidence</h6>
                                    <small class="text-muted">Tingkat kepercayaan hubungan antar produk</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        // Inisialisasi date range picker
        $(document).ready(function() {
            $('.date-range-picker').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' | '
                },
                autoUpdateInput: false
            });

            $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' | ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            // Inisialisasi DataTable
            $('#rules-table').DataTable({
                pageLength: 25,
                order: [
                    [4, 'desc']
                ] // Urutkan berdasarkan confidence
            });
        });
    </script>
@endsection
