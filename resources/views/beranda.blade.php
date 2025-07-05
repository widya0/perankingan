@extends('layouts.apps')

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    html, body { 
        scroll-behavior: smooth;
        margin: 0;
        padding: 0;
        width: 90%;
        overflow-x: hidden; /* Mencegah scroll horizontal */
    }
    .transition-opacity { 
        transition: opacity 0.5s ease-in-out; 
    }
</style>
@endpush

@section('content')
    <div style="max-width: 1000px; margin: 50px auto;">
        <h3 style="margin-bottom: 1rem; text-align: left; padding-left: 40px;">Grafik Penerima Bantuan</h3>
        <div style="padding: 24px; margin: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background-color: white;">
            <canvas id="myChart" height="300"></canvas>
        </div>
    </div>

    @php
        $colors = ['#22c55e', '#3b82f6', '#eab308'];
        $dusunList = ['Bendungan', 'Made', 'Ngampel'];
    @endphp

    <h3 style="margin-bottom: 1rem; text-align: left; padding-left: 40px;">Total Penerima Bantuan Tiap Dusun</h3>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 50px;">
        @foreach ($dusunList as $index => $dusun)
            <div style="padding: 24px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; background-color: {{ $colors[$index % count($colors)] }}; color: white;">
                <h5 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Dusun {{ $dusun }}</h5>
                <p style="font-size: 2rem; font-weight: bold;">
                    {{ $dusunSummary[$dusun] ?? 0 }}
                </p>
                <p style="font-size: 0.875rem; opacity: 0.9;">Penerima Bantuan</p>
            </div>
        @endforeach
    </div>




@endsection

@section('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($labels ?? ['2022', '2023', '2024', '2025']) !!};
        const data = {!! json_encode($dataDiterima ?? [0, 0, 0, 0]) !!};

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar', // Jenis grafik
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Penerima',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                    // barThickness: 'flex',
                    // maxBarThickness: 100 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
