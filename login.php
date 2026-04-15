<?php
/**
 * MobileHub — Login / Register Page
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logoutUser();
}

// Redirect if already logged in
if (isLoggedIn() && !isset($_GET['action'])) {
    header('Location: ' . SITE_URL . '/');
    exit;
}

$redirect = $_GET['redirect'] ?? '';
$error = '';
$success = '';
$activeTab = $_POST['form_type'] ?? 'login';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type'])) {
    if ($_POST['form_type'] === 'login') {
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if ($email && $password) {
            $result = loginUser($email, $password);
            if ($result['success']) {
                $redir = $redirect ?: ($result['role'] === 'admin' ? '/admin/' : '/');
                header('Location: ' . SITE_URL . $redir);
                exit;
            } else {
                $error = $result['message'];
            }
        } else {
            $error = 'Please fill in all fields';
        }
    } elseif ($_POST['form_type'] === 'register') {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($name && $email && $phone && $password) {
            if ($password !== $confirmPassword) {
                $error = 'Passwords do not match';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters';
            } else {
                $result = registerUser($name, $email, $phone, $password);
                if ($result['success']) {
                    header('Location: ' . SITE_URL . ($redirect ?: '/'));
                    exit;
                } else {
                    $error = $result['message'];
                }
            }
        } else {
            $error = 'Please fill in all required fields';
        }
        $activeTab = 'register';
    }
}

$pageTitle = 'Login';
require_once __DIR__ . '/includes/header.php';
?>

  <div class="section-padding" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
          <div class="text-center mb-4 reveal">
            <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 800;">
              Welcome to <span style="color: var(--primary);">MobileHub</span>
            </h1>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Sign in to your account or create a new one</p>
          </div>

          <div class="form-glass reveal">
            <?php if ($error): ?>
              <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Auth Tabs -->
            <div class="auth-tabs" id="authTabs">
              <button class="auth-tab <?php echo $activeTab === 'login' ? 'active' : ''; ?>" onclick="switchTab('login')">Login</button>
              <button class="auth-tab <?php echo $activeTab === 'register' ? 'active' : ''; ?>" onclick="switchTab('register')">Register</button>
            </div>

            <!-- Login Form -->
            <form method="POST" id="loginForm" style="display: <?php echo $activeTab === 'login' ? 'block' : 'none'; ?>;">
              <input type="hidden" name="form_type" value="login">
              <?php if ($redirect): ?><input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>"><?php endif; ?>
              
              <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email" placeholder="you@example.com" required>
              </div>
              <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
              </div>
              <button type="submit" class="btn-gradient w-100 justify-content-center">
                <i class="bi bi-box-arrow-in-right"></i> Sign In
              </button>
            </form>

            <!-- Register Form -->
            <form method="POST" id="registerForm" style="display: <?php echo $activeTab === 'register' ? 'block' : 'none'; ?>;">
              <input type="hidden" name="form_type" value="register">
              <?php if ($redirect): ?><input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>"><?php endif; ?>
              
              <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" placeholder="John Doe" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email" placeholder="you@example.com" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-control" name="phone" placeholder="+91 XXXXX XXXXX" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Minimum 6 characters" minlength="6" required>
              </div>
              <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" placeholder="Re-enter password" required>
              </div>
              <button type="submit" class="btn-gradient w-100 justify-content-center">
                <i class="bi bi-person-plus"></i> Create Account
              </button>
            </form>

            <div class="text-center mt-4">
              <p style="font-size: 0.82rem; color: var(--text-muted);">
                Admin? <strong>admin@mobilehub.com</strong> / <strong>admin123</strong>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function switchTab(tab) {
      document.getElementById('loginForm').style.display = tab === 'login' ? 'block' : 'none';
      document.getElementById('registerForm').style.display = tab === 'register' ? 'block' : 'none';
      document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
      if (tab === 'login') document.querySelectorAll('.auth-tab')[0].classList.add('active');
      else document.querySelectorAll('.auth-tab')[1].classList.add('active');
    }
  </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
