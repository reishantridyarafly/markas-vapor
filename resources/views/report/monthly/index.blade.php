@extends('layouts.backend.main')
@section('title', 'Laporan Bulanan')
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
                                <form action="{{ route('monthly-report.print') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="mb-3">
                                                <label for="month" class="form-label">Bulan</label>
                                                <select name="month" id="month" class="form-select" required>
                                                    <option value="">-- Pilih Bulan --</option>
                                                    <option value="1">Januari</option>
                                                    <option value="2">Februari</option>
                                                    <option value="3">Maret</option>
                                                    <option value="4">April</option>
                                                    <option value="5">Mei</option>
                                                    <option value="6">Juni</option>
                                                    <option value="7">Juli</option>
                                                    <option value="8">Agustus</option>
                                                    <option value="9">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="mb-3">
                                                <label for="year" class="form-label">Tahun</label>
                                                <select name="year" id="year" class="form-select" required>
                                                    <option value="">-- Pilih Tahun --</option>
                                                    <?php
                                                    $currentYear = date('Y');
                                                    for ($i = 0; $i < 15; $i++) {
                                                        $year = $currentYear - $i;
                                                        echo "<option value=\"$year\">$year</option>";
                                                    }
                                                    ?>
                                                </select>
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
