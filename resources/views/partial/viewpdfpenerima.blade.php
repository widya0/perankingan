<!-- Modal Preview PDF -->
<div class="modal fade" id="PreviewPDFPenerimaModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="max-width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="previewModalLabel">Preview Hasil Perankingan (PDF)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <iframe href="{{ route('PreviewPDFRanking', ['filter_dusun' => $filterDusun, 'filter_tahun' => $filterTahun, 'filter_status' => $filterStatus]) }}"
                width="100%" height="600px" style="border:none;"></iframe>
      </div>
      <div class="modal-footer">
        <a href="{{ route('DownloadPDFPenerima', ['filter_dusun' => $filterDusun, 'filter_tahun' => $filterTahun, 'filter_status' => $filterStatus]) }}"
           class="btn btn-success" target="_blank">
           <i class="bi bi-download"></i> Unduh PDF
        </a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
