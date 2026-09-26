<section class="hero-section" id="home">
    <div class="container">
        <div class="hero-content">
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
