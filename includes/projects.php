<?php
// Load projects from JSON store
$projectsJsonFile = __DIR__ . '/../config/projects.json';
$allProjects = [];
if (file_exists($projectsJsonFile)) {
    $rawProjects = json_decode(file_get_contents($projectsJsonFile), true);
    if (is_array($rawProjects)) {
        $allProjects = $rawProjects;
    }
}

// Category translation labels map with dynamic categories.json support
$categoriesJsonFile = __DIR__ . '/../config/categories.json';
$categoryStore = [];
if (file_exists($categoriesJsonFile)) {
    $rawCats = json_decode(file_get_contents($categoriesJsonFile), true);
    if (is_array($rawCats)) {
        $categoryStore = $rawCats;
    }
}

$categoryMap = [
    'grid' => t('proj_filter_grid'),
    'dist' => t('proj_filter_dist'),
    'lighting' => t('proj_filter_lighting'),
    'civil' => t('proj_filter_civil'),
    'mep' => t('proj_filter_mep'),
    'oilgas' => t('proj_filter_oilgas'),
    'epc' => t('proj_filter_epc'),
    'om' => t('proj_filter_om'),
];

// Merge custom or updated categories from store
foreach ($categoryStore as $cKey => $cVal) {
    if (!empty($cVal[$lang])) {
        $categoryMap[$cKey] = $cVal[$lang];
    } elseif (!empty($cVal['en'])) {
        $categoryMap[$cKey] = $cVal['en'];
    }
}

// Determine active categories for filters
$activeCategories = [];
foreach ($allProjects as $p) {
    $cat = $p['category'] ?? '';
    if ($cat && !in_array($cat, $activeCategories)) {
        $activeCategories[] = $cat;
    }
}
?>
<section class="projects-section" id="projects">
    <div class="container text-center" style="max-width: 100%; overflow: hidden; padding-left: 0; padding-right: 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            <span class="section-tag"><?php echo t('proj_tag'); ?></span>
            <h2 class="section-title"><?php echo t('proj_title'); ?></h2>

            <!-- Category Filters -->
            <div class="portfolio-filter">
                <button class="filter-btn active" data-filter="all"><?php echo t('proj_filter_all'); ?></button>
                <?php foreach ($activeCategories as $catKey): ?>
                    <button class="filter-btn" data-filter="<?php echo htmlspecialchars($catKey); ?>">
                        <?php echo htmlspecialchars($categoryMap[$catKey] ?? ucfirst($catKey)); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Horizontal Marquee Track Wrapper with drag-to-scroll & auto-move -->
        <div class="projects-marquee-wrapper" id="projectsMarqueeWrapper">
            <div class="projects-marquee-track text-left" id="projectsTrack">
                <?php foreach ($allProjects as $proj): 
                    $langData = $proj[$lang] ?? $proj['en'] ?? [];
                    $title = $langData['title'] ?? '';
                    $catLabel = $langData['category_label'] ?? ($categoryMap[$proj['category'] ?? ''] ?? '');
                    $desc = $langData['desc'] ?? '';
                    $image = !empty($proj['image']) ? $proj['image'] : 'assets/images/project_civil_infra.jpg';
                    $cat = htmlspecialchars($proj['category'] ?? 'general');
                ?>
                <div class="project-card" data-category="<?php echo $cat; ?>">
                    <div class="project-img-wrapper">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>">
                        <span class="project-cat-tag"><?php echo htmlspecialchars($catLabel); ?></span>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo htmlspecialchars($title); ?></h3>
                        <p class="project-desc"><?php echo htmlspecialchars($desc); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Scroll Controls placed below the project row -->
        <div class="projects-nav-controls">
            <button type="button" class="proj-nav-btn prev-btn" id="projPrevBtn" aria-label="Previous Projects" title="Scroll Left"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="proj-nav-btn next-btn" id="projNextBtn" aria-label="Next Projects" title="Scroll Right"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</section>
