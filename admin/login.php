<?php
require_once __DIR__ . '/auth.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$info = '';

if (isset($_GET['logged_out'])) {
    $info = 'You have been successfully logged out.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($token)) {
        $error = 'Security session expired. Please refresh and try again.';
    } elseif (isAdminLoginRateLimited()) {
        $error = 'Too many unsuccessful attempts. Please try again in 15 minutes.';
    } elseif (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } elseif (verifyAdminCredentials($username, $password)) {
        // Successful login
        clearAdminLoginFailures();
        session_regenerate_id(true);
        $_SESSION['fescon_admin_logged_in'] = true;
        $_SESSION['fescon_admin_user'] = $username;
        $_SESSION['fescon_admin_login_time'] = time();
        $_SESSION['fescon_admin_last_activity'] = time();
        header('Location: index.php');
        exit;
    } else {
        recordAdminLoginFailure();
        $error = 'Invalid username or password. Please try again.';
    }
}

$csrfToken = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login - FESCON Oman</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #0a192f;
            --primary-light: #172a46;
            --accent-gold: #c5a059;
            --accent-gold-hover: #b08d46;
            --accent-red: #d32f2f;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-glass: rgba(255, 255, 255, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            background: radial-gradient(circle at 10% 20%, #0d233e 0%, #06101e 90%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: var(--text-main);
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(197, 160, 89, 0.12) 0%, transparent 70%);
            top: 10%;
            right: 15%;
            border-radius: 50%;
            pointer-events: none;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(15, 33, 58, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(197, 160, 89, 0.25);
            border-radius: 18px;
            padding: 2.75rem 2.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 20px rgba(197, 160, 89, 0.08);
            position: relative;
            z-index: 10;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .brand-logo-wrap img {
            max-height: 48px;
            width: auto;
            display: block;
        }

        .brand-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
        }

        .brand-title span {
            color: var(--accent-gold);
        }

        .brand-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .alert {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: rgba(220, 38, 38, 0.15);
            border: 1px solid rgba(220, 38, 38, 0.4);
            color: #fca5a5;
        }

        .alert-info {
            background: rgba(14, 165, 233, 0.15);
            border: 1px solid rgba(14, 165, 233, 0.4);
            color: #7dd3fc;
        }

        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #e2e8f0;
            letter-spacing: 0.3px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: var(--text-muted);
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.65rem;
            background: rgba(8, 19, 36, 0.65);
            border: 1px solid var(--border-glass);
            border-radius: 10px;
            color: #ffffff;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.25s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-gold);
            background: rgba(8, 19, 36, 0.9);
            box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.2);
        }

        .form-control:focus + .input-icon {
            color: var(--accent-gold);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.95rem;
            padding: 0.25rem;
        }

        .toggle-password:hover {
            color: #ffffff;
        }

        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, var(--accent-gold) 0%, #aa853c 100%);
            border: none;
            border-radius: 10px;
            color: #08121f;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(197, 160, 89, 0.3);
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(197, 160, 89, 0.45);
            background: linear-gradient(135deg, #d4af37 0%, #ba9343 100%);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            color: var(--text-muted);
            font-size: 0.86rem;
            text-decoration: none;
            transition: color 0.2s;
            justify-content: center;
            width: 100%;
        }

        .back-link:hover {
            color: var(--accent-gold);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-header">
            <div class="brand-logo-wrap">
                <img src="../assets/images/logo_english.jpg" alt="FESCON" onerror="this.style.display='none'">
            </div>
            <h1 class="brand-title">FESCON <span>PORTAL</span></h1>
            <p class="brand-subtitle">Project Management Administration</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($info)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <span><?php echo htmlspecialchars($info); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <div class="input-group">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter admin username" required autofocus value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" id="togglePassBtn" aria-label="Toggle Password Visibility">
                        <i class="fas fa-eye" id="togglePassIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt"></i>
                <span>Sign In to Dashboard</span>
            </button>
        </form>

        <a href="../" class="back-link">
            <i class="fas fa-arrow-left"></i>
            <span>Return to Fescon Public Website</span>
        </a>
    </div>

    <script>
        const togglePassBtn = document.getElementById('togglePassBtn');
        const passwordInput = document.getElementById('password');
        const togglePassIcon = document.getElementById('togglePassIcon');

        if (togglePassBtn && passwordInput && togglePassIcon) {
            togglePassBtn.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                togglePassIcon.classList.toggle('fa-eye');
                togglePassIcon.classList.toggle('fa-eye-slash');
            });
        }
    </script>
</body>
</html>
