<!-- Dashboard -->
<a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
</a>

<!-- Analisis Data -->
<a href="{{ route('analisis.index') }}" class="menu-item {{ request()->routeIs('analisis*') ? 'active' : '' }}">
    <i class="fas fa-chart-bar"></i>
    <span>Analisis Data</span>
</a>

<!-- Gudang Aturan -->
{{-- <a href="{{ route('aturan.index') }}" class="menu-item {{ request()->routeIs('aturan*') ? 'active' : '' }}">
    <i class="fas fa-book"></i>
    <span>Gudang Aturan</span>
</a>

<!-- Chat AI -->
<a href="{{ route('chat.index') }}" class="menu-item {{ request()->routeIs('chat*') ? 'active' : '' }}">
    <i class="fas fa-robot"></i>
    <span>Chat AI</span>
</a> --}}