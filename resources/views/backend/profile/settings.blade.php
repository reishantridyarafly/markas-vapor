<div class="tab-pane fade" id="settings" role="tabpanel">
    <div class="subscription-plan px-4 pt-4">
        <div class="card">
            <form id="form_settings">
                <div class="card-body">
                    <h6 class="fw-bold">Pengaturan Profile</h6>
                    <div class="my-4 py-2">
                        <div class="row">
                            <div class="mb-3">
                                <label for="name" class="form-label">Photo</label>
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" alt="{{ auth()->user()->name }}" id="photoPreview"
                                        style="width: 100px; height: 100px; margin-right: 15px"
                                        src="{{ auth()->user()->avatar == '' ? 'https://ui-avatars.com/api/?background=random&name=' . auth()->user()->first_name . ' ' . auth()->user()->last_name : asset('storage/avatar/' . auth()->user()->avatar) }}">
                                    <button type="button" class="btn btn-danger btn-sm mt-1" id="deletePhoto">Hapus
                                        Foto</button>
                                </div>
                                <small style="display: block; margin-top: 10px;">Maksimal ukuran 5 Mb.</small>
                                <input type="file" name="photo" id="photo" class="form-control mt-2"
                                    onchange="previewPhoto(this)">
                                <div class="invalid-feedback errorPhoto"></div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label class="form-label" for="first_name">Nama Depan</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control"
                                        value="{{ auth()->user()->first_name }}">
                                    <small class="text-danger errorFirstName mt-2"></small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label class="form-label" for="last_name">Nama Belakang</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control"
                                        value="{{ auth()->user()->last_name }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="telephone">No Telepon</label>
                                    <input type="number" id="telephone" name="telephone" class="form-control"
                                        value="{{ auth()->user()->telephone }}">
                                    <small class="text-danger errorTelephone mt-2"></small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        value="{{ auth()->user()->email }}">
                                    <small class="text-danger errorEmail mt-2"></small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3" id="datepicker4">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" id="birth_date"
                                        value="{{ auth()->user()->birth_date }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="Password">Jenis Kelamin</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="L" {{ auth()->user()->gender == 'L' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="P" {{ auth()->user()->gender == 'P' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="bio">Bio</label>
                                <textarea name="bio" id="bio" rows="3" class="form-control">{{ auth()->user()->bio }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit" id="simpan_settings">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewPhoto(input) {
        var photoPreview = document.getElementById('photoPreview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#form_settings').submit(function(e) {
            e.preventDefault();
            $.ajax({
                data: new FormData(this),
                url: "{{ route('profile.settings') }}",
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $('#simpan_settings').attr('disable', 'disabled');
                    $('#simpan_settings').text('Proses...');
                },
                complete: function() {
                    $('#simpan_settings').removeAttr('disable');
                    $('#simpan_settings').text('Simpan');
                },
                success: function(response) {
                    if (response.errors) {
                        if (response.errors.photo) {
                            $('#photo').addClass('is-invalid');
                            $('.errorPhoto').html(response.errors.photo);
                        } else {
                            $('#photo').removeClass('is-invalid');
                            $('.errorPhoto').html('');
                        }

                        if (response.errors.first_name) {
                            $('#first_name').addClass('is-invalid');
                            $('.errorFirstName').html(response.errors.first_name);
                        } else {
                            $('#first_name').removeClass('is-invalid');
                            $('.errorFirstName').html('');
                        }

                        if (response.errors.email) {
                            $('#email').addClass('is-invalid');
                            $('.errorEmail').html(response.errors.email);
                        } else {
                            $('#email').removeClass('is-invalid');
                            $('.errorEmail').html('');
                        }

                        if (response.errors.telephone) {
                            $('#telephone').addClass('is-invalid');
                            $('.errorTelephone').html(response.errors.telephone);
                        } else {
                            $('#telephone').removeClass('is-invalid');
                            $('.errorTelephone').html('');
                        }
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.success,
                        }).then(function() {
                            top.location.href = "{{ route('profile.index') }}";
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                        thrownError);
                }
            });
        });

        $('body').on('click', '#deletePhoto', function() {
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
                        url: "{{ route('profile.deletePhoto') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: response.success,
                                }).then(function() {
                                    top.location.href =
                                        "{{ route('profile.index') }}";
                                });
                            } else {
                                if (response.errors.photo) {
                                    $('#photo').addClass('is-invalid');
                                    $('.errorPhoto').html(response.errors.photo);
                                } else {
                                    $('#photo').removeClass('is-invalid');
                                    $('.errorPhoto').html('');
                                }
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.error(xhr.status + "\n" + xhr.responseText +
                                "\n" + thrownError);
                        }
                    });
                }
            });
        });
    });
</script>
