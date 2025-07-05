@extends('layouts.apps')

@section('content')
    <!-- Hero Section -->
    <div class="hero">
        <div class="hero img">
        <img src="{{ asset('images/rumah.jpg') }}" alt="Desa Background">
        <div class="hero-text">
            <h1 class="display-3 fw-bold">Selamat Datang</h1>
            <p class="fs-4 mt-2">Portal Bantuan Rumah Tidak Layak Huni Desa Sumberejo</p>
        </div>
        </div>
    </div>

    <!-- Section lain tetap aman -->
    <section id="section2" class="min-vh-100 bg-white d-flex flex-column justify-content-center align-items-center transition-opacity py-5">
        <div class="container d-flex align-items-center">
            <div class="col-md-4">
                <img src="{{ asset('images/home1.png') }}" class="img-fluid rounded shadow">
            </div>

            <div class="col-md-8 ps-4">
                <h2 class="display-6 fw-bold text-success">Bantuan Rumah Tidak Layak Huni</h2>
                <p class="mt-2 text-muted text-justify">
                    Program Bantuan Rumah Tidak Layak Huni merupakan program yang bertujuan untuk memperbaiki kondisi rumah-rumah tidak layak 
                    huni milik masyarakat kurang mampu. Rumah yang tidak memenuhi standar kelayakan sering kali menjadi sumber permasalahan 
                    kesejahteraan, kesehatan, dan keselamatan bagi penghuninya. Melalui program ini, disalurkan bantuan berupa renovasi 
                    total maupun sebagian, agar rumah yang sebelumnya rusak, reyot, atau tidak sehat dapat menjadi hunian yang layak, aman, 
                    dan nyaman.
                    </p>
                <p class="mt-2 text-muted text-justify">
                    Sasaran utama dari program ini adalah keluarga berpenghasilan rendah yang tinggal di rumah dengan kondisi memprihatinkan 
                    seperti atap bocor, dinding rapuh atau retak, lantai yang belum memadai, atau bagian lain rumah yang berpotensi membahayakan 
                    penghuninya. Renovasi yang dilakukan mencakup perbaikan struktur bangunan, penggantian atap, pemasangan lantai yang layak 
                    agar rumah lebih aman dan nyaman ditempati. Dalam proses pelaksanaannya, program ini juga melibatkan masyarakat setempat, baik 
                    dalam bentuk tenaga kerja maupun gotong royong, guna menumbuhkan rasa kepedulian dan kebersamaan dalam lingkungan sekitar.</p>
                <p class="mt-2 text-muted text-justify">
                    Manfaat dari Bantuan Renovasi Rumah tidak hanya dirasakan dari sisi fisik bangunan, tetapi juga dari aspek sosial dan 
                    psikologis. Dengan tinggal di rumah yang layak, penghuni merasa lebih aman dari cuaca ekstrem, lebih sehat karena lingkungan 
                    yang bersih, dan lebih sejahtera secara mental karena memiliki tempat tinggal yang nyaman. Selain itu, program ini turut 
                    mendorong pemerataan pembangunan dan pengurangan angka kemiskinan melalui penyediaan tempat tinggal yang manusiawi bagi 
                    seluruh lapisan masyarakat.</p>
            </div>
        </div>

        <div class="container mt-4 bg-white shadow-sm rounded p-4">
            <h3 class="fs-4 fw-bold mb-2">Daftar Penerima Bantuan Tahun {{ $tahun }}</h3>
            <table class="table table-bordered text-center">
                <thead class="bg-success text-white">
                    <tr class="table-success text-white" style="background-color: #00796B;">
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Status Pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penerimaTampil as $item)
                        <tr>
                            <td>{{ $item->alternatif->nama ?? '-' }}</td>
                            <td>{{ 'Dusun '.$item->alternatif->dusun.', RT. '.$item->alternatif->rt.', RW. '.$item->alternatif->rw ?? '-'  }}</td>
                            <td>
                                @if ($item->status_penerima === 'Diajukan')
                                    <span class="badge bg-warning text-dark">Diajukan</span>
                                @else
                                    <span class="badge bg-success">Disetujui</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada data yang ditampilkan ke publik.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </section>
@endsection
