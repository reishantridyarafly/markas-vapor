@extends('layouts.backend.main')
@section('title', 'Laporan Harian')
@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">@yield('title')</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">@yield('title')</li>
                    </ul>
                </div>
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <form action="{{ route('daily-report.print') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">Tanggal Awal</label>
                                                <input type="date" name="start_date" id="start_date"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="mb-3">
                                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                                <input type="date" name="end_date" id="end_date"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-success me-2" name="action" value="excel"
                                                    type="submit">
                                                    <i class="ri-file-excel-line"></i> Cetak Excel
                                                </button>
                                                <button class="btn btn-danger" name="action" value="pdf" type="submit">
                                                    <i class="ri-file-pdf-line"></i> Cetak PDF
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>
@endsection
