<?php
/**
 * FESCON Oman - Admin Dashboard (Project & Category CRUD Management)
 */
require_once __DIR__ . '/auth.php';
requireAdminLogin();

// Set UTF-8 header
header('Content-Type: text/html; charset=UTF-8');

$projects = loadProjects();
$categories = loadCategories();
$csrfToken = getCsrfToken();

$message = $_SESSION['flash_message'] ?? '';
$messageType = $_SESSION['flash_type'] ?? 'success';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);

// Collect all existing images in assets/images/
$existingImages = [];
$assetsImgDir = dirname(__DIR__) . '/assets/images/';
if (is_dir($assetsImgDir)) {
    $files = scandir($assetsImgDir);
    foreach ($files as $f) {
        if (preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $f)) {
            $existingImages[] = 'assets/images/' . $f;
        }
    }
}

// Handle POST actions (Projects & Categories CRUD)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($token)) {
        $_SESSION['flash_message'] = 'Security validation failed (Invalid CSRF). Please try again.';
        $_SESSION['flash_type'] = 'error';
        header('Location: index.php');
        exit;
    }

    // Helper for project image upload/selection
    $handleImageUpload = function($currentImage = '') use ($assetsImgDir) {
        if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['image_file']['tmp_name'];
            $origName = $_FILES['image_file']['name'];
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
            ];

            if (!in_array($ext, $allowedExts, true)) {
                return ['error' => 'Invalid image format. Allowed: JPG, PNG, WEBP.'];
            }

            if ((int)$_FILES['image_file']['size'] > 5 * 1024 * 1024) {
                return ['error' => 'Image size must not exceed 5 MB.'];
            }

            if (!class_exists('finfo')) {
                return ['error' => 'Server image validation is unavailable. Please contact the site administrator.'];
            }

            $imageInfo = @getimagesize($tmpName);
            $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($tmpName);
            if ($imageInfo === false || !isset($allowedMimes[$mimeType]) || $imageInfo[0] > 6000 || $imageInfo[1] > 6000) {
                return ['error' => 'Invalid image file or unsupported image dimensions.'];
            }

            $safeName = 'proj_' . bin2hex(random_bytes(12)) . '.' . $allowedMimes[$mimeType];
            $destPath = $assetsImgDir . $safeName;

            if (move_uploaded_file($tmpName, $destPath)) {
                return ['path' => 'assets/images/' . $safeName];
            } else {
                return ['error' => 'Failed to save uploaded image. Check folder permissions.'];
            }
        }

        $selectedExisting = trim($_POST['existing_image'] ?? '');
        if (!empty($selectedExisting)) {
            $selectedFilename = basename($selectedExisting);
            $selectedPath = $assetsImgDir . $selectedFilename;
            if (is_file($selectedPath) && preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $selectedFilename)) {
                return ['path' => 'assets/images/' . $selectedFilename];
            }
            return ['error' => 'Selected image is not available.'];
        }

        return ['path' => !empty($currentImage) ? $currentImage : 'assets/images/project_civil_infra.jpg'];
    };

    // ==========================================
    // PROJECTS CRUD
    // ==========================================

    // 1. CREATE PROJECT
    if ($action === 'create') {
        $category = trim($_POST['category'] ?? 'civil');
        $enTitle = trim($_POST['en_title'] ?? '');
        $enCatLabel = trim($_POST['en_cat_label'] ?? '');
        $enDesc = trim($_POST['en_desc'] ?? '');
        $arTitle = trim($_POST['ar_title'] ?? '');
        $arCatLabel = trim($_POST['ar_cat_label'] ?? '');
        $arDesc = trim($_POST['ar_desc'] ?? '');

        if (empty($enTitle) && empty($arTitle)) {
            $_SESSION['flash_message'] = 'Please enter a project title (English or Arabic). / يرجى إدخال عنوان المشروع';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php');
            exit;
        }

        if (empty($enTitle)) {
            $enTitle = $arTitle;
        }
        if (empty($arTitle)) {
            $arTitle = $enTitle;
        }

        if (empty($enDesc) && !empty($arDesc)) {
            $enDesc = $arDesc;
        }
        if (empty($arDesc) && !empty($enDesc)) {
            $arDesc = $enDesc;
        }

        if (empty($enCatLabel) && isset($categories[$category])) {
            $enCatLabel = $categories[$category]['en'];
        }
        if (empty($arCatLabel) && isset($categories[$category])) {
            $arCatLabel = $categories[$category]['ar'];
        }

        $imgResult = $handleImageUpload();
        if (isset($imgResult['error'])) {
            $_SESSION['flash_message'] = $imgResult['error'];
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php');
            exit;
        }

        $maxId = 0;
        foreach ($projects as $p) {
            if (isset($p['id']) && (int)$p['id'] > $maxId) {
                $maxId = (int)$p['id'];
            }
        }
        $newId = $maxId + 1;

        $newProject = [
            'id' => $newId,
            'category' => $category,
            'image' => $imgResult['path'],
            'en' => [
                'title' => $enTitle,
                'category_label' => $enCatLabel ?: ucfirst($category),
                'desc' => $enDesc
            ],
            'ar' => [
                'title' => $arTitle,
                'category_label' => $arCatLabel ?: ($categories[$category]['ar'] ?? ucfirst($category)),
                'desc' => $arDesc
            ]
        ];

        $projects[] = $newProject;
        saveProjects($projects);

        $_SESSION['flash_message'] = 'Project added successfully! / تم إضافة المشروع بنجاح';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    }

    // 2. UPDATE PROJECT
    elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $foundIndex = -1;

        foreach ($projects as $idx => $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                $foundIndex = $idx;
                break;
            }
        }

        if ($foundIndex === -1) {
            $_SESSION['flash_message'] = 'Project not found.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php');
            exit;
        }

        $category = trim($_POST['category'] ?? $projects[$foundIndex]['category'] ?? 'civil');
        $enTitle = trim($_POST['en_title'] ?? '');
        $enCatLabel = trim($_POST['en_cat_label'] ?? '');
        $enDesc = trim($_POST['en_desc'] ?? '');
        $arTitle = trim($_POST['ar_title'] ?? '');
        $arCatLabel = trim($_POST['ar_cat_label'] ?? '');
        $arDesc = trim($_POST['ar_desc'] ?? '');

        if (empty($enTitle) && empty($arTitle)) {
            $_SESSION['flash_message'] = 'Please enter a project title (English or Arabic). / يرجى إدخال عنوان المشروع';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php');
            exit;
        }

        if (empty($enTitle)) {
            $enTitle = $arTitle;
        }
        if (empty($arTitle)) {
            $arTitle = $enTitle;
        }

        if (empty($enDesc) && !empty($arDesc)) {
            $enDesc = $arDesc;
        }
        if (empty($arDesc) && !empty($enDesc)) {
            $arDesc = $enDesc;
        }

        $currImg = $projects[$foundIndex]['image'] ?? '';
        $imgResult = $handleImageUpload($currImg);
        if (isset($imgResult['error'])) {
            $_SESSION['flash_message'] = $imgResult['error'];
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php');
            exit;
        }

        $projects[$foundIndex]['category'] = $category;
        $projects[$foundIndex]['image'] = $imgResult['path'];
        $projects[$foundIndex]['en']['title'] = $enTitle;
        $projects[$foundIndex]['en']['category_label'] = $enCatLabel ?: ($categories[$category]['en'] ?? ucfirst($category));
        $projects[$foundIndex]['en']['desc'] = $enDesc;

        $projects[$foundIndex]['ar']['title'] = $arTitle;
        $projects[$foundIndex]['ar']['category_label'] = $arCatLabel ?: ($categories[$category]['ar'] ?? $projects[$foundIndex]['en']['category_label']);
        $projects[$foundIndex]['ar']['desc'] = $arDesc;

        saveProjects($projects);

        $_SESSION['flash_message'] = 'Project #' . $id . ' updated successfully! / تم تحديث المشروع بنجاح';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    }

    // 3. DELETE PROJECT
    elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $newProjects = [];

        foreach ($projects as $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                continue;
            }
            $newProjects[] = $p;
        }

        saveProjects($newProjects);
        $_SESSION['flash_message'] = 'Project has been removed. / تم حذف المشروع';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    }

    // 4. REORDER PROJECT
    elseif ($action === 'move') {
        $id = (int)($_POST['id'] ?? 0);
        $direction = $_POST['direction'] ?? 'up';

        $index = -1;
        foreach ($projects as $i => $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                $index = $i;
                break;
            }
        }

        if ($index !== -1) {
            $swapIndex = ($direction === 'up') ? $index - 1 : $index + 1;
            if ($swapIndex >= 0 && $swapIndex < count($projects)) {
                $temp = $projects[$index];
                $projects[$index] = $projects[$swapIndex];
                $projects[$swapIndex] = $temp;
                saveProjects($projects);
                $_SESSION['flash_message'] = 'Project order updated.';
                $_SESSION['flash_type'] = 'success';
            }
        }
        header('Location: index.php');
        exit;
    }

    // ==========================================
    // CATEGORIES CRUD
    // ==========================================

    // 5. CREATE CATEGORY
    elseif ($action === 'create_category') {
        $rawKey = trim($_POST['cat_key'] ?? '');
        $catKey = preg_replace('/[^a-z0-9_\-]/', '', strtolower($rawKey));
        $catEn = trim($_POST['cat_en'] ?? '');
        $catAr = trim($_POST['cat_ar'] ?? '');
        $catColor = normalizeCategoryColor($_POST['cat_color'] ?? '#3b82f6');

        if (empty($catKey)) {
            $_SESSION['flash_message'] = 'Category key/code is required (letters and numbers only).';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?manage_cats=1');
            exit;
        }

        if (isset($categories[$catKey])) {
            $_SESSION['flash_message'] = 'Category code "' . htmlspecialchars($catKey) . '" already exists.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?manage_cats=1');
            exit;
        }

        if (empty($catEn) && empty($catAr)) {
            $_SESSION['flash_message'] = 'Please enter category name in English or Arabic.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?manage_cats=1');
            exit;
        }

        if (empty($catEn)) $catEn = $catAr;
        if (empty($catAr)) $catAr = $catEn;
        $categories[$catKey] = [
            'en' => $catEn,
            'ar' => $catAr,
            'color' => $catColor
        ];

        saveCategories($categories);
        $_SESSION['flash_message'] = 'New category "' . htmlspecialchars($catEn) . '" created successfully!';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php?manage_cats=1');
        exit;
    }

    // 6. UPDATE CATEGORY
    elseif ($action === 'update_category') {
        $catKey = trim($_POST['cat_key'] ?? '');
        $catEn = trim($_POST['cat_en'] ?? '');
        $catAr = trim($_POST['cat_ar'] ?? '');
        $catColor = normalizeCategoryColor($_POST['cat_color'] ?? '#3b82f6');

        if (!isset($categories[$catKey])) {
            $_SESSION['flash_message'] = 'Category not found.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?manage_cats=1');
            exit;
        }

        if (empty($catEn) && empty($catAr)) {
            $_SESSION['flash_message'] = 'Please enter category name in English or Arabic.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?manage_cats=1');
            exit;
        }

        if (empty($catEn)) $catEn = $catAr;
        if (empty($catAr)) $catAr = $catEn;
        $categories[$catKey] = [
            'en' => $catEn,
            'ar' => $catAr,
            'color' => $catColor
        ];

        saveCategories($categories);
        $_SESSION['flash_message'] = 'Category "' . htmlspecialchars($catEn) . '" updated successfully!';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php?manage_cats=1');
        exit;
    }

    // 7. DELETE CATEGORY
    elseif ($action === 'delete_category') {
        $catKey = trim($_POST['cat_key'] ?? '');

        if (!isset($categories[$catKey])) {
            $_SESSION['flash_message'] = 'Category not found.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?manage_cats=1');
            exit;
        }

        // Reassign any projects using this category to another valid category
        $otherKeys = array_keys($categories);
        $fallbackKey = 'general';
        foreach ($otherKeys as $k) {
            if ($k !== $catKey) {
                $fallbackKey = $k;
                break;
            }
        }

        $projectsUpdated = false;
        foreach ($projects as $idx => $p) {
            if (($p['category'] ?? '') === $catKey) {
                $projects[$idx]['category'] = $fallbackKey;
                $projectsUpdated = true;
            }
        }

        if ($projectsUpdated) {
            saveProjects($projects);
        }

        $delName = $categories[$catKey]['en'] ?? $catKey;
        unset($categories[$catKey]);
        saveCategories($categories);

        $_SESSION['flash_message'] = 'Category "' . htmlspecialchars($delName) . '" deleted successfully.';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php?manage_cats=1');
        exit;
    }
}

