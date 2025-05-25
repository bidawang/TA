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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
<nav class="navbar fixed-top navbar-light bg-white shadow-sm border-bottom">
  <div class="container-fluid d-flex justify-content-between align-items-center px-3">
    <a class="navbar-brand fw-bold d-flex align-items-center text-primary" href="#">
      <i class="bi bi-joystick me-2 fs-4"></i>Rental<span class="text-dark">PS</span>
    </a>

    @auth
    <div class="dropdown">
      <a href="#" class="dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="rounded-circle border border-2 border-primary shadow-sm" width="36" height="36" referrerpolicy="no-referrer">
      </a>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="profileDropdown">
        <li><a class="dropdown-item" href="{{ route('user.profile', Auth::user()->google_id) }}"><i class="bi bi-person-circle me-2"></i>Profil</a></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
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
<div class="container mt-5 pt-4 mb-5">
  @yield('content')
</div>

<!-- Bottom Navigation -->
@if(auth()->check())
<div class="bottom-nav d-flex justify-content-around bg-white shadow-sm py-2 fixed-bottom border-top">
  @php $role = auth()->user()->role; @endphp

  @if($role === 'admin')
    <x-nav-icon link="{{ route('setrental.index', ['rental_id' => session('id_rental')]) }}" icon="bi-tools" label="Rental"/>
    <x-nav-icon link="{{ route('transaksi.index') }}" icon="bi-calendar-check" label="Booking"/>
    <x-nav-icon link="{{ route('rental.show', ['rental' => session('id_rental')]) }}" icon="bi-house-heart" label="Home"/>
    <x-nav-icon link="{{ route('pendapatan', ['id' => session('id_rental')]) }}" icon="bi-graph-up-arrow" label="Pendapatan"/>
    <x-nav-icon link="{{ route('wallet.index') }}" icon="bi-cash-coin" label="Dompet"/>
    <x-nav-icon link="{{ route('riwayat.index') }}" icon="bi-clock-history" label="Riwayat"/>
  
  @elseif($role === 'developer')
    <x-nav-icon link="{{ route('rental.index') }}" icon="bi-building" label="Rental"/>
    <x-nav-icon link="{{ route('user.index') }}" icon="bi-people" label="User"/>
    <x-nav-icon link="{{ route('transaksi.index') }}" icon="bi-calendar-check" label="Booking"/>
    <x-nav-icon link="{{ route('wallet.index') }}" icon="bi-credit-card-2-front" label="Penarikan"/>
    <x-nav-icon link="{{ route('riwayat.index') }}" icon="bi-clock-history" label="Riwayat"/>

  @elseif($role === 'user')
    <x-nav-icon link="{{ route('transaksi.index', ['filter' => 'pending']) }}" icon="bi-calendar-event" label="Booking"/>
    <x-nav-icon link="{{ route('dashboard') }}" icon="bi-house-door" label="Home"/>
    <x-nav-icon link="{{ route('transaksi.index', ['filter' => 'selesai']) }}" icon="bi-bookmark-check" label="Riwayat"/>
  @endif
</div>
@endif

@if(!auth()->check())
<div class="bottom-nav text-center bg-white shadow-sm py-3 fixed-bottom text-muted small">
  <div>&copy; {{ date('Y') }} RentalPS</div>
  <div>
    <a href="mailto:contact@rentalps.com" class="text-decoration-none text-muted">Contact</a> ·
    <a href="tel:+6281234567890" class="text-decoration-none text-muted">+62 812-3456-7890</a>
  </div>
  <div class="mt-1">
    <a href="https://instagram.com/rentalps" target="_blank" class="text-decoration-none mx-1"><i class="bi bi-instagram text-danger"></i></a>
    <a href="https://twitter.com/rentalps" target="_blank" class="text-decoration-none mx-1"><i class="bi bi-twitter text-info"></i></a>
    <a href="https://facebook.com/rentalps" target="_blank" class="text-decoration-none mx-1"><i class="bi bi-facebook text-primary"></i></a>
  </div>
</div>
@endif



<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>



<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@stack('scripts')

</body>
</html>
