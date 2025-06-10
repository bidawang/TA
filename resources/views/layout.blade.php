<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ config('app.name', 'RentalPS') }}</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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
  <nav class="navbar fixed-top navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid d-flex justify-content-between align-items-center px-3">
      <!-- Branding -->
      <a class="navbar-brand fw-bold d-flex align-items-center text-primary" href="#">
        <i class="bi bi-joystick me-2 fs-4"></i>Rental<span class="text-dark">PS</span>
      </a>

      <div class="d-flex align-items-center gap-3">
        @auth
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'user')

        {{-- Notifikasi --}}
        <div class="dropdown me-2">
          <a href="#" class="position-relative text-dark" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-label="Notifikasi Transaksi">
            <i class="bi bi-bell fs-5"></i>
            <span id="notifBadge"></span> {{-- <- ini yang diisi via JS --}}
          </a>

          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-0" id="notifList" aria-labelledby="notifDropdown" style="min-width: 300px; max-height: 300px; overflow-y: auto;">
            @forelse($transaksiNotif as $trans)
            <li class="px-3 py-2 border-bottom">
              <div class="d-flex flex-column">
                @if(auth()->user()->role === 'user')
                <a href="{{ route('transaksi.index', ['filter' => 'pending']) }}"
                  class="text-decoration-none text-dark mb-1">
                  <div class="fw-semibold">
                    📄 Booking di <span class="text-primary">{{ $trans->rental->nama ?? 'Rental' }}</span>
                  </div>
                  <div class="small text-muted">
                    Status: <span class="fw-semibold text-capitalize">{{ $trans->status }}</span>
                  </div>
                </a>
                <small class="text-muted fst-italic">Silakan klik untuk lakukan pembayaran</small>
                <small class="text-muted">{{ $trans->created_at->diffForHumans() }}</small>

                @elseif(auth()->user()->role === 'admin')
                <a href="{{ route('transaksi.index') }}" class="text-decoration-none text-dark mb-1">
                  <div class="fw-semibold">
                    🧾 {{ $trans->setrental->name ?? '-' }}
                  </div>
                  <div class="small text-muted">
                    {{ $trans->user->email ?? '-' }} •
                    <span class="fw-semibold text-capitalize">{{ $trans->status }}</span>
                  </div>
                </a>
                <small class="text-muted">{{ $trans->created_at->diffForHumans() }}</small>
                @endif
              </div>
            </li>
            @empty
            <li class="text-center text-muted small py-3">Tidak ada notifikasi transaksi.</li>
            @endforelse
          </ul>


        </div>
        @endif
        {{-- Profile Dropdown --}}
        <div class="dropdown">
          <a href="#" class="dropdown-toggle d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="rounded-circle border border-2 border-primary shadow-sm" width="36" height="36" referrerpolicy="no-referrer">
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="profileDropdown">
            <li>
              <a class="dropdown-item" href="{{ route('user.profile', Auth::user()->google_id) }}">
                <i class="bi bi-person-circle me-2"></i> Profil
              </a>
            </li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">
                  <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
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
    </div>
  </nav>

  <!-- Konten -->
  <div class="container mt-2 mb-5">
    @yield('content')
  </div>
  @if(auth()->check())
  @php $role = auth()->user()->role; @endphp

  {{-- Bottom Navbar --}}
  <div class="bottom-nav bg-white shadow-sm border-top fixed-bottom w-100">
    @if($role === 'admin')
  <div class="container-fluid d-flex justify-content-between align-items-center px-3">
      <x-nav-icon link="{{ route('setrental.index', ['rental_id' => session('id_rental')]) }}" icon="bi-tools" label="Rental" />
      <x-nav-icon link="{{ route('transaksi.index') }}" icon="bi-calendar-check" label="Booking" />
      <x-nav-icon link="{{ route('rental.show', ['rental' => session('id_rental')]) }}" icon="bi-house-heart" label="Home" />
      <x-nav-icon link="{{ route('pendapatan', ['id' => session('id_rental')]) }}" icon="bi-graph-up-arrow" label="Pendapatan" />
      <x-nav-icon link="{{ route('wallet.index') }}" icon="bi-cash-coin" label="Dompet" />
      <x-nav-icon link="{{ route('riwayat.index') }}" icon="bi-clock-history" label="Riwayat" />
    </div>

    @elseif($role === 'developer')
  <div class="container-fluid d-flex justify-content-between align-items-center px-3">
      <x-nav-icon link="{{ route('rental.index') }}" icon="bi-building" label="Rental" />
      <x-nav-icon link="{{ route('user.index') }}" icon="bi-people" label="User" />
      <x-nav-icon link="{{ route('transaksi.index') }}" icon="bi-calendar-check" label="Booking" />
      <x-nav-icon link="{{ route('wallet.index') }}" icon="bi-credit-card-2-front" label="Penarikan" />
      <x-nav-icon link="{{ route('riwayat.index') }}" icon="bi-clock-history" label="Riwayat" />
    </div>

    @elseif($role === 'user')
    <div class="d-flex flex-column align-items-center w-100">
      <div class="d-flex justify-content-around w-100 py-2">
        <x-nav-icon link="{{ route('transaksi.index', ['filter' => 'pending']) }}" icon="bi-calendar-event" label="Booking" />
        <x-nav-icon link="{{ route('dashboard') }}" icon="bi-house-door" label="Home" />
        <x-nav-icon link="{{ route('transaksi.index', ['filter' => 'selesai']) }}" icon="bi-bookmark-check" label="Riwayat" />
      </div>

      <div class="text-center text-muted small py-2" style="background-color: #f8f9fa;">
        Punya rental?
        <a href="{{ route('rental.create') }}" class="text-decoration-none fw-semibold text-primary">
          Daftarkan di sini
        </a>
      </div>

    </div>
    @endif
  </div>
  @endif

  @if(!auth()->check())
  <div class="bottom-nav text-center bg-white shadow-sm py-3 fixed-bottom text-muted">
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

  <script>
    function fetchNotifikasi() {
      $.ajax({
        url: "{{ route('notifikasi.transaksi') }}",
        method: "GET",
        success: function(data) {
          let badge = '';
          let list = '';

          if (data.length > 0) {
            badge = `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${data.length}</span>`;
            data.forEach(t => {
              list += `<li class="px-3 py-2 border-bottom">
                    <div class="d-flex flex-column">
                      <a href="/transaksi?filter=pending" class="text-decoration-none text-dark mb-1">
                        <div class="fw-semibold">
                          📄 Booking di <span class="text-primary">${t.rental?.nama || '-'}</span>
                        </div>
                        <div class="small text-muted">
                          Status: <span class="fw-semibold text-capitalize">${t.status}</span>
                        </div>
                      </a>
                      <small class="text-muted fst-italic">${new Date(t.created_at).toLocaleString('id-ID')}</small>
                    </div>
                  </li>`;
            });
          } else {
            list = `<li class="text-center text-muted small py-3">Tidak ada notifikasi transaksi.</li>`;
          }

          $('#notifBadge').html(badge);
          $('#notifList').html(list);
        }
      });
    }

    setInterval(fetchNotifikasi, 3000);
    fetchNotifikasi(); // langsung load saat halaman dibuka
  </script>

  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  @stack('scripts')

</body>

</html>