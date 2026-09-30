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
        'hero_title' => 'Engineering Excellence Across the GCC',
        'hero_subtitle' => 'Delivering integrated services, MEP, Electrical Distribution, Street Lighting, and Civil Infrastructure Solutions across the GCC with Uncompromising Quality, Safety, and Innovation.',
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

        // Why Choose Us Section
        'why_tag' => 'WHY CHOOSE FESCON',
        'why_title' => 'Why Choose Us?',
        'why_desc' => 'At Fescon, we combine multidisciplinary expertise with innovative solutions to deliver excellence in every project. Our commitment to quality, sustainability, and client satisfaction sets us apart as a trusted partner in Engineering and Pre-Construction Services.',
        'why_motto_header' => 'No compromise on:',
        'why_list_1' => 'Quality',
        'why_list_2' => 'Quantity',
        'why_list_3' => 'Commitment',

        // About Section
        'about_tag' => 'WHO WE ARE',
        'about_title' => 'Electrical Infrastructure, Civil Infrastructure, MEP Works, Oil & Gas',
        'about_branch_note' => 'Fescon International LLC leads turnkey projects in Electrical Grids, Civil Infrastructure, MEP Solutions, and Oil & Gas sector developments in Oman, supported by the extensive regional project execution portfolio of Fescon Pvt Ltd.',
        'about_mission_title' => 'Our Mission',
        'about_mission_text' => 'To deliver world-class engineering, construction, and infrastructure solutions through technical excellence, innovation, uncompromising quality, and a commitment to safety—creating lasting value for our clients, communities, and the nation.',
        'about_vision_title' => 'Our Vision',
        'about_vision_text' => 'To become Oman’s most trusted engineering partner, recognized for delivering sustainable, high-quality projects across diverse sectors while setting new standards of professionalism, reliability, and engineering excellence.',

        // Core Values
        'val_prof_title' => 'High-Rise Buildings',
        'val_prof_desc' => 'Comprehensive structural engineering, architectural execution, and turnkey construction for modern commercial and residential high-rise towers.',
        'val_qual_title' => 'Water Drainage',
        'val_qual_desc' => 'Advanced stormwater drainage networks, flood protection, concrete culverts, and municipal drainage infrastructure.',
        'val_time_title' => 'Lagoon-Centered Urban Planning',
        'val_time_desc' => 'Integrating lagoons, canals, waterfront promenades, and mixed-use developments to create sustainable, connected, and vibrant coastal communities.',
        'val_sust_title' => 'Energy Efficient Street Lighting',
        'val_sust_desc' => 'Integrating high-efficiency LED street lights, solar street lighting, and optimized distribution transformers.',
        'val_civil_title' => 'Civil Infrastructure',
        'val_civil_desc' => 'Highways, road networks, grading, structural foundations, and comprehensive municipal civil engineering works.',
        'val_mep_title' => 'MEP Works',
        'val_mep_desc' => 'Integrated mechanical, electrical, and plumbing engineering solutions for commercial, industrial, and residential projects.',
        'val_oilgas_title' => 'Oil & Gas Sector',
        'val_oilgas_desc' => 'Specialized electrical, civil, and instrumentation engineering support for upstream and downstream energy facilities.',
        'val_dist_title' => 'Electrical Distribution',
        'val_dist_desc' => 'Medium and low-voltage underground cabling, transformer substations, switchgear, and feeder network deployment.',


        // Leadership Section
        'lead_tag' => 'EXECUTIVE LEADERSHIP',
        'lead_title' => 'Leadership Behind Our Engineering Excellence',
        'lead_subtitle' => 'Led by veteran engineering executives with extensive experience in utility grid installations, street lighting, and electrical network deployment.',
        'ceo_name' => 'Engr. Ehsan Ullah',
        'ceo_title' => 'Chief Executive Officer',
        'ceo_msg_title' => 'Message from the CEO',
        'ceo_msg_p1' => 'Welcome to Fescon International. We are committed to delivering engineering excellence through innovative, reliable, and sustainable solutions across infrastructure, energy, industrial, building projects and Oil and Gas field. Our success is built on professionalism, quality, safety, and long-term partnerships.',
        'ceo_msg_p2' => 'With a highly skilled team and a client-focused approach, we strive to exceed expectations by delivering every project with precision, integrity, and technical expertise—contributing to Oman’s continued growth and development.',
        
        'dir_name' => 'Abdullah Muhammad Salim Al-Basrawi',
        'dir_title' => 'Director',
        'dir_msg_title' => 'Message from the Director',
        'dir_msg_p1' => 'At Fescon International, we believe that great engineering creates lasting value. Our mission is to provide dependable engineering and construction services that support the nation’s progress while maintaining the highest standards of quality, safety, and environmental responsibility.',
        'dir_msg_p2' => 'Together with our clients, partners, and dedicated professionals, we are building sustainable infrastructure and delivering solutions that shape a stronger future for the Sultanate of Oman.',

        // Services Section
        'serv_tag' => 'OUR SERVICES',
        'serv_title' => 'Integrated Engineering & Infrastructure Solutions',
        'serv_subtitle' => 'Turnkey engineering solutions designed to power cities, electrical networks, and high-voltage substation grid stations.',
        
        'serv_1_title' => 'Street Lighting Systems',
        'serv_1_desc' => 'Complete design, pole erection, LED luminaire installation, underground cabling, feeder control panels, and highway illumination for urban & rural roadways.',
        
        'serv_2_title' => 'Electrical Distribution Networks',
        'serv_2_desc' => 'Turnkey Medium Voltage (MV) and Low Voltage (LV) distribution networks, ring main units (RMUs), feeder pillars, transformer installations, and cable jointing.',
        
        'serv_3_title' => 'Substations & Transmission',
        'serv_3_desc' => 'Engineering services for 400 kV, 220 kV and 132 kV transmission grid stations, as well as 33 kV and 11 kV distribution substations, including GIS, power transformers, protection and control systems, and SCADA integration.',
        
        'serv_4_title' => 'Civil Infrastructure & Construction',
        'serv_4_desc' => 'Highways, arterial roads, structural foundations, earthworks, drainage systems, substation civil structures, and comprehensive municipal infrastructure.',
        
        'serv_5_title' => 'MEP Engineering Solutions',
        'serv_5_desc' => 'Integrated mechanical, electrical, and plumbing engineering, HVAC systems, fire protection, and comprehensive building utility installations for commercial and industrial facilities.',
        
        'serv_6_title' => 'Oil & Gas Sector Services',
        'serv_6_desc' => 'Specialized electrical, civil, piping, and instrumentation engineering support for upstream, downstream, and petrochemical oilfield facilities.',
        
        'serv_7_title' => 'Lagoon-Centered Urban Planning',
        'serv_7_desc' => 'Integrating lagoons, canals, waterfront promenades, and mixed-use developments to create sustainable, connected, and vibrant coastal communities.',
        
        'serv_8_title' => 'Operation & Maintenance (O&M)',
        'serv_8_desc' => '24/7 preventive, corrective, and predictive maintenance, thermographic imaging, relay calibration, and emergency repair for power grids, street lighting, and utility assets.',

        // Certifications Section
        'cert_tag' => 'OFFICIAL REGISTRATIONS & CREDENTIALS',
        'cert_title' => 'Oman Ministry & Chamber Accreditations',
        'cert_subtitle' => 'Officially certified and registered in Sultanate of Oman for Excellent Grade commercial & electrical works.',
        
        'cert_1_badge' => 'Grade: Excellent (الممتازة)',
        'cert_1_title' => 'Oman Chamber of Commerce & Industry (OCCI)',
        'cert_1_subtitle' => 'Membership Certificate - Excellent Grade',
        'cert_1_cr' => 'Commercial Register (CR) No: 1667600',
        'cert_1_grade' => 'Registered Grade: Excellent (الممتازة)',
        'cert_1_occi' => 'OCCI License No: 871399',
        'cert_1_location' => 'Head Office: Muscat Governorate, Sultanate of Oman',
        'cert_1_btn' => 'View Official OCCI Certificate (PDF)',

        'cert_2_badge' => 'Tax Registered Entity',
        'cert_2_title' => 'Tax Authority - Sultanate of Oman',
        'cert_2_subtitle' => 'Official Tax Registration Card',
        'cert_2_card_no' => 'Tax Card No: 151400492',
        'cert_2_tin' => 'Tax Identification No (TIN): 2314366',
        'cert_2_license' => 'CR / License No: 1667600',
        'cert_2_expiry' => 'Valid till: 29/07/2028',
        'cert_2_btn' => 'View Official Tax Card (PDF)',

        // Projects Section
        'proj_tag' => 'OUR PROJECT PORTFOLIO',
        'proj_title' => 'Engineering Projects & Infrastructure Solutions',
        'proj_subtitle' => 'Demonstrating proven track record in highway street lighting, power distribution feeder networks, and high-voltage grid stations.',
        'proj_filter_all' => 'All Projects',
        'proj_filter_grid' => 'Grid Stations',
        'proj_filter_dist' => 'Distribution Networks',
        'proj_filter_lighting' => 'Street Lighting',
        'proj_filter_civil' => 'Civil Infrastructure',
        'proj_filter_mep' => 'MEP Works',
        'proj_filter_oilgas' => 'Oil & Gas',
        'proj_filter_epc' => 'Turnkey EPC',
        'proj_filter_om' => 'Operation & Maintenance',
        
        'proj_1_title' => 'Expressway LED Street Lighting Infrastructure',
        'proj_1_cat' => 'Street Lighting',
        'proj_1_desc' => 'Installation of 12m octagonal lighting poles, energy-efficient LED luminaires, photocell feeder control panels, and 45km underground cabling.',
        
        'proj_2_title' => '132kV Primary Grid Station Turnkey Services',
        'proj_2_cat' => 'Grid Stations',
        'proj_2_desc' => 'Turnkey engineering execution for 132kV primary grid station including GIS switchgear rooms, 2x20MVA power transformers, protection relays, and SCADA integration.',
        
        'proj_3_title' => 'Urban MV/LV Underground Power Distribution',
        'proj_3_cat' => 'Distribution Networks',
        'proj_3_desc' => 'Laying 11kV XLPE cables, Ring Main Units (RMU), compact package transformer substations, and low-voltage distribution feeder pillars.',
        
        'proj_4_title' => 'Highway & Arterial Road Civil Infrastructure',
        'proj_4_cat' => 'Civil Infrastructure',
        'proj_4_desc' => 'Heavy earthworks, highway grading, asphalt paving, concrete stormwater culverts, bridges, and municipal roadway infrastructure.',
        
        'proj_5_title' => 'Commercial Complex & Facility MEP Integration',
        'proj_5_cat' => 'MEP Works',
        'proj_5_desc' => 'Complete HVAC central chiller plant, fire suppression systems, electrical busways, LV switchgear, and sanitary plumbing networks.',
        
        'proj_6_title' => 'Oilfield & Refinery Electrical & Instrumentation',
        'proj_6_cat' => 'Oil & Gas',
        'proj_6_desc' => 'High-reliability electrical power feeds, process instrumentation, explosion-proof installations, pipeline support foundations, and site sub-stations.',

        'proj_7_title' => 'High-Voltage Power Transmission Line EPC',
        'proj_7_cat' => 'Turnkey EPC',
        'proj_7_desc' => 'Turnkey EPC engineering for high-voltage overhead power transmission lines, lattice towers, stringing, and substation grid interconnections.',

        'proj_8_title' => 'Utility Substation & Industrial Plant O&M',
        'proj_8_cat' => 'Operation & Maintenance',
        'proj_8_desc' => '24/7 preventive maintenance, FLIR thermographic diagnostic imaging, protection relay calibration, and emergency grid restoration.',

        // Contact Section
        'contact_tag' => 'GET IN TOUCH',
        'contact_title' => 'Connect With Our Engineering Experts',
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
        'footer_cr' => 'CR No: 1667600',
        'footer_occi_grade' => 'OCCI Grade: Excellent',
        'footer_tax_card' => 'Tax Card: 151400492',
        'footer_admin' => 'Admin',
        'footer_rights' => 'Fescon International LLC. All Rights Reserved.',
        'footer_tagline' => 'Powering Roads, Networks & Grid Infrastructure',
        'modal_doc_title' => 'Document Preview'
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
        'hero_title' => 'التميز الهندسي في جميع دول مجلس التعاون الخليجي',
        'hero_subtitle' => 'تقديم حلول هندسية متكاملة، الأعمال الكهروميكانيكية، التوزيع الكهربائي، إنارة الشوارع، والبنية التحتية المدنية في دول الخليج بجودة وسلامة وابتكار لا مساومة عليها.',
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

        // Why Choose Us Section
        'why_tag' => 'لماذا تختار فيسكون',
        'why_title' => 'لماذا تختارنا؟',
        'why_desc' => 'في فيسكون، ندمج بين الخبرات متعددة التخصصات والحلول المبتكرة لتقديم التميز في كل مشروع. إن التزامنا بالجودة والاستدامة ورضا العملاء يميزنا كشريك موثوق في مجالات الهندسة وخدمات ما قبل الإنشاء.',
        'why_motto_header' => 'لا مساومة على:',
        'why_list_1' => 'الجودة',
        'why_list_2' => 'الكمية',
        'why_list_3' => 'الالتزام',

        // About Section
        'about_tag' => 'نبذة عن الشركة',
        'about_title' => 'البنية التحتية الكهربائية، البنية التحتية المدنية، الأعمال الكهروميكانيكية، والنفط والغاز',
        'about_branch_note' => 'تقود شركة فيسكون العالمية ش م م مشاريع متكاملة في شبكات الكهرباء، البنية التحتية المدنية، حلول الكهروميكانيكية، ومشاريع قطاع النفط والغاز في سلطنة عمان، مدعومة بالسجل الحافل لفرعها الإقليمي شركة فيسكون الخاصة المحدودة.',
        'about_mission_title' => 'رسالتنا',
        'about_mission_text' => 'تقديم حلول هندسية وإنشائية وبنية تحتية عالمية المستوى من خلال التميز التقني والابتكار والجودة التي لا مساومة عليها والالتزام بالسلامة—مما يخلق قيمة مستدامة لعملائنا ومجتمعاتنا ووطننا.',
        'about_vision_title' => 'رؤيتنا',
        'about_vision_text' => 'أن نصبح الشريك الهندسي الأكثر ثقة في سلطنة عمان، والمشهود له بتنفيذ مشاريع مستدامة عالية الجودة عبر مختلف القطاعات مع إرساء معايير جديدة للاحترافية والموثوقية والتميز الهندسي.',

        // Core Values
        'val_prof_title' => 'الأبراج والمباني الشاهقة',
        'val_prof_desc' => 'هندسة إنشائية ومعمارية متطورة، وتنفيذ متكامل وتسليم مفتاح للأبراج التجارية والمجمعات السكنية الشاهقة.',
        'val_qual_title' => 'تصريف مياه الأمطار والسيول',
        'val_qual_desc' => 'شبكات تصريف مياه الأمطار المتطورة، قنوات الحماية من الفيضانات، والعبّارات الخرسانية لتطوير البنية التحتية البلدية.',
        'val_time_title' => 'التخطيط الحضري المتمحور حول البحيرات',
        'val_time_desc' => 'دمج البحيرات والقنوات المائية والمماشي البحرية والمشاريع متعددة الاستخدامات لبناء مجتمعات ساحلية مستدامة ومترابطة ونابضة بالحياة.',
        'val_sust_title' => 'كفاءة الطاقة وإنارة الشوارع',
        'val_sust_desc' => 'دمج أنظمة إنارة الشوارع الموفرة للطاقة والإنارة الشمسية لتقليل الاستهلاك وحماية البيئة.',
        'val_civil_title' => 'البنية التحتية المدنية',
        'val_civil_desc' => 'إنشاء الطرق السريعة، شبكات الطرق، الأساسات الإنشائية، والأعمال الهندسية المدنية والبلدية الشاملة.',
        'val_mep_title' => 'أعمال الكهروميكانيكية',
        'val_mep_desc' => 'حلول هندسية متكاملة للأعمال الميكانيكية والكهربائية والسباكة للمشاريع التجارية والصناعية والسكنية.',
        'val_oilgas_title' => 'قطاع النفط والغاز',
        'val_oilgas_desc' => 'خدمات هندسية متخصصة في الأعمال الكهربائية والمدنية وأجهزة التحكم لمرافق ومنشآت الطاقة.',
        'val_dist_title' => 'توزيع الطاقة الكهربائية',
        'val_dist_desc' => 'كابلات أرضية للجهد المتوسط والمنخفض، محطات المحولات الفرعية، لوحات التوزيع وشبكات التغذية الكهربائية.',

        // Leadership Section
        'lead_tag' => 'القيادة التنفيذية',
        'lead_title' => 'القيادة وراء تميزنا الهندسي',
        'lead_subtitle' => 'يقود الشركة فريق تنفيذي هندسي يمتلك خبرات واسعة في تركيب محطات المحولات وشبكات إنارة الطرق وتوزيع الطاقة.',
        'ceo_name' => 'المهندس إحسان الله',
        'ceo_title' => 'الرئيس التنفيذي',
        'ceo_msg_title' => 'كلمة الرئيس التنفيذي',
        'ceo_msg_p1' => 'مرحباً بكم في فيسكون الدولية. نحن ملتزمون بتقديم التميز الهندسي من خلال حلول مبتكرة وموثوقة ومستدامة عبر قطاعات البنية التحتية، الطاقة، المشاريع الصناعية، المباني، وقطاع النفط والغاز. إن نجاحنا قائم على الاحترافية، الجودة، السلامة، والشراكات طويلة الأجل.',
        'ceo_msg_p2' => 'مع فريق عمل عالي المهارة ونهج يركز على العميل، نسعى جاهدين لتجاوز التوقعات من خلال تنفيذ كل مشروع بدقة ونزاهة وخبرة تقنية—مما يساهم في النمو والتطوير المستمر لسلطنة عمان.',

        'dir_name' => 'عبدالله محمد سالم البصراوي',
        'dir_title' => 'المدير',
        'dir_msg_title' => 'كلمة المدير',
        'dir_msg_p1' => 'في فيسكون الدولية، نؤمن بأن الهندسة العظيمة تخلق قيمة مستدامة. رسالتنا هي تقديم خدمات هندسية وإنشائية موثوقة تدعم تقدم الوطن مع الحفاظ على أعلى معايير الجودة والسلامة والمسؤولية البيئية.',
        'dir_msg_p2' => 'جنباً إلى جنب مع عملائنا وشركائنا والمحترفين المخلصين، نحن نبني بنية تحتية مستدامة ونقدم حلولاً تصيغ مستقبلاً أقوى لسلطنة عمان.',

        // Services Section
        'serv_tag' => 'خدماتنا',
        'serv_title' => 'حلول هندسية وبنية تحتية متكاملة',
        'serv_subtitle' => 'خدمات هندسية متكاملة لإنارة الشوارع والطرق العامة، شبكات التوزيع الكهربائي، ومحطات الجهد العالي والمتوسط.',
        
        'serv_1_title' => 'أنظمة إنارة الشوارع والطرق',
        'serv_1_desc' => 'التصميم الكامل، تركيب أعمدة الإنارة، كشافات الإنارة الحديثة، الكابلات الأرضية، لوحات التحكم، وإنارة الطرق السريعة والحضرية.',
        
        'serv_2_title' => 'شبكات التوزيع الكهربائي',
        'serv_2_desc' => 'مشاريع متكاملة لشبكات التوزيع للجهد المتوسط والمنخفض، وحدات الربط الحلقي، كبائن التوزيع، وتوصيل المحولات والكابلات.',
        
        'serv_3_title' => 'محطات المحولات ونقل الطاقة',
        'serv_3_desc' => 'خدمات هندسية متكاملة لمحطات نقل الطاقة بجهد ٤٠٠ و ٢٢٠ و ١٣٢ كيلوفولت، ومحطات التوزيع بجهد ٣٣ و ١١ كيلوفولت، بما في ذلك المفاتيح المعزولة بالغاز، محولات القدرة، أنظمة الحماية والتحكم، وربط أنظمة التحكم والمراقبة عن بعد.',
        
        'serv_4_title' => 'البنية التحتية المدنية والإنشاءات',
        'serv_4_desc' => 'إنشاء الطرق السريعة والرئيسية، الأساسات الإنشائية، الأعمال الترابية، شبكات تصريف المياه، والأعمال المدنية المتكاملة للمحطات والمرافق البلدية.',
        
        'serv_5_title' => 'حلول الأعمال الكهروميكانيكية',
        'serv_5_desc' => 'خدمات هندسية متكاملة للأعمال الميكانيكية والكهربائية والسباكة، أنظمة التكييف والتهوية، ومكافحة الحرائق للمنشآت التجارية والصناعية.',
        
        'serv_6_title' => 'خدمات قطاع النفط والغاز',
        'serv_6_desc' => 'خدمات هندسية متخصصة في الأعمال الكهربائية، المدنية، خطوط الأنابيب، وأجهزة القياس والتحكم لدعم منشآت النفط والغاز ومصافي التكرير.',
        
        'serv_7_title' => 'التخطيط الحضري المتمحور حول البحيرات',
        'serv_7_desc' => 'دمج البحيرات والقنوات المائية والمماشي البحرية والمشاريع متعددة الاستخدامات لبناء مجتمعات ساحلية مستدامة ومترابطة ونابضة بالحياة.',
        
        'serv_8_title' => 'خدمات التشغيل والصيانة',
        'serv_8_desc' => 'صيانة وقائية وعلاجية وتنبؤية على مدار الساعة، فحص حراري، معايرة مرحلات الحماية، واستجابة طارئة لشبكات الكهرباء والإنارة والمرافق.',

        // Certifications Section
        'cert_tag' => 'التسجيلات والشهادات الرسمية',
        'cert_title' => 'اعتمادات غرفة تجارة وصناعة عمان وجهاز الضرائب',
        'cert_subtitle' => 'فيسكون العالمية ش م م مسجلة ومعتمدة رسمياً لدى الجهات الحكومية في سلطنة عمان للدرجة الممتازة.',
        
        'cert_1_badge' => 'الدرجة: الممتازة',
        'cert_1_title' => 'غرفة تجارة وصناعة عمان',
        'cert_1_subtitle' => 'شهادة انتساب - الدرجة الممتازة',
        'cert_1_cr' => 'رقم السجل التجاري: ١٦٦٧٦٠٠',
        'cert_1_grade' => 'الدرجة المسجلة: الممتازة',
        'cert_1_occi' => 'رقم عضوية الغرفة: ٨٧١٣٩٩',
        'cert_1_location' => 'المقر الرئيسي: محافظة مسقط، سلطنة عمان',
        'cert_1_btn' => 'عرض شهادة الانتساب الرسمية',

        'cert_2_badge' => 'كيان مسجل ضريبياً',
        'cert_2_title' => 'جهاز الضرائب - سلطنة عمان',
        'cert_2_subtitle' => 'البطاقة الضريبية الرسمية',
        'cert_2_card_no' => 'رقم البطاقة الضريبية: ١٥١٤٠٠٤٩٢',
        'cert_2_tin' => 'رقم التعريف الضريبي: ٢٣١٤٣٦٦',
        'cert_2_license' => 'رقم السجل التجاري / الترخيص: ١٦٦٧٦٠٠',
        'cert_2_expiry' => 'صالحة حتى: ٢٩/٠٧/٢٠٢٨',
        'cert_2_btn' => 'عرض البطاقة الضريبية الرسمية',

        // Projects Section
        'proj_tag' => 'معرض مشاريعنا',
        'proj_title' => 'المشاريع الهندسية وحلول البنية التحتية',
        'proj_subtitle' => 'استعراض لمشاريعنا المنجزة في إنارة طرق الشوارع السريعة، شبكات المغذيات الكهربائية، ومحطات المحولات ذات الجهد العالي.',
        'proj_filter_all' => 'جميع المشاريع',
        'proj_filter_grid' => 'محطات المحولات',
        'proj_filter_dist' => 'شبكات التوزيع',
        'proj_filter_lighting' => 'إنارة الشوارع',
        'proj_filter_civil' => 'البنية التحتية المدنية',
        'proj_filter_mep' => 'أعمال الكهروميكانيكية',
        'proj_filter_oilgas' => 'النفط والغاز',
        'proj_filter_epc' => 'مشاريع تسليم المفتاح',
        'proj_filter_om' => 'التشغيل والصيانة',
        
        'proj_1_title' => 'مشروع إنارة الشوارع للطريق السريع',
        'proj_1_cat' => 'إنارة الشوارع',
        'proj_1_desc' => 'تركيب أعمدة إنارة بارتفاع ١٢ متر، كشافات إنارة موفرة للطاقة، لوحات تغذية وتحكم، ومد ٤٥ كم من كابلات التغذية الأرضية.',
        
        'proj_2_title' => 'خدمات محطة محولات رئيسية جهد ١٣٢ كيلوفولت',
        'proj_2_cat' => 'محطات المحولات',
        'proj_2_desc' => 'تنفيذ خدمات هندسية لمحطة محولات رئيسية جهد ١٣٢ كيلوفولت تشمل مبنى المفاتيح المعزولة بالغاز، محولات الطاقة، أنظمة البطاريات، وأنظمة التحكم والمراقبة.',
        
        'proj_3_title' => 'تمديد شبكة التوزيع الكهربائي الجهد المتوسط والمنخفض',
        'proj_3_cat' => 'شبكات التوزيع',
        'proj_3_desc' => 'تمديد كابلات جهد ١١ كيلوفولت، تركيب وحدات الربط الحلقي، محطات مدمجة سعة ١٠٠٠ ك.ف.أ، وكبائن توزيع للمنطقة التجارية.',
        
        'proj_4_title' => 'تطوير البنية التحتية المدنية للطرق السريعة',
        'proj_4_cat' => 'البنية التحتية المدنية',
        'proj_4_desc' => 'أعمال تمهيد وتسوية ترابية ثقيلة، سفلتة الطرق، قنوات تصريف مياه الأمطار والجسور الإنشائية لمحاور الطرق السريعة الرئيسية.',
        
        'proj_5_title' => 'الأعمال الكهروميكانيكية المتكاملة للمنشآت التجارية والصناعية',
        'proj_5_cat' => 'أعمال الكهروميكانيكية',
        'proj_5_desc' => 'تنفيذ متكامل لمحطات التبريد والتكييف المركزي، أنظمة الإنذار ومكافحة الحرائق، مسارات الكابلات الكهربائية، ولوحات التوزيع وشبكات السباكة.',
        
        'proj_6_title' => 'دعم الأعمال الكهربائية والتحكم لمنشآت النفط والغاز',
        'proj_6_cat' => 'قطاع النفط والغاز',
        'proj_6_desc' => 'تمديد شبكات التغذية الكهربائية عالية الموثوقية، أجهزة القياس والتحكم، تمديدات مقاومة للانفجار، وقواعد الأنابيب ومحطات التشغيل الحقلية.',

        'proj_7_title' => 'مشروع متكامل لخطوط نقل الطاقة الهوائية',
        'proj_7_cat' => 'مشاريع تسليم المفتاح',
        'proj_7_desc' => 'تنفيذ متكامل لخطوط نقل الطاقة الكهربائية ذات الجهد العالي، الأبراج الفولاذية، شد الكابلات وربط المحطات بالشبكة الرئيسية.',

        'proj_8_title' => 'تشغيل وصيانة المحطات والمرافق الصناعية',
        'proj_8_cat' => 'التشغيل والصيانة',
        'proj_8_desc' => 'صيانة وقائية على مدار الساعة، فحص وتشخيص حراري بالأشعة تحت الحمراء، معايرة مرحلات الحماية، واستجابة طارئة لإعادة تشغيل الشبكة.',

        // Contact Section
        'contact_tag' => 'تواصل معنا',
        'contact_title' => 'تواصل مع خبرائنا الهندسيين',
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
        'footer_cr' => 'السجل التجاري: ١٦٦٧٦٠٠',
        'footer_occi_grade' => 'درجة الغرفة: الممتازة',
        'footer_tax_card' => 'البطاقة الضريبية: ١٥١٤٠٠٤٩٢',
        'footer_admin' => 'لوحة التحكم',
        'footer_rights' => 'فيسكون العالمية ش م م. جميع الحقوق محفوظة.',
        'footer_tagline' => 'إنارة الطرق، شبكات التوزيع ومحطات المحولات',
        'modal_doc_title' => 'معاينة المستند'
    ]
];

// Helper function to get text
function t($key) {
    global $translations, $lang;
    return isset($translations[$lang][$key]) ? $translations[$lang][$key] : (isset($translations['en'][$key]) ? $translations['en'][$key] : $key);
}
