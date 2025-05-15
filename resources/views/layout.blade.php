<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ config('app.name', 'RentalPS') }}</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <!-- Select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <style>
    body {
      padding-top: 56px;
      padding-bottom: 60px;
      background: #f8f9fa;
    }
    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: white;
      border-top: 1px solid #ccc;
      display: flex;
      justify-content: space-around;
      padding: 5px 0;
      z-index: 1030;
    }
    .bottom-nav a {
      text-align: center;
      font-size: 12px;
      color: #555;
      text-decoration: none;
    }
    .bottom-nav a:hover {
      color: #0d6efd;
    }
    .bottom-action {
      position: fixed;
      bottom: 60px;
      left: 0;
      width: 100%;
      z-index: 1029;
      background: #f8f9fa;
      border-top: 1px solid #ccc;
    }
    .product-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 15px;
      text-align: center;
      background: white;
    }
    .product-card img {
      max-width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 6px;
    }
    @media (max-width: 576px) {
      .carousel-img {
        width: 200px !important;
        height: 100px !important;
      }
    }
    

    .media-body h6 {
        font-size: 1rem;
    }
    .media-body p {
        font-size: 0.95rem;
    }
    
</style>


  @stack('styles')
</head>
<body>

<!-- Navbar -->
<nav class="navbar fixed-top navbar-light bg-white shadow-sm">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
      <i class="bi bi-controller me-2"></i>RentalPS
    </a>

    @auth
    <div class="dropdown">
      <a href="#" class="text-dark dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="rounded-circle" width="32" height="32" referrerpolicy="no-referrer">
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
        <li><a class="dropdown-item" href="{{ route('user.profile', Auth::user()->google_id) }}">Profil</a></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item">Logout</button>
          </form>
        </li>
      </ul>
    </div>
    @else
    <a href="{{ route('google.login') }}" class="btn btn-outline-primary btn-sm">
      <i class="bi bi-box-arrow-in-right me-1"></i> Login
    </a>
    @endauth
  </div>
</nav>

<!-- Konten -->
<div class="container mt-3 mb-5">
  @yield('content')
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav">
  <a href="{{ route('rental.index') }}"><i class="bi bi-shop"></i><br/>Rental</a>
  <a href="{{ route('user.index') }}"><i class="bi bi-people"></i><br/>User</a>
  <a href="#"><i class="bi bi-house-door"></i><br/>Home</a>
<a href="#"><i class="bi bi-cash-stack"></i><br/>Pendapatan</a>
<a href="{{ route('riwayat.index')}}"><i class="bi bi-clock-history"></i><br/>Riwayat</a>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@stack('scripts')

</body>
</html>
