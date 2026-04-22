<!-- Dashboard -->
<a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i>
    <span>Dashboard</span>
</a>

<!-- Gudang Aturan -->
{{-- <a href="{{ route('aturan.index') }}" class="menu-item {{ request()->routeIs('aturan*') ? 'active' : '' }}">
    <i class="fas fa-book"></i>
    <span>Gudang Aturan</span>
</a>

<!-- SKPD -->
<a href="{{ route('skpd.index') }}" class="menu-item {{ request()->routeIs('skpd*') ? 'active' : '' }}">
    <i class="fas fa-building"></i>
    <span>SKPD</span>
</a> --}}

<!-- Chat AI -->
<a href="{{ route('chat.index') }}" class="menu-item {{ request()->routeIs('chat*') ? 'active' : '' }}">
    <i class="fas fa-robot"></i>
    <span>Chat AI</span>
</a>

<!-- Upload Data -->
{{-- <a href="#" class="menu-item {{ request()->routeIs('upload*') ? 'active open' : '' }}"
    onclick="toggleSubmenu('upload-submenu')">
    <i class="fas fa-cloud-upload-alt"></i>
    <span>Upload Data</span>
    <i class="fas fa-chevron-down chevron"></i>
</a>
<div id="upload-submenu" class="submenu {{ request()->routeIs('upload*') ? 'open' : '' }}">
    <a href="{{ route('upload.dpa.index') }}"
        class="submenu-item {{ request()->routeIs('upload.dpa*') ? 'active' : '' }}">DPA</a>
    <a href="{{ route('upload.anggaran.index') }}"
        class="submenu-item {{ request()->routeIs('upload.anggaran*') ? 'active' : '' }}">Anggaran
        Kas</a>
    <a href="{{ route('upload.spj-fungsional.index') }}"
        class="submenu-item {{ request()->routeIs('upload.spj-fungsional*') ? 'active' : '' }}">SPJ
        Fungsional (Jan-Des)</a>
    <a href="{{ route('upload.bku.index') }}"
        class="submenu-item {{ request()->routeIs('upload.bku*') ? 'active' : '' }}">BKU Perbulan
        (Jan-Des)</a>
    <a href="{{ route('upload.rekening') }}"
        class="submenu-item {{ request()->routeIs('upload.rekening') ? 'active' : '' }}">Rekening
        Koran IBB (Jan-Des)</a>
    <a href="{{ route('upload.spj-transaksi.index') }}"
        class="submenu-item {{ request()->routeIs('upload.spj-transaksi*') ? 'active' : '' }}">SPJ
        Per Transaksi</a>
</div>

<!-- Sinkronisasi Otomatis -->
<a href="#" class="menu-item {{ request()->routeIs('sinkronisasi*') ? 'active open' : '' }}"
    onclick="toggleSubmenu('sinkronisasi-submenu')">
    <i class="fas fa-sync-alt"></i>
    <span>Sinkron Otomatis</span>
    <i class="fas fa-chevron-down chevron"></i>
</a>
<div id="sinkronisasi-submenu" class="submenu {{ request()->routeIs('sinkronisasi*') ? 'open' : '' }}">
    <a href="{{ route('sinkronisasi.rekening-bku') }}"
        class="submenu-item {{ request()->routeIs('sinkronisasi.rekening-bku') ? 'active' : '' }}">
        <i class="fas fa-exchange-alt mr-2 text-xs"></i>
        Rekening Koran ↔ BKU Perbulan
    </a>
    <a href="{{ route('sinkronisasi.spj-bku') }}"
        class="submenu-item {{ request()->routeIs('sinkronisasi.spj-bku') ? 'active' : '' }}">
        <i class="fas fa-exchange-alt mr-2 text-xs"></i>
        SPJ Fungsional ↔ BKU Perbulan
    </a>
    <a href="{{ route('sinkronisasi.spj-bku2') }}"
        class="submenu-item {{ request()->routeIs('sinkronisasi.spj-bku2') ? 'active' : '' }}">
        <i class="fas fa-exchange-alt mr-2 text-xs"></i>
        SPJ ↔ BKU Perbulan
    </a>
    <a href="{{ route('sinkronisasi.saldo') }}"
        class="submenu-item {{ request()->routeIs('sinkronisasi.saldo') ? 'active' : '' }}">
        <i class="fas fa-balance-scale mr-2 text-xs"></i>
        Saldo Bank vs BKU Per Bulan
    </a>
</div>

<!-- Analisis Data -->
<a href="#" class="menu-item {{ request()->routeIs('analisis*') ? 'active open' : '' }}"
    onclick="toggleSubmenu('analisis-submenu')">
    <i class="fas fa-chart-line"></i>
    <span>Analisis Data</span>
    <i class="fas fa-chevron-down chevron"></i>
</a>
<div id="analisis-submenu" class="submenu {{ request()->routeIs('analisis*') ? 'open' : '' }}">
    <a href="{{ route('analisis.terperinci') }}"
        class="submenu-item {{ request()->routeIs('analisis.terperinci') ? 'active' : '' }}">Analisis Data
        Terperinci</a>
</div>

<!-- Laporan -->
<a href="#" class="menu-item {{ request()->routeIs('laporan*') ? 'active open' : '' }}"
    onclick="toggleSubmenu('laporan-submenu')">
    <i class="fas fa-file-alt"></i>
    <span>Laporan</span>
    <i class="fas fa-chevron-down chevron"></i>
</a>
<div id="laporan-submenu" class="submenu {{ request()->routeIs('laporan*') ? 'open' : '' }}">
    <a href="{{ route('laporan.transaksi') }}"
        class="submenu-item {{ request()->routeIs('laporan.transaksi') ? 'active' : '' }}">
        <i class="fas fa-table mr-2 text-xs"></i>
        Laporan Transaksi
    </a>
    <a href="{{ route('laporan.kelengkapan') }}"
        class="submenu-item {{ request()->routeIs('laporan.kelengkapan') ? 'active' : '' }}">
        <i class="fas fa-check-circle mr-2 text-xs"></i>
        Laporan Kelengkapan Dokumen
    </a>
    <a href="{{ route('laporan.transfer') }}"
        class="submenu-item {{ request()->routeIs('laporan.transfer') ? 'active' : '' }}">
        <i class="fas fa-university mr-2 text-xs"></i>
        Laporan Transfer Bank
    </a>
    <a href="{{ route('laporan.distribusi') }}"
        class="submenu-item {{ request()->routeIs('laporan.distribusi') ? 'active' : '' }}">
        <i class="fas fa-donate mr-2 text-xs"></i>
        Laporan Distribusi Dana
    </a>
    <a href="{{ route('laporan.temuan') }}"
        class="submenu-item {{ request()->routeIs('laporan.temuan') ? 'active' : '' }}">
        <i class="fas fa-search mr-2 text-xs"></i>
        Laporan Temuan Audit
    </a>
    <div class="px-6 py-2">
        <p class="text-white/50 text-xs mb-1">Area Audit:</p>
        <a href="{{ route('laporan.audit.rkbd') }}"
            class="submenu-item text-white/60 {{ request()->routeIs('laporan.audit.rkbd') ? 'active' : '' }}">•
            RKBD</a>
        <a href="{{ route('laporan.audit.kepegawaian') }}"
            class="submenu-item text-white/60 {{ request()->routeIs('laporan.audit.kepegawaian') ? 'active' : '' }}">•
            Kepegawaian</a>
        <a href="{{ route('laporan.audit.keuangan') }}"
            class="submenu-item text-white/60 {{ request()->routeIs('laporan.audit.keuangan') ? 'active' : '' }}">•
            Keuangan</a>
        <a href="{{ route('laporan.audit.lainnya') }}"
            class="submenu-item text-white/60 {{ request()->routeIs('laporan.audit.lainnya') ? 'active' : '' }}">•
            Lainnya</a>
    </div>
    <a href="{{ route('laporan.rekap') }}"
        class="submenu-item {{ request()->routeIs('laporan.rekap') ? 'active' : '' }}">
        <i class="fas fa-file-invoice mr-2 text-xs"></i>
        Laporan Rekap GU vs LS
    </a>
    <a href="{{ route('laporan.rincian') }}"
        class="submenu-item {{ request()->routeIs('laporan.rincian') ? 'active' : '' }}">
        <i class="fas fa-list mr-2 text-xs"></i>
        Laporan Rincian Obyek
    </a>
    <a href="{{ route('laporan.modal') }}"
        class="submenu-item {{ request()->routeIs('laporan.modal') ? 'active' : '' }}">
        <i class="fas fa-building mr-2 text-xs"></i>
        Laporan Belanja Modal
    </a> --}}
    {{--
</div> --}}