<div class="tab-pane fade" id="password" role="tabpanel">
    <div class="subscription-plan px-4 pt-4">
        <div class="card">
            <form id="form_password">
                <div class="card-body">
                    <h6 class="fw-bold">Ubah Password</h6>
                    <div class="my-4 py-2">
                        <div class="row">
                            <div class="mb-3">
                                <label for="old_password" class="form-label">Password Lama</label>
                                <input type="password" class="form-control password" id="old_password"
                                    name="old_password">
                                <small class="text-danger errorOldPassword mt-2"></small>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control password" id="password" name="password">
                                <small class="text-danger errorPassword"></small>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi
                                    Password</label>
                                <input type="password" class="form-control password" id="password_confirmation"
                                    name="password_confirmation">
                                <small class="text-danger errorConfirmPassword mt-2"></small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit" id="simpan_password">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#form_password').submit(function(e) {
            e.preventDefault();
            $.ajax({
                data: $(this).serialize(),
                url: "{{ route('profile.changePassword') }}",
                type: "POST",
                dataType: 'json',
                beforeSend: function() {
                    $('#simpan_password').attr('disable', 'disabled');
                    $('#simpan_password').text('Proses...');
                },
                complete: function() {
                    $('#simpan_password').removeAttr('disable');
                    $('#simpan_password').text('Simpan');
                },
                success: function(response) {
                    if (response.errors) {
                        if (response.errors.old_password) {
                            $('#old_password').addClass('is-invalid');
                            $('.errorOldPassword').html(response.errors.old_password);
                        } else {
                            $('#old_password').removeClass('is-invalid');
                            $('.errorOldPassword').html('');
                        }

                        if (response.errors.password) {
                            $('#new_password').addClass('is-invalid');
                            $('.errorPassword').html(response.errors.password);
                        } else {
                            $('#new_password').removeClass('is-invalid');
                            $('.errorPassword').html('');
                        }

                        if (response.errors.password_confirmation) {
                            $('#password_confirmation').addClass('is-invalid');
                            $('.errorConfirmPassword').html(response.errors
                                .password_confirmation);
                        } else {
                            $('#password_confirmation').removeClass('is-invalid');
                            $('.errorConfirmPassword').html('');
                        }
                    } else {
                        if (response.error_password) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.error_password,
                            })
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: response.success,
                            }).then(function() {
                                top.location.href =
                                    "{{ route('profile.index') }}";
                            });
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                        thrownError);
                }
            });
        });
    });
</script>
