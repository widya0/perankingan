@extends('layouts.apps')

@section('content')

<div class="container-fluid mt-4 mb-5 px-5">
    <h4>{{'Tambah Calon Penerima Bantuan' }}</h4>
    <form action="{{ route('TambahCalon') }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="nama" value="{{ $dataCalon['nama'] ?? '' }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">NIK</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="nik" value="{{ $dataCalon['nik'] ?? '' }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Dusun</label>
            <div class="col-sm-4">
                <select class="form-select" name="dusun">
                    <option value="Bendungan" {{ (isset($calon) && $calon['dusun'] == 'Bendungan') ? 'selected' : '' }}>Bendungan</option>
                    <option value="Made" {{ (isset($calon) && $calon['dusun'] == 'Made') ? 'selected' : '' }}>Made</option>
                    <option value="Ngampel" {{ (isset($calon) && $calon['dusun'] == 'Ngampel') ? 'selected' : '' }}>Ngampel</option>
                </select>
            </div>
        <label class="col-sm-2 col-form-label">Status</label>
        <div class="col-sm-4">
            <select class="form-select" name="status">
                <option value="Aktif" {{ (isset($calon) && $calon['status'] == 'Aktif') ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ (isset($calon) && $calon['status'] == 'Tidak Aktif') ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">RT</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="rt" value="{{ $dataCalon['rt'] ?? '' }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">RW</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="rw" value="{{ $dataCalon['rw'] ?? '' }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Tahun Masuk</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="tahun_masuk" value="{{ $dataCalon['tahun_masuk'] ?? '' }}" required>
        </div>
    </div>

    <h5>Penilaian Kriteria</h5>
    @foreach($dataKriteria as $item)
    <div class="row mb-2">
        <label class="col-sm-4 col-form-label">{{ $item->nama_kriteria }}</label>
        <div class="col-sm-8">
            <select class="form-select" name="kriteria[{{ $item->id_kriteria }}]">
                <option value="">-- Pilih --</option>
                @foreach($item->subKriteria as $sub)
                    <option value="{{ $sub->id_sub_kriteria }}" style="color: black; background-color: white;"
                        {{ isset($dataCalon['kriteria'][$item->id_kriteria]) && $dataCalon['kriteria'][$item->id_kriteria] == $sub->id_sub_kriteria ? 'selected' : '' }}>
                        {{ $sub->rentang }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    @endforeach

    <h5>Dokumen</h5>
    <div class="row mb-2">
        <label class="col-sm-2 col-form-label">KTP</label>
        <div class="col-sm-10">
            <input type="file" class="form-control" name="ktp" accept="image/*" capture="environment" value="{{ $dataCalon['ktp'] ?? '' }}">
        </div>
    </div>
    <div class="row mb-2">
        <label class="col-sm-2 col-form-label">Foto Rumah</label>
        <div class="col-sm-10">
            <input type="file" class="form-control" name="rumah" accept="image/*" capture="environment" value="{{ $dataCalon['rumah'] ?? '' }}">
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">KK</label>
        <div class="col-sm-10">
            <input type="file" class="form-control" name="kk" accept="image/*" capture="environment" value="{{ $dataCalon['kk'] ?? '' }}">
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('datacalon') }}" class="btn btn-secondary">Batal</a>
    </div>

</form>

</div>
@endsection
