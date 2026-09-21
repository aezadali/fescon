<section class="projects-section" id="projects">
    <div class="container text-center" style="max-width: 100%; overflow: hidden; padding-left: 0; padding-right: 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            <span class="section-tag"><?php echo t('proj_tag'); ?></span>
            <h2 class="section-title"><?php echo t('proj_title'); ?></h2>
            <p class="section-subtitle"><?php echo t('proj_subtitle'); ?></p>

            <!-- Category Filters -->
            <div class="portfolio-filter">
                <button class="filter-btn active" data-filter="all"><?php echo t('proj_filter_all'); ?></button>
                <button class="filter-btn" data-filter="lighting"><?php echo t('proj_filter_lighting'); ?></button>
                <button class="filter-btn" data-filter="dist"><?php echo t('proj_filter_dist'); ?></button>
                <button class="filter-btn" data-filter="grid"><?php echo t('proj_filter_grid'); ?></button>
            </div>
        </div>

        <!-- Horizontal Marquee Track Wrapper -->
        <div class="projects-marquee-wrapper">
            <div class="projects-marquee-track text-left" id="projectsTrack">
                <!-- Original 6 Project Cards -->
                <div class="project-card" data-category="lighting">
                    <div class="project-img-wrapper">
                        <img src="assets/images/street_lighting.jpg" alt="Expressway LED Street Lighting">
                        <span class="project-cat-tag"><?php echo t('proj_1_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_1_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_1_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card" data-category="grid">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_substation.jpg" alt="Primary Grid Station">
                        <span class="project-cat-tag"><?php echo t('proj_2_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_2_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_2_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card" data-category="dist">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_civil.jpg" alt="Electrical Distribution Network">
                        <span class="project-cat-tag"><?php echo t('proj_3_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_3_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_3_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card" data-category="lighting">
                    <div class="project-img-wrapper">
                        <img src="assets/images/street_lighting.jpg" alt="Highway Dual Carriageway Lighting">
                        <span class="project-cat-tag"><?php echo t('proj_4_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_4_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_4_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card" data-category="grid">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_substation.jpg" alt="132kV Substation Gantry">
                        <span class="project-cat-tag"><?php echo t('proj_5_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_5_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_5_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card" data-category="dist">
                    <div class="project-img-wrapper">
                        <img src="assets/images/parallax_about_bg.jpg" alt="Industrial Zone Power Distribution">
                        <span class="project-cat-tag"><?php echo t('proj_6_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_6_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_6_desc'); ?></p>
                    </div>
                </div>

                <!-- Duplicate Cards for Infinite Seamless Loop -->
                <div class="project-card clone-card" data-category="lighting">
                    <div class="project-img-wrapper">
                        <img src="assets/images/street_lighting.jpg" alt="Expressway LED Street Lighting">
                        <span class="project-cat-tag"><?php echo t('proj_1_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_1_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_1_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card clone-card" data-category="grid">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_substation.jpg" alt="Primary Grid Station">
                        <span class="project-cat-tag"><?php echo t('proj_2_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_2_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_2_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card clone-card" data-category="dist">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_civil.jpg" alt="Electrical Distribution Network">
                        <span class="project-cat-tag"><?php echo t('proj_3_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_3_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_3_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card clone-card" data-category="lighting">
                    <div class="project-img-wrapper">
                        <img src="assets/images/street_lighting.jpg" alt="Highway Dual Carriageway Lighting">
                        <span class="project-cat-tag"><?php echo t('proj_4_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_4_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_4_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card clone-card" data-category="grid">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_substation.jpg" alt="132kV Substation Gantry">
                        <span class="project-cat-tag"><?php echo t('proj_5_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_5_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_5_desc'); ?></p>
                    </div>
                </div>

                <div class="project-card clone-card" data-category="dist">
                    <div class="project-img-wrapper">
                        <img src="assets/images/parallax_about_bg.jpg" alt="Industrial Zone Power Distribution">
                        <span class="project-cat-tag"><?php echo t('proj_6_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_6_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_6_desc'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
