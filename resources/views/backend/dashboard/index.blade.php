@extends('layouts.backend.main')
@section('title', 'Dashboard')
@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Selamat datang, <strong>{{ auth()->user()->first_name }}</strong></li>
                    </ul>
                </div>
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <!-- [Mini Card] start -->
                    <div class="col-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-4 col-md-6">
                                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                                            <div class="card-body rounded-3 text-center">
                                                <i class="bi bi-cash-stack fs-3 text-primary"></i>
                                                <div class="fs-4 fw-bolder text-dark mt-3 mb-1">
                                                    {{ 'Rp ' . number_format($totalRevenue, 0, ',', '.') }}</div>
                                                <p
                                                    class="fs-12 fw-medium text-muted text-spacing-1 mb-0 text-truncate-1-line">
                                                    Total Pendapatan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-md-6">
                                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                                            <div class="card-body rounded-3 text-center">
                                                <i class="bi bi-box fs-3 text-primary"></i>
                                                <div class="fs-4 fw-bolder text-dark mt-3 mb-1">{{ $product_count }}</div>
                                                <p
                                                    class="fs-12 fw-medium text-muted text-spacing-1 mb-0 text-truncate-1-line">
                                                    Total Produk</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-md-6">
                                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                                            <div class="card-body rounded-3 text-center">
                                                <i class="bi bi-archive fs-3 text-primary"></i>
                                                <div class="fs-4 fw-bolder text-dark mt-3 mb-1">{{ $catalog_count }}</div>
                                                <p
                                                    class="fs-12 fw-medium text-muted text-spacing-1 mb-0 text-truncate-1-line">
                                                    Total Katalog</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-md-6">
                                        <div class="card stretch stretch-full border border-dashed border-gray-5">
                                            <div class="card-body rounded-3 text-center">
                                                <i class="bi bi-star-fill fs-3 text-primary"></i>
                                                <div class="fs-4 fw-bolder text-dark mt-3 mb-1">{{ $rating_count }}</div>
                                                <p
                                                    class="fs-12 fw-medium text-muted text-spacing-1 mb-0 text-truncate-1-line">
                                                    Total Penilai</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Mini Card] end -->
                    <!-- [Goal Progress] start -->
                    <div class="col-xxl-4">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Transaksi</h5>
                            </div>
                            <div class="card-body custom-card-action">
                                <div class="row g-4">
                                    <div class="col-sm-12">
                                        <div class="px-4 py-3 text-center border border-dashed rounded-3">
                                            <h2 class="fs-13 tx-spacing-1">Total Transaksi</h2>
                                            <div class="fs-13 text-muted text-truncate-1-line">
                                                <strong>{{ $total_transaction }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="px-4 py-3 text-center border border-dashed rounded-3">
                                            <h2 class="fs-13 tx-spacing-1">Selesai</h2>
                                            <div class="fs-13 text-muted text-truncate-1-line">
                                                <strong>{{ $total_completed }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="px-4 py-3 text-center border border-dashed rounded-3">
                                            <h2 class="fs-13 tx-spacing-1">Proses</h2>
                                            <div class="fs-13 text-muted text-truncate-1-line">
                                                <strong>{{ $total_process }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="px-4 py-3 text-center border border-dashed rounded-3">
                                            <h2 class="fs-13 tx-spacing-1">Pending</h2>
                                            <div class="fs-13 text-muted text-truncate-1-line">
                                                <strong>{{ $total_pending }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="px-4 py-3 text-center border border-dashed rounded-3">
                                            <h2 class="fs-13 tx-spacing-1">Gagal</h2>
                                            <div class="fs-13 text-muted text-truncate-1-line">
                                                <strong>{{ $total_failed }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="px-4 py-3 text-center border border-dashed rounded-3">
                                            <h2 class="fs-13 tx-spacing-1">Pengembalian</h2>
                                            <div class="fs-13 text-muted text-truncate-1-line">
                                                <strong>{{ $total_refund }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Goal Progress] end -->

                    <!-- [Marketing Campaign] start -->
                    <div class="col-xxl-8">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Pendapatan Bulanan</h5>
                            </div>
                            <div class="card-body custom-card-action p-0">
                                <div id="campaign-alytics-bar-chart"></div>
                            </div>
                        </div>
                    </div>
                    <!-- [Marketing Campaign] end -->

                    <!-- [Leads Overview] start -->
                    <div class="col-xxl-8">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Brand Terlaris</h5>
                            </div>
                            <div class="card-body custom-card-action">
                                <div id="leads-overview-donut"></div>
                            </div>
                        </div>
                    </div>
                    <!-- [Leads Overview] end -->

                    <!-- [Browser States] start -->
                    <div class="col-xxl-4">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Penilaian Terbaru</h5>
                            </div>
                            <div class="card-body custom-card-action">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Product</th>
                                            <th>Penilaian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($rating as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $row->user->first_name . ' ' . $row->user->last_name }}</td>
                                                <td>{{ $row->product->name }}</td>
                                                <td>{{ $row->rating }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Data tidak tersedia</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- [Browser States] end -->

                    <!-- [Project Remainders] start -->
                    <div class="col-xxl-12">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Transaksi Terbaru</h5>
                            </div>
                            <div class="card-body custom-card-action mb-3">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Kode</th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Pelanggan</th>
                                            <th scope="col">Pembayaran</th>
                                            <th scope="col">Transaksi</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Total</th>
                                            <th scope="col" class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transaction as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $row->code }}</td>
                                                <td>{{ $row->transaction_date }}</td>
                                                <td>{{ $row->customer_name }}</td>
                                                <td>{{ $row->type_payment == 'cash' ? 'Tunai' : 'Transfer' }}</td>
                                                <td>
                                                    @if ($row->type_transaction == 'online')
                                                        <span
                                                            class="badge rounded-pill text-bg-success text-light">Online</span>
                                                    @else<span
                                                            class="badge rounded-pill text-bg-danger text-light">Offline</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($row->status == 'pending')
                                                        <span class="fw-bold text-warning">Pending</span>
                                                    @elseif ($row->status == 'process')
                                                        <span class="fw-bold text-warning">Proses</span>
                                                    @elseif ($row->status == 'completed')
                                                        <span class="fw-bold text-success">Selesai</span>
                                                    @elseif ($row->status == 'refund')
                                                        <span class="fw-bold text-danger">Pengembalian</span>
                                                    @else
                                                        <span class="fw-bold text-danger">Gagal</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ 'Rp ' . number_format($row->total_price, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0)" class="avatar-text avatar-md"
                                                                data-bs-toggle="dropdown" data-bs-offset="0,21">
                                                                <i class="feather feather-more-horizontal"></i>
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="{{ route('transaction.detail', $row->id) }}"
                                                                        class="dropdown-item">
                                                                        <i class="feather feather-eye me-3"></i>
                                                                        <span>Lihat</span>
                                                                    </a>
                                                                <li>
                                                                    <button class="dropdown-item" id="btnDelete"
                                                                        data-id="{{ $row->id }}">
                                                                        <i class="feather feather-trash-2 me-3"></i>
                                                                        <span>Hapus</span>
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Data tidak tersedia</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- [Project Remainders] end -->
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

            $('body').on('click', '#btnDelete', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus',
                    text: "Apakah anda yakin?",
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
                            url: "{{ url('produk/"+id+"') }}",
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.message) {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal
                                                .stopTimer;
                                            toast.onmouseleave = Swal
                                                .resumeTimer;
                                        }
                                    });
                                    Toast.fire({
                                        icon: "success",
                                        title: response.message
                                    });
                                    $('#datatable').DataTable().ajax.reload()
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

            var monthlyRevenue = @json($monthlyRevenue);

            var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var data = new Array(12).fill(0);

            monthlyRevenue.forEach(function(item) {
                data[item.month - 1] = item.total;
            });

            function formatRupiah(angka, prefix) {
                var number_string = angka.toString().replace(/[^,\d]/g, ''),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }


            new ApexCharts(document.querySelector("#campaign-alytics-bar-chart"), {
                chart: {
                    type: "bar",
                    height: 370,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: "Pendapatan",
                    data: data
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        endingShape: "rounded",
                        columnWidth: "30%"
                    }
                },
                dataLabels: {
                    enabled: false,
                    offsetX: -6,
                    style: {
                        fontSize: "12px",
                        colors: ["#fff"]
                    }
                },
                stroke: {
                    show: false,
                    width: 1,
                    colors: ["#fff"]
                },
                colors: ["#DB4540"],
                xaxis: {
                    categories: months,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: "#64748b",
                            fontFamily: "Inter"
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return formatRupiah(value, 'Rp ');
                        },
                        style: {
                            color: "#64748b",
                            fontFamily: "Inter"
                        }
                    }
                },
                grid: {
                    strokeDashArray: 3,
                    borderColor: "#e9ecef"
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return formatRupiah(value, 'Rp ');
                        }
                    },
                    style: {
                        colors: "#64748b",
                        fontFamily: "Inter"
                    }
                },
                legend: {
                    show: false,
                    fontFamily: "Inter",
                    labels: {
                        colors: "#64748b"
                    }
                }
            }).render();

            var topBrands = @json($topBrands);
            var labels = [];
            var series = [];

            topBrands.forEach(function(item) {
                labels.push(item.name);
                series.push(item.total_sales);
            });

            new ApexCharts(document.querySelector("#leads-overview-donut"), {
                chart: {
                    width: 328,
                    type: "donut"
                },
                dataLabels: {
                    enabled: false
                },
                series: series,
                labels: labels,
                colors: ["#3454d1", "#1565c0", "#1976d2", "#1e88e5", "#2196f3", "#42a5f5", "#64b5f6",
                    "#90caf9", "#aad6fa", "#cfe8ff"
                ],
                stroke: {
                    width: 0,
                    lineCap: "round"
                },
                legend: {
                    show: false,
                    position: "bottom",
                    fontFamily: "Inter",
                    fontWeight: 500,
                    labels: {
                        colors: "#A0ACBB",
                        fontFamily: "Inter"
                    },
                    markers: {
                        width: 10,
                        height: 10
                    },
                    itemMargin: {
                        horizontal: 20,
                        vertical: 5
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "85%",
                            labels: {
                                show: false,
                                name: {
                                    show: false,
                                    fontSize: "16px",
                                    colors: "#A0ACBB",
                                    fontFamily: "Inter"
                                },
                                value: {
                                    show: false,
                                    fontSize: "30px",
                                    fontFamily: "Inter",
                                    color: "#A0ACBB",
                                    formatter: function(e) {
                                        return e;
                                    }
                                }
                            }
                        }
                    }
                },
                responsive: [{
                    breakpoint: 380,
                    options: {
                        chart: {
                            width: 280
                        },
                        legend: {
                            show: false
                        }
                    }
                }],
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value;
                        }
                    },
                    style: {
                        colors: "#A0ACBB",
                        fontFamily: "Inter"
                    }
                }
            }).render();
        })
    </script>
@endsection
