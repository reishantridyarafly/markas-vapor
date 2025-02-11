<div class="tab-pane fade" id="delete" role="tabpanel">
    <div class="subscription-plan px-4 pt-4">
        <div class="card">
            <form id="form_delete">
                <div class="card-body">
                    <h6 class="fw-bold">Hapus Akun</h6>
                    <p class="fs-11 text-muted">Apakah anda yakin ingin <strong>menghapus akun ini?</strong>?</p>
                    <div class="row">
                        <div class="mb-3">
                            <input type="password" class="form-control password" id="delete_password"
                                name="delete_password" placeholder="Masukan password">
                            <small class="text-danger errorDeletePassword mt-2"></small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-danger" type="submit" id="delete_account">Hapus Akun</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<script>
    $(document).ready(function() {
        $('#form_delete').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak akan dapat mengembalikannya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        data: $(this).serialize(),
                        url: "{{ route('profile.deleteAccount') }}",
                        type: "POST",
                        dataType: 'json',
                        beforeSend: function() {
                            $('#delete_account').prop('disabled', true);
                            $('#delete_account').text('Proses...')
                        },
                        success: function(response) {
                            if (response.errors && response.errors
                                .delete_password) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.errors.delete_password,
                                });
                            } else if (response.success) {
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
                                    title: "Akun Anda telah berhasil dihapus."
                                });
                                var logoutForm = document
                                    .getElementById('logout-form');
                                logoutForm.submit();
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.error(xhr.status + "\n" + xhr.responseText +
                                "\n" + thrownError);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan, silakan coba lagi nanti.',
                            });
                        },
                        complete: function() {
                            $('#delete_account').prop('disabled', false);
                            $('#delete_account').text('Hapus Akun')
                        }
                    });
                }
            });
        });
    });
</script>
