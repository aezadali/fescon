    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img src="<?php echo ($lang === 'ar') ? 'assets/images/logo_arabic.jpg' : 'assets/images/logo_english.jpg'; ?>" alt="Fescon International Logo">
                    <p><?php echo t('footer_about'); ?></p>
                </div>

                <div>
                    <h4 class="footer-title"><?php echo t('footer_quick_links'); ?></h4>
                    <ul class="footer-links">
                        <li><a href="#home"><?php echo t('nav_home'); ?></a></li>
                        <li><a href="#about"><?php echo t('nav_about'); ?></a></li>
                        <li><a href="#leadership"><?php echo t('nav_leadership'); ?></a></li>
                        <li><a href="#certifications"><?php echo t('nav_certifications'); ?></a></li>
                        <li><a href="#projects"><?php echo t('nav_projects'); ?></a></li>
                        <li><a href="#contact"><?php echo t('nav_contact'); ?></a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title"><?php echo t('footer_services'); ?></h4>
                    <ul class="footer-links">
                        <li><a href="#services"><?php echo t('serv_1_title'); ?></a></li>
                        <li><a href="#services"><?php echo t('serv_2_title'); ?></a></li>
                        <li><a href="#services"><?php echo t('serv_3_title'); ?></a></li>
                        <li><a href="#services"><?php echo t('serv_4_title'); ?></a></li>
                        <li><a href="#services"><?php echo t('serv_5_title'); ?></a></li>
                        <li><a href="#services"><?php echo t('serv_6_title'); ?></a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title"><?php echo t('footer_cert'); ?></h4>
                    <ul class="footer-links">
                        <li><i class="fas fa-shield-alt text-gold"></i> CR No: 1667600</li>
                        <li><i class="fas fa-award text-gold"></i> OCCI Grade: Excellent</li>
                        <li><i class="fas fa-file-invoice text-gold"></i> Tax Card: 151400492</li>
                        <li><i class="fas fa-phone-alt text-gold"></i> +968 9919 9710</li>
                        <li><i class="fas fa-envelope text-gold"></i> info@fesconinternational.com</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div>&copy; <?php echo date('Y'); ?> <?php echo t('footer_rights'); ?></div>
                <div class="text-gold" style="font-weight:700;"><?php echo t('footer_tagline'); ?></div>
            </div>
        </div>
    </footer>

    <!-- Document & Card Modal Viewer -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3 id="modalTitle">Document Preview</h3>
                <button class="modal-close" id="modalClose">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Dynamic Content Loaded via JS -->
            </div>
        </div>
    </div>

    <!-- Main JavaScript -->
    <script src="assets/js/main.js"></script>
</body>
</html>