// Calculate stats
$totalProjects = count($projects);
$categoryCounts = [];
foreach ($projects as $p) {
    $c = $p['category'] ?? 'other';
    $categoryCounts[$c] = ($categoryCounts[$c] ?? 0) + 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects & Categories Dashboard - FESCON Oman Administration</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #08121e;
            --bg-surface: #0f2238;
            --bg-card: #142a45;
            --bg-hover: #1c385c;
            --accent-gold: #c5a059;
            --accent-gold-light: #e0be77;
            --accent-red: #ef4444;
            --accent-green: #10b981;
            --accent-blue: #3b82f6;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
            --border-gold: rgba(197, 160, 89, 0.35);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .admin-nav {
            background: rgba(11, 25, 44, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-gold);
            padding: 0.9rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: #ffffff;
        }

        .nav-logo {
            background: #ffffff;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .nav-logo img {
            height: 32px;
            display: block;
        }

        .nav-title-group h1 {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .nav-title-group h1 span {
            color: var(--accent-gold);
        }

        .nav-title-group p {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-live-site {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--border-color);
            color: #cbd5e1;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-live-site:hover {
            background: rgba(197, 160, 89, 0.15);
            color: var(--accent-gold);
            border-color: var(--border-gold);
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: var(--accent-red);
            color: #ffffff;
        }

        /* Main Container */
        .admin-main {
            flex: 1;
            max-width: 1380px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-gold);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: rgba(197, 160, 89, 0.15);
            color: var(--accent-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .stat-content .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .stat-content .stat-val {
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1;
        }

        /* Flash Message */
        .alert-toast {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.92rem;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-toast.success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #6ee7b7;
        }

        .alert-toast.error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }

        /* Action Toolbar */
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.75rem;
            background: var(--bg-surface);
            padding: 1rem 1.25rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
            min-width: 280px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            width: 100%;
            max-width: 320px;
        }

        .search-box input {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: #ffffff;
            padding: 0.65rem 1rem 0.65rem 2.4rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .search-box input:focus {
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 2px rgba(197, 160, 89, 0.2);
        }

        .search-box i {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .filter-pills {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            overflow-x: auto;
            max-width: 100%;
            padding-bottom: 2px;
        }

        .pill-btn {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.4rem 0.85rem;
            border-radius: 20px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .pill-btn:hover {
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.25);
        }

        .pill-btn.active {
            background: var(--accent-gold);
            color: #08121f;
            border-color: var(--accent-gold);
        }

        .toolbar-right-btns {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-manage-cats {
            background: rgba(197, 160, 89, 0.12);
            color: var(--accent-gold-light);
            border: 1px solid var(--border-gold);
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.25s;
            white-space: nowrap;
        }

        .btn-manage-cats:hover {
            background: var(--accent-gold);
            color: #08121f;
            transform: translateY(-1px);
        }

        .btn-add-project {
            background: linear-gradient(135deg, var(--accent-gold) 0%, #aa853c 100%);
            color: #08121f;
            border: none;
            padding: 0.65rem 1.35rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 4px 15px rgba(197, 160, 89, 0.3);
            white-space: nowrap;
        }

        .btn-add-project:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(197, 160, 89, 0.45);
        }

        /* Projects Table Container */
        .table-container {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .projects-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .projects-table th {
            background: rgba(11, 25, 44, 0.8);
            padding: 1rem 1.25rem;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            font-weight: 700;
        }

        .projects-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .projects-table tr:last-child td {
            border-bottom: none;
        }

        .projects-table tr:hover td {
            background: var(--bg-hover);
        }

        .proj-order-controls {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .btn-order {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            width: 24px;
            height: 22px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.7rem;
            transition: all 0.2s;
        }

        .btn-order:hover {
            color: var(--accent-gold);
            border-color: var(--accent-gold);
            background: var(--bg-hover);
        }

        .btn-order:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .proj-thumb-wrap {
            position: relative;
            width: 80px;
            height: 56px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            cursor: pointer;
            background: #000;
        }

        .proj-thumb-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s;
        }

        .proj-thumb-wrap:hover img {
            transform: scale(1.1);
        }

        .cat-badge {
            display: inline-block;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .title-bilingual {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .title-en {
            font-weight: 700;
            color: #ffffff;
            font-size: 0.95rem;
        }

        .title-ar {
            font-family: 'Tajawal', sans-serif;
            font-size: 0.92rem;
            color: #94a3b8;
            direction: rtl;
            text-align: left;
        }

        .desc-preview {
            max-width: 280px;
            font-size: 0.8rem;
            color: var(--text-muted);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .action-btns {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.45rem 0.85rem;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s;
        }

        .btn-edit {
            background: rgba(59, 130, 246, 0.15);
            color: #93c5fd;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .btn-edit:hover {
            background: var(--accent-blue);
            color: #ffffff;
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .btn-delete:hover {
            background: var(--accent-red);
            color: #ffffff;
        }

        /* =========================================
           MODAL STYLES (Fixed scrollability)
           ========================================= */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.78);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
            overflow-y: auto;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-gold);
            border-radius: 16px;
            width: 100%;
            max-width: 820px;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
            transform: scale(0.96);
            transition: transform 0.25s ease;
            overflow: hidden; /* Header and Footer are fixed, Body scrolls */
        }

        .modal-overlay.active .modal-card {
            transform: scale(1);
        }

        /* Critical: form must be a flex container that shares height */
        .modal-card form {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            min-height: 0;
            overflow: hidden;
        }

        .modal-header {
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(11, 25, 44, 0.7);
            flex-shrink: 0;
        }

        .modal-header h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .modal-header h3 i {
            color: var(--accent-gold);
        }

        .btn-close-modal {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.35rem;
            cursor: pointer;
            padding: 0.25rem;
            line-height: 1;
            transition: color 0.2s;
        }

        .btn-close-modal:hover {
            color: #ffffff;
        }

        .modal-body {
            padding: 1.5rem 1.75rem;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch;
            flex: 1 1 auto;
            min-height: 0;
        }

        /* Custom scrollbar for modal-body */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }
        .modal-body::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }
        .modal-body::-webkit-scrollbar-thumb {
            background: var(--accent-gold);
            border-radius: 4px;
        }

        .modal-footer {
            padding: 1.1rem 1.75rem;
            border-top: 1px solid var(--border-color);
            background: rgba(11, 25, 44, 0.7);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
            flex-shrink: 0;
        }

        /* Language Tabs inside Modal */
        .modal-lang-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.75rem;
        }

        .modal-tab-btn {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .modal-tab-btn:hover {
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.25);
        }

        .modal-tab-btn.active {
            background: var(--accent-gold);
            color: #08121f;
            border-color: var(--accent-gold);
        }

        .tab-content-panel {
            display: none;
        }

        .tab-content-panel.active {
            display: block;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .form-full {
            grid-column: 1 / -1;
        }

        .form-section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent-gold);
            font-weight: 700;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px dashed rgba(197, 160, 89, 0.25);
            padding-bottom: 0.4rem;
        }

        .modal-form-group {
            margin-bottom: 1.1rem;
        }

        .modal-form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 0.4rem;
        }

        .modal-form-control {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.7rem 0.9rem;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.88rem;
            outline: none;
            transition: all 0.2s;
        }

        .modal-form-control:focus {
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 2px rgba(197, 160, 89, 0.2);
        }

        textarea.modal-form-control {
            resize: vertical;
            min-height: 85px;
        }

        .rtl-input {
            direction: rtl;
            font-family: 'Tajawal', sans-serif;
            text-align: right;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .img-preview-box {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.5rem;
            background: rgba(0, 0, 0, 0.25);
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px dashed var(--border-color);
        }

        .img-preview-box img {
            width: 75px;
            height: 52px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

        .img-preview-info {
            font-size: 0.78rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--border-color);
            color: #cbd5e1;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .btn-save {
            background: linear-gradient(135deg, var(--accent-gold) 0%, #aa853c 100%);
            border: none;
            color: #08121f;
            padding: 0.65rem 1.5rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-save:hover {
            background: linear-gradient(135deg, #d4af37 0%, #ba9343 100%);
            transform: translateY(-1px);
        }

        .btn-confirm-delete {
            background: var(--accent-red);
            color: #ffffff;
            border: none;
            padding: 0.65rem 1.5rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Lightbox Preview */
        .lightbox-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .lightbox-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .lightbox-img {
            max-width: 90%;
            max-height: 85vh;
            border-radius: 8px;
            border: 2px solid var(--accent-gold);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
        }

        /* Categories Table & Badges inside Modal */
        .cats-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .cats-table th {
            background: rgba(11, 25, 44, 0.6);
            padding: 0.75rem 1rem;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }

        .cats-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.88rem;
            vertical-align: middle;
        }

        .cats-table tr:hover td {
            background: var(--bg-hover);
        }

        .cat-color-dot {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            margin-right: 6px;
            vertical-align: middle;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .admin-nav {
                padding: 0.75rem 1rem;
            }
            .admin-main {
                padding: 1rem;
            }
            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box {
                max-width: 100%;
            }
        }

        /* =========================================
           CONTROL CENTER THEME REFINEMENT
           ========================================= */
        :root {
            --bg-body: #07111f;
            --bg-surface: rgba(15, 34, 56, 0.88);
            --bg-card: #112a46;
            --bg-hover: #193957;
            --shadow-panel: 0 18px 45px rgba(1, 9, 20, 0.28);
        }

        body {
            background:
                radial-gradient(circle at 12% -10%, rgba(197, 160, 89, 0.17), transparent 30rem),
                radial-gradient(circle at 96% 0%, rgba(37, 99, 235, 0.14), transparent 28rem),
                linear-gradient(150deg, #07111f 0%, #0a192c 47%, #08131f 100%);
            letter-spacing: 0.01em;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: 0.18;
            background-image: linear-gradient(rgba(255, 255, 255, 0.018) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.018) 1px, transparent 1px);
            background-size: 28px 28px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.9), transparent 68%);
        }

        .admin-nav {
            min-height: 78px;
            padding: 0.8rem clamp(1rem, 3vw, 3rem);
            background: rgba(7, 17, 31, 0.82);
            border-bottom-color: rgba(197, 160, 89, 0.23);
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.18);
        }

        .nav-logo {
            padding: 0.38rem 0.82rem;
            border: 1px solid rgba(197, 160, 89, 0.32);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .nav-title-group h1 {
            font-size: 1.2rem;
        }

        .nav-title-group p {
            margin-top: 0.15rem;
        }

        .btn-live-site,
        .btn-logout,
        .btn-manage-cats,
        .btn-add-project,
        .btn-save,
        .btn-cancel,
        .btn-action,
        .btn-order {
            outline: none;
        }

        .btn-live-site:focus-visible,
        .btn-logout:focus-visible,
        .btn-manage-cats:focus-visible,
        .btn-add-project:focus-visible,
        .btn-save:focus-visible,
        .btn-cancel:focus-visible,
        .btn-action:focus-visible,
        .btn-order:focus-visible,
        .pill-btn:focus-visible {
            box-shadow: 0 0 0 3px rgba(224, 190, 119, 0.4);
        }

        .admin-main {
            max-width: 1440px;
            padding: clamp(1.25rem, 3vw, 2.75rem);
            position: relative;
        }

        .dashboard-hero {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            margin: 0.25rem 0 2rem;
        }

        .dashboard-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--accent-gold-light);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .dashboard-eyebrow::before {
            content: '';
            width: 1.85rem;
            height: 1px;
            background: var(--accent-gold);
        }

        .dashboard-hero h2 {
            margin-top: 0.35rem;
            color: #ffffff;
            font-size: clamp(1.75rem, 3vw, 2.4rem);
            letter-spacing: -0.035em;
            line-height: 1.12;
        }

        .dashboard-hero p {
            max-width: 670px;
            margin-top: 0.6rem;
            color: var(--text-muted);
            font-size: 0.94rem;
        }

        .dashboard-status {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.62rem 0.85rem;
            color: #b8f5d9;
            border: 1px solid rgba(16, 185, 129, 0.28);
            border-radius: 999px;
            background: rgba(16, 185, 129, 0.08);
            font-size: 0.78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .dashboard-status i {
            font-size: 0.62rem;
            color: #34d399;
        }

        .stats-grid {
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            min-height: 112px;
            padding: 1.2rem 1.3rem;
            border-radius: 16px;
            background: linear-gradient(145deg, rgba(21, 46, 74, 0.92), rgba(10, 28, 47, 0.92));
            box-shadow: var(--shadow-panel);
            transition: transform 0.22s ease, border-color 0.22s ease, box-shadow 0.22s ease;
        }

        .stat-card::after {
            width: 3px;
            background: linear-gradient(to bottom, var(--accent-gold-light), var(--accent-gold));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(197, 160, 89, 0.42);
            box-shadow: 0 22px 44px rgba(0, 0, 0, 0.3);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
        }

        .stat-content .stat-label {
            font-size: 0.7rem;
            letter-spacing: 0.09em;
        }

        .stat-content .stat-val {
            margin-top: 0.35rem;
            font-size: 1.85rem;
        }

        .toolbar {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 16px;
            background: rgba(13, 31, 51, 0.78);
            box-shadow: var(--shadow-panel);
        }

        .search-box input {
            min-height: 43px;
            border-radius: 10px;
            background: rgba(5, 18, 33, 0.56);
        }

        .filter-pills {
            scrollbar-width: thin;
            scrollbar-color: var(--accent-gold) transparent;
        }

        .pill-btn {
            min-height: 34px;
            border-radius: 999px;
        }

        .btn-manage-cats,
        .btn-add-project {
            min-height: 43px;
            border-radius: 10px;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 16px;
            background: rgba(13, 31, 51, 0.82);
            box-shadow: var(--shadow-panel);
        }

        .projects-table {
            min-width: 920px;
        }

        .projects-table th {
            padding-top: 1.05rem;
            padding-bottom: 1.05rem;
            background: rgba(5, 18, 33, 0.75);
            color: #aab8cb;
        }

        .projects-table tr {
            transition: background 0.2s ease;
        }

        .projects-table td {
            padding-top: 1.1rem;
            padding-bottom: 1.1rem;
        }

        .proj-thumb-wrap {
            width: 88px;
            height: 60px;
            border-radius: 9px;
        }

        .action-btns {
            justify-content: flex-end;
        }

        .modal-card {
            border-radius: 18px;
            background: #0d2138;
        }

        .modal-header,
        .modal-footer {
            background: rgba(5, 18, 33, 0.78);
        }

        @media (max-width: 760px) {
            .admin-nav {
                min-height: auto;
                align-items: flex-start;
                gap: 0.8rem;
                flex-direction: column;
            }

            .nav-actions {
                width: 100%;
            }

            .btn-live-site,
            .btn-logout {
                flex: 1;
                justify-content: center;
            }

            .dashboard-hero {
                align-items: flex-start;
                flex-direction: column;
                margin-bottom: 1.5rem;
            }

            .dashboard-status {
                white-space: normal;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .stat-card {
                min-height: 100px;
                padding: 1rem;
                gap: 0.8rem;
            }

            .stat-icon {
                width: 42px;
                height: 42px;
                font-size: 1.05rem;
            }

            .stat-content .stat-val {
                font-size: 1.55rem;
            }

            .toolbar-left {
                min-width: 0;
            }

            .toolbar-right-btns {
                width: 100%;
            }

            .btn-manage-cats,
            .btn-add-project {
                flex: 1;
                justify-content: center;
                padding: 0.65rem 0.75rem;
            }
        }

        @media (max-width: 430px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .nav-title-group p {
                display: none;
            }

            .toolbar-right-btns {
                flex-direction: column;
            }

            .btn-manage-cats,
            .btn-add-project {
                width: 100%;
            }

            .modal-overlay {
                padding: 0.7rem;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Admin Navigation Bar -->
    <header class="admin-nav">
        <a href="index.php" class="nav-brand">
            <div class="nav-logo">
                <img src="../assets/images/logo_english.jpg" alt="FESCON" onerror="this.style.display='none'">
            </div>
            <div class="nav-title-group">
                <h1>FESCON <span>CONTROL</span></h1>
                <p>Project & Category Management</p>
            </div>
        </a>

        <div class="nav-actions">
            <a href="../" target="_blank" class="btn-live-site">
                <i class="fas fa-external-link-alt"></i>
                <span>View Live Site</span>
            </a>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="admin-main">

        <section class="dashboard-hero" aria-labelledby="dashboard-heading">
            <div>
                <span class="dashboard-eyebrow">Administration workspace</span>
                <h2 id="dashboard-heading">Portfolio control center</h2>
                <p>Manage your projects, categories, visuals, and ordering from one focused workspace.</p>
            </div>
            <div class="dashboard-status"><i class="fas fa-circle" aria-hidden="true"></i> Secure session active · <?php echo htmlspecialchars($_SESSION['fescon_admin_user'] ?? 'Administrator'); ?></div>
        </section>

        <!-- Flash Toast Notification -->
        <?php if (!empty($message)): ?>
            <div class="alert-toast <?php echo $messageType; ?>" id="flashToast">
                <span>
                    <i class="fas <?php echo ($messageType === 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>" style="margin-right: 8px;"></i>
                    <?php echo htmlspecialchars($message); ?>
                </span>
                <button type="button" onclick="document.getElementById('flashToast').remove();" style="background: none; border: none; color: inherit; cursor: pointer; font-size: 1.1rem;">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Projects</div>
                    <div class="stat-val"><?php echo $totalProjects; ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(197, 160, 89, 0.15); color: var(--accent-gold);">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Active Categories</div>
                    <div class="stat-val"><?php echo count($categories); ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Grid & Distribution</div>
                    <div class="stat-val"><?php echo ($categoryCounts['grid'] ?? 0) + ($categoryCounts['dist'] ?? 0); ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                    <i class="fas fa-road"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Civil & Infrastructure</div>
                    <div class="stat-val"><?php echo ($categoryCounts['civil'] ?? 0) + ($categoryCounts['lighting'] ?? 0) + ($categoryCounts['mep'] ?? 0); ?></div>
                </div>
            </div>
        </div>

        <!-- Controls Toolbar -->
        <div class="toolbar">
            <div class="toolbar-left">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="projectSearchInput" aria-label="Search projects">
                </div>

                <div class="filter-pills">
                    <button class="pill-btn active" data-filter="all">All (<?php echo $totalProjects; ?>)</button>
                    <?php foreach ($categories as $catKey => $catInfo): ?>
                        <button class="pill-btn" data-filter="<?php echo htmlspecialchars($catKey); ?>">
                            <?php echo htmlspecialchars($catInfo['en']); ?> (<?php echo $categoryCounts[$catKey] ?? 0; ?>)
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="toolbar-right-btns">
                <button type="button" class="btn-manage-cats" id="openCategoriesModalBtn">
                    <i class="fas fa-tags"></i>
                    <span>Manage Categories (<?php echo count($categories); ?>)</span>
                </button>
                <button type="button" class="btn-add-project" id="openAddModalBtn">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add New Project</span>
                </button>
            </div>
        </div>

        <!-- Projects Data Table -->
        <div class="table-container">
            <table class="projects-table" id="projectsDataTable">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">Order</th>
                        <th style="width: 95px;">Image</th>
                        <th style="width: 140px;">Category</th>
                        <th>Project Title (EN / AR)</th>
                        <th>Description Preview</th>
                        <th style="width: 160px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="projectsTableBody">
                    <?php if (empty($projects)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                <i class="fas fa-folder-open" style="font-size: 2.5rem; margin-bottom: 0.75rem; display: block; color: rgba(255,255,255,0.2);"></i>
                                No projects found in database. Click "Add New Project" to create your first showcase project!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($projects as $index => $proj): 
                            $catKey = $proj['category'] ?? 'civil';
                            $catBadge = $categories[$catKey] ?? [
                                'en' => ucfirst($catKey),
                                'ar' => $catKey,
                                'color' => '#64748b'
                            ];
                            $en = $proj['en'] ?? [];
                            $ar = $proj['ar'] ?? [];
                            $img = !empty($proj['image']) ? '../' . $proj['image'] : '../assets/images/project_civil_infra.jpg';
                            $projId = (int)($proj['id'] ?? ($index + 1));
                        ?>
                            <tr class="project-row" data-category="<?php echo htmlspecialchars($catKey); ?>" data-search="<?php echo htmlspecialchars(strtolower(($en['title'] ?? '') . ' ' . ($ar['title'] ?? '') . ' ' . ($catBadge['en'] ?? '') . ' ' . ($en['desc'] ?? '') . ' ' . ($ar['desc'] ?? ''))); ?>">
                                <!-- Order Up / Down -->
                                <td style="text-align: center;">
                                    <div class="proj-order-controls">
                                        <form method="POST" action="index.php" style="margin: 0;">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                            <input type="hidden" name="action" value="move">
                                            <input type="hidden" name="id" value="<?php echo $projId; ?>">
                                            <input type="hidden" name="direction" value="up">
                                            <button type="submit" class="btn-order" title="Move Up" <?php echo ($index === 0) ? 'disabled' : ''; ?>>
                                                <i class="fas fa-chevron-up"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="index.php" style="margin: 0;">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                                            <input type="hidden" name="action" value="move">
                                            <input type="hidden" name="id" value="<?php echo $projId; ?>">
                                            <input type="hidden" name="direction" value="down">
                                            <button type="submit" class="btn-order" title="Move Down" <?php echo ($index === count($projects) - 1) ? 'disabled' : ''; ?>>
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                                <!-- Image Preview -->
                                <td>
                                    <div class="proj-thumb-wrap" onclick="openLightbox('<?php echo htmlspecialchars($img); ?>')">
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="Preview" loading="lazy">
                                    </div>
                                </td>

                                <!-- Category Badge -->
                                <td>
                                    <span class="cat-badge" style="background: <?php echo htmlspecialchars($catBadge['color']); ?>22; color: <?php echo htmlspecialchars($catBadge['color']); ?>; border: 1px solid <?php echo htmlspecialchars($catBadge['color']); ?>55;">
                                        <?php echo htmlspecialchars($catBadge['en']); ?>
                                    </span>
                                </td>

                                <!-- Bilingual Titles -->
                                <td>
                                    <div class="title-bilingual">
                                        <div class="title-en"><?php echo htmlspecialchars($en['title'] ?? 'Untitled'); ?></div>
                                        <div class="title-ar"><?php echo htmlspecialchars($ar['title'] ?? ''); ?></div>
                                    </div>
                                </td>

                                <!-- Description -->
                                <td>
                                    <div class="desc-preview" title="<?php echo htmlspecialchars($en['desc'] ?? $ar['desc'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($en['desc'] ?? $ar['desc'] ?? 'No description provided.'); ?>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td style="text-align: right;">
                                    <div class="action-btns" style="justify-content: flex-end;">
                                        <button type="button" class="btn-action btn-edit" onclick="openEditModal(<?php echo $projId; ?>)">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" class="btn-action btn-delete" onclick="openDeleteModal(<?php echo $projId; ?>)">
                                            <i class="fas fa-trash-alt"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <!-- ==========================================
         ADD PROJECT MODAL
         ========================================== -->
    <div class="modal-overlay" id="addProjectModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> Add New Showcase Project</h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('addProjectModal')">&times;</button>
            </div>
            <form method="POST" action="index.php" enctype="multipart/form-data" accept-charset="UTF-8" id="addProjectForm" onsubmit="return validateProjectForm('add')">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                <input type="hidden" name="action" value="create">

                <div class="modal-body">
                    <!-- Category Selection -->
                    <div class="modal-form-group" style="margin-bottom: 1.25rem;">
                        <label class="modal-form-label">Category / تصنيف المشروع *</label>
                        <select name="category" class="modal-form-control" required id="addCategorySelect" onchange="autoFillCategoryLabels('add')">
                            <?php foreach ($categories as $catKey => $catInfo): ?>
                                <option value="<?php echo htmlspecialchars($catKey); ?>">
                                    <?php echo htmlspecialchars($catInfo['en']); ?> (<?php echo htmlspecialchars($catInfo['ar']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Language Tabs -->
                    <div class="modal-lang-tabs">
                        <button type="button" class="modal-tab-btn active" onclick="switchLangTab('add', 'ar')">
                            <i class="fas fa-language"></i> 🇴🇲 المحتوى العربي (Arabic)
                        </button>
                        <button type="button" class="modal-tab-btn" onclick="switchLangTab('add', 'en')">
                            <i class="fas fa-globe"></i> 🇬🇧 English Details
                        </button>
                    </div>

                    <!-- Arabic Tab Content -->
                    <div class="tab-content-panel active" id="add_tab_ar">
                        <div class="modal-form-group">
                            <label class="modal-form-label">عنوان المشروع (بالعربية) *</label>
                            <input type="text" name="ar_title" id="add_ar_title" class="modal-form-control rtl-input" aria-label="Project title in Arabic">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">مسمى التصنيف (بالعربية)</label>
                            <input type="text" name="ar_cat_label" id="add_ar_cat_label" class="modal-form-control rtl-input" aria-label="Category label in Arabic">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">وصف وتفاصيل المشروع (بالعربية)</label>
                            <textarea name="ar_desc" id="add_ar_desc" class="modal-form-control rtl-input" aria-label="Project description in Arabic"></textarea>
                        </div>
                    </div>

                    <!-- English Tab Content -->
                    <div class="tab-content-panel" id="add_tab_en">
                        <div class="modal-form-group">
                            <label class="modal-form-label">Project Title (English)</label>
                            <input type="text" name="en_title" id="add_en_title" class="modal-form-control" aria-label="Project title in English">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Category Label (English)</label>
                            <input type="text" name="en_cat_label" id="add_en_cat_label" class="modal-form-control" aria-label="Category label in English">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Description (English)</label>
                            <textarea name="en_desc" id="add_en_desc" class="modal-form-control" aria-label="Project description in English"></textarea>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="form-full" style="margin-top: 1rem;">
                        <div class="form-section-title"><i class="fas fa-image"></i> Project Image / صورة المشروع</div>
                    </div>

                    <div class="form-full">
                        <div class="modal-form-group">
                            <label class="modal-form-label">Upload Project Photo (JPG, PNG, WEBP)</label>
                            <input type="file" name="image_file" class="modal-form-control" accept="image/jpeg,image/png,image/webp">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('addProjectModal')">Cancel</button>
                    <button type="submit" class="btn-save"><i class="fas fa-plus"></i> Save Project</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==========================================
         EDIT PROJECT MODAL
         ========================================== -->
    <div class="modal-overlay" id="editProjectModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Showcase Project</h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('editProjectModal')">&times;</button>
            </div>
            <form method="POST" action="index.php" enctype="multipart/form-data" accept-charset="UTF-8" id="editProjectForm" onsubmit="return validateProjectForm('edit')">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id" value="">

                <div class="modal-body">
                    <!-- Category Selection -->
                    <div class="modal-form-group" style="margin-bottom: 1.25rem;">
                        <label class="modal-form-label">Category / تصنيف المشروع *</label>
                        <select name="category" class="modal-form-control" required id="edit_category">
                            <?php foreach ($categories as $catKey => $catInfo): ?>
                                <option value="<?php echo htmlspecialchars($catKey); ?>">
                                    <?php echo htmlspecialchars($catInfo['en']); ?> (<?php echo htmlspecialchars($catInfo['ar']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Language Tabs -->
                    <div class="modal-lang-tabs">
                        <button type="button" class="modal-tab-btn active" onclick="switchLangTab('edit', 'ar')">
                            <i class="fas fa-language"></i> 🇴🇲 المحتوى العربي (Arabic)
                        </button>
                        <button type="button" class="modal-tab-btn" onclick="switchLangTab('edit', 'en')">
                            <i class="fas fa-globe"></i> 🇬🇧 English Details
                        </button>
                    </div>

                    <!-- Arabic Tab Content -->
                    <div class="tab-content-panel active" id="edit_tab_ar">
                        <div class="modal-form-group">
                            <label class="modal-form-label">عنوان المشروع (بالعربية) *</label>
                            <input type="text" name="ar_title" id="edit_ar_title" class="modal-form-control rtl-input" aria-label="Project title in Arabic">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">تصنيف المشروع (بالعربية)</label>
                            <input type="text" name="ar_cat_label" id="edit_ar_cat_label" class="modal-form-control rtl-input" aria-label="Category label in Arabic">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">تفاصيل ووصف المشروع (بالعربية)</label>
                            <textarea name="ar_desc" id="edit_ar_desc" class="modal-form-control rtl-input" aria-label="Project description in Arabic"></textarea>
                        </div>
                    </div>

                    <!-- English Tab Content -->
                    <div class="tab-content-panel" id="edit_tab_en">
                        <div class="modal-form-group">
                            <label class="modal-form-label">Project Title (English)</label>
                            <input type="text" name="en_title" id="edit_en_title" class="modal-form-control">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Category Label (English)</label>
                            <input type="text" name="en_cat_label" id="edit_en_cat_label" class="modal-form-control">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Description (English)</label>
                            <textarea name="en_desc" id="edit_en_desc" class="modal-form-control"></textarea>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="form-full" style="margin-top: 1rem;">
                        <div class="form-section-title"><i class="fas fa-image"></i> Project Image / صورة المشروع</div>
                    </div>

                    <div class="form-full">
                        <div class="modal-form-group">
                            <label class="modal-form-label">Replace with New Photo (JPG, PNG, WEBP)</label>
                            <input type="file" name="image_file" class="modal-form-control" accept="image/jpeg,image/png,image/webp">
                        </div>
                    </div>

                    <div class="form-full">
                        <label class="modal-form-label">Current Active Image:</label>
                        <div class="img-preview-box">
                            <img id="edit_preview_img" src="../assets/images/project_civil_infra.jpg" alt="Current Image">
                            <div class="img-preview-info">
                                <strong id="edit_preview_name">assets/images/project_civil_infra.jpg</strong><br>
                                <span>Leave photo upload empty to preserve this current image.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('editProjectModal')">Cancel</button>
                    <button type="submit" class="btn-save"><i class="fas fa-check"></i> Update Project</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==========================================
         MANAGE CATEGORIES MODAL (Category CRUD)
         ========================================== -->
    <div class="modal-overlay" id="categoriesModal">
        <div class="modal-card" style="max-width: 860px;">
            <div class="modal-header">
                <h3><i class="fas fa-tags"></i> Manage Project Categories / إدارة التصنيفات</h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('categoriesModal')">&times;</button>
            </div>
            
            <div class="modal-body">
                <!-- Add New Category Form Box -->
                <div style="background: rgba(8, 18, 30, 0.6); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <div class="form-section-title" id="catFormTitle">
                        <i class="fas fa-plus-circle"></i> Add New Category / إضافة تصنيف جديد
                    </div>

                    <form method="POST" action="index.php" accept-charset="UTF-8" id="categoryForm">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                        <input type="hidden" name="action" id="cat_action" value="create_category">

                        <div class="form-grid">
                            <div class="modal-form-group">
                                <label class="modal-form-label">Category Code / Key * (e.g. solar, marine)</label>
                                <input type="text" name="cat_key" id="cat_key_input" class="modal-form-control" aria-label="Category code" required pattern="[a-zA-Z0-9_\-]+" title="Letters, numbers and hyphens only">
                            </div>

                            <div class="modal-form-group">
                                <label class="modal-form-label">Badge Color *</label>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <input type="color" name="cat_color" id="cat_color_input" value="#3b82f6" style="width: 44px; height: 38px; border: none; border-radius: 6px; cursor: pointer; background: transparent;">
                                    <input type="text" id="cat_color_hex" class="modal-form-control" value="#3b82f6" style="width: 110px;" oninput="document.getElementById('cat_color_input').value = this.value">
                                </div>
                            </div>

                            <div class="modal-form-group">
                                <label class="modal-form-label">English Name *</label>
                                <input type="text" name="cat_en" id="cat_en_input" class="modal-form-control" aria-label="Category name in English" required>
                            </div>

                            <div class="modal-form-group">
                                <label class="modal-form-label">الاسم بالعربية (Arabic Name) *</label>
                                <input type="text" name="cat_ar" id="cat_ar_input" class="modal-form-control rtl-input" aria-label="Category name in Arabic" required>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem;">
                            <button type="button" class="btn-cancel" id="cancelCatEditBtn" style="display: none;" onclick="resetCategoryForm()">Cancel Edit</button>
                            <button type="submit" class="btn-save" id="saveCatBtn"><i class="fas fa-save"></i> Save Category</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Categories Table -->
                <div class="form-section-title">
                    <i class="fas fa-list"></i> Existing Categories (<?php echo count($categories); ?>)
                </div>

                <table class="cats-table">
                    <thead>
                        <tr>
                            <th style="width: 140px;">Badge & Color</th>
                            <th>Code</th>
                            <th>English Name</th>
                            <th>Arabic Name</th>
                            <th style="width: 80px; text-align: center;">Projects</th>
                            <th style="width: 140px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cKey => $cInfo): 
                            $pCount = $categoryCounts[$cKey] ?? 0;
                        ?>
                            <tr>
                                <td>
                                    <span class="cat-badge" style="background: <?php echo htmlspecialchars($cInfo['color']); ?>22; color: <?php echo htmlspecialchars($cInfo['color']); ?>; border: 1px solid <?php echo htmlspecialchars($cInfo['color']); ?>66;">
                                        <span class="cat-color-dot" style="background: <?php echo htmlspecialchars($cInfo['color']); ?>;"></span>
                                        <?php echo htmlspecialchars($cInfo['en']); ?>
                                    </span>
                                </td>
                                <td><code><?php echo htmlspecialchars($cKey); ?></code></td>
                                <td style="font-weight: 600; color: #ffffff;"><?php echo htmlspecialchars($cInfo['en']); ?></td>
                                <td class="rtl-input" style="font-weight: 600; color: #94a3b8;"><?php echo htmlspecialchars($cInfo['ar']); ?></td>
                                <td style="text-align: center;">
                                    <span style="background: rgba(255,255,255,0.06); padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.78rem; font-weight: 700;">
                                        <?php echo $pCount; ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-btns" style="justify-content: flex-end;">
                                        <button type="button" class="btn-action btn-edit" title="Edit Category" onclick='editCategory(<?php echo json_encode($cKey, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>, <?php echo json_encode($cInfo['en'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>, <?php echo json_encode($cInfo['ar'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>, <?php echo json_encode($cInfo['color'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>)'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn-action btn-delete" title="Delete Category" onclick="confirmDeleteCategory('<?php echo htmlspecialchars(addslashes($cKey)); ?>', '<?php echo htmlspecialchars(addslashes($cInfo['en'])); ?>', <?php echo $pCount; ?>)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('categoriesModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- ==========================================
         DELETE CATEGORY CONFIRMATION MODAL
         ========================================== -->
    <div class="modal-overlay" id="deleteCategoryModal">
        <div class="modal-card" style="max-width: 480px;">
            <div class="modal-header" style="background: rgba(239, 68, 68, 0.1);">
                <h3 style="color: #fca5a5;"><i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i> Delete Category</h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('deleteCategoryModal')">&times;</button>
            </div>
            <form method="POST" action="index.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                <input type="hidden" name="action" value="delete_category">
                <input type="hidden" name="cat_key" id="delete_cat_key" value="">

                <div class="modal-body" style="padding: 1.5rem; text-align: center;">
                    <i class="fas fa-trash-alt" style="font-size: 2.8rem; color: #ef4444; margin-bottom: 1rem; opacity: 0.85;"></i>
                    <p style="font-size: 1.05rem; font-weight: 600; margin-bottom: 0.5rem; color: #ffffff;">Are you sure you want to delete this category?</p>
                    <p id="delete_cat_name" style="color: var(--accent-gold); font-size: 1rem; font-weight: 700; margin-bottom: 1rem;"></p>
                    <p id="delete_cat_warning" style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.4;"></p>
                </div>

                <div class="modal-footer" style="justify-content: center; gap: 1rem;">
                    <button type="button" class="btn-cancel" onclick="closeModal('deleteCategoryModal')">Cancel</button>
                    <button type="submit" class="btn-confirm-delete"><i class="fas fa-trash-alt"></i> Yes, Delete Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==========================================
         DELETE PROJECT CONFIRMATION MODAL
         ========================================== -->
    <div class="modal-overlay" id="deleteProjectModal">
        <div class="modal-card" style="max-width: 480px;">
            <div class="modal-header" style="background: rgba(239, 68, 68, 0.1);">
                <h3 style="color: #fca5a5;"><i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i> Delete Project</h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('deleteProjectModal')">&times;</button>
            </div>
            <form method="POST" action="index.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="delete_proj_id" value="">

                <div class="modal-body" style="padding: 1.5rem; text-align: center;">
                    <i class="fas fa-trash-alt" style="font-size: 2.8rem; color: #ef4444; margin-bottom: 1rem; opacity: 0.85;"></i>
                    <p style="font-size: 1.05rem; font-weight: 600; margin-bottom: 0.5rem; color: #ffffff;">Are you sure you want to delete this project?</p>
                    <p id="delete_proj_title" style="color: var(--accent-gold); font-size: 1rem; font-weight: 700; margin-bottom: 1rem;"></p>
                    <p style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.4;">This will immediately remove the project card from the continuous marquee and portfolio section of the public website.</p>
                </div>

                <div class="modal-footer" style="justify-content: center; gap: 1rem;">
                    <button type="button" class="btn-cancel" onclick="closeModal('deleteProjectModal')">Cancel</button>
                    <button type="submit" class="btn-confirm-delete"><i class="fas fa-trash-alt"></i> Yes, Delete Project</button>
                </div>
            </form>
        </div>
    </div>

    <!-- LIGHTBOX PREVIEW MODAL -->
    <div class="lightbox-modal" id="lightboxModal" onclick="closeLightbox()">
        <img src="" alt="Full Preview" class="lightbox-img" id="lightboxImage">
    </div>

    <!-- Client-side Scripts -->
    <script>
        const categoriesMap = <?php echo json_encode($categories, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
        const allProjectsData = <?php echo json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

        // Modal Management
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        // Switch Language Tabs inside Modals
        function switchLangTab(prefix, lang) {
            const modalEl = document.getElementById(prefix + 'ProjectModal');
            const tabs = modalEl.querySelectorAll('.modal-tab-btn');
            tabs.forEach(t => t.classList.remove('active'));

            const panels = modalEl.querySelectorAll('.tab-content-panel');
            panels.forEach(p => p.classList.remove('active'));

            event.currentTarget.classList.add('active');

            const activePanel = document.getElementById(prefix + '_tab_' + lang);
            if (activePanel) activePanel.classList.add('active');
        }

        // Add Project modal trigger
        document.getElementById('openAddModalBtn').addEventListener('click', () => {
            autoFillCategoryLabels('add');
            openModal('addProjectModal');
        });

        // Manage Categories modal trigger
        document.getElementById('openCategoriesModalBtn').addEventListener('click', () => {
            openModal('categoriesModal');
        });

        // Check if URL has ?manage_cats=1
        if (window.location.search.includes('manage_cats=1')) {
            openModal('categoriesModal');
        }

        // Sync color picker with hex input
        document.getElementById('cat_color_input').addEventListener('input', function() {
            document.getElementById('cat_color_hex').value = this.value;
        });

        // Category Edit Setup
        function editCategory(key, en, ar, color) {
            document.getElementById('catFormTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Category: <code>' + key + '</code>';
            document.getElementById('cat_action').value = 'update_category';
            const keyInput = document.getElementById('cat_key_input');
            keyInput.value = key;
            keyInput.readOnly = true;
            keyInput.style.opacity = '0.6';
            document.getElementById('cat_en_input').value = en;
            document.getElementById('cat_ar_input').value = ar;
            document.getElementById('cat_color_input').value = color || '#3b82f6';
            document.getElementById('cat_color_hex').value = color || '#3b82f6';
            document.getElementById('cancelCatEditBtn').style.display = 'inline-flex';
            document.getElementById('saveCatBtn').innerHTML = '<i class="fas fa-check"></i> Update Category';
            
            // Scroll to form inside modal
            document.querySelector('#categoriesModal .modal-body').scrollTop = 0;
        }

        // Reset Category Form
        function resetCategoryForm() {
            document.getElementById('catFormTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Add New Category / إضافة تصنيف جديد';
            document.getElementById('cat_action').value = 'create_category';
            const keyInput = document.getElementById('cat_key_input');
            keyInput.value = '';
            keyInput.readOnly = false;
            keyInput.style.opacity = '1';
            document.getElementById('cat_en_input').value = '';
            document.getElementById('cat_ar_input').value = '';
            document.getElementById('cat_color_input').value = '#3b82f6';
            document.getElementById('cat_color_hex').value = '#3b82f6';
            document.getElementById('cancelCatEditBtn').style.display = 'none';
            document.getElementById('saveCatBtn').innerHTML = '<i class="fas fa-save"></i> Save Category';
        }

        // Confirm Delete Category
        function confirmDeleteCategory(key, name, count) {
            document.getElementById('delete_cat_key').value = key;
            document.getElementById('delete_cat_name').textContent = name + ' (' + key + ')';
            
            let warningText = 'This category will be permanently removed.';
            if (count > 0) {
                warningText = 'Notice: ' + count + ' project(s) currently use this category. If deleted, those projects will be safely reassigned so they remain visible.';
            }
            document.getElementById('delete_cat_warning').textContent = warningText;
            openModal('deleteCategoryModal');
        }

        // Autofill labels based on category in Add/Edit Project modals
        function autoFillCategoryLabels(prefix) {
            const catSelect = document.getElementById(prefix === 'add' ? 'addCategorySelect' : 'edit_category');
            const enLabel = document.getElementById(prefix + '_en_cat_label');
            const arLabel = document.getElementById(prefix + '_ar_cat_label');
            if (catSelect && categoriesMap[catSelect.value]) {
                if (enLabel && (!enLabel.value || prefix === 'add')) {
                    enLabel.value = categoriesMap[catSelect.value].en;
                }
                if (arLabel && (!arLabel.value || prefix === 'add')) {
                    arLabel.value = categoriesMap[catSelect.value].ar;
                }
            }
        }

        // Validate project form has at least one title before submission
        function validateProjectForm(prefix) {
            const arTitle = document.getElementById(prefix + '_ar_title').value.trim();
            const enTitle = document.getElementById(prefix + '_en_title').value.trim();
            if (!arTitle && !enTitle) {
                alert('Please enter at least a project title in Arabic or English.\nيرجى إدخال عنوان المشروع بالعربية أو الإنجليزية.');
                return false;
            }
            return true;
        }

        // Edit Project Modal Setup using ID lookup from allProjectsData
        function openEditModal(id) {
            const project = allProjectsData.find(p => parseInt(p.id) === parseInt(id));
            if (!project) return;

            document.getElementById('edit_id').value = project.id;
            document.getElementById('edit_category').value = project.category || 'civil';
            
            const en = project.en || {};
            const ar = project.ar || {};

            document.getElementById('edit_ar_title').value = ar.title || '';
            document.getElementById('edit_ar_cat_label').value = ar.category_label || (categoriesMap[project.category] ? categoriesMap[project.category].ar : '');
            document.getElementById('edit_ar_desc').value = ar.desc || '';

            document.getElementById('edit_en_title').value = en.title || '';
            document.getElementById('edit_en_cat_label').value = en.category_label || (categoriesMap[project.category] ? categoriesMap[project.category].en : '');
            document.getElementById('edit_en_desc').value = en.desc || '';

            const imgPath = project.image ? ('../' + project.image) : '../assets/images/project_civil_infra.jpg';
            document.getElementById('edit_preview_img').src = imgPath;
            document.getElementById('edit_preview_name').textContent = project.image || 'assets/images/project_civil_infra.jpg';

            openModal('editProjectModal');
        }

        // Delete Project Modal Setup using ID lookup
        function openDeleteModal(id) {
            const project = allProjectsData.find(p => parseInt(p.id) === parseInt(id));
            const title = project ? ((project.ar && project.ar.title) ? project.ar.title : (project.en && project.en.title ? project.en.title : 'Project #' + id)) : ('Project #' + id);
            
            document.getElementById('delete_proj_id').value = id;
            document.getElementById('delete_proj_title').textContent = title;
            openModal('deleteProjectModal');
        }

        // Lightbox
        function openLightbox(src) {
            document.getElementById('lightboxImage').src = src;
            document.getElementById('lightboxModal').classList.add('active');
        }

        function closeLightbox() {
            document.getElementById('lightboxModal').classList.remove('active');
        }

        // Realtime Filter & Search
        const searchInput = document.getElementById('projectSearchInput');
        const pillButtons = document.querySelectorAll('.pill-btn');
        const tableRows = document.querySelectorAll('.project-row');

        let activeFilter = 'all';

        function filterRows() {
            const query = (searchInput.value || '').toLowerCase().trim();

            tableRows.forEach(row => {
                const rowCat = row.getAttribute('data-category');
                const rowSearch = row.getAttribute('data-search') || '';

                const matchesCat = (activeFilter === 'all' || rowCat === activeFilter);
                const matchesQuery = (!query || rowSearch.includes(query));

                if (matchesCat && matchesQuery) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterRows);
        }

        pillButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                pillButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeFilter = btn.getAttribute('data-filter');
                filterRows();
            });
        });

        // Close modal when clicking outside of card
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    overlay.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
