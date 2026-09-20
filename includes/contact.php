<section class="contact-section" id="contact">
    <div class="container">
        <div class="text-center">
            <span class="section-tag"><?php echo t('contact_tag'); ?></span>
            <h2 class="section-title"><?php echo t('contact_title'); ?></h2>
            <p class="section-subtitle"><?php echo t('contact_subtitle'); ?></p>
        </div>

        <div class="contact-grid">
            <!-- Contact Info Box -->
            <div class="contact-info-box">
                <h3><?php echo t('contact_info_title'); ?></h3>
                
                <!-- Head Office Oman -->
                <div class="contact-office-card">
                    <h4><i class="fas fa-landmark"></i> <?php echo t('contact_oman_title'); ?></h4>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo t('contact_oman_address'); ?></p>
                    <p><i class="fas fa-shield-alt"></i> <?php echo t('contact_oman_cr'); ?></p>
                    <p><i class="fas fa-phone-alt"></i> <a href="tel:+96899199710" style="color:#FFF; font-weight:700;"><?php echo t('contact_oman_phone'); ?></a></p>
                    <p><i class="fas fa-envelope"></i> info@fesconinternational.com</p>
                </div>

                <!-- Branch Office Pakistan -->
                <div class="contact-office-card">
                    <h4><i class="fas fa-code-branch"></i> <?php echo t('contact_pak_title'); ?></h4>
                    <p><i class="fas fa-info-circle"></i> <?php echo t('contact_pak_desc'); ?></p>
                    <p><i class="fas fa-globe"></i> <a href="https://fescon.com.pk/" target="_blank" style="color:var(--accent-gold-light);"><?php echo t('contact_pak_website'); ?></a></p>
                </div>
            </div>

            <!-- Contact Form Box -->
            <div class="contact-form-box">
                <div class="form-alert" id="formAlert"></div>
                
                <form id="contactForm" method="POST">
                    <div class="form-group">
                        <label class="form-label"><?php echo t('form_name'); ?> *</label>
                        <input type="text" name="name" class="form-control" placeholder="<?php echo t('form_name_ph'); ?>" required>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                        <div class="form-group">
                            <label class="form-label"><?php echo t('form_email'); ?> *</label>
                            <input type="email" name="email" class="form-control" placeholder="<?php echo t('form_email_ph'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?php echo t('form_phone'); ?></label>
                            <input type="tel" name="phone" class="form-control" placeholder="<?php echo t('form_phone_ph'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?php echo t('form_subject'); ?></label>
                        <input type="text" name="subject" class="form-control" placeholder="<?php echo t('form_subject_ph'); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?php echo t('form_message'); ?> *</label>
                        <textarea name="message" class="form-control" placeholder="<?php echo t('form_message_ph'); ?>" required></textarea>
                    </div>

                    <button type="submit" id="submitBtn" class="submit-btn-theme">
                        <i class="fas fa-paper-plane"></i>
                        <span><?php echo t('form_submit'); ?></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
