<div class="profile-page">
    <div class="page-header">
        <h1><i class="fas fa-user-cog"></i> Profil Admin</h1>
    </div>

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-photo">
                    <div class="photo-placeholder admin-photo">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
                <div class="profile-name">
                    <h2><?= e($user['username']) ?></h2>
                    <p class="email"><?= e($user['email']) ?></p>
                    <span class="badge badge-primary">Administrator</span>
                </div>
            </div>

            <div class="profile-details">
                <div class="detail-group">
                    <label><i class="fas fa-user"></i> Username</label>
                    <span><?= e($user['username']) ?></span>
                </div>
                <div class="detail-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <span><?= e($user['email']) ?></span>
                </div>
                <div class="detail-group">
                    <label><i class="fas fa-calendar"></i> Bergabung Sejak</label>
                    <span><?= formatDate($user['created_at']) ?></span>
                </div>
            </div>

            <div class="profile-actions">
                <a href="<?= url('/profile/edit') ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
                <a href="<?= url('/dashboard') ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
