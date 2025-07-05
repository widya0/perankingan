@extends('layouts.apps')

@push('styles')
    
@endpush

@section('content')

<!-- @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif -->

<div class="container-fluid mt-4 px-5">

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Data Kriteria</h4>
        @if(auth()->User()->level == 'Admin')
        <a href="{{ route('TambahKriteria') }}" class="btn btn-primary mb-3">Tambah Kriteria</a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-bordered shadow-sm">
            <thead class="table-success text-white" style="background-color: #00796B;">
                <tr class="text-center">
                    <th>Kriteria</th>
                    <th>Derajat Kepentingan</th>
                    <th>Bobot</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataKriteria as $item)
                <tr class="align-middle text-center">
                    <td class="text-start">{{ $item->nama_kriteria }}</td>
                    <td>{{ $item->bobot_kriteria }}</td>
                    <td>{{ isset($bobot[$item->id_kriteria]) ? number_format($bobot[$item->id_kriteria], 2) : 'Data Tidak Ada' }}</td>
                    <td>
                        @if(auth()->User()->level == 'Admin')
                        <a href="{{ route('EditKriteria', $item->id_kriteria) }}" title="Edit"
                            class="border-0 d-inline-flex align-items-center justify-content-center text-white rounded mt-2"
                            style="background-color: #0d6efd; width: 28px; height: 28px;">
                            <i class="bi bi-pencil" style="font-size: 16px;"></i></a>
                        <form id="hapus-form-{{ $item->id_kriteria }}" action="{{ route('HapusKriteria', $item->id_kriteria) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" title="Hapus"
                            class="border-0 d-inline-flex align-items-center justify-content-center text-white rounded mt-2"
                            onclick="konfirmasiHapusKriteria('{{ $item->nama_kriteria }}', '{{ $item->id_kriteria }}')"
                            style="background-color: #dc3545; width: 28px; height: 28px;">
                            <i class="bi bi-trash" style="font-size: 16px;"></i></a>      
                            </button>
                        </form>
                    @else
                        <a href="{{ route('LihatKriteria', $item->id_kriteria) }}" class="text-dark" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                    @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
   function konfirmasiHapusKriteria(nama_kriteria, id_kriteria) {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Data "' + nama_kriteria + '" akan dihapus permanen!',
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
                document.getElementById('hapus-form-' + id_kriteria).submit();
            }
        });
    }
</script>
@endsection
