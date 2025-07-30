<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login HIK</title>
  <link  rel="stylesheet" href="{{url('plugins/fontawesome-free/css/all.min.css')}}" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <style>
    body {
      background-color: #e6f0eb; /* warna background lembut hijau muda */
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-card {
      background: #ffffff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 350px;
    }
    .btn-green {
      background-color: #007f3e;
      color: #fff;
    }
    .btn-green:hover {
      background-color: #005c2d;
      color: #fff;
    }
  </style>
</head>
<body>
    <div class="login-card text-centfer">
        <img src="{{ asset('img/logo_hik.png') }}" alt="Logo HIK" class="mb-3" style="max-width: 150px;" />
        <h4 class="mb-4">LOGIN</h4>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @error('email')
                <div class="alert alert-danger" role="alert">
                    <strong>Gagal Login!</strong> {{ $message }}
                </div>
            @enderror
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan email" required value="{{ old('email') }}" />
            </div>
            <div class="mb-3 text-start position-relative">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password" required />
                    {{-- Tombol untuk show/hide password --}}
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-3">
                {{-- Link Lupa Password --}}
                <a href="{{ route('password.request') }}">Lupa Password?</a>
            </div>
            <button type="submit" class="btn btn-green w-100">MASUK</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Script untuk toggle show/hide password
        $('#togglePassword').on('click', function() {
            const passwordField = $('#password');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            // Ganti ikon
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });
    </script>
</body>
</html>
