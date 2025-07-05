<!DOCTYPE html>
<html>
<head>
    <title>Data Penerima Bantuan</title>

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
        Daftar Penerima<br>
        Bantuan Rumah Tidak Layak Huni Tahun {{ $filterTahun ?? date('Y') }}<br>
        Desa Sumberejo Kecamatan Madiun Kabupaten Madiun
    </p>
    <br>

    @if($filterDusun)
        <p text-align: left;><strong>Dusun  {{ $filterDusun }}</strong></p>
    @endif
    <table>
        <thead style="text-align: center;">
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>Tahun Seleksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($penerima as $id => $nilai)
                @if(isset($alternatif[$id]))
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $alternatif[$id]->nama }}</td>
                    <td>{{ $alternatif[$id]->nik }}</td>
                    <td>{{ 'Dusun '.$alternatif[$id]->dusun.', RT. '.$alternatif[$id]->rt.', RW. '.$alternatif[$id]->rw }}</td>
                    <td>{{ number_format($nilai, 4) }}</td>
                    <td>{{ $alternatif[$id]->nama }}</td>
                    <td>{{ $alternatif[$id]->nama }}</td>
                </tr>
                @endif
            @endforeach

            @foreach($penerima as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $data->alternatif->nama }}</td>
                    <td>{{ $data->alternatif->nik }}</td>
                    <td>{{ 'Dusun '.$data->alternatif->dusun.', RT. '.$data->alternatif->rt.', RW. '.$data->alternatif->rw }}</td>
                    <td>{{ $data->status_penerima }}</td>
                    <td>{{ $data->tahun_seleksi }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>
</html>
