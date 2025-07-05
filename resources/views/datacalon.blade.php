@extends('layouts.apps')

@push('styles')
    
@endpush

@section('content')
<div class="container-fluid mt-4 px-5">

    <form action="{{ route('datacalon') }}" method="GET" class="mb-3 d-flex align-items-center gap-2">
        <label for="filter_dusun" class="form-label mb-0">Filter Dusun:</label>
        <select name="filter_dusun" id="filter_dusun" class="form-select w-auto" onchange="this.form.submit()">
            <option value="">Desa Sumberejo</option>
             @foreach($dusunList as $dusun)
                <option value="{{ $dusun }}" {{ $filterDusun == $dusun ? 'selected' : '' }}>
                    {{ $dusun }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Data Kriteria</h4>
        <a href="{{ route('TambahCalon') }}" class="btn btn-primary mb-3">Tambah Calon</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-success text-white" style="background-color: #00796B;">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataCalon as $item)
                <tr class="align-middle text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-start">{{ $item->nama }}</td>
                    <td>{{ 'Dusun '.$item->dusun.', RT. '.$item->rt.', RW. '.$item->rw }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <!-- Edit -->
                        <a href="{{ route('EditCalon', $item->id_alternatif) }}" title="Edit"
                        class="d-inline-flex align-items-center justify-content-center text-white rounded mt-1"
                        style="background-color: #0d6efd; width: 28px; height: 28px;">
                            <i class="bi bi-pencil-square" style="font-size: 16px;"></i>
                        </a>

                        <!-- Hapus -->
                        @if ($item->verifikasi !== 'Terverifikasi')
                            <form id="hapus-form-{{ $item->id_alternatif }}" action="{{ route('HapusCalon', $item->id_alternatif) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" title="Hapus"
                                        onclick="konfirmasiHapus('{{ $item->nama }}', '{{ $item->id_alternatif }}')"
                                        class="border-0 d-inline-block text-white text-center rounded mt-2"
                                        style="background-color: #dc3545; width: 28px; height: 28px;">
                                    <i class="bi bi-trash" style="font-size: 16px;"></i>
                                </button>
                            </form>
                        @else
                            <button title="Tidak Dapat Dihapus"
                                    class="border-0 d-inline-block text-white text-center rounded mt-2"
                                    style="background-color:rgb(162, 162, 162); width: 28px; height: 28px;">
                                <i class="bi bi-trash" style="font-size: 16px;"></i>
                            </button>
                        @endif

                        <form id="verifikasi-form-{{ $item->id_alternatif }}" action="{{ route('verifikasi', $item->id_alternatif) }}" method="PUT" style="display:inline;">
                            @csrf
                            @if (auth()->user()->level === 'Admin')
                                <button type="button" title="{{ $item->verifikasi === 'Terverifikasi' ? 'Sudah Diverifikasi' : 'Belum Diverifikasi' }}"
                                onclick="konfirmasiVerifikasi('{{ $item->nama }}', '{{ $item->id_alternatif }}', '{{ $item->verifikasi }}')"    
                                class="border-0 d-inline-flex align-items-center justify-content-center text-white rounded mt-2"
                                    style="background-color: {{ $item->verifikasi === 'Terverifikasi' ? '#198754' : 'rgb(162, 162, 162)' }}; width: 28px; height: 28px;"
                                    onclick="return confirm('Yakin ingin mengubah status verifikasi?')">
                                    <i class="bi {{ $item->verifikasi === 'Terverifikasi' ? 'bi-check' : 'bi-check2-square' }}"
                                    style="font-size: 16px;"></i>
                                </button>
                            @else
                                {{-- User biasa hanya bisa lihat icon --}}
                                @if ($item->verifikasi === 'Terverifikasi')
                                    <span class="d-inline-flex align-items-center justify-content-center text-white rounded mt-2"
                                        style="background-color: #198754; width: 28px; height: 28px;">
                                        <i class="bi bi-check" style="font-size: 16px;"></i>
                                    </span>
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center text-white rounded mt-2"
                                        style="background-color:rgb(162, 162, 162); width: 28px; height: 28px;">
                                        <i class="bi bi-check2-square" style="font-size: 16px;"></i>
                                    </span>
                                @endif
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function konfirmasiHapus(nama, id) {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Data "' + nama + '" akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary me-2'     
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapus-form-' + id).submit();
            }
        });
    }

    function konfirmasiVerifikasi(nama, id, verifikasi) {

        let pesan = verifikasi === 'Terverifikasi'
            ? 'Data "' + nama + '" akan diubah menjadi BELUM DIVERIFIKASI. Lanjutkan?'
            : 'Data "' + nama + '" akan diubah menjadi TERVERIFIKASI. Lanjutkan?';

        let labelTombol = verifikasi === 'Terverifikasi' ? 'Batalkan Verifikasi' : 'Verifikasi';
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
                document.getElementById('verifikasi-form-' + id).submit();
            }
        });
    }
</script>
@endsection
