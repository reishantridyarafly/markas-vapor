<div class="tab-pane fade show active p-4" id="about" role="tabpanel">
    <div class="profile-details mb-5">
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0">Detail Profile:</h5>
        </div>
        <div class="row g-0 mb-4">
            <div class="col-sm-6 text-muted">Nama Lengkap:</div>
            <div class="col-sm-6 fw-semibold">{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</div>
        </div>
        <div class="row g-0 mb-4">
            <div class="col-sm-6 text-muted">No Telepon:</div>
            <div class="col-sm-6 fw-semibold">{{ auth()->user()->telephone }}</div>
        </div>

        <div class="row g-0 mb-4">
            <div class="col-sm-6 text-muted">Email:</div>
            <div class="col-sm-6 fw-semibold">{{ auth()->user()->email }}</div>
        </div>

        <div class="row g-0 mb-4">
            <div class="col-sm-6 text-muted">Tanggal Lahir:</div>
            <div class="col-sm-6 fw-semibold">
                {{ \Carbon\Carbon::parse(auth()->user()->tanggal_lahir)->isoFormat('LL') ?? 'Data belum tersedia' }}
            </div>
        </div>

        <div class="row g-0 mb-4">
            <div class="col-sm-6 text-muted">Jenis Kelamin:</div>
            <div class="col-sm-6 fw-semibold">
                {{ auth()->user()->gender == 'L' ? 'Laki-laki' : (auth()->user()->gender == 'P' ? 'Perempuan' : 'Data belum tersedia') }}

            </div>
        </div>
    </div>
</div>
