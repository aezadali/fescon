<?php
require_once __DIR__ . '/config/lang.php';

$privacy = [
    'en' => [
        'title' => 'Privacy Notice | Fescon International',
        'description' => 'Privacy notice for Fescon International website inquiries.',
        'tag' => 'PRIVACY NOTICE',
        'heading' => 'Your information, handled responsibly.',
        'intro' => 'This notice explains how Fescon International handles information submitted through this website.',
        'sections' => [
            ['What we collect', 'When you submit an inquiry, we collect the information you provide: your name, email address, phone number, subject, and message.'],
            ['How we use it', 'We use your information only to respond to your inquiry, assess a potential project or business opportunity, and provide requested follow-up.'],
            ['How we protect it', 'Access to website administration is restricted. We use technical controls designed to protect submitted information from unauthorized access or misuse.'],
            ['Sharing and retention', 'We do not sell personal information. We share it only when necessary to respond to your inquiry, meet a legal obligation, or protect our rights. Information is retained only for as long as needed for these purposes.'],
            ['Your choices', 'You may request access, correction, or deletion of your inquiry information by contacting us at info@fesconinternational.com.'],
        ],
        'back' => 'Back to Home',
    ],
    'ar' => [
        'title' => 'إشعار الخصوصية | فيسكون العالمية',
        'description' => 'إشعار الخصوصية لاستفسارات موقع فيسكون العالمية.',
        'tag' => 'إشعار الخصوصية',
        'heading' => 'نتعامل مع معلوماتك بمسؤولية.',
        'intro' => 'يوضح هذا الإشعار كيفية تعامل فيسكون العالمية مع المعلومات المقدمة عبر هذا الموقع.',
        'sections' => [
            ['المعلومات التي نجمعها', 'عند إرسال استفسار، نجمع المعلومات التي تقدمها: الاسم والبريد الإلكتروني ورقم الهاتف والموضوع والرسالة.'],
            ['كيفية استخدام المعلومات', 'نستخدم معلوماتك فقط للرد على استفسارك وتقييم فرصة مشروع أو تعاون محتمل وتقديم المتابعة التي طلبتها.'],
            ['كيفية حمايتها', 'يقتصر الوصول إلى إدارة الموقع على الأشخاص المصرح لهم. ونستخدم ضوابط تقنية مصممة لحماية المعلومات المرسلة من الوصول غير المصرح به أو سوء الاستخدام.'],
            ['المشاركة والاحتفاظ', 'لا نبيع المعلومات الشخصية. ولا نشاركها إلا عند الضرورة للرد على استفسارك أو للوفاء بالتزام قانوني أو لحماية حقوقنا. نحتفظ بالمعلومات فقط للمدة اللازمة لهذه الأغراض.'],
            ['خياراتك', 'يمكنك طلب الوصول إلى معلومات استفسارك أو تصحيحها أو حذفها عبر التواصل معنا على info@fesconinternational.com.'],
        ],
        'back' => 'العودة إلى الرئيسية',
    ],
];

$content = $privacy[$lang];
$pageTitle = $content['title'];
$pageDescription = $content['description'];
require_once __DIR__ . '/includes/header.php';
?>

<main class="privacy-page">
    <section class="privacy-hero">
        <div class="container text-center">
            <span class="section-tag"><?php echo $content['tag']; ?></span>
            <h1><?php echo $content['heading']; ?></h1>
            <p><?php echo $content['intro']; ?></p>
        </div>
    </section>
    <section class="privacy-content">
        <div class="container privacy-card">
            <?php foreach ($content['sections'] as $section): ?>
                <div class="privacy-item">
                    <h2><?php echo $section[0]; ?></h2>
                    <p><?php echo $section[1]; ?></p>
                </div>
            <?php endforeach; ?>
            <a class="ceo-bio-back privacy-back" href="index.php?lang=<?php echo rawurlencode($lang); ?>#home">
                <i class="fas fa-arrow-<?php echo $lang === 'ar' ? 'right' : 'left'; ?>" aria-hidden="true"></i>
                <?php echo $content['back']; ?>
            </a>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
