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
                <input type="text" class="form-control" name="nama_kriteria" value="{{  old('nama_kriteria', $dataKriteria->nama_kriteria ?? '') }}" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Sifat</label>
            <div class="col-sm-10">
                <select class="form-select" name="sifat_kriteria">
                    <option value="" disabled selected>Pilih sifat...</option>
                    <option value="Benefit" {{ (old('sifat_kriteria', $dataKriteria->sifat_kriteria ?? '') == 'Benefit') ? 'selected' : '' }}>Benefit</option>
                    <option value="Cost" {{ (old('sifat_kriteria', $dataKriteria->sifat_kriteria ?? '') == 'Cost') ? 'selected' : '' }}>Cost</option>

                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Derajat Kepentingan</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name="bobot_kriteria" value="{{  old('bobot_kriteria', $dataKriteria->bobot_kriteria ?? '') }}" required>
            </div>
        </div>

        <div class="alert alert-warning">
            <strong>Perhatian:</strong> Rentang nilai maksimal untuk setiap kriteria adalah 5.
        </div>


        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rentang / Sub-kriteria</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($dataSubKriteria) && is_array($dataSubKriteria) && count($dataSubKriteria) > 0)
                @foreach($dataSubKriteria as $index => $sub)
                    @if(isset($sub['rentang']) && isset($sub['nilai_rentang']))
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="dataSubKriteria[{{$index}}][rentang]" value="{{ old("dataSubKriteria.$index.rentang", $sub['rentang'] ?? '') }}">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="dataSubKriteria[{{$index}}][nilai_rentang]" value="{{ old("dataSubKriteria.$index.nilai_rentang", $sub['nilai_rentang'] ?? '') }}">
                        </td>
                    </tr>
                    @endif
                @endforeach
            @else
                {{-- Tambahkan input kosong jika belum ada sub-kriteria --}}
                @foreach(range(0, 4) as $index) <!-- Buat 5 sub-kriteria kosong -->
                    <tr>
                        <td><input type="text" class="form-control" name="dataSubKriteria[{{$index}}][rentang]" value="{{ old("dataSubKriteria.$index.rentang") }}"></td>
                        <td><input type="number" class="form-control" name="dataSubKriteria[{{$index}}][nilai_rentang]" value="{{ old("dataSubKriteria.$index.nilai_rentang") }}"></td>
                    </tr>

                @endforeach
            @endif

            </tbody>
        </table>

        <div class="text-end mt-3">
            <button type="submit" class="btn btn-success mb-3">Simpan</button>
            <a href="{{ route('datakriteria') }}" class="btn btn-secondary mb-3">Batal</a>
        </div>
</form>
</div>
@endsection
