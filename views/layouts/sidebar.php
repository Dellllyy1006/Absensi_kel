<?php $user = $session->get('user'); ?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h3>Menu Admin</h3>
    </div>
    
    <nav class="sidebar-nav">
        <a href="<?= url('/dashboard') ?>" class="sidebar-link <?= isCurrentPage('/dashboard') ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="<?= url('/absensi') ?>" class="sidebar-link <?= isCurrentPage('/absensi') && !isCurrentPage('/absensi/') ? 'active' : '' ?>">
            <i class="fas fa-clipboard-list"></i>
            <span>Data Absensi</span>
        </a>
        
        <a href="<?= url('/qr') ?>" class="sidebar-link <?= isCurrentPage('/qr') ? 'active' : '' ?>">
            <i class="fas fa-qrcode"></i>
            <span>Sesi QR Code</span>
        </a>
        
        <a href="<?= url('/absensi/manual') ?>" class="sidebar-link <?= isCurrentPage('/absensi/manual') ? 'active' : '' ?>">
            <i class="fas fa-edit"></i>
            <span>Input Manual</span>
        </a>
        
        <a href="<?= url('/absensi/bulk') ?>" class="sidebar-link <?= isCurrentPage('/absensi/bulk') ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            <span>Absensi Massal</span>
        </a>
        
        <div class="sidebar-divider"></div>
        
        <a href="<?= url('/profile') ?>" class="sidebar-link <?= isCurrentPage('/profile') ? 'active' : '' ?>">
            <i class="fas fa-user-cog"></i>
            <span>Profil</span>
        </a>
    </nav>
</aside>
