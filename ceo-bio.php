<?php
require_once __DIR__ . '/config/lang.php';

$bio = [
    'en' => [
        'page_title' => 'Engr. Ehsan Ullah | CEO & Founder | Fescon International',
        'page_description' => 'Executive profile of Engr. Ehsan Ullah, CEO and Founder of Fescon International.',
        'tag' => 'EXECUTIVE PROFILE',
        'name' => 'ENGR. EHSAN ULLAH',
        'title' => 'CEO & Founder',
        'intro' => 'Engr. Ehsan Ullah is an engineer, entrepreneur and business leader with more than 15 years of experience across engineering, construction, manufacturing, agriculture, livestock, food and beverage, furniture and business development. He holds an MSc in Electrical Engineering (Power).',
        'p2' => 'As Founder and CEO, he leads a diversified portfolio of businesses with international operations and interests across Oman, Türkiye, Pakistan and Qatar. His experience includes engineering and project execution, industrial manufacturing, construction, corporate farming and livestock, food production, trading and business development.',
        'p3' => 'His business portfolio includes FESCON International in Oman; real estate and construction activities in Türkiye; FESCON, INNOX, Techno Cables and Vatan Group of Companies in Pakistan; and trading and restaurant operations in Qatar.',
        'p4' => 'Through the Vatan Group of Companies, his business interests extend across food and beverage, chemicals, cables, furniture, agriculture and livestock.',
        'p5' => 'Engr. Ehsan Ullah’s focus is on building professionally managed businesses, developing international partnerships and creating sustainable long-term growth across diverse industries.',
        'reach_label' => 'International Reach',
        'sectors_label' => 'Business Sectors',
        'countries' => '🇴🇲 Oman  |  🇹🇷 Türkiye  |  🇵🇰 Pakistan  |  🇶🇦 Qatar',
        'sectors' => 'Engineering • Construction • Manufacturing • Agriculture • Food • Investment • Business Development',
        'back' => 'Back to Leadership',
    ],
    'ar' => [
        'page_title' => 'المهندس إحسان الله | الرئيس التنفيذي والمؤسس | فيسكون العالمية',
        'page_description' => 'الملف التنفيذي للمهندس إحسان الله، الرئيس التنفيذي والمؤسس لشركة فيسكون العالمية.',
        'tag' => 'الملف التنفيذي',
        'name' => 'المهندس إحسان الله',
        'title' => 'الرئيس التنفيذي والمؤسس',
        'intro' => 'المهندس إحسان الله مهندس ورائد أعمال وقائد أعمال يتمتع بخبرة تزيد على 15 عاماً في مجالات الهندسة والإنشاءات والتصنيع والزراعة والثروة الحيوانية والأغذية والمشروبات والأثاث وتطوير الأعمال. وهو حاصل على درجة الماجستير في الهندسة الكهربائية (القوى).',
        'p2' => 'بصفته المؤسس والرئيس التنفيذي، يقود محفظة متنوعة من الأعمال ذات عمليات واهتمامات دولية في عُمان وتركيا وباكستان وقطر. وتشمل خبرته الهندسة وتنفيذ المشاريع والتصنيع الصناعي والإنشاءات والزراعة المؤسسية والثروة الحيوانية وإنتاج الأغذية والتجارة وتطوير الأعمال.',
        'p3' => 'تشمل محفظة أعماله شركة فيسكون العالمية في عُمان، وأنشطة العقارات والإنشاءات في تركيا، وشركات فيسكون وإنوكس وتكنو كيبلز ومجموعة شركات وطن في باكستان، إلى جانب أنشطة التجارة والمطاعم في قطر.',
        'p4' => 'ومن خلال مجموعة شركات وطن، تمتد اهتماماته التجارية إلى الأغذية والمشروبات والكيماويات والكابلات والأثاث والزراعة والثروة الحيوانية.',
        'p5' => 'يركز المهندس إحسان الله على بناء أعمال تُدار باحتراف، وتطوير شراكات دولية، وتحقيق نمو مستدام طويل الأمد عبر قطاعات متنوعة.',
        'reach_label' => 'الانتشار الدولي',
        'sectors_label' => 'قطاعات الأعمال',
        'countries' => '🇴🇲 عُمان  |  🇹🇷 تركيا  |  🇵🇰 باكستان  |  🇶🇦 قطر',
        'sectors' => 'الهندسة • الإنشاءات • التصنيع • الزراعة • الأغذية • الاستثمار • تطوير الأعمال',
        'back' => 'العودة إلى القيادة',
    ],
];

$content = $bio[$lang];
$pageTitle = $content['page_title'];
$pageDescription = $content['page_description'];
require_once __DIR__ . '/includes/header.php';
?>

<main class="ceo-bio-page">
    <section class="ceo-bio-hero">
        <div class="container ceo-bio-hero-grid">
            <div class="ceo-bio-photo-wrap">
                <img src="assets/images/ehsan-ullah-bio.jpg" alt="<?php echo $content['name']; ?>" class="ceo-bio-photo">
            </div>

            <div class="ceo-bio-intro">
                <span class="section-tag"><?php echo $content['tag']; ?></span>
                <h1><?php echo $content['name']; ?></h1>
                <p class="ceo-bio-role"><?php echo $content['title']; ?></p>
                <p class="ceo-bio-lead"><?php echo $content['intro']; ?></p>
                <a href="index.php?lang=<?php echo rawurlencode($lang); ?>#leadership" class="ceo-bio-back">
                    <i class="fas fa-arrow-<?php echo $lang === 'ar' ? 'right' : 'left'; ?>" aria-hidden="true"></i>
                    <?php echo $content['back']; ?>
                </a>
            </div>
        </div>
    </section>

    <section class="ceo-bio-content">
        <div class="container ceo-bio-layout">
            <article class="ceo-bio-story">
                <p><?php echo $content['p2']; ?></p>
                <p><?php echo $content['p3']; ?></p>
                <p><?php echo $content['p4']; ?></p>
                <p><?php echo $content['p5']; ?></p>
            </article>

            <aside class="ceo-bio-highlights" aria-label="<?php echo $content['reach_label']; ?>">
                <div class="ceo-highlight">
                    <span class="ceo-highlight-icon"><i class="fas fa-globe-americas" aria-hidden="true"></i></span>
                    <div>
                        <h2><?php echo $content['reach_label']; ?></h2>
                        <p><?php echo $content['countries']; ?></p>
                    </div>
                </div>
                <div class="ceo-highlight">
                    <span class="ceo-highlight-icon"><i class="fas fa-industry" aria-hidden="true"></i></span>
                    <div>
                        <h2><?php echo $content['sectors_label']; ?></h2>
                        <p><?php echo $content['sectors']; ?></p>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
