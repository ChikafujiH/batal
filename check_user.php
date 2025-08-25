<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => ''];

try {
    $db = Database::getInstance()->getConnection();
    
    switch ($action) {
        case 'check_username':
            $username = sanitizeInput($_POST['username'] ?? '');
            
            if (empty($username)) {
                $response = ['success' => false, 'message' => ''];
                break;
            }
            
            if (strlen($username) < 3) {
                $response = ['success' => false, 'message' => 'Username minimal 3 karakter'];
                break;
            }
            
            // Check if username/email exists
            $stmt = $db->prepare("SELECT id, username, email, is_active FROM users WHERE (username = ? OR email = ?) LIMIT 1");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $response = ['success' => false, 'message' => 'Username atau email tidak ditemukan'];
            } elseif (!$user['is_active']) {
                $response = ['success' => false, 'message' => 'Akun tidak aktif. Hubungi administrator'];
            } else {
                $response = ['success' => true, 'message' => 'Username ditemukan', 'user_id' => $user['id']];
            }
            break;
            
        case 'validate_login':
            $username = sanitizeInput($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $response = ['success' => false, 'message' => 'Username dan password harus diisi'];
                break;
            }
            
            if (strlen($password) < 3) {
                $response = ['success' => false, 'message' => 'Password minimal 3 karakter'];
                break;
            }
            
            // Get user data
            $stmt = $db->prepare("SELECT id, username, email, password, full_name, role, is_active FROM users WHERE (username = ? OR email = ?) AND is_active = 1 LIMIT 1");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $response = ['success' => false, 'message' => 'Username atau email tidak ditemukan'];
            } elseif (!password_verify($password, $user['password'])) {
                $response = ['success' => false, 'message' => 'Password salah'];
            } else {
                $response = [
                    'success' => true, 
                    'message' => 'Login valid! Silakan klik tombol Login',
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'full_name' => $user['full_name'],
                        'role' => $user['role']
                    ]
                ];
            }
            break;
            
        case 'check_email':
            $email = sanitizeInput($_POST['email'] ?? '');
            
            if (empty($email)) {
                $response = ['success' => false, 'message' => ''];
                break;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response = ['success' => false, 'message' => 'Format email tidak valid'];
                break;
            }
            
            // Check if email exists
            $stmt = $db->prepare("SELECT id, email, is_active FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $response = ['success' => false, 'message' => 'Email tidak terdaftar'];
            } elseif (!$user['is_active']) {
                $response = ['success' => false, 'message' => 'Akun dengan email ini tidak aktif'];
            } else {
                $response = ['success' => true, 'message' => 'Email ditemukan'];
            }
            break;
            
        default:
            $response = ['success' => false, 'message' => 'Invalid action'];
    }
    
} catch (PDOException $e) {
    error_log('Database error in check_user.php: ' . $e->getMessage());
    $response = ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
}

echo json_encode($response);
?>