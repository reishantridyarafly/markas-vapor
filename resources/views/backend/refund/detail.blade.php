@extends('layouts.backend.main')
@section('title', 'Detail Pengembalian')
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
                        <li class="breadcrumb-item"><a href="{{ route('refund.index') }}">Pengembalian</a></li>
                        <li class="breadcrumb-item">@yield('title')</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Kembali</span>
                            </a>
                        </div>
                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">
                                    <span class="d-block mb-2">Detail Transaksi :</span>
                                </h5>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-2 fw-medium">Invoice</div>
                                <div class="col-lg-10"><a href="{{ route('transaction.detail', $refund->transaction->id) }}"
                                        target="_blank">{{ $refund->transaction->code }}</a>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-2 fw-medium">Nama Pelanggan</div>
                                <div class="col-lg-10">{{ $refund->user->first_name . ' ' . $refund->user->last_name }}
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-2 fw-medium">Email</div>
                                <div class="col-lg-10">{{ $refund->user->email }}</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-2 fw-medium">No Telepon</div>
                                <div class="col-lg-10">{{ $refund->user->telephone }}</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-2 fw-medium">Alasan</div>
                                <div class="col-lg-10">{{ $refund->reason }}</div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-2 fw-medium">Status</div>
                                <div class="col-lg-10">
                                    @if ($refund->status == 'pending')
                                        <span class="fw-bold text-warning">Pending</span>
                                    @elseif ($refund->status == 'process')
                                        <span class="fw-bold text-warning">Proses</span>
                                    @elseif ($refund->status == 'completed')
                                        <span class="fw-bold text-success">Selesai</span>
                                    @elseif ($refund->status == 'refund')
                                        <span class="fw-bold text-danger">Pengembalian</span>
                                    @else
                                        <span class="fw-bold text-danger">Ditolak</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card card-body general-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold mb-0">
                                    <span class="d-block mb-2">Bukti Foto :</span>
                                </h5>
                                @if (auth()->user()->type == 'Administrator')
                                    <div class="d-flex">
                                        @if ($refund->status == 'pending')
                                            <a href="javascript:void(0);" id="btnFailed" data-id="{{ $refund->id }}"
                                                class="btn btn-sm btn-danger me-2">Tolak</a>
                                            <a href="javascript:void(0);" id="btnProcess" data-id="{{ $refund->id }}"
                                                class="btn btn-sm btn-warning me-2">Proses</a>
                                        @endif
                                        @if ($refund->status == 'process')
                                            <a href="javascript:void(0);" id="btnCompleted" data-id="{{ $refund->id }}"
                                                class="btn btn-sm btn-success">Selesai</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    @foreach ($refund->refundProofs as $proof)
                                        <div class="col-3 mb-4">
                                            <a href="{{ asset('storage/uploads/refunds/' . $proof->file_refund) }}"
                                                target="_blank">
                                                <img class="w-100"
                                                    src="{{ asset('storage/uploads/refunds/' . $proof->file_refund) }}"
                                                    alt="Bukti Pengembalian - {{ $refund->transaction->code }}">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '#btnFailed', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Tolak',
                    text: "Apakah anda yakin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, tolak!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('refund.failed') }}",
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: response.message,
                                }).then(function() {
                                    window.location.href = window.location.href;
                                });
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log(xhr.status + "\n" + xhr.responseText +
                                    "\n" +
                                    thrownError);
                            }
                        })
                    }
                })
            })

            $('body').on('click', '#btnProcess', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Proses',
                    text: "Apakah anda yakin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, proses!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('refund.process') }}",
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: response.message,
                                }).then(function() {
                                    window.location.href = window.location.href;
                                });
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log(xhr.status + "\n" + xhr.responseText +
                                    "\n" +
                                    thrownError);
                            }
                        })
                    }
                })
            })

            $('body').on('click', '#btnCompleted', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Selesai',
                    text: "Apakah anda yakin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, selesai!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('refund.completed') }}",
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: response.message,
                                }).then(function() {
                                    window.location.href = window.location.href;
                                });
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log(xhr.status + "\n" + xhr.responseText +
                                    "\n" +
                                    thrownError);
                            }
                        })
                    }
                })
            })
        })
    </script>
@endsection
