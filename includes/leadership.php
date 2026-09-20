<section class="leadership-section" id="leadership">
    <div class="container text-center">
        <span class="section-tag"><?php echo t('lead_tag'); ?></span>
        <h2 class="section-title"><?php echo t('lead_title'); ?></h2>
        <p class="section-subtitle"><?php echo t('lead_subtitle'); ?></p>

        <div class="leadership-grid">
            <!-- CEO Card -->
            <div class="executive-card">
                <div class="executive-header">
                    <img src="assets/images/ehsan_ullah.jpg" alt="Engr. Ehsan Ullah" class="executive-avatar" style="cursor:pointer;" title="Click to view full photo" onclick="openModal('Engr. Ehsan Ullah - CEO & Director', 'assets/images/ehsan_ullah.jpg')">
                    <div class="executive-meta text-left">
                        <h3><?php echo ($lang === 'ar') ? t('ceo_name_ar') : t('ceo_name'); ?></h3>
                        <div class="executive-title"><?php echo ($lang === 'ar') ? t('ceo_title_ar') : t('ceo_title'); ?></div>
                        <?php if ($lang === 'ar'): ?>
                            <div class="executive-arabic"><?php echo t('ceo_name_ar'); ?> - <?php echo t('ceo_title_ar'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="executive-body">
                    <div class="executive-quote">
                        <h4 style="margin-bottom:0.5rem; color:var(--primary-navy);"><?php echo t('ceo_msg_title'); ?></h4>
                        <p><?php echo t('ceo_msg_p1'); ?></p>
                        <p style="margin-top:0.5rem;"><?php echo t('ceo_msg_p2'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Director Card -->
            <div class="executive-card">
                <div class="executive-header">
                    <img src="assets/images/abdullah_albasrawi.jpg" alt="Abdullah Muhammad Salim Al-Basrawi" class="executive-avatar" style="cursor:pointer;" title="Click to view full photo" onclick="openModal('Abdullah Muhammad Salim Al-Basrawi - Director', 'assets/images/abdullah_albasrawi.jpg')">
                    <div class="executive-meta text-left">
                        <h3><?php echo ($lang === 'ar') ? t('dir_name_ar') : t('dir_name'); ?></h3>
                        <div class="executive-title"><?php echo ($lang === 'ar') ? t('dir_title_ar') : t('dir_title'); ?></div>
                        <?php if ($lang === 'ar'): ?>
                            <div class="executive-arabic"><?php echo t('dir_name_ar'); ?> - <?php echo t('dir_title_ar'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="executive-body">
                    <div class="executive-quote">
                        <h4 style="margin-bottom:0.5rem; color:var(--primary-navy);"><?php echo t('dir_msg_title'); ?></h4>
                        <p><?php echo t('dir_msg_p1'); ?></p>
                        <p style="margin-top:0.5rem;"><?php echo t('dir_msg_p2'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tagline Banner -->
        <div class="tagline-banner">
            <div class="tagline-text text-center" style="width:100%;">
                <h4><?php echo ($lang === 'ar') ? 'هندسة لمستقبل أفضل' : 'ENGINEERING A BETTER TOMORROW'; ?></h4>
                <p><?php echo ($lang === 'ar') ? 'مستقبل بنيتنا التحتية يبدأ اليوم برؤية ودقة متكاملة' : 'Building tomorrow’s infrastructure through vision, precision, and excellence.'; ?></p>
            </div>
        </div>
    </div>
</section>
