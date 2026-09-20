<section class="hero-section" id="home">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge-pill">
                <i class="fas fa-certificate"></i>
                <span><?php echo t('hero_badge'); ?></span>
            </div>
            
            <h1 class="hero-title">
                <?php if ($lang === 'ar'): ?>
                    <span>هندسة لمستقبل أفضل</span>
                <?php else: ?>
                    <span>Engineering A Better Tomorrow</span>
                <?php endif; ?>
            </h1>
            
            <p class="hero-subtitle"><?php echo t('hero_subtitle'); ?></p>
            
            <div class="hero-buttons">
                <a href="#projects" class="btn btn-primary">
                    <i class="fas fa-layer-group"></i>
                    <span><?php echo t('hero_cta_projects'); ?></span>
                </a>
                <a href="#contact" class="btn btn-outline">
                    <i class="fas fa-paper-plane"></i>
                    <span><?php echo t('hero_cta_contact'); ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Floating Hero Stats Bar -->
<div class="container">
    <div class="hero-stats-bar">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo t('hero_stat_1_num'); ?></div>
                <div class="stat-label"><?php echo t('hero_stat_1_label'); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo t('hero_stat_2_num'); ?></div>
                <div class="stat-label"><?php echo t('hero_stat_2_label'); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo t('hero_stat_3_num'); ?></div>
                <div class="stat-label"><?php echo t('hero_stat_3_label'); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo t('hero_stat_4_num'); ?></div>
                <div class="stat-label"><?php echo t('hero_stat_4_label'); ?></div>
            </div>
        </div>
    </div>
</div>
