<?php
require_once __DIR__ . '/../config/lang.php';
$logoSrc = ($lang === 'ar') ? 'assets/images/logo_arabic.jpg' : 'assets/images/logo_english.jpg';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('site_title'); ?></title>
    <meta name="description" content="<?php echo t('site_desc'); ?>">
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Top Contact & Reg Bar -->
    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-info">
                <div class="top-info-item">
                    <i class="fas fa-file-contract"></i>
                    <span><?php echo t('top_cr'); ?></span>
                </div>
                <div class="top-info-item">
                    <i class="fas fa-award"></i>
                    <span><?php echo t('top_grade'); ?></span>
                </div>
            </div>
            <div class="top-info">
                <div class="top-info-item">
                    <i class="fas fa-phone-alt"></i>
                    <a href="tel:+96899199710"><?php echo t('top_phone'); ?></a>
                </div>
                <div class="top-info-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:info@fesconinternational.com"><?php echo t('top_email'); ?></a>
                </div>
                <span class="top-badge"><?php echo ($lang === 'ar') ? 'سلطنة عمان' : 'Sultanate of Oman'; ?></span>
            </div>
        </div>
    </div>

    <!-- Main Sticky Header -->
    <header class="main-header">
        <div class="container header-container">
            <a href="index.php?lang=<?php echo $lang; ?>" class="brand-logo">
                <img src="<?php echo $logoSrc; ?>" alt="Fescon International LLC Logo">
            </a>

            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="#home" class="nav-link active"><?php echo t('nav_home'); ?></a></li>
                    <li><a href="#about" class="nav-link"><?php echo t('nav_about'); ?></a></li>
                    <li><a href="#leadership" class="nav-link"><?php echo t('nav_leadership'); ?></a></li>
                    <li><a href="#services" class="nav-link"><?php echo t('nav_services'); ?></a></li>
                    <li><a href="#certifications" class="nav-link"><?php echo t('nav_certifications'); ?></a></li>
                    <li><a href="#projects" class="nav-link"><?php echo t('nav_projects'); ?></a></li>
                    <li><a href="#contact" class="nav-link"><?php echo t('nav_contact'); ?></a></li>
                </ul>
            </nav>

            <div class="nav-actions">
                <a href="<?php echo t('lang_switch_url'); ?>" class="lang-btn">
                    <i class="fas fa-globe"></i>
                    <span><?php echo t('lang_toggle'); ?></span>
                </a>
                <div class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>
