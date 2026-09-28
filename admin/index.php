<?php
/**
 * FESCON Oman - Admin Dashboard (Project CRUD Management)
 */
require_once __DIR__ . '/auth.php';
requireAdminLogin();

$projects = loadProjects();
$categories = getStandardCategories();
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

// Handle POST actions (Create, Update, Delete, Reorder)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($token)) {
        $_SESSION['flash_message'] = 'Security validation failed (Invalid CSRF). Please try again.';
        $_SESSION['flash_type'] = 'error';
        header('Location: index.php');
        exit;
    }

    // Helper for image handling
    $handleImageUpload = function($currentImage = '') use ($assetsImgDir) {
        // Check if new file was uploaded
        if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['image_file']['tmp_name'];
            $origName = $_FILES['image_file']['name'];
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $allowedExts)) {
                return ['error' => 'Invalid image format. Allowed: JPG, PNG, WEBP.'];
            }

            // Clean filename
            $safeName = 'proj_' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\-]/', '', pathinfo($origName, PATHINFO_FILENAME)) . '.' . $ext;
            $destPath = $assetsImgDir . $safeName;

            if (move_uploaded_file($tmpName, $destPath)) {
                return ['path' => 'assets/images/' . $safeName];
            } else {
                return ['error' => 'Failed to save uploaded image. Check folder permissions.'];
            }
        }

        // Check if selected existing image from dropdown
        $selectedExisting = trim($_POST['existing_image'] ?? '');
        if (!empty($selectedExisting)) {
            return ['path' => $selectedExisting];
        }

        // Return current image or default
        return ['path' => !empty($currentImage) ? $currentImage : 'assets/images/project_civil_infra.jpg'];
    };

    // 1. CREATE ACTION
    if ($action === 'create') {
        $category = trim($_POST['category'] ?? 'civil');
        $enTitle = trim($_POST['en_title'] ?? '');
        $enCatLabel = trim($_POST['en_cat_label'] ?? '');
        $enDesc = trim($_POST['en_desc'] ?? '');
        $arTitle = trim($_POST['ar_title'] ?? '');
        $arCatLabel = trim($_POST['ar_cat_label'] ?? '');
        $arDesc = trim($_POST['ar_desc'] ?? '');

        if (empty($enTitle)) {
            $_SESSION['flash_message'] = 'English title is required.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php');
            exit;
        }

        // If category label empty, fallback to category map
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

        // Find max ID
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
                'title' => $arTitle ?: $enTitle,
                'category_label' => $arCatLabel ?: ($categories[$category]['ar'] ?? ucfirst($category)),
                'desc' => $arDesc ?: $enDesc
            ]
        ];

        // Add to projects list
        $projects[] = $newProject;
        saveProjects($projects);

        $_SESSION['flash_message'] = 'New project "' . htmlspecialchars($enTitle) . '" created successfully!';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    }

    // 2. UPDATE ACTION
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

        if (empty($enTitle)) {
            $_SESSION['flash_message'] = 'English title is required.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php');
            exit;
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

        $projects[$foundIndex]['ar']['title'] = $arTitle ?: $enTitle;
        $projects[$foundIndex]['ar']['category_label'] = $arCatLabel ?: ($categories[$category]['ar'] ?? $projects[$foundIndex]['en']['category_label']);
        $projects[$foundIndex]['ar']['desc'] = $arDesc ?: $enDesc;

        saveProjects($projects);

        $_SESSION['flash_message'] = 'Project #' . $id . ' updated successfully!';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    }

    // 3. DELETE ACTION
    elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $newProjects = [];
        $deletedTitle = '';

        foreach ($projects as $p) {
            if (isset($p['id']) && (int)$p['id'] === $id) {
                $deletedTitle = $p['en']['title'] ?? ('Project #' . $id);
                continue;
            }
            $newProjects[] = $p;
        }

        saveProjects($newProjects);
        $_SESSION['flash_message'] = 'Project "' . htmlspecialchars($deletedTitle) . '" has been removed.';
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    }

    // 4. REORDER (MOVE UP / DOWN)
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
    <title>Projects Dashboard - FESCON Oman Administration</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
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
        }

        .search-box {
            position: relative;
            width: 100%;
            max-width: 380px;
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

        .btn-add-project {
            background: linear-gradient(135deg, var(--accent-gold) 0%, #aa853c 100%);
            color: #08121f;
            border: none;
            padding: 0.7rem 1.4rem;
            border-radius: 8px;
            font-size: 0.92rem;
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
            font-size: 0.88rem;
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

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(6px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
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
            max-width: 780px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            transform: scale(0.95);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .modal-overlay.active .modal-card {
            transform: scale(1);
        }

        .modal-header {
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(11, 25, 44, 0.6);
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
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.25rem;
            line-height: 1;
            transition: color 0.2s;
        }

        .btn-close-modal:hover {
            color: #ffffff;
        }

        .modal-body {
            padding: 1.75rem;
            overflow-y: auto;
            flex: 1;
        }

        .modal-footer {
            padding: 1.25rem 1.75rem;
            border-top: 1px solid var(--border-color);
            background: rgba(11, 25, 44, 0.6);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
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
            min-height: 80px;
        }

        .rtl-input {
            direction: rtl;
            font-family: 'Tajawal', sans-serif;
            text-align: right;
        }

        .img-preview-box {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.5rem;
            background: rgba(0, 0, 0, 0.2);
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px dashed var(--border-color);
        }

        .img-preview-box img {
            width: 70px;
            height: 50px;
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
                <p>Project Portfolio Management</p>
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

        <!-- Flash Toast Notification -->
        <?php if (!empty($message)): ?>
            <div class="alert-toast <?php echo $messageType; ?>" id="flashToast">
                <span>
                    <i class="fas <?php echo ($messageType === 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>" style="margin-right: 8px;"></i>
                    <?php echo htmlspecialchars($message); ?>
                </span>
                <button type="button" onclick="document.getElementById('flashToast').remove();" style="background: none; border: none; color: inherit; cursor: pointer; font-size: 1rem;">&times;</button>
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
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Grid & Distribution</div>
                    <div class="stat-val"><?php echo ($categoryCounts['grid'] ?? 0) + ($categoryCounts['dist'] ?? 0); ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(234, 88, 12, 0.15); color: #fb923c;">
                    <i class="fas fa-road"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Civil & Lighting</div>
                    <div class="stat-val"><?php echo ($categoryCounts['civil'] ?? 0) + ($categoryCounts['lighting'] ?? 0); ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">MEP, EPC & Oil/Gas</div>
                    <div class="stat-val"><?php echo ($categoryCounts['mep'] ?? 0) + ($categoryCounts['epc'] ?? 0) + ($categoryCounts['oilgas'] ?? 0) + ($categoryCounts['om'] ?? 0); ?></div>
                </div>
            </div>
        </div>

        <!-- Controls Toolbar -->
        <div class="toolbar">
            <div class="toolbar-left">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="projectSearchInput" placeholder="Search by title, category, description...">
                </div>

                <div class="filter-pills">
                    <button class="pill-btn active" data-filter="all">All (<?php echo $totalProjects; ?>)</button>
                    <?php foreach ($categories as $catKey => $catInfo): ?>
                        <button class="pill-btn" data-filter="<?php echo $catKey; ?>">
                            <?php echo htmlspecialchars($catInfo['en']); ?> (<?php echo $categoryCounts[$catKey] ?? 0; ?>)
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="button" class="btn-add-project" id="openAddModalBtn">
                <i class="fas fa-plus-circle"></i>
                <span>Add New Project</span>
            </button>
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
                            $projId = $proj['id'] ?? ($index + 1);
                        ?>
                            <tr class="project-row" data-category="<?php echo htmlspecialchars($catKey); ?>" data-search="<?php echo htmlspecialchars(strtolower(($en['title'] ?? '') . ' ' . ($ar['title'] ?? '') . ' ' . ($catBadge['en'] ?? '') . ' ' . ($en['desc'] ?? ''))); ?>">
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
                                    <span class="cat-badge" style="background: <?php echo $catBadge['color']; ?>22; color: <?php echo $catBadge['color']; ?>; border: 1px solid <?php echo $catBadge['color']; ?>55;">
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
                                    <div class="desc-preview" title="<?php echo htmlspecialchars($en['desc'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($en['desc'] ?? 'No description provided.'); ?>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td style="text-align: right;">
                                    <div class="action-btns" style="justify-content: flex-end;">
                                        <button type="button" class="btn-action btn-edit" onclick='openEditModal(<?php echo json_encode($proj, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" class="btn-action btn-delete" onclick="openDeleteModal(<?php echo $projId; ?>, '<?php echo htmlspecialchars(addslashes($en['title'] ?? 'Project #' . $projId)); ?>')">
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

    <!-- ADD PROJECT MODAL -->
    <div class="modal-overlay" id="addProjectModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> Add New Showcase Project</h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('addProjectModal')">&times;</button>
            </div>
            <form method="POST" action="index.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                <input type="hidden" name="action" value="create">

                <div class="modal-body">
                    <div class="form-grid">
                        <!-- Category Selection -->
                        <div class="modal-form-group form-full">
                            <label class="modal-form-label">Category</label>
                            <select name="category" class="modal-form-control" required id="addCategorySelect" onchange="autoFillCategoryLabels('add')">
                                <?php foreach ($categories as $catKey => $catInfo): ?>
                                    <option value="<?php echo $catKey; ?>"><?php echo htmlspecialchars($catInfo['en']); ?> (<?php echo htmlspecialchars($catInfo['ar']); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- English Section -->
                        <div class="form-full">
                            <div class="form-section-title"><i class="fas fa-globe"></i> English Content</div>
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Project Title (English) *</label>
                            <input type="text" name="en_title" class="modal-form-control" placeholder="e.g. 132kV Substation Transmission..." required>
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Category Label (English)</label>
                            <input type="text" name="en_cat_label" id="add_en_cat_label" class="modal-form-control" placeholder="Grid Stations">
                        </div>

                        <div class="modal-form-group form-full">
                            <label class="modal-form-label">Description (English)</label>
                            <textarea name="en_desc" class="modal-form-control" placeholder="Detailed engineering summary, specs, equipment installed..."></textarea>
                        </div>

                        <!-- Arabic Section -->
                        <div class="form-full">
                            <div class="form-section-title"><i class="fas fa-language"></i> Arabic Content (المحتوى العربي)</div>
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">عنوان المشروع (بالعربية)</label>
                            <input type="text" name="ar_title" class="modal-form-control rtl-input" placeholder="اسم المشروع بالعربية">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">تصنيف المشروع (بالعربية)</label>
                            <input type="text" name="ar_cat_label" id="add_ar_cat_label" class="modal-form-control rtl-input" placeholder="محطات المحولات">
                        </div>

                        <div class="modal-form-group form-full">
                            <label class="modal-form-label">تفاصيل ووصف المشروع (بالعربية)</label>
                            <textarea name="ar_desc" class="modal-form-control rtl-input" placeholder="تفاصيل الأعمال الهندسية والتنفيذية..."></textarea>
                        </div>

                        <!-- Image Section -->
                        <div class="form-full">
                            <div class="form-section-title"><i class="fas fa-image"></i> Project Image</div>
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Upload New Photo (JPG, PNG, WEBP)</label>
                            <input type="file" name="image_file" class="modal-form-control" accept="image/jpeg,image/png,image/webp">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">OR Pick From Existing Assets</label>
                            <select name="existing_image" class="modal-form-control">
                                <option value="">-- Choose Existing Image --</option>
                                <?php foreach ($existingImages as $imgAsset): ?>
                                    <option value="<?php echo htmlspecialchars($imgAsset); ?>"><?php echo htmlspecialchars(basename($imgAsset)); ?></option>
                                <?php endforeach; ?>
                            </select>
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

    <!-- EDIT PROJECT MODAL -->
    <div class="modal-overlay" id="editProjectModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Showcase Project</h3>
                <button type="button" class="btn-close-modal" onclick="closeModal('editProjectModal')">&times;</button>
            </div>
            <form method="POST" action="index.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id" value="">

                <div class="modal-body">
                    <div class="form-grid">
                        <!-- Category Selection -->
                        <div class="modal-form-group form-full">
                            <label class="modal-form-label">Category</label>
                            <select name="category" class="modal-form-control" required id="edit_category">
                                <?php foreach ($categories as $catKey => $catInfo): ?>
                                    <option value="<?php echo $catKey; ?>"><?php echo htmlspecialchars($catInfo['en']); ?> (<?php echo htmlspecialchars($catInfo['ar']); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- English Section -->
                        <div class="form-full">
                            <div class="form-section-title"><i class="fas fa-globe"></i> English Content</div>
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Project Title (English) *</label>
                            <input type="text" name="en_title" id="edit_en_title" class="modal-form-control" required>
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Category Label (English)</label>
                            <input type="text" name="en_cat_label" id="edit_en_cat_label" class="modal-form-control">
                        </div>

                        <div class="modal-form-group form-full">
                            <label class="modal-form-label">Description (English)</label>
                            <textarea name="en_desc" id="edit_en_desc" class="modal-form-control"></textarea>
                        </div>

                        <!-- Arabic Section -->
                        <div class="form-full">
                            <div class="form-section-title"><i class="fas fa-language"></i> Arabic Content (المحتوى العربي)</div>
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">عنوان المشروع (بالعربية)</label>
                            <input type="text" name="ar_title" id="edit_ar_title" class="modal-form-control rtl-input">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">تصنيف المشروع (بالعربية)</label>
                            <input type="text" name="ar_cat_label" id="edit_ar_cat_label" class="modal-form-control rtl-input">
                        </div>

                        <div class="modal-form-group form-full">
                            <label class="modal-form-label">تفاصيل ووصف المشروع (بالعربية)</label>
                            <textarea name="ar_desc" id="edit_ar_desc" class="modal-form-control rtl-input"></textarea>
                        </div>

                        <!-- Image Section -->
                        <div class="form-full">
                            <div class="form-section-title"><i class="fas fa-image"></i> Project Image</div>
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">Replace with New Photo (JPG, PNG, WEBP)</label>
                            <input type="file" name="image_file" class="modal-form-control" accept="image/jpeg,image/png,image/webp">
                        </div>

                        <div class="modal-form-group">
                            <label class="modal-form-label">OR Choose Another Asset</label>
                            <select name="existing_image" id="edit_existing_image" class="modal-form-control">
                                <option value="">-- Keep Current Image --</option>
                                <?php foreach ($existingImages as $imgAsset): ?>
                                    <option value="<?php echo htmlspecialchars($imgAsset); ?>"><?php echo htmlspecialchars(basename($imgAsset)); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-full">
                            <label class="modal-form-label">Current Image:</label>
                            <div class="img-preview-box">
                                <img id="edit_preview_img" src="../assets/images/project_civil_infra.jpg" alt="Current Image">
                                <div class="img-preview-info">
                                    <strong id="edit_preview_name">assets/images/project_civil_infra.jpg</strong><br>
                                    <span>To keep this current photo unchanged, leave the upload & dropdown fields empty.</span>
                                </div>
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

    <!-- DELETE CONFIRMATION MODAL -->
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
                    <p id="delete_proj_title" style="color: var(--accent-gold); font-size: 0.95rem; font-weight: 700; margin-bottom: 1rem;"></p>
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
        const categoriesMap = <?php echo json_encode($categories); ?>;

        // Open & Close Modals
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        // Add modal trigger
        document.getElementById('openAddModalBtn').addEventListener('click', () => {
            autoFillCategoryLabels('add');
            openModal('addProjectModal');
        });

        // Autofill labels based on category
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

        // Edit Modal Setup
        function openEditModal(project) {
            document.getElementById('edit_id').value = project.id;
            document.getElementById('edit_category').value = project.category || 'civil';
            
            const en = project.en || {};
            const ar = project.ar || {};

            document.getElementById('edit_en_title').value = en.title || '';
            document.getElementById('edit_en_cat_label').value = en.category_label || (categoriesMap[project.category] ? categoriesMap[project.category].en : '');
            document.getElementById('edit_en_desc').value = en.desc || '';

            document.getElementById('edit_ar_title').value = ar.title || '';
            document.getElementById('edit_ar_cat_label').value = ar.category_label || (categoriesMap[project.category] ? categoriesMap[project.category].ar : '');
            document.getElementById('edit_ar_desc').value = ar.desc || '';

            const imgPath = project.image ? ('../' + project.image) : '../assets/images/project_civil_infra.jpg';
            document.getElementById('edit_preview_img').src = imgPath;
            document.getElementById('edit_preview_name').textContent = project.image || 'assets/images/project_civil_infra.jpg';

            openModal('editProjectModal');
        }

        // Delete Modal Setup
        function openDeleteModal(id, title) {
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
