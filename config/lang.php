<?php
// Fescon International Limited - Bilingual Language Configuration (English & Arabic)
session_start();

// Check if language param is passed via URL or Session
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'ar'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // Default language
}

$lang = $_SESSION['lang'];
$isRtl = ($lang === 'ar');
$dir = $isRtl ? 'rtl' : 'ltr';

$translations = [
    'en' => [
        'site_title' => 'Fescon International LLC | Street Lighting, Distribution Networks & Grid Stations',
        'site_desc' => 'Premier Engineering Service Provider in Oman specializing in Street Lighting, Electrical Distribution Networks, and High Voltage Grid Stations.',
        'nav_home' => 'Home',
        'nav_about' => 'About Us',
        'nav_leadership' => 'Leadership',
        'nav_services' => 'Services',
        'nav_certifications' => 'Certifications',
        'nav_projects' => 'Projects',
        'nav_contact' => 'Contact Us',
        'lang_toggle' => 'العربية',
        'lang_switch_url' => '?lang=ar',
        
        // Top Bar
        'top_cr' => 'Commercial Reg: 1667600',
        'top_grade' => 'Grade: Excellent',
        'top_phone' => '+968 9919 9710',
        'top_email' => 'info@fesconinternational.com',
        
        // Hero Section
        'hero_badge' => 'Power Transmission, Distribution & Street Lighting Experts',
        'hero_title' => 'Powering Roads, Networks & Grid Infrastructure',
        'hero_subtitle' => 'Fescon International LLC delivers engineering solutions in Street Lighting Systems, Medium & Low Voltage Electrical Distribution Networks, and High-Voltage Grid Stations across Sultanate of Oman & regional operations.',
        'hero_cta_projects' => 'Explore Projects',
        'hero_cta_contact' => 'Get In Touch',
        'hero_stat_1_num' => '15+',
        'hero_stat_1_label' => 'Years of Engineering Heritage',
        'hero_stat_2_num' => '100+',
        'hero_stat_2_label' => 'Grid & Distribution Projects',
        'hero_stat_3_num' => '100%',
        'hero_stat_3_label' => 'Utility Compliance Rate',
        'hero_stat_4_num' => 'Excellent',
        'hero_stat_4_label' => 'OCCI Oman Grade',

        // About Section
        'about_tag' => 'WHO WE ARE',
        'about_title' => 'Electrical Grid & Infrastructure Specialists in Oman',
        'about_desc_1' => 'Fescon International LLC is an established engineering service provider in Muscat, Sultanate of Oman, delivering turnkey Street Lighting systems, Electrical Distribution Networks, and Grid Station development. Operating in synergy with Fescon Pvt Ltd, our execution branch, we deliver utility-grade power infrastructure.',
        'about_desc_2' => 'From municipal highway street lighting projects to complex 132/33kV grid substations and underground electrical distribution feeders, Fescon combines engineering precision, modern computational design, and strict safety compliance.',
        'about_branch_note' => 'Fescon International LLC leads turnkey projects in Street Lighting, Electrical Distribution Networks, and Grid Stations in Oman, supported by the extensive regional project portfolio of Fescon Pvt Ltd.',
        'about_mission_title' => 'Our Mission',
        'about_mission_text' => 'To illuminate communities and power nations by constructing reliable Street Lighting networks, resilient Electrical Distribution systems, and state-of-the-art Grid Stations.',
        'about_vision_title' => 'Our Vision',
        'about_vision_text' => 'To be the benchmark power engineering service provider in Oman for street lighting, grid expansion, and electrical distribution infrastructure.',

        // Core Values
        'val_prof_title' => 'Grid Precision & Safety',
        'val_prof_desc' => 'Certified high-voltage engineers providing meticulous calculation, protection coordination, and fault analysis.',
        'val_qual_title' => 'Utility Standards Quality',
        'val_qual_desc' => 'Adhering strictly to Oman grid code, municipal street lighting guidelines, and international electrical standards.',
        'val_time_title' => 'Timely Commissioning',
        'val_time_desc' => 'Punctual project energization and handover through systematic project management and testing.',
        'val_sust_title' => 'Energy Efficient Street Lighting',
        'val_sust_desc' => 'Integrating high-efficiency LED street lights, solar street lighting, and optimized distribution transformers.',

        // Leadership Section
        'lead_tag' => 'EXECUTIVE LEADERSHIP',
        'lead_title' => 'Engineered for Electrical Power & Infrastructure Excellence',
        'lead_subtitle' => 'Led by veteran engineering executives with extensive experience in utility grid installations, street lighting, and electrical network deployment.',
        'ceo_name' => 'Engr. Ehsan Ullah',
        'ceo_title' => 'Chief Executive & Director',
        'ceo_msg_title' => 'Message from the CEO',
        'ceo_msg_p1' => 'Welcome to Fescon! Our core commitment lies in delivering high-performance Street Lighting infrastructure, robust Electrical Distribution Networks, and advanced Grid Stations. We take pride in powering cities and supporting industrial growth with safety and technical mastery.',
        'ceo_msg_p2' => 'Together with our specialized engineering team in Oman and regional branch, we ensure every substation energization and street lighting network installation exceeds client expectations.',
        
        'dir_name' => 'Abdullah Muhammad Salim Al-Basrawi',
        'dir_title' => 'Director',
        'dir_msg_title' => 'Message from the Director',
        'dir_msg_p1' => 'Supporting Oman Vision 2040, Fescon International is dedicated to modernizing the Sultanate’s electrical grid, expanding street lighting across major expressways, and reinforcing regional distribution networks.',
        'dir_msg_p2' => 'Our local presence in Muscat guarantees seamless coordination with authorities, high quality execution, and lasting community impact.',

        // Services Section
        'serv_tag' => 'OUR SERVICES',
        'serv_title' => 'Street Lighting, Distribution Networks & Grid Stations',
        'serv_subtitle' => 'Turnkey engineering solutions designed to power cities, electrical networks, and high-voltage substation grid stations.',
        
        'serv_1_title' => 'Street Lighting Systems',
        'serv_1_desc' => 'Complete design, pole erection, LED luminaire installation, underground cabling, feeder control panels, and highway illumination for urban & rural roadways.',
        
        'serv_2_title' => 'Electrical Distribution Networks',
        'serv_2_desc' => 'Turnkey Medium Voltage (MV) and Low Voltage (LV) distribution networks, ring main units (RMUs), feeder pillars, transformer installations, and cable jointing.',
        
        'serv_3_title' => 'Grid Stations & Substations',
        'serv_3_desc' => 'Engineering service execution of 132kV, 33kV, and 11kV Grid Substations, Gas-Insulated Switchgears (GIS), power transformers, control & protection relay panels, and SCADA integration.',
        
        'serv_4_title' => 'Turnkey Electrical Engineering Services',
        'serv_4_desc' => 'End-to-end Engineering, Procurement management, and Construction supervision for power transmission, substation civil works, and grid interconnections.',
        
        'serv_5_title' => 'Grid & Street Lighting O&M Services',
        'serv_5_desc' => 'Comprehensive operation, preventive maintenance, thermographic imaging, relay calibration, and emergency repair for grid stations & street lighting assets.',
        
        'serv_6_title' => 'Substation Civil & Structural Works',
        'serv_6_desc' => 'Specialized civil construction for grid control buildings, transformer foundations, cable trenches, gantry structures, and perimeter security fencing.',

        // Certifications Section
        'cert_tag' => 'OFFICIAL REGISTRATIONS & CREDENTIALS',
        'cert_title' => 'Oman Ministry & Chamber Accreditations',
        'cert_subtitle' => 'Officially certified and registered in Sultanate of Oman for Excellent Grade commercial & electrical works.',
        
        'cert_1_title' => 'Oman Chamber of Commerce & Industry (OCCI)',
        'cert_1_subtitle' => 'Membership Certificate - Excellent Grade',
        'cert_1_cr' => 'Commercial Register (CR) No: 1667600',
        'cert_1_grade' => 'Registered Grade: Excellent (الممتازة)',
        'cert_1_occi' => 'OCCI License No: 871399',
        'cert_1_location' => 'Head Office: Muscat Governorate, Sultanate of Oman',
        'cert_1_btn' => 'View Official OCCI Certificate (PDF)',

        'cert_2_title' => 'Tax Authority - Sultanate of Oman',
        'cert_2_subtitle' => 'Official Tax Registration Card',
        'cert_2_card_no' => 'Tax Card No: 151400492',
        'cert_2_tin' => 'Tax Identification No (TIN): 2314366',
        'cert_2_license' => 'CR / License No: 1667600',
        'cert_2_expiry' => 'Valid till: 29/07/2028',
        'cert_2_btn' => 'View Official Tax Card (PDF)',

        // Projects Section
        'proj_tag' => 'OUR PROJECT PORTFOLIO',
        'proj_title' => 'Street Lighting, Distribution & Grid Showcase',
        'proj_subtitle' => 'Demonstrating proven track record in highway street lighting, power distribution feeder networks, and high-voltage grid stations.',
        'proj_filter_all' => 'All Projects',
        'proj_filter_lighting' => 'Street Lighting',
        'proj_filter_dist' => 'Distribution Networks',
        'proj_filter_grid' => 'Grid Stations',
        
        'proj_1_title' => 'Expressway LED Street Lighting Project',
        'proj_1_cat' => 'Street Lighting',
        'proj_1_desc' => 'Installation of 12m octagonal lighting poles, energy-efficient LED luminaires, photocell feeder control panels, and 45km underground cables.',
        
        'proj_2_title' => '33/11kV Primary Grid Station Services',
        'proj_2_cat' => 'Grid Stations',
        'proj_2_desc' => 'Turnkey engineering services for 2x20MVA primary grid station including GIS switchgear room, power transformers, DC battery systems, and SCADA interface.',
        
        'proj_3_title' => 'Urban MV/LV Electrical Distribution Expansion',
        'proj_3_cat' => 'Distribution Networks',
        'proj_3_desc' => 'Laying of 11kV XLPE cables, installation of Ring Main Units (RMU), 1000kVA package substations, and low-voltage distribution pillars for commercial district.',
        
        'proj_4_title' => 'Highway Dual-Carriageway Street Lighting Network',
        'proj_4_cat' => 'Street Lighting',
        'proj_4_desc' => 'Turnkey street lighting infrastructure for 30km dual carriageway featuring heavy-duty galvanized poles, feeder pillars, and automated timer panels.',
        
        'proj_5_title' => '132kV Substation Transformer & Gantry Work',
        'proj_5_cat' => 'Grid Stations',
        'proj_5_desc' => 'Testing, installation, and commissioning of 132kV outdoor power transformers, circuit breakers, protection relays, and steel lattice gantries.',
        
        'proj_6_title' => 'Industrial Zone Distribution Network & O&M',
        'proj_6_cat' => 'Distribution Networks',
        'proj_6_desc' => 'Complete overhead & underground power distribution network development, feeder management, and 24/7 emergency response maintenance.',

        // Contact Section
        'contact_tag' => 'GET IN TOUCH',
        'contact_title' => 'Connect With Fescon Power Experts',
        'contact_subtitle' => 'Consult with our engineering team for Street Lighting tenders, Distribution Network inquiries, or Grid Station proposals.',
        'contact_info_title' => 'Muscat Head Office & Execution Branch',
        
        'contact_oman_title' => 'Head Office (Muscat, Oman)',
        'contact_oman_address' => 'Muscat Governorate, Sultanate of Oman',
        'contact_oman_cr' => 'CR No: 1667600 | Grade: Excellent',
        'contact_oman_phone' => '+968 9919 9710',
        'contact_oman_tel' => '9919 9710',

        'contact_pak_title' => 'Execution Branch (Fescon Pvt Ltd)',
        'contact_pak_desc' => 'Regional Engineering Execution & Project Arm',
        'contact_pak_website' => 'fescon.com.pk',

        'form_name' => 'Your Name / Company',
        'form_name_ph' => 'Full Name',
        'form_email' => 'Your Email Address',
        'form_email_ph' => 'example@gmail.com',
        'form_phone' => 'Phone / WhatsApp Number',
        'form_phone_ph' => '+000 0000 0000',
        'form_subject' => 'Project Category / Subject',
        'form_subject_ph' => 'Street Lighting / Grid Station Inquiry',
        'form_message' => 'Project Specifications / Details',
        'form_message_ph' => 'Describe your project specifications or BOQ details...',
        'form_submit' => 'Submit Engineering Inquiry',
        'form_sending' => 'Processing Inquiry...',
        'form_success' => 'Thank you! Your inquiry has been sent successfully. Our Muscat electrical engineering team will contact you shortly.',

        // Footer
        'footer_about' => 'Fescon International LLC is Oman’s trusted engineering service provider specializing in Street Lighting Systems, Medium & Low Voltage Distribution Networks, and High Voltage Grid Stations.',
        'footer_quick_links' => 'Quick Navigation',
        'footer_services' => 'Core Services',
        'footer_cert' => 'Oman Legal Credentials',
        'footer_rights' => 'Fescon International LLC. All Rights Reserved.',
        'footer_tagline' => 'Powering Roads, Networks & Grid Infrastructure'
    ],
    'ar' => [
        'site_title' => 'فيسكون العالمية ش م م | إنارة الشوارع، شبكات التوزيع ومحطات المحولات',
        'site_desc' => 'مُزود خدمات هندسية رائد في سلطنة عمان متخصص في مشاريع إنارة الشوارع، شبكات التوزيع الكهربائي، ومحطات المحولات والجهد العالي.',
        'nav_home' => 'الرئيسية',
        'nav_about' => 'من نحن',
        'nav_leadership' => 'القيادة التنفيذية',
        'nav_services' => 'خدماتنا',
        'nav_certifications' => 'الشهادات والاعتمادات',
        'nav_projects' => 'المشاريع',
        'nav_contact' => 'اتصل بنا',
        'lang_toggle' => 'English',
        'lang_switch_url' => '?lang=en',
        
        // Top Bar
        'top_cr' => 'السجل التجاري: ١٦٦٧٦٠٠',
        'top_grade' => 'الدرجة: الممتازة',
        'top_phone' => '+968 9919 9710',
        'top_email' => 'info@fesconinternational.com',
        
        // Hero Section
        'hero_badge' => 'خبراء إنارة الشوارع، شبكات التوزيع ومحطات المحولات',
        'hero_title' => 'إنارة الطرق، شبكات التوزيع ومحطات المحولات',
        'hero_subtitle' => 'تنفذ شركة فيسكون العالمية ش م م مشاريع أنظمة إنارة الشوارع، شبكات التوزيع الكهربائي للجهد المتوسط والمنخفض، ومحطات المحولات في سلطنة عمان.',
        'hero_cta_projects' => 'استعرض مشاريعنا',
        'hero_cta_contact' => 'تواصل معنا',
        'hero_stat_1_num' => '+١٥',
        'hero_stat_1_label' => 'عاماً من الخبرة الهندسية',
        'hero_stat_2_num' => '+١٠٠',
        'hero_stat_2_label' => 'مشروع محطات وتوزيع منجز',
        'hero_stat_3_num' => '١٠٠٪',
        'hero_stat_3_label' => 'الالتزام بمعايير شبكات الكهرباء',
        'hero_stat_4_num' => 'الممتازة',
        'hero_stat_4_label' => 'درجة الغرفة التجارية بعمان',

        // About Section
        'about_tag' => 'نبذة عن الشركة',
        'about_title' => 'متخصصون في محطات المحولات وشبكات التوزيع وإنارة الشوارع',
        'about_desc_1' => 'تأسست شركة فيسكون العالمية ش م م في محافظة مسقط بسلطنة عمان، كـ مُزود خدمات هندسية متخصص في تنفيذ مشاريع إنارة الشوارع، شبكات التوزيع الكهربائي، ومحطات المحولات. وتعمل الشركة بتكامل تام مع فرعها التنفيذي (شركة فيسكون الخاصة المحدودة) لتقديم بنية تحتية كهربائية عالية الجودة.',
        'about_desc_2' => 'ابتداءً من إنارة الطرق السريعة الحضرية وصولاً إلى محطات المحولات الرئيسية بجهد 132/33 كيلوفولت وشبكات التوزيع الكابلات الأرضية، تجمع فيسكون بين الدقة الهندسية والتصاميم الحديثة والالتزام الصارم بأعلى معايير السلامة.',
        'about_branch_note' => 'تقود شركة فيسكون العالمية ش م م المشاريع المتكاملة في إنارة الشوارع وشبكات التوزيع الكهربائي ومحطات المحولات في سلطنة عمان، مدعومة بالسجل الحافل لفرعها الإقليمي شركة فيسكون الخاصة المحدودة.',
        'about_mission_title' => 'رسالتنا',
        'about_mission_text' => 'إضاءة الطرق والمجتمعات وتوفير الطاقة الموثوقة من خلال إنشاء شبكات إنارة شوارع متطورة، وشبكات توزيع كهربائي قوية، ومحطات محولات حديثة.',
        'about_vision_title' => 'رؤيتنا',
        'about_vision_text' => 'أن نكون مُزود الخدمات الهندسية الأول في سلطنة عمان لأنظمة إنارة الشوارع وتوسعة شبكات الكهرباء ومحطات المحولات.',

        // Core Values
        'val_prof_title' => 'دقة محطات المحولات والسلامة',
        'val_prof_desc' => 'مهندسون معتمدون في الجهد العالي يقدمون تحليلات دقيقة وحماية متكاملة لشبكات الكهرباء.',
        'val_qual_title' => 'جودة معتمدة من شركات الكهرباء',
        'val_qual_desc' => 'التزام صارم بكود شبكة الكهرباء العمانية ومعايير إنارة الشوارع البلدية والمواصفات الدولية.',
        'val_time_title' => 'التشغيل والتسليم في الموعد',
        'val_time_desc' => 'تشغيل المحولات وإطلاق التيار في المواعيد المحددة من خلال إدارة المشاريع واختبارات الجودة.',
        'val_sust_title' => 'كفاءة الطاقة وإنارة الشوارع',
        'val_sust_desc' => 'دمج أنظمة إنارة الشوارع الموفرة للطاقة والإنارة الشمسية لتقليل الاستهلاك وحماية البيئة.',

        // Leadership Section
        'lead_tag' => 'القيادة التنفيذية',
        'lead_title' => 'قيادة متميزة في شبكات الكهرباء والبنية التحتية',
        'lead_subtitle' => 'يقود الشركة فريق تنفيذي هندسي يمتلك خبرات واسعة في تركيب محطات المحولات وشبكات إنارة الطرق وتوزيع الطاقة.',
        'ceo_name' => 'Engr. Ehsan Ullah',
        'ceo_title' => 'Chief Executive & Director',
        'ceo_name_ar' => 'المهندس إحسان الله',
        'ceo_title_ar' => 'الرئيس التنفيذي والمدير',
        'ceo_msg_title' => 'كلمة الرئيس التنفيذي',
        'ceo_msg_p1' => 'مرحباً بكم في فيسكون! يتركز التزامنا الرئيسي في تقديم بنية تحتية عالية الأداء لإنارة الشوارع، وشبكات توزيع كهربائي قوية، ومحطات محولات متقدمة. نحن نفخر بإضاءة المدن ودعم النمو الصناعي بأعلى درجات السلامة والبراعة الفنية.',
        'ceo_msg_p2' => 'بالتعاون مع فريقنا الهندسي المتخصص في عمان وفرعنا الإقليمي، نضمن أن كل مشروع محطة محولات أو شبكة إنارة شوارع يتجاوز تطلعات عملائنا.',

        'dir_name' => 'Abdullah Muhammad Salim Al-Basrawi',
        'dir_title' => 'Director',
        'dir_name_ar' => 'عبدالله محمد سالم البصراوي',
        'dir_title_ar' => 'المدير',
        'dir_msg_title' => 'كلمة المدير التنفيذي',
        'dir_msg_p1' => 'دعماً لرؤية عمان ٢٠٤٠، تلتزم فيسكون العالمية بتحديث شبكة الكهرباء في السلطنة، وتوسيع إنارة الشوارع عبر الطرق الرئيسية، وتعزيز شبكات التوزيع الإقليمية.',
        'dir_msg_p2' => 'حضورنا المحلي في مسقط يضمن التنسيق السلس مع الجهات المعنية، والتنفيذ عالي الجودة، والأثر المستدام.',

        // Services Section
        'serv_tag' => 'خدماتنا',
        'serv_title' => 'إنارة الشوارع، شبكات التوزيع ومحطات المحولات',
        'serv_subtitle' => 'خدمات هندسية متكاملة لإنارة الشوارع والطرق العامة، شبكات التوزيع الكهربائي، ومحطات الجهد العالي والمتوسط.',
        
        'serv_1_title' => 'أنظمة إنارة الشوارع والطرق',
        'serv_1_desc' => 'التصميم الكامل، تركيب أعمدة الإنارة، كشافات LED الحديثة، الكابلات الأرضية، لوحات التحكم، وإنارة الطرق السريعة والحضرية.',
        
        'serv_2_title' => 'شبكات التوزيع الكهربائي',
        'serv_2_desc' => 'مشاريع متكاملة لشبكات التوزيع للجهد المتوسط (MV) والمنخفض (LV)، وحدات الربط الحلقي (RMU)، كبائن التوزيع، وتوصيل المحولات والكابلات.',
        
        'serv_3_title' => 'محطات المحولات والجهد العالي',
        'serv_3_desc' => 'خدمات هندسية لمحطات المحولات بجهد 132kV و 33kV و 11kV، مفاتيح المعزولة بالغاز (GIS)، محولات الطاقة، لوحات الحماية والتحكم، وأنظمة SCADA.',
        
        'serv_4_title' => 'الخدمات الهندسية الكهربائية المتكاملة',
        'serv_4_desc' => 'إدارة شاملة للهندسة والتوريد والإنشاء لمشاريع نقل الطاقة والأعمال المدنية للمحطات والربط الكهربائي.',
        
        'serv_5_title' => 'تشغيل وصيانة المحطات وإنارة الشوارع',
        'serv_5_desc' => 'خدمات التشغيل والصيانة الوقائية الشاملة، الفحص الحراري، معايرة مرحلات الحماية، والإصلاح الطارئ لإنارة الشوارع ومحطات الكهرباء.',
        
        'serv_6_title' => 'الأعمال المدنية والإنشائية للمحطات',
        'serv_6_desc' => 'إنشاءات مدنية متخصصة لمباني تحكم المحطات، قواعد المحولات، خنادق الكابلات، الهياكل المعدنية، والسياج الأمني المحيط.',

        // Certifications Section
        'cert_tag' => 'التسجيلات والشهادات الرسمية',
        'cert_title' => 'اعتمادات غرفة تجارة وصناعة عمان وجهاز الضرائب',
        'cert_subtitle' => 'فيسكون العالمية ش م م مسجلة ومعتمدة رسمياً لدى الجهات الحكومية في سلطنة عمان للدرجة الممتازة.',
        
        'cert_1_title' => 'غرفة تجارة وصناعة عمان (OCCI)',
        'cert_1_subtitle' => 'شهادة انتساب - الدرجة الممتازة',
        'cert_1_cr' => 'رقم السجل التجاري (CR): ١٦٦٧٦٠٠',
        'cert_1_grade' => 'الدرجة المسجلة: الممتازة',
        'cert_1_occi' => 'رقم الغرفة (OCCI): ٨٧١٣٩٩',
        'cert_1_location' => 'المقر الرئيسي: محافظة مسقط، سلطنة عمان',
        'cert_1_btn' => 'عرض شهادة الانتساب الرسمية (PDF)',

        'cert_2_title' => 'جهاز الضرائب - سلطنة عمان',
        'cert_2_subtitle' => 'البطاقة الضريبية الرسمية',
        'cert_2_card_no' => 'رقم البطاقة الضريبية: ١٥١٤٠٠٤٩٢',
        'cert_2_tin' => 'رقم التعريف الضريبي (TIN): ٢٣١٤٣٦٦',
        'cert_2_license' => 'رقم السجل التجاري / الترخيص: ١٦٦٧٦٠٠',
        'cert_2_expiry' => 'صالحة حتى: ٢٩/٠٧/٢٠٢٨',
        'cert_2_btn' => 'عرض البطاقة الضريبية الرسمية (PDF)',

        // Projects Section
        'proj_tag' => 'معرض مشاريعنا',
        'proj_title' => 'مشاريع إنارة الشوارع وشبكات التوزيع والمحطات',
        'proj_subtitle' => 'استعراض لمشاريعنا المنجزة في إنارة طرق الشوارع السريعة، شبكات المغذيات الكهربائية، ومحطات المحولات ذات الجهد العالي.',
        'proj_filter_all' => 'جميع المشاريع',
        'proj_filter_lighting' => 'إنارة الشوارع',
        'proj_filter_dist' => 'شبكات التوزيع',
        'proj_filter_grid' => 'محطات المحولات',
        
        'proj_1_title' => 'مشروع إنارة الشوارع للطريق السريع',
        'proj_1_cat' => 'إنارة الشوارع',
        'proj_1_desc' => 'تركيب أعمدة إنارة بارتفاع ١٢ متر، كشافات LED موفرة للطاقة، لوحات تغذية وتحكم، ومد ٤٥ كم من كابلات التغذية الأرضية.',
        
        'proj_2_title' => 'خدمات محطة محولات رئيسية جهد ٣٣/١١ كيلوفولت',
        'proj_2_cat' => 'محطات المحولات',
        'proj_2_desc' => 'تنفيذ خدمات هندسية لمحطة محولات سعة 2x20MVA تشمل مبنى مفاتيح GIS، محولات الطاقة، أنظمة البطاريات، وربط SCADA.',
        
        'proj_3_title' => 'توسعة شبكة التوزيع الكهربائي الجهد المتوسط والمنخفض',
        'proj_3_cat' => 'شبكات التوزيع',
        'proj_3_desc' => 'تمديد كابلات جهد ١١ كيلوفولت، تركيب وحدات RMU، محطات مدمجة سعة 1000kVA، وكبائن توزيع للمنطقة التجارية.',
        
        'proj_4_title' => 'شبكة إنارة شوارع الطرق المزدوجة السريعة',
        'proj_4_cat' => 'إنارة الشوارع',
        'proj_4_desc' => 'مشروع متكامل لإنارة شوارع طريق مزدوج بطول ٣٠ كم يشمل أعمدة مجلفنة ثقيلة، كبائن تغذية، ولوحات تحكم أوتوماتيكية.',
        
        'proj_5_title' => 'أعمال محولات وركائز محطة جهد ١٣٢ كيلوفولت',
        'proj_5_cat' => 'محطات المحولات',
        'proj_5_desc' => 'اختبار وتركيب وتدشين محولات طاقة جهد 132kV خارجية، قواطع التيار، مرحلات الحماية، والهياكل الحديدية.',
        
        'proj_6_title' => 'شبكة توزيع المنطقة الصناعية والصيانة',
        'proj_6_cat' => 'شبكات التوزيع',
        'proj_6_desc' => 'تطوير شبكة توزيع الطاقة الهوائية والأرضية للمنطقة الصناعية، وإدارة المغذيات وصيانة طوارئ على مدار الساعة.',

        // Contact Section
        'contact_tag' => 'تواصل معنا',
        'contact_title' => 'تواصل مع خبراء الكهرباء والمحطات',
        'contact_subtitle' => 'استشر فريقنا الهندسي لمناقصات إنارة الشوارع، استفسارات شبكات التوزيع، أو عروض المحطات.',
        'contact_info_title' => 'المقر الرئيسي بمسقط وفرع التنفيذ',
        
        'contact_oman_title' => 'المقر الرئيسي (مسقط، عمان)',
        'contact_oman_address' => 'محافظة مسقط، سلطنة عمان',
        'contact_oman_cr' => 'السجل التجاري: ١٦٦٧٦٠٠ | الدرجة الممتازة',
        'contact_oman_phone' => '+968 9919 9710',
        'contact_oman_tel' => '9919 9710',

        'contact_pak_title' => 'فرع التنفيذ (شركة فيسكون الخاصة المحدودة)',
        'contact_pak_desc' => 'فرع التنفيذ الهندسي الإقليمي والمشاريع',
        'contact_pak_website' => 'fescon.com.pk',

        'form_name' => 'الاسم / اسم الشركة',
        'form_name_ph' => 'الاسم الكامل',
        'form_email' => 'عنوان البريد الإلكتروني',
        'form_email_ph' => 'example@gmail.com',
        'form_phone' => 'رقم الهاتف / الواتساب',
        'form_phone_ph' => '+000 0000 0000',
        'form_subject' => 'فئة المشروع / الموضوع',
        'form_subject_ph' => 'استفسار إنارة شوارع / محطة محولات',
        'form_message' => 'تفاصيل ومواصفات المشروع',
        'form_message_ph' => 'اكتب تفاصيل ومواصفات المشروع أو جدول الكميات...',
        'form_submit' => 'إرسال الاستفسار الهندسي',
        'form_sending' => 'جاري المعالجة...',
        'form_success' => 'شكراً لك! تم إرسال استفسارك بنجاح. سيتواصل معك فريق الهندسة الكهربائية بمسقط قريباً.',

        // Footer
        'footer_about' => 'فيسكون العالمية ش م م مُزود خدمات هندسية موثوق في عمان متخصص في أنظمة إنارة الشوارع، شبكات التوزيع الكهربائي للجهد المتوسط والمنخفض، ومحطات المحولات والجهد العالي.',
        'footer_quick_links' => 'روابط الموقع',
        'footer_services' => 'خدماتنا الرئيسية',
        'footer_cert' => 'الاعتمادات القانونية بعمان',
        'footer_rights' => 'فيسكون العالمية ش م م. جميع الحقوق محفوظة.',
        'footer_tagline' => 'إنارة الطرق، شبكات التوزيع ومحطات المحولات'
    ]
];

// Helper function to get text
function t($key) {
    global $translations, $lang;
    return isset($translations[$lang][$key]) ? $translations[$lang][$key] : (isset($translations['en'][$key]) ? $translations['en'][$key] : $key);
}
