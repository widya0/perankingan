@extends('layouts.apps')

@section('content')

<div class="container-fluid mt-4 mb-5 px-5">
    <h4>{{'Tambah Calon Penerima Bantuan' }}</h4>

    @if($dataCalon->verifikasi === 'Terverifikasi')
        <div class="alert alert-info">Data ini sudah diverifikasi dan tidak bisa diubah.</div>
        <fieldset disabled>
    @endif
    <form id="formUpdateCalon" action="{{ route('UpdateCalon', ['id_kriteria' => $dataCalon->id_alternatif]) }}" method="POST" enctype="multipart/form-data" {{ $dataCalon->verifikasi === 'Terverifikasi' ? 'onsubmit="return false;"' : '' }}>

        @csrf
        @method('PUT')
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nama" id="nama" value="{{ $dataCalon->nama }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">NIK</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nik" id="nik" value="{{ $dataCalon->nik }}" required>
            </div>
        </div>
            
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Dusun</label>
            <div class="col-sm-4">
                <select class="form-select" name="dusun" required>
                    <option value="Bendungan" {{ $dataCalon->dusun == 'Bendungan' ? 'selected' : '' }}>Bendungan</option>
                    <option value="Made" {{ $dataCalon->dusun == 'Made' ? 'selected' : '' }}>Made</option>
                    <option value="Ngampel" {{ $dataCalon->dusun == 'Ngampel' ? 'selected' : '' }}>Ngampel</option>
                </select>
            </div>

            <label class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-4">
                <select class="form-select" name="status" required>
                    <option value="Aktif" {{ $dataCalon->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ $dataCalon->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
        </div>


        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">RT</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="rt" value="{{ $dataCalon->rt}}" required>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">RW</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="rw" value="{{ $dataCalon->rw }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tahun Masuk</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="tahun_masuk" value="{{ $dataCalon->tahun_masuk }}" required>
            </div>
        </div>

            <!-- Kriteria dan Sub-Kriteria -->
        <h5>Penilaian Kriteria</h5>
       @foreach ($dataKriteria as $item)
            <div class="row mb-2">
                <label class="col-sm-4 col-form-label" for="kriteria_{{ $item->id_kriteria }}">{{ $item->nama_kriteria }}</label>
                <div class="col-sm-8">
                    <select name="kriteria[{{ $item->id_kriteria }}]" id="kriteria_{{ $item->id_kriteria }}" class="form-control">
                        @foreach ($item->subKriteria as $subKriteria)
                            <option value="{{ $subKriteria->id_sub_kriteria }}" 
                                @if (in_array($subKriteria->id_sub_kriteria, $dataPenilaian->where('id_kriteria', $item->id_kriteria)->pluck('id_sub_kriteria')->toArray())) selected @endif>
                                {{ $subKriteria->rentang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endforeach


            <!-- Dokumen (KTP, KK, Foto Rumah) -->
           <div class="row mb-2">
                <label class="col-sm-4 col-form-label" for="ktp">KTP</label>
                <input type="file" name="ktp" class="form-control">
                @if ($dataCalon->ktp)
                    <a href="{{ asset('storage/' . $dataCalon->ktp) }}" target="_blank">Lihat KTP</a>
                @endif
            </div>

            <div class="row mb-2">
                <label class="col-sm-4 col-form-label" for="rumah">Foto Rumah</label>
                <input type="file" name="rumah" class="form-control">
                @if ($dataCalon->rumah)
                    <a href="{{ asset('storage/' . $dataCalon->rumah) }}" target="_blank">Lihat Foto Rumah</a>
                @endif
            </div>

            <div class="row mb-2">
                <label class="col-sm-4 col-form-label" for="kk">Kartu Keluarga</label>
                <input type="file" name="kk" class="form-control">
                @if ($dataCalon->kk)
                    <a href="{{ asset('storage/' . $dataCalon->kk) }}" target="_blank">Lihat KK</a>
                @endif
            </div>

            @if($dataCalon->verifikasi === 'Terverifikasi')
                </fieldset>
            @endif
        <div class="text-end">
            <button type="submit" form="formUpdateCalon" class="btn btn-success">Simpan</button>
            <a href="{{ route('datacalon') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>

    @if ($dataCalon->verifikasi !== 'Terverifikasi' && auth()->user()->level === 'Admin')
        <form action="{{ route('verifikasi', $dataCalon->id_alternatif) }}" method="POST" class="text-end mt-3" onsubmit="return confirm('Yakin ingin memverifikasi data ini?')">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2-square me-1"></i> Verifikasi Data
            </button>
        </form>
    @endif

</div>
<script>
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
</script>
@endsection
