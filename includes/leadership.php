<section class="leadership-section" id="leadership">
    <div class="container text-center">
        <span class="section-tag"><?php echo t('lead_tag'); ?></span>
        <h2 class="section-title"><?php echo t('lead_title'); ?></h2>

        <div class="leadership-grid">
            <!-- CEO Card -->
            <div class="executive-card">
                <div class="executive-header">
                    <img src="assets/images/ehsan_ullah.jpg" alt="<?php echo htmlspecialchars(t('ceo_name')); ?>" class="executive-avatar" style="cursor:pointer;" title="<?php echo ($lang === 'ar') ? 'انقر لعرض الصورة' : 'Click to view photo'; ?>" onclick="openModal('<?php echo htmlspecialchars(t('ceo_name') . ' - ' . t('ceo_title'), ENT_QUOTES); ?>', 'assets/images/ehsan_ullah.jpg')">
                    <div class="executive-meta text-left">
                        <h3><?php echo t('ceo_name'); ?></h3>
                        <div class="executive-title"><?php echo t('ceo_title'); ?></div>
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
                    <img src="assets/images/abdullah_albasrawi.jpg" alt="<?php echo htmlspecialchars(t('dir_name')); ?>" class="executive-avatar" style="cursor:pointer;" title="<?php echo ($lang === 'ar') ? 'انقر لعرض الصورة' : 'Click to view photo'; ?>" onclick="openModal('<?php echo htmlspecialchars(t('dir_name') . ' - ' . t('dir_title'), ENT_QUOTES); ?>', 'assets/images/abdullah_albasrawi.jpg')">
                    <div class="executive-meta text-left">
                        <h3><?php echo t('dir_name'); ?></h3>
                        <div class="executive-title"><?php echo t('dir_title'); ?></div>
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
    </div>
</section>
