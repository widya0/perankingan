<!-- Modal Profil -->
<div class="modal fade" id="modalProfile" tabindex="-1" aria-labelledby="modalProfileLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProfileLabel">Profil Saya</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if ($errors->any() && old('modal_profile'))
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if(auth()->check())
        <form action="{{ route('EditProfile')}}" method="POST">
          @csrf
          <input type="hidden" name="modal_profile" value="1" />
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" value="{{ auth()->user()->username }}" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Level</label>
            <input type="text" class="form-control" value="{{ auth()->user()->level }}" disabled>
          </div>
          <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                  <input type="password" class="form-control" id="passwordProfil" name="password" placeholder="Isi jika ingin mengganti password" autocomplete="off">
                  <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                      <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                  </span>
              </div>
          </div>


          <button type="submit" class="btn btn-success">Simpan</button>
        </form>
        @endif

      </div>
    </div>
  </div>
</div>

<script>
   function togglePassword() {
    const passwordInput = document.getElementById('passwordProfil');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    }
}
</script>
