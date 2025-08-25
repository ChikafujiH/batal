<?php
require_once 'config.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);
    
    if (empty($username) || empty($password)) {
        $error_message = 'Username dan password harus diisi';
    } else {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Get user by username or email
            $stmt = $db->prepare("SELECT id, username, email, password, full_name, role, is_active FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                
                // Create session token for security
                $session_token = generateSecureToken();
                $_SESSION['session_token'] = $session_token;
                
                // Save session to database
                $expires_at = date('Y-m-d H:i:s', time() + SESSION_TIMEOUT);
                $stmt = $db->prepare("INSERT INTO user_sessions (user_id, session_token, expires_at, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $user['id'], 
                    $session_token, 
                    $expires_at,
                    $_SERVER['REMOTE_ADDR'],
                    $_SERVER['HTTP_USER_AGENT']
                ]);
                
                // Set remember me cookie
                if ($remember_me) {
                    setcookie('remember_token', $session_token, time() + (30 * 24 * 3600), '/', '', false, true);
                }
                
                // Redirect to homepage
                header('Location: index.php');
                exit();
            } else {
                $error_message = 'Username atau password salah';
            }
        } catch (PDOException $e) {
            $error_message = 'Terjadi kesalahan sistem. Silakan coba lagi.';
            error_log('Login error: ' . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f3460 0%, #16537e 50%, #1e5f8b 100%);
            color: white;
            height: 100vh;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Animated background particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }

        .particle:nth-child(1) { width: 3px; height: 3px; top: 20%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 5px; height: 5px; top: 60%; left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 2px; height: 2px; top: 40%; left: 80%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 4px; height: 4px; top: 80%; left: 60%; animation-delay: 1s; }
        .particle:nth-child(5) { width: 3px; height: 3px; top: 10%; left: 70%; animation-delay: 3s; }
        .particle:nth-child(6) { width: 2px; height: 2px; top: 70%; left: 30%; animation-delay: 5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            25% { transform: translateY(-20px) rotate(90deg); opacity: 0.8; }
            50% { transform: translateY(-40px) rotate(180deg); opacity: 1; }
            75% { transform: translateY(-20px) rotate(270deg); opacity: 0.8; }
        }

        /* Header */
        .header {
            position: absolute;
            top: 30px;
            left: 40px;
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 100;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            filter: drop-shadow(0 0 10px rgba(0, 212, 255, 0.3));
            transition: all 0.3s ease;
        }

        .logo-img:hover {
            filter: drop-shadow(0 0 15px rgba(0, 212, 255, 0.5));
            transform: scale(1.05);
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        /* Login Container */
        .login-container {
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 50px 40px;
            width: 420px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
            transform: perspective(1000px) rotateX(2deg);
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, 
                rgba(0, 212, 255, 0.2) 0%, 
                rgba(255, 255, 255, 0.1) 25%,
                rgba(0, 212, 255, 0.2) 50%,
                rgba(255, 255, 255, 0.1) 75%,
                rgba(0, 212, 255, 0.2) 100%
            );
            border-radius: 22px;
            z-index: -1;
            animation: borderGlow 3s ease-in-out infinite;
        }

        @keyframes borderGlow {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.8; }
        }

        .login-title {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #00d4ff, #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 40px;
            font-size: 14px;
        }

        /* Real-time validation styles */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .validation-message {
            position: absolute;
            bottom: -25px;
            left: 0;
            right: 0;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 6px;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .validation-message.show {
            opacity: 1;
            transform: translateY(0);
        }

        .validation-message.success {
            background: rgba(0, 255, 127, 0.1);
            border: 1px solid rgba(0, 255, 127, 0.3);
            color: #00ff7f;
        }

        .validation-message.error {
            background: rgba(255, 69, 0, 0.1);
            border: 1px solid rgba(255, 69, 0, 0.3);
            color: #ff4500;
        }

        .validation-message.warning {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: #ffc107;
        }

        .form-input.valid {
            border-color: #00ff7f;
            box-shadow: 0 0 15px rgba(0, 255, 127, 0.3);
        }

        .form-input.invalid {
            border-color: #ff4500;
            box-shadow: 0 0 15px rgba(255, 69, 0, 0.3);
        }

        .form-input.checking {
            border-color: #ffc107;
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.3);
        }

        /* Loading indicator for input */
        .input-loader {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #00d4ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .input-loader.show {
            opacity: 1;
        }

        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }

        /* Login button states */
        .login-btn.ready {
            background: linear-gradient(45deg, #00ff7f 0%, #00cc66 100%);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 0 20px rgba(0, 255, 127, 0.4); }
            50% { box-shadow: 0 0 30px rgba(0, 255, 127, 0.6); }
        }

        .login-btn:disabled {
            background: rgba(128, 128, 128, 0.5);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-input:focus {
            outline: none;
            border-color: #00d4ff;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
            background: rgba(255, 255, 255, 0.15);
        }

        /* Remember Me */
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            user-select: none;
        }

        .checkbox {
            width: 18px;
            height: 18px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            position: relative;
            transition: all 0.3s ease;
        }

        .checkbox.checked {
            background: linear-gradient(45deg, #00d4ff, #0099cc);
            border-color: #00d4ff;
        }

        .checkbox.checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .forgot-link {
            color: #00d4ff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .forgot-link:hover {
            color: #ffffff;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(45deg, #00d4ff 0%, #0099cc 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.4), 
                transparent
            );
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 212, 255, 0.4);
        }

        .login-btn:active {
            transform: translateY(0px);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 25px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .register-link a {
            color: #00d4ff;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: white;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        /* Success/Error Messages */
        .message {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .message.success {
            background: rgba(0, 255, 127, 0.1);
            border: 1px solid rgba(0, 255, 127, 0.3);
            color: #00ff7f;
        }

        .message.error {
            background: rgba(255, 69, 0, 0.1);
            border: 1px solid rgba(255, 69, 0, 0.3);
            color: #ff4500;
        }

        /* Demo credentials box */
        .demo-credentials {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.8);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
        }

        .demo-credentials h4 {
            color: #00d4ff;
            margin-bottom: 5px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                width: 90%;
                margin: 20px;
                padding: 30px 25px;
            }

            .header {
                left: 20px;
                top: 20px;
            }

            .logo-text {
                font-size: 20px;
            }

            .login-title {
                font-size: 28px;
            }

            .demo-credentials {
                position: relative;
                bottom: auto;
                right: auto;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated background particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Header -->
    <div class="header">
        <img src="images/kotak.png" alt="Logo" class="logo-img">
        <div class="logo-text"><?= APP_NAME ?></div>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <h1 class="login-title">Selamat Datang</h1>
        <p class="login-subtitle">Silakan login ke akun <?= APP_NAME ?> Anda</p>

        <?php if ($error_message): ?>
            <div class="message error"><?= $error_message ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="message success"><?= $success_message ?></div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
            <div class="form-group">
                <label class="form-label" for="username">Username atau Email</label>
                <div style="position: relative;">
                    <input 
                        type="text" 
                        id="username" 
                        name="username"
                        class="form-input" 
                        placeholder="Masukkan username atau email"
                        value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                        required
                        autocomplete="username"
                    >
                    <div class="input-loader" id="usernameLoader"></div>
                </div>
                <div class="validation-message" id="usernameValidation"></div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div style="position: relative;">
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        class="form-input" 
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password"
                    >
                    <div class="input-loader" id="passwordLoader"></div>
                </div>
                <div class="validation-message" id="passwordValidation"></div>
            </div>

            <div class="remember-forgot">
                <label class="remember-me">
                    <input type="checkbox" name="remember_me" style="display: none;">
                    <div class="checkbox" onclick="toggleCheckbox(this)"></div>
                    <span>Ingat saya</span>
                </label>
                <a href="#" class="forgot-link">Lupa password?</a>
            </div>

            <button type="submit" class="login-btn" id="loginBtn" disabled>
                Login
            </button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </div>
    </div>

    <!-- Demo credentials -->
    <div class="demo-credentials">
        <h4>Demo Login:</h4>
        <div>Admin: admin / admin123</div>
        <div>User: user1 / user123</div>
    </div>

    <script>
        // Real-time validation variables
        let usernameValid = false;
        let passwordValid = false;
        let validationTimeout = null;
        let currentUserId = null;

        // Elements
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const loginBtn = document.getElementById('loginBtn');
        const usernameValidation = document.getElementById('usernameValidation');
        const passwordValidation = document.getElementById('passwordValidation');
        const usernameLoader = document.getElementById('usernameLoader');
        const passwordLoader = document.getElementById('passwordLoader');

        function toggleCheckbox(element) {
            const checkbox = element.previousElementSibling;
            const isChecked = element.classList.contains('checked');
            
            if (isChecked) {
                element.classList.remove('checked');
                checkbox.checked = false;
            } else {
                element.classList.add('checked');
                checkbox.checked = true;
            }
        }

        function showValidationMessage(element, message, type) {
            element.textContent = message;
            element.className = `validation-message ${type}`;
            if (message) {
                element.classList.add('show');
            } else {
                element.classList.remove('show');
            }
        }

        function setInputState(input, state) {
            input.classList.remove('valid', 'invalid', 'checking');
            if (state) {
                input.classList.add(state);
            }
        }

        function showLoader(loader, show) {
            if (show) {
                loader.classList.add('show');
            } else {
                loader.classList.remove('show');
            }
        }

        function updateLoginButton() {
            if (usernameValid && passwordValid) {
                loginBtn.disabled = false;
                loginBtn.classList.add('ready');
                loginBtn.textContent = 'LOGIN SIAP!';
            } else {
                loginBtn.disabled = true;
                loginBtn.classList.remove('ready');
                loginBtn.textContent = 'LOGIN';
            }
        }

        async function validateUsername(username) {
            if (!username || username.length < 3) {
                usernameValid = false;
                if (username.length > 0 && username.length < 3) {
                    showValidationMessage(usernameValidation, 'Username minimal 3 karakter', 'error');
                    setInputState(usernameInput, 'invalid');
                } else {
                    showValidationMessage(usernameValidation, '', '');
                    setInputState(usernameInput, '');
                }
                updateLoginButton();
                return;
            }

            showLoader(usernameLoader, true);
            setInputState(usernameInput, 'checking');

            try {
                const formData = new FormData();
                formData.append('action', 'check_username');
                formData.append('username', username);

                const response = await fetch('check_user.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    usernameValid = true;
                    currentUserId = data.user_id;
                    showValidationMessage(usernameValidation, '✓ ' + data.message, 'success');
                    setInputState(usernameInput, 'valid');
                } else {
                    usernameValid = false;
                    currentUserId = null;
                    showValidationMessage(usernameValidation, '✕ ' + data.message, 'error');
                    setInputState(usernameInput, 'invalid');
                }
            } catch (error) {
                usernameValid = false;
                showValidationMessage(usernameValidation, '✕ Terjadi kesalahan koneksi', 'error');
                setInputState(usernameInput, 'invalid');
            }

            showLoader(usernameLoader, false);
            updateLoginButton();
        }

        async function validateLogin(username, password) {
            if (!username || !password || !usernameValid) {
                passwordValid = false;
                updateLoginButton();
                return;
            }

            if (password.length < 3) {
                passwordValid = false;
                showValidationMessage(passwordValidation, 'Password minimal 3 karakter', 'error');
                setInputState(passwordInput, 'invalid');
                updateLoginButton();
                return;
            }

            showLoader(passwordLoader, true);
            setInputState(passwordInput, 'checking');

            try {
                const formData = new FormData();
                formData.append('action', 'validate_login');
                formData.append('username', username);
                formData.append('password', password);

                const response = await fetch('check_user.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    passwordValid = true;
                    showValidationMessage(passwordValidation, '✓ ' + data.message, 'success');
                    setInputState(passwordInput, 'valid');
                } else {
                    passwordValid = false;
                    showValidationMessage(passwordValidation, '✕ ' + data.message, 'error');
                    setInputState(passwordInput, 'invalid');
                }
            } catch (error) {
                passwordValid = false;
                showValidationMessage(passwordValidation, '✕ Terjadi kesalahan koneksi', 'error');
                setInputState(passwordInput, 'invalid');
            }

            showLoader(passwordLoader, false);
            updateLoginButton();
        }

        // Event listeners
        usernameInput.addEventListener('input', function() {
            const username = this.value.trim();
            
            // Clear previous timeout
            if (validationTimeout) {
                clearTimeout(validationTimeout);
            }
            
            // Reset password validation when username changes
            passwordValid = false;
            showValidationMessage(passwordValidation, '', '');
            setInputState(passwordInput, '');
            
            // Validate username after 500ms delay
            validationTimeout = setTimeout(() => {
                validateUsername(username);
            }, 500);
        });

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const username = usernameInput.value.trim();
            
            // Clear previous timeout
            if (validationTimeout) {
                clearTimeout(validationTimeout);
            }
            
            // Validate complete login after 800ms delay
            validationTimeout = setTimeout(() => {
                validateLogin(username, password);
            }, 800);
        });

        // Form submission with additional validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (!usernameValid || !passwordValid) {
                e.preventDefault();
                
                if (!usernameValid) {
                    showValidationMessage(usernameValidation, '✕ Username harus valid terlebih dahulu', 'error');
                }
                if (!passwordValid) {
                    showValidationMessage(passwordValidation, '✕ Password tidak sesuai', 'error');
                }
                
                return false;
            }
            
            // Show loading state
            loginBtn.textContent = 'SEDANG MASUK...';
            loginBtn.disabled = true;
        });

        // Auto-focus and styling
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = '#00d4ff';
                this.style.boxShadow = '0 0 20px rgba(0, 212, 255, 0.3)';
            });
            
            input.addEventListener('blur', function() {
                if (!this.classList.contains('valid') && !this.classList.contains('invalid') && !this.classList.contains('checking')) {
                    this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                    this.style.boxShadow = 'none';
                }
            });
        });

        console.log('<?= APP_NAME ?> Login with Real-time Validation loaded');
        console.log('Demo credentials: admin/admin123 or user1/user123');
    </script>
</body>
</html>