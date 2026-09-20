<section class="certifications-section" id="certifications">
    <div class="container text-center">
        <span class="section-tag" style="background:rgba(255,255,255,0.1); color:var(--accent-gold-light); border-color:rgba(197,155,39,0.3);"><?php echo t('cert_tag'); ?></span>
        <h2 class="section-title"><?php echo t('cert_title'); ?></h2>
        <p class="section-subtitle"><?php echo t('cert_subtitle'); ?></p>

        <div class="cert-grid text-left">
            <!-- Cert 1: Oman Chamber of Commerce -->
            <div class="cert-card">
                <div>
                    <div class="cert-badge">
                        <i class="fas fa-award"></i>
                        <span>Grade: Excellent (الممتازة)</span>
                    </div>
                    <div class="cert-header">
                        <h3><?php echo t('cert_1_title'); ?></h3>
                        <div class="sub"><?php echo t('cert_1_subtitle'); ?></div>
                    </div>
                    <ul class="cert-details-list">
                        <li><i class="fas fa-id-card"></i> <?php echo t('cert_1_cr'); ?></li>
                        <li><i class="fas fa-star"></i> <?php echo t('cert_1_grade'); ?></li>
                        <li><i class="fas fa-hashtag"></i> <?php echo t('cert_1_occi'); ?></li>
                        <li><i class="fas fa-map-marker-alt"></i> <?php echo t('cert_1_location'); ?></li>
                    </ul>
                </div>
                <button class="btn btn-primary" style="width:100%; font-size:0.9rem;" onclick="openModal('<?php echo t('cert_1_title'); ?>', 'assets/docs/Oman_Chamber_Certificate.pdf', true)">
                    <i class="fas fa-file-pdf"></i>
                    <span><?php echo t('cert_1_btn'); ?></span>
                </button>
            </div>

            <!-- Cert 2: Tax Card Authority Oman -->
            <div class="cert-card">
                <div>
                    <div class="cert-badge" style="background:var(--primary-red); color:#FFFFFF;">
                        <i class="fas fa-shield-alt"></i>
                        <span>Tax Registered Entity</span>
                    </div>
                    <div class="cert-header">
                        <h3><?php echo t('cert_2_title'); ?></h3>
                        <div class="sub"><?php echo t('cert_2_subtitle'); ?></div>
                    </div>
                    <ul class="cert-details-list">
                        <li><i class="fas fa-credit-card"></i> <?php echo t('cert_2_card_no'); ?></li>
                        <li><i class="fas fa-fingerprint"></i> <?php echo t('cert_2_tin'); ?></li>
                        <li><i class="fas fa-file-invoice"></i> <?php echo t('cert_2_license'); ?></li>
                        <li><i class="fas fa-calendar-check"></i> <?php echo t('cert_2_expiry'); ?></li>
                    </ul>
                </div>
                <button class="btn btn-primary" style="width:100%; font-size:0.9rem;" onclick="openModal('<?php echo t('cert_2_title'); ?>', 'assets/docs/Oman_Tax_Card.pdf', true)">
                    <i class="fas fa-file-pdf"></i>
                    <span><?php echo t('cert_2_btn'); ?></span>
                </button>
            </div>
        </div>
    </div>
</section>
