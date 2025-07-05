@extends('layouts.apps')

@push('styles')
    
@endpush

@section('content')
<div class="container-fluid mt-4 px-5">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
      
    <h4>Data Akun Petugas</h4>

    <button type="button" class="btn btn-primary mb-3" onclick="bukaModalTambahAkun()" data-bs-toggle="modal" data-bs-target="#tambahAkunModal">Tambah Akun</button>

        <!-- Modal -->
    <div class="modal fade" id="tambahAkunModal" tabindex="-1" aria-labelledby="tambahAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- modal-lg biar agak gede -->
        <div class="modal-content">
        <div class="modal-header" style="background-color: #00796B;">
            <h5 class="modal-title text-white" id="tambahAkunModalLabel">Tambah Akun</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-akun" action="" method="POST">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="hidden" name="_method" id="method_akun" value="POST">
            <div class="modal-body px-5 py-4">

            <div class="mb-4 row align-items-center">
                <label for="username" class="col-sm-3 col-form-label">Username</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                </div>
                @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4 row align-items-center">
                <label for="password" class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-9">
                    <div class="input-group w-100">
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
                        <span class="input-group-text" onclick="toggleAccount()" style="cursor: pointer;">
                            <i class="bi bi-eye-slash" id="toggleAccountIcon"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>
            </div>
            
            <div class="mb-4 row align-items-center">
                <label for="level" class="col-sm-3 col-form-label">Level</label>
                <div class="col-sm-9">
                    <input type="text" id="level" name="level" class="form-control" value="Petugas" readonly>
                </div>
            </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn" style="background-color: #00796B; color: white;">Simpan</button>
            </div>
        </form>
        </div>
    </div>
    </div>


    </div>

    <div class="table-responsive">
        <table class="table table-bordered shadow-sm">
            <thead class="table-success text-white" style="background-color: #00796B;">
                <tr class="text-center">
                    <th>Nama</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataTambahAkun as $item)
                <tr class="align-middle text-center">
                    <td class="text-start">{{ $item->username }}</td>
                    <td>{{ $item->level }}</td>
                    <td>
                        <a title="Edit"
                            class="border-0 d-inline-flex align-items-center justify-content-center text-white rounded mt-2"
                            onclick="bukaModalEditAkun('{{ $item->id_pengguna }}', '{{ $item->username }}')"
                            data-bs-toggle="modal" data-bs-target="#tambahAkunModal"
                            style="background-color: #0d6efd; width: 28px; height: 28px;">
                            <i class="bi bi-pencil" style="font-size: 16px;"></i>
                        </a>
                        <!-- <form action="{{ route('HapusAkun', $item->id_pengguna) }}" method="POST" style="display:inline;" onsubmit="return confirmHapusAkun('{{ $item->username }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="border-0 d-inline-flex align-items-center justify-content-center text-white rounded mt-2"
                            style="background-color: #dc3545; width: 28px; height: 28px;">
                            <i class="bi bi-trash" style="font-size: 16px;"></i>
                            </button>
                        </form> -->

                        <form id="hapus-form-{{ $item->id_pengguna }}" action="{{ route('HapusAkun', $item->id_pengguna) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" title="Hapus"
                            class="border-0 d-inline-flex align-items-center justify-content-center text-white rounded mt-2"
                            onclick="konfirmasiHapusAkun('{{ $item->id_pengguna }}', '{{ $item->username }}')"
                            style="background-color: #dc3545; width: 28px; height: 28px;">
                            <i class="bi bi-trash" style="font-size: 16px;"></i></a>      
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleAccount() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleAccountIcon');
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


    function bukaModalTambahAkun() {
        document.getElementById('form-akun').action = "{{ route('TambahAkun') }}";
        document.getElementById('method_akun').value = 'POST';
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        document.getElementById('tambahAkunModalLabel').innerText = 'Tambah Akun';
    }

    function bukaModalEditAkun(id, username) {
            setTimeout(() => {
        document.getElementById('form-akun').action = "/tambahakun/" + id;
        document.getElementById('method_akun').value = 'PUT';
        document.getElementById('username').value = username;
        document.getElementById('password').value = '';
        document.getElementById('tambahAkunModalLabel').innerText = 'Edit Akun';
    }, 200); 
    }

    function confirmHapusAkun(username) {
        return confirm('Yakin ingin menghapus akun dengan username "' + username + '"?');
    }

    function konfirmasiHapusAkun(id_pengguna, username) {
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Akun "' + username + '" akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary me-2'     
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapus-form-' + id_pengguna).submit();
            }
        });
    }

</script>


@endsection
