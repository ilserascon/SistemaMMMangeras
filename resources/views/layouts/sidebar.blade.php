<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ url('/home') }}"><img src="{{ asset('stisla/assets/img/logo_mmmangueras.jpg') }}" alt="logo" width="70%"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ url('/home') }}"></a>
    </div>
    <br>
    <ul class="sidebar-menu">
      <li class="menu-header">&nbsp;</li>
      @if (Auth::check() && Auth::user()->role && Auth::user()->role->nombre === 'Administrador')
        <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> <span>Usuarios</span></a>
        </li>
        <li class="{{ request()->is('bodegas*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('bodegas.index') }}"><i class="fas fa-warehouse"></i> <span>Bodegas</span></a>
        </li>
        <li class="{{ request()->is('productos*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('productos.index') }}"><i class="fas fa-boxes"></i> <span>Productos</span></a>
        </li>
        <li class="{{ request()->is('facturas*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('facturas.index') }}"><i class="fas fa-file-invoice"></i> <span>Facturas</span></a>
        </li>
      @endif
    </ul>
  </aside>
</div>
