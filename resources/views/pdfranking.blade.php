<!DOCTYPE html>
<html>
<head>
    <title>Hasil Perankingan</title>

    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 13px; text-transform: uppercase;}
        p {font-size: 13px; line-height: 1.6;}
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 5px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <p style="text-align: center;">
        Daftar Urutan Prioritas Usulan Calon Penerima<br>
        Bantuan Rumah Tidak Layak Huni {{ $periode }}<br>
        Desa Sumberejo Kecamatan Madiun Kabupaten Madiun
    </p>
    <br>

    @if($filterDusun)
        <p text-align: left;><strong>Dusun  {{ $filterDusun }}</strong></p>
    @endif
    <table>
        <thead style="text-align: center;">
            <tr>
                <th>No. Urutan <br> Prioritas</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Alamat</th>
                <th>Nilai Perankingan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($ranking as $id => $nilai)
                @if(isset($alternatif[$id]))
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $alternatif[$id]->nama }}</td>
                    <td>{{ $alternatif[$id]->nik }}</td>
                    <td>{{ 'Dusun '.$alternatif[$id]->dusun.', RT. '.$alternatif[$id]->rt.', RW. '.$alternatif[$id]->rw }}</td>
                    <td>{{ number_format($nilai, 4) }}</td>
                </tr>
                @endif
            @endforeach

            @if(count($ranking) === 0)
                <tr>
                    <td colspan="4" style="text-align:center;">Tidak ada data perankingan.</td>
                </tr>
            @endif

        </tbody>
    </table>
</body>
</html>
