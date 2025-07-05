@extends('layouts.apps')

@section('content')
<div class="container-fluid mt-4 mb-5 px-5">
    <h4>Perankingan</h4>
    <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
        <div>
            <label>Perankingan berdasarkan :</label>
            <form action="{{ route('perankingan') }}" method="GET" class="mb-3 d-flex align-items-center gap-2">
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
        </div>
        <div>
            @if(auth()->User()->level == 'Admin')
            <div class="d-flex flex-wrap gap-3 justify-content-end">
                <form id="pengajuan-form-{{ $periode }}" action="{{ route('AjukanPeringkat', ['periode' => $periode]) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="button" title="Ajukan"
                    onclick="konfirmasiPengajuan('{{ $periode }}')"    
                    class="btn btn-primary mt-2">
                        <i class="bi bi-send" style="font-size: 16px;"></i>Ajukan Penerima Bantuan
                    </button>
                </form>

                <a href="{{ route('PreviewPDFRanking', ['periode' => $periode, 'filter_dusun' => $filterDusun]) }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1" 
                style="display: inline-flex; align-items: center; gap: 0.5rem; padding-top: 0.375rem; padding-bottom: 0.375rem; line-height: 1;">
                    <i class="bi bi-download"></i> Unduh Hasil
                </a>
                <a href="{{ route('HitungPeringkat') }}" class="btn btn-warning d-inline-flex align-items-center gap-1"
                style="display: inline-flex; align-items: center; gap: 0.5rem; padding-top: 0.375rem; padding-bottom: 0.375rem; line-height: 1;">
                    <i class="bi bi-calculator"></i> Hitung Peringkat
                </a>
            </div>
            @endif
        </div>

        
    </div>
</br>
<div class="mb-4">
    <button class="btn fw-semibold mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#matriksKeputusan" aria-expanded="false" aria-controls="matriksKeputusan"
        style="background-color: #20746D; color: white;">
        Hasil Matriks Keputusan
    </button>

    <div class="collapse" id="matriksKeputusan">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>Alternatif</th>
                        @foreach ($kriteria as $k)
                            <th>{{ $k->nama_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailSAW['matriks_keputusan'] as $idAlt => $nilaiKriteria)
                        <tr>
                            <td>{{ $alternatif[$idAlt]->nama ?? 'ID: '.$idAlt }}</td>
                            @foreach ($kriteria as $k)
                                <td>{{ $nilaiKriteria[$k->id_kriteria] ?? '-' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mb-4">
    <button class="btn fw-semibold mb-2" 
    type="button" 
    data-bs-toggle="collapse" 
    data-bs-target="#matriksNormalisasi" 
    aria-expanded="false" 
    aria-controls="matriksNormalisasi"
    style="background-color: #20746D; color: white;">
       Hasil Matriks Normalisasi
    </button>

    <div class="collapse" id="matriksNormalisasi">
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>Alternatif</th>
                        @foreach ($kriteria as $k)
                            <th>{{ $k->nama_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailSAW['matriks_normalisasi'] as $idAlt => $nilaiKriteria)
                        <tr>
                            <td>{{ $alternatif[$idAlt]->nama ?? 'ID: '.$idAlt }}</td>
                            @foreach ($kriteria as $k)
                                <td>{{ number_format($nilaiKriteria[$k->id_kriteria] ?? 0, 2) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="mb-4">
    <button class="btn fw-semibold mb-2"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#prosesPreferensi"
        aria-expanded="false"
        aria-controls="prosesPreferensi"
        style="background-color: #20746D; color: white;">
        Proses Hitung Preferensi
    </button>

    <div class="collapse" id="prosesPreferensi">
        <div class="border p-3 rounded shadow-sm bg-white">
            @foreach ($detailSAW['preferensi'] as $idAlt => $total)
                <div class="mb-3">
                    <h6 class="text-success fw-semibold">
                        {{ $alternatif[$idAlt]->nama ?? 'ID: '.$idAlt }}
                    </h6>
                    <div class="ps-3">
                        @php $rincian = []; @endphp
                        @foreach ($kriteria as $k)
                            @php
                                $idKriteria = $k->id_kriteria;
                                $normal = $detailSAW['matriks_normalisasi'][$idAlt][$idKriteria] ?? 0;
                                $bobotKriteria = $bobot[$idKriteria] ?? 0;
                                $rincian[] = "({$normal} × {$bobotKriteria})";
                            @endphp
                        @endforeach
                        <p class="mb-0">
                            Preferensi = {{ implode(' + ', $rincian) }}
                            = <strong>{{ number_format($total, 4) }}</strong>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<div class="mb-4">
    <button class="btn fw-semibold mb-2"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#nilaiPreferensi"
        aria-expanded="false"
        aria-controls="nilaiPreferensi"
        style="background-color: #20746D; color: white;">
        Hasil Preferensi
    </button>

    <div class="collapse" id="nilaiPreferensi">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>Alternatif</th>
                        <th>Nilai Preferensi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailSAW['preferensi'] as $idAlt => $nilai)
                        <tr>
                            <td>{{ $alternatif[$idAlt]->nama ?? 'ID: '.$idAlt }}</td>
                            <td>{{ number_format($nilai, 4) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-success text-white" style="background-color: #00796B;">
                <tr class="text-center">
                    <th style="width: 80px;" class="text-center">Peringkat</th>
                    <th>Nama</th>
                    <th>Dusun</th>
                    <th>Nilai</th>
                </tr>
            </thead>
                <tbody>
                   @php $no = 1; @endphp
                    @foreach($ranking as $id_alternatif => $nilai)
                        @if(isset($alternatif[$id_alternatif]))
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $alternatif[$id_alternatif]->nama }}</td>
                                <td>{{ $alternatif[$id_alternatif]->dusun }}</td>
                                <td>{{ number_format($nilai, 4) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
        </table>
    </div>

</div>

<script>
    function konfirmasiPengajuan(periode) {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Tiga Data Teratas Perankingan Periode"' + periode + '" akan diajukan. Lanjutkan?',
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
                document.getElementById('pengajuan-form-' + periode).submit();
            }
        });
    }
</script>

@include('partial.viewpdfranking')
@endsection
