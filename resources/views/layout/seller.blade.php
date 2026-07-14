<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Seller Dashboard | Flipkart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
      .sidebar {
        min-height: 100vh;
        background-color: #343a40;
      }
      .sidebar a {
        color: #c2c7d0;
        text-decoration: none;
        padding: 10px 15px;
        display: block;
      }
      .sidebar a:hover, .sidebar a.active {
        color: #fff;
        background-color: #495057;
      }
      .content-wrapper {
        padding: 20px;
      }
    </style>
  </head>
  <body>
    <div class="d-flex">
      <!-- Sidebar -->
      <div class="sidebar p-3" style="width: 250px;">
        <h4 class="text-white text-center mb-4">Seller Panel</h4>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a href="{{ route('seller.dashboard') }}" class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
              <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('seller.products.index') }}" class="nav-link {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
              <i class="bi bi-box me-2"></i> My Products
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('seller.reports.index') }}" class="nav-link {{ request()->routeIs('seller.reports.*') ? 'active' : '' }}">
              <i class="bi bi-bar-chart-fill me-2"></i> Reports
            </a>
          </li>
          <li class="nav-item mt-5">
            <form action="{{ route('seller.logout') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
            </form>
          </li>
        </ul>
      </div>

      <!-- Main Content -->
      <div class="flex-grow-1 bg-light">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4">
          <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Welcome, {{ auth()->guard('seller')->user()->owner_name }}</span>
          </div>
        </nav>

        <!-- Content -->
        <div class="content-wrapper">
          @yield('content')
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
