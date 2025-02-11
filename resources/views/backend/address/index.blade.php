@extends('layouts.backend.main')
@section('title', 'Alamat')
@section('content')
    <main class="nxl-container apps-container apps-notes">
        <div class="nxl-content without-header nxl-full-content">
            <!-- [ Main Content ] start -->
            <div class="main-content d-flex">

                <!-- [ Main Area  ] start -->
                <div class="content-area" data-scrollbar-target="#psScrollbarInit">
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
                        <div class="page-header-right ms-auto">
                            <div class="page-header-right-items">
                                <div class="d-flex d-md-none">
                                    <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                        <i class="feather-arrow-left me-2"></i>
                                        <span>Kembali</span>
                                    </a>
                                </div>
                                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                                    <a href="{{ route('address.create') }}" class="btn btn-primary">
                                        <i class="feather-plus me-2"></i>
                                        <span>Tambah @yield('title')</span>
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
                    <!-- [ page-header ] end -->
                    <div class="content-area-body pb-0">
                        <div class="row note-has-grid" id="note-full-container">
                            <!--! BEGIN: [Single Note Item] !-->
                            @forelse ($address as $row)
                                <div
                                    class="col-xxl-6 col-xl-6 col-lg-4 col-sm-6 single-note-item all-category note-important note-tasks">
                                    <div class="card card-body mb-4 stretch stretch-full">
                                        <span class="side-stick"></span>
                                        <h5 class="note-title text-truncate w-75 mb-1"> {{ $row->name }}
                                        </h5>
                                        <p class="fs-11 text-muted note-date">{{ $row->telephone }}</p>
                                        <div class="note-content flex-grow-1">
                                            <p class="text-muted note-inner-content text-truncate-3-line">
                                                {{ $row->detail_address }}</p>
                                            <p class="text-muted note-inner-content text-truncate-3-line">
                                                {{ $row->city_name }}, {{ $row->province_name }}, {{ $row->postal_code }}
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="{{ route('address.edit', $row->id) }}" class="avatar-text avatar-md"><i
                                                    class="feather-edit-2 favourite-note"></i></a>
                                            <span id="btnDelete" data-id="{{ $row->id }}"
                                                class="avatar-text avatar-md"><i
                                                    class="feather-trash-2 remove-note"></i></span>
                                            <div class="ms-auto">
                                                @if ($row->default_address == 0)
                                                    <span class="badge bg-primary-subtle text-primary">Default</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 single-note-item all-category note-important note-tasks">
                                    <div class="card card-body mb-4 stretch stretch-full">
                                        <span class="side-stick"></span>
                                        <h5 class="note-title text-truncate w-75 mb-1">Data tidak tersedia</h5>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- [ Content Area ] end -->
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

            $('body').on('click', '#btnDelete', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus',
                    text: "Apakah kamu yakin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('alamat/"+id+"') }}",
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.message) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sukses',
                                        text: response.message,
                                    }).then(function() {
                                        top.location.href =
                                            "{{ route('address.index') }}";
                                    });
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                                    thrownError);
                            }
                        })
                    }
                })
            })
        });
    </script>
@endsection
