@extends('layouts.apps')

@section('content')
<div class="container-fluid mt-4 px-5">
    <h4>{{ 'Tambah Kriteria' }}</h4>
    <div class="mb-3">
        <button class="btn btn-outline-info btn-sm transition-soft" type="button" data-bs-toggle="collapse" data-bs-target="#catatanKriteria" aria-expanded="false" aria-controls="catatanKriteria">
        ℹ️ Catatan Penilaian
        </button>

        <div class="collapse mt-2" id="catatanKriteria">
            <div class="alert alert-info">
                Pemberian sifat pada kriteria didasarkan pada:
                <ul class="mb-1">
                    <li><strong>Benefit:</strong> semakin tinggi nilainya, semakin baik. Contoh: <em>Jumlah Penghuni Rumah</em>, <em>Sanggup Swadaya</em>.</li>
                    <li><strong>Cost:</strong> semakin rendah nilainya, semakin baik. Contoh: <em>Pendapatan</em>, <em>Tingkat Kerusakan Rumah</em>.</li>
                </ul>
                <br>
                <strong>Bobot</strong> diberikan nilai antara 1 sampai 5 tergantung tingkat kepentingannya. Semakin tinggi nilai bobotnya, semakin penting kriteria tersebut.
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{  isset($dataKriteria) ? route('UpdateKriteria', $dataKriteria->id_kriteria) : route('SimpanKriteria') }}" method="POST">
        @csrf
        @if(isset($dataKriteria))
            @method('PUT')
        @endif

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Kriteria</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nama_kriteria" value="{{  old('nama_kriteria', $dataKriteria->nama_kriteria ?? '') }}" {{ isset($isViewOnly) && $isViewOnly ? 'readonly' : '' }} required>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Sifat</label>
            <div class="col-sm-10">
                <select class="form-select" name="sifat_kriteria" {{ isset($isViewOnly) && $isViewOnly ? 'disabled' : '' }}>
                    <option value="" disabled selected>Pilih sifat...</option>
                    <option value="Benefit" {{ (old('sifat_kriteria', $dataKriteria->sifat_kriteria ?? '') == 'Benefit') ? 'selected' : '' }}>Benefit</option>
                    <option value="Cost" {{ (old('sifat_kriteria', $dataKriteria->sifat_kriteria ?? '') == 'Cost') ? 'selected' : '' }}>Cost</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Bobot</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="bobot_kriteria" value="{{  old('bobot_kriteria', $dataKriteria->bobot_kriteria ?? '') }}" {{ isset($isViewOnly) && $isViewOnly ? 'readonly' : '' }} required>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rentang / Sub-kriteria</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
        @if(!empty($dataSubKriteria) && count($dataSubKriteria) > 0)
            @foreach($dataSubKriteria as $sub)
                <tr>
                    <td><input type="text" class="form-control" value="{{ $sub['rentang'] }}" readonly></td>
                    <td><input type="number" class="form-control" value="{{ $sub['nilai_rentang'] }}" readonly></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="2" class="text-center">Tidak ada data sub-kriteria.</td>
            </tr>
        @endif
    </tbody>
        </table>

        <div class="text-end mt-3">
            <a href="{{ route('datakriteria') }}" class="btn btn-secondary mb-3">Kembali</a>
        </div>
</form>
</div>
@endsection
