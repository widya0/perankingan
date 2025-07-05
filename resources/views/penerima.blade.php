@extends('layouts.apps')

@section('content')
<div class="container-fluid mt-4 mb-5 px-5">
    <h4>Penerima Bantuan</h4>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="mb-0 fw-bold">Penerima berdasarkan :</p>
                <form action="" method="GET" class="mb-3 d-flex align-items-center gap-2">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-auto">
                            <label class="form-label mb-0 me-2">Filter:</label>
                        </div>

                    <div class="col-12 col-md-auto">
                        <select name="filter_dusun" id="filter_dusun" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="">Desa Sumberejo</option>
                            @foreach($dusunList as $dusun)
                                <option value="{{ $dusun }}" {{ $filterDusun == $dusun ? 'selected' : '' }}>
                                    {{ $dusun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-auto">
                        <select name="filter_tahun" id="filter_tahun" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ request('filter_tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-auto">
                        <select name="filter_status" id="filter_status" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            @foreach ($statusList as $status)
                                <option value="{{ $status }}" {{ request('filter_status') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        @if(auth()->User()->level == 'Admin')
                            
        <div>
            <a href="{{ route('PreviewPDFPenerima', ['filter_dusun' => $filterDusun, 'filter_tahun' => $filterTahun, 'filter_status' => $filterStatus]) }}" class="btn btn-warning me-2 my-2">
                <i class="bi bi-download"></i> Unduh Hasil
            </a>
            <!-- Tombol Kirim ke Dashboard -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kirimDashboardModal">
                <i class="bi bi-send"></i> Kirim ke Dashboard
            </button>

        </div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-success text-white" style="background-color: #00796B;">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Nilai Peringkat</th>
                    <th>Dusun</th>
                    <th>Tahun Seleksi</th>
                    <th>Status Pengajuan Bantuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penerima as $data)

                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->alternatif->nama ?? '-' }}</td>
                        <td>{{ number_format($data->nilai_hasil, 4) }}</td>
                        <td>{{ $data->alternatif->dusun ?? '-' }}</td>
                        <td>{{ $data->tahun_seleksi }}</td>
                        <td>
                            @if(auth()->user()->level === 'Admin')
                            <form id="ubahstatus-form-{{ $data->id_penerima_bantuan }}" action="{{ route('UpdateStatusPenerima', $data->id_penerima_bantuan) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <div class="dropdown">
                                    <button class="badge border-0 dropdown-toggle {{ $data->status_penerima === 'Diterima' ? 'bg-success' : 'bg-warning text-dark' }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $data->status_penerima }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
<!-- <button type="submit" name="status_penerima" value="Diajukan"
    class="dropdown-item {{ $data->status_penerima === 'Diajukan' ? 'active' : '' }}"
    onclick="return confirmUbahStatusPenerima({{ $data->id_penerima_bantuan }}, 'Diajukan', '{{ addslashes($data->nama_penerima) }}')">
    Diajukan
</button> -->

                                            <button type="button" data-status="Diajukan"
                                            onclick='konfirmasiUbahStatus(this, {{ $data->id_penerima_bantuan }}, {!! json_encode($data->alternatif->nama) !!})'    
                                            class="dropdown-item {{ $data->status_penerima === 'Diajukan' ? 'active' : '' }}"
                                                style="font-size: 16px;">Diajukan
                                            </button>
                                        </li>
                                        <li>
<!-- <button type="submit" name="status_penerima" value="Diterima"
    class="dropdown-item {{ $data->status_penerima === 'Diterima' ? 'active' : '' }}"
    onclick="return confirmUbahStatusPenerima({{ $data->id_penerima_bantuan }}, 'Diterima', '{{ addslashes($data->nama_penerima) }}')">
    Diterima
</button> -->

                                            <button type="button" data-status="Diterima"
                                            onclick='konfirmasiUbahStatus(this, {{ $data->id_penerima_bantuan }}, {!! json_encode($data->alternatif->nama) !!})'   
                                            class="dropdown-item {{ $data->status_penerima === 'Diterima' ? 'active' : '' }}"
                                                style="font-size: 16px;">Diterima
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                            
                            @else
                            <!-- Kalau bukan Admin, tampilkan teks status biasa (tanpa dropdown & form) -->
                            <span class="badge {{ $data->status_penerima === 'Diterima' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $data->status_penerima }}
                            </span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data.</td>
                    </tr>

                @endforelse
            </tbody>

        </table>
    </div>

</div>
@include('partial.viewpdfpenerima')
@include('partial.modalkirimdashboard')

<script>
    // function confirmUbahStatusPenerima(id, status, nama) {
    //     return confirm('Yakin ingin mengubah status menjadi "' + status + '"?');
    // }
function konfirmasiUbahStatus(button, id, nama) {
    const status = button.getAttribute('data-status');
    const pesan = status === 'Diajukan'
        ? 'Data "' + nama + '" akan diubah menjadi DIAJUKAN. Lanjutkan?'
        : 'Data "' + nama + '" akan diubah menjadi DITERIMA. Lanjutkan?';

    Swal.fire({
        title: 'Anda yakin?',
        text: pesan,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-secondary me-2'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('ubahstatus-form-' + id);

            // Tambah hidden input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status_penerima';
            input.value = status;
            form.appendChild(input);

            form.submit();
        }
    });
}

</script>

@endsection
