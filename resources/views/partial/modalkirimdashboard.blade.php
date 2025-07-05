<!-- Modal Kirim ke Dashboard -->
<div class="modal fade" id="kirimDashboardModal" tabindex="-1" aria-labelledby="kirimDashboardModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('KirimDashboard') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kirimDashboardModalLabel">Kirim Data ke Dashboard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="tahun" class="form-label">Pilih Tahun:</label>
                    <select name="tahun" id="tahun" class="form-select" required>
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="aksi" class="form-label">Aksi:</label>
                    <select name="aksi" id="aksi" class="form-select" required>
                        <option value="Tampil">Tampilkan di Dashboard</option>
                        <option value="Sembunyi">Sembunyikan dari Dashboard</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Kirim</button>
            </div>
        </div>
    </form>
  </div>
</div>
