<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ config('app.name', 'Mini Marketplace') }}</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Impor Select2 CSS dan JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body {
      padding-top: 56px;
      padding-bottom: 60px;
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
    .bottom-action {
  position: fixed;
  bottom: 60px; /* posisi tepat di atas .bottom-nav */
  left: 0;
  width: 100%;
  z-index: 1029;
  background: #f8f9fa;
  border-top: 1px solid #ccc;
}

    .bottom-nav a:hover {
      color: #007bff;
    }
    .product-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 15px;
      text-align: center;
    }
    .product-card img {
      max-width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 6px;
    }
  </style>
</head>
<body>

<!-- Headbar -->
<nav class="navbar fixed-top navbar-light bg-white shadow-sm">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
      <i class="bi bi-shop-window me-2"></i>MiniShop
    </a>
<!-- Bagian Kanan Navbar -->
@auth
  <!-- Jika sudah login, tampilkan dropdown profil -->
  <div class="dropdown">
  <a href="#" class="text-dark dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="rounded-circle" width="32" height="32" referrerpolicy="no-referrer">
</a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
      <li><a class="dropdown-item" href="{{route('user.profile', Auth::user()->google_id)}}">Profil</a></li>
      <li>
        <form method="POST" action="{{route('logout')}}">
          @csrf
          <button type="submit" class="dropdown-item">Logout</button>
        </form>
      </li>
    </ul>
  </div>
@else
  <!-- Jika belum login, tampilkan tombol login -->
  <a href="{{ route('google.login') }}" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-box-arrow-in-right me-1"></i> Login
  </a>
@endauth
  </div>
</nav>

<!-- Konten halaman -->
<div class="container mt-3 mb-5">
  @yield('content')
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav">
  <a href="{{route('rental.index')}}"><i class="bi bi-shop"></i><br/>Rental</a>
  <a href="{{route('user.index')}}"><i class="bi bi-people"></i><br/>User</a>
  <a href="#"><i class="bi bi-house-door"></i><br/>Home</a>
  <a href="#"><i class="bi bi-controller"></i><br/>Game</a>
  <a href="#"><i class="bi bi-receipt"></i><br/>Payment</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
