<section class="projects-section" id="projects">
    <div class="container text-center" style="max-width: 100%; overflow: hidden; padding-left: 0; padding-right: 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            <span class="section-tag"><?php echo t('proj_tag'); ?></span>
            <h2 class="section-title"><?php echo t('proj_title'); ?></h2>

            <!-- Category Filters -->
            <div class="portfolio-filter">
                <button class="filter-btn active" data-filter="all"><?php echo t('proj_filter_all'); ?></button>
                <button class="filter-btn" data-filter="grid"><?php echo t('proj_filter_grid'); ?></button>
                <button class="filter-btn" data-filter="dist"><?php echo t('proj_filter_dist'); ?></button>
                <button class="filter-btn" data-filter="lighting"><?php echo t('proj_filter_lighting'); ?></button>
                <button class="filter-btn" data-filter="civil"><?php echo t('proj_filter_civil'); ?></button>
                <button class="filter-btn" data-filter="mep"><?php echo t('proj_filter_mep'); ?></button>
                <button class="filter-btn" data-filter="oilgas"><?php echo t('proj_filter_oilgas'); ?></button>
                <button class="filter-btn" data-filter="epc"><?php echo t('proj_filter_epc'); ?></button>
                <button class="filter-btn" data-filter="om"><?php echo t('proj_filter_om'); ?></button>
            </div>
        </div>

        <!-- Horizontal Marquee Track Wrapper with drag-to-scroll & auto-move -->
        <div class="projects-marquee-wrapper" id="projectsMarqueeWrapper">
            <div class="projects-marquee-track text-left" id="projectsTrack">
                <!-- Project 1: Street Lighting -->
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

                <!-- Project 2: Grid Stations -->
                <div class="project-card" data-category="grid">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_substation.jpg" alt="132kV Primary Grid Station">
                        <span class="project-cat-tag"><?php echo t('proj_2_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_2_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_2_desc'); ?></p>
                    </div>
                </div>

                <!-- Project 3: Distribution Networks -->
                <div class="project-card" data-category="dist">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_distribution.jpg" alt="Underground Power Distribution">
                        <span class="project-cat-tag"><?php echo t('proj_3_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_3_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_3_desc'); ?></p>
                    </div>
                </div>

                <!-- Project 4: Civil Infrastructure -->
                <div class="project-card" data-category="civil">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_civil_infra.jpg" alt="Civil Infrastructure Development">
                        <span class="project-cat-tag"><?php echo t('proj_4_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_4_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_4_desc'); ?></p>
                    </div>
                </div>

                <!-- Project 5: MEP Works -->
                <div class="project-card" data-category="mep">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_mep.jpg" alt="Commercial & Industrial MEP Integration">
                        <span class="project-cat-tag"><?php echo t('proj_5_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_5_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_5_desc'); ?></p>
                    </div>
                </div>

                <!-- Project 6: Oil & Gas -->
                <div class="project-card" data-category="oilgas">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_oilgas.jpg" alt="Oil & Gas Electrical & Instrumentation">
                        <span class="project-cat-tag"><?php echo t('proj_6_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_6_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_6_desc'); ?></p>
                    </div>
                </div>

                <!-- Project 7: Turnkey EPC -->
                <div class="project-card" data-category="epc">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_transmission_epc.jpg" alt="High-Voltage Transmission Line EPC">
                        <span class="project-cat-tag"><?php echo t('proj_7_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_7_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_7_desc'); ?></p>
                    </div>
                </div>

                <!-- Project 8: Operation & Maintenance -->
                <div class="project-card" data-category="om">
                    <div class="project-img-wrapper">
                        <img src="assets/images/project_om_maintenance.jpg" alt="Substation & Industrial Plant O&M">
                        <span class="project-cat-tag"><?php echo t('proj_8_cat'); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo t('proj_8_title'); ?></h3>
                        <p class="project-desc"><?php echo t('proj_8_desc'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Controls placed below the project row -->
        <div class="projects-nav-controls">
            <button type="button" class="proj-nav-btn prev-btn" id="projPrevBtn" aria-label="Previous Projects" title="Scroll Left"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="proj-nav-btn next-btn" id="projNextBtn" aria-label="Next Projects" title="Scroll Right"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</section>
