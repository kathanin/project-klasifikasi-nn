<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HIK</title>

  <link rel="stylesheet" href="{{url('plugins/fontawesome-free/css/all.min.css')}}" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  @yield('css')
  <style>
    body {
      background-color: #f6f9f8;
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
      height: 100vh;
      background-color: #ffffff;
      border-right: 1px solid #e0e0e0;
      padding: 20px;
    }
    .sidebar .nav-link {
      color: #333;
      font-weight: 500;
    }
    .sidebar .nav-link.active {
      background-color: #007f3e;
      color: #fff;
      border-radius: 8px;
    }
    .sidebar .nav-link i {
      margin-right: 8px;
    }
    .content {
      padding: 30px;
    }
    .card {
      border-radius: 12px;
      border: none;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .logout {
      margin-top: auto;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column sticky-top">
      <img src="{{url('img/logo_hik.png')}}" alt="Logo HIK" style="width: 150px;" class="mb-4" />
      <nav class="nav flex-column">
      @php
          $role = Auth::user()->role;
      @endphp
        <a class="nav-link {{ Request::is('dashboards') ? 'active' : '' }}" href="{{url('/dashboards')}}"><i class="bi bi-speedometer2"></i> Dashboard</a>
      @if($role === 'pimpinan' || $role === 'marketing')
        <a class="nav-link {{ Request::is('nasabah') ? 'active' : '' }}" href="{{url('/nasabah')}}"><i class="bi bi-people"></i> Data Nasabah</a>
        <a class="nav-link {{ Request::is('klasifikasi') ? 'active' : '' }}" href="{{url('/klasifikasi')}}"><i class="bi bi-bar-chart"></i> Klasifikasi</a>
        <a class="nav-link {{ Request::is('analisa') ? 'active' : '' }}" href="{{url('/analisa')}}"><i class="bi bi-graph-up"></i> Analisa</a>
      @endif
      @if($role === 'pimpinan')
        <a class="nav-link {{ Request::is('persetujuan') ? 'active' : '' }}" href="{{url('/persetujuan')}}"><i class="bi bi-file-earmark-text"></i> Persetujuan</a>
      @endif
      @if($role === 'pimpinan' || $role === 'legal')
        <a class="nav-link {{ Request::is('legal') ? 'active' : '' }}" href="{{url('/legal')}}"><i class="bi bi-shield-lock"></i> Legal</a>
      @endif
      </nav>
      <div class="logout mt-auto">
        <hr />
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        <!-- <a class="nav-link" href="#"><i class="bi bi-box-arrow-right"></i> Logout</a> -->
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
      <!-- Top Navbar -->
      <nav class="navbar navbar-light bg-white shadow-sm px-4 sticky-top">
        <h5>@yield('judul-content')</h5>
        <div class="ms-auto">
          <span class="me-3">{{auth()->user()->name}}</span>
          <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
        </div>
      </nav>

      <!-- Content -->
      <div class="content">
        @yield('content')        
      </div>
    </div>
  </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('js-plugin')

    <script>
        // Jalankan semua skrip kustom setelah halaman siap
        $(document).ready(function() {
            // Skrip dari @section('js-ready') akan dieksekusi di sini
            @yield('js-ready')

            // Skrip untuk menampilkan notifikasi dari session (sukses atau error)
            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
</body>
</html>
