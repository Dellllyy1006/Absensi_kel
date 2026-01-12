<?php
$user = $session->get('user');
$flash = $session->getFlash();
?>
<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= url('/dashboard') ?>">
            <i class="fas fa-qrcode"></i>
            <span><?= APP_NAME ?></span>
        </a>
    </div>
    
    <?php if ($user): ?>
    <div class="navbar-menu">
        <div class="navbar-user">
            <span class="user-name">
                <i class="fas fa-user-circle"></i>
                <?= e($user['username']) ?>
            </span>
            <span class="user-role badge <?= $user['role'] === 'admin' ? 'badge-primary' : 'badge-secondary' ?>">
                <?= ucfirst($user['role']) ?>
            </span>
        </div>
        <a href="<?= url('/auth/logout') ?>" class="btn btn-outline btn-sm">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
    <?php endif; ?>
</nav>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['type'] ?> alert-dismissible">
    <span><?= $flash['message'] ?></span>
    <button type="button" class="alert-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
<?php endif; ?>
