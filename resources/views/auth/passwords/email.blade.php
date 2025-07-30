<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - HIK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #e6f0eb;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .reset-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
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
    <div class="reset-card text-center">
        <img src="{{ asset('img/logo_hik.png') }}" alt="Logo HIK" class="mb-3" style="max-width: 150px;" />
        <h4 class="mb-4">Reset Password</h4>

        {{-- Notifikasi Sukses --}}
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3 text-start">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan email Anda">
                
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-green w-100 mt-3">
                Kirim Link Reset Password
            </button>
            <div class="mt-3">
                <a href="{{ route('login') }}">Kembali ke Login</a>
            </div>
        </form>
    </div>
</body>
</html>