<section class="about-section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-text">
                <h2 class="section-title"><?php echo t('about_title'); ?></h2>
                
                <div class="about-branch-box" style="margin-top:1.5rem;">
                    <i class="fas fa-network-wired text-red" style="margin-right:8px;"></i>
                    <?php echo t('about_branch_note'); ?>
                </div>

                <div class="mission-vision-grid">
                    <div class="mv-card">
                        <div class="mv-icon"><i class="fas fa-bullseye"></i></div>
                        <h3 class="mv-title"><?php echo t('about_mission_title'); ?></h3>
                        <p class="mv-text"><?php echo t('about_mission_text'); ?></p>
                    </div>
                    <div class="mv-card">
                        <div class="mv-icon"><i class="fas fa-eye"></i></div>
                        <h3 class="mv-title"><?php echo t('about_vision_title'); ?></h3>
                        <p class="mv-text"><?php echo t('about_vision_text'); ?></p>
                    </div>
                </div>
            </div>

            <div class="about-visual">
                <div class="about-img-box">
                    <img src="assets/images/project_civil.jpg" alt="Fescon Civil Construction Landmark">
                </div>
                <div class="about-experience-badge">
                    <div class="num">15+</div>
                    <div class="txt"><?php echo ($lang === 'ar') ? 'سنوات من الريادة' : 'Years of Excellence'; ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Grid -->
<section class="values-section">
    <div class="container">
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-user-shield"></i></div>
                <h4 class="value-title"><?php echo t('val_prof_title'); ?></h4>
                <p class="value-desc"><?php echo t('val_prof_desc'); ?></p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-medal"></i></div>
                <h4 class="value-title"><?php echo t('val_qual_title'); ?></h4>
                <p class="value-desc"><?php echo t('val_qual_desc'); ?></p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-clock"></i></div>
                <h4 class="value-title"><?php echo t('val_time_title'); ?></h4>
                <p class="value-desc"><?php echo t('val_time_desc'); ?></p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-leaf"></i></div>
                <h4 class="value-title"><?php echo t('val_sust_title'); ?></h4>
                <p class="value-desc"><?php echo t('val_sust_desc'); ?></p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-road"></i></div>
                <h4 class="value-title"><?php echo t('val_civil_title'); ?></h4>
                <p class="value-desc"><?php echo t('val_civil_desc'); ?></p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-screwdriver-wrench"></i></div>
                <h4 class="value-title"><?php echo t('val_mep_title'); ?></h4>
                <p class="value-desc"><?php echo t('val_mep_desc'); ?></p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-gas-pump"></i></div>
                <h4 class="value-title"><?php echo t('val_oilgas_title'); ?></h4>
                <p class="value-desc"><?php echo t('val_oilgas_desc'); ?></p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-network-wired"></i></div>
                <h4 class="value-title"><?php echo t('val_dist_title'); ?></h4>
                <p class="value-desc"><?php echo t('val_dist_desc'); ?></p>
            </div>
        </div>
    </div>
</section>
