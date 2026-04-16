<?php
/**
 * MobileHub — Authentication Helpers
 */

require_once __DIR__ . '/db.php';

/**
 * Register a new user
 */
function registerUser($name, $email, $phone, $password) {
    global $conn;
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        return ['success' => false, 'message' => 'Email already registered'];
    }
    $stmt->close();
    
    // Hash password and insert
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')");
    $stmt->bind_param("ssss", $name, $email, $phone, $hashed);
    
    if ($stmt->execute()) {
        $userId = $stmt->insert_id;
        $stmt->close();
        // Auto login after registration
        loginSession($userId, $name, $email, 'customer');
        return ['success' => true, 'message' => 'Registration successful'];
    }
    
    $stmt->close();
    return ['success' => false, 'message' => 'Registration failed. Please try again.'];
}

/**
 * Login user
 */
function loginUser($email, $password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, name, email, password, role, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (!$user['status']) {
            $stmt->close();
            return ['success' => false, 'message' => 'Account has been deactivated'];
        }
        
        if (password_verify($password, $user['password'])) {
            $stmt->close();
            loginSession($user['id'], $user['name'], $user['email'], $user['role']);
            return ['success' => true, 'message' => 'Login successful', 'role' => $user['role']];
        }
    }
    
    $stmt->close();
    return ['success' => false, 'message' => 'Invalid email or password'];
}

/**
 * Set login session
 */
function loginSession($id, $name, $email, $role) {
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = $role;
    $_SESSION['logged_in'] = true;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Require login — redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Require admin — redirect if not admin
 */
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ' . SITE_URL . '/login.php');
        exit;
    }
}

/**
 * Logout user
 */
function logoutUser() {
    session_unset();
    session_destroy();
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

/**
 * Get current user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    global $conn;
    $stmt = $conn->prepare("SELECT id, name, email, phone, role, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $user;
}
