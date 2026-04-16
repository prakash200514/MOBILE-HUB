<?php
/**
 * MobileHub — Diagnostic Script
 * Use this to verify your database connection and fix the admin account.
 */
require_once __DIR__ . '/includes/db.php';

echo "<h1>MobileHub Login Diagnostic</h1>";

// 1. Check Connection
if ($conn->connect_error) {
    echo "<p style='color:red;'>❌ Database Connection Failed: " . $conn->connect_error . "</p>";
    echo "<p>Please check your <code>includes/db.php</code> settings.</p>";
    exit;
} else {
    echo "<p style='color:green;'>✅ Database Connected Successfully (Host: " . DB_HOST . ", DB: " . DB_NAME . ")</p>";
}

// 2. Check for Admin User
$email = 'admin@mobilehub.com';
$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='color:orange;'>⚠️ Admin account not found (admin@mobilehub.com).</p>";
    echo "<p>Attempting to create a new admin account...</p>";
    
    $name = 'Admin';
    $password = 'admin123';
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $role = 'admin';
    
    $create = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $create->bind_param("ssss", $name, $email, $hashed, $role);
    if ($create->execute()) {
        echo "<p style='color:green;'>✅ Success! Admin account created. Password is <b>admin123</b></p>";
    } else {
        echo "<p style='color:red;'>❌ Failed to create admin account: " . $conn->error . "</p>";
    }
} else {
    $user = $result->fetch_assoc();
    echo "<p style='color:green;'>✅ Admin account found (Role: " . $user['role'] . ")</p>";
    
    // 3. Verify Password Hash
    $testPass = 'admin123';
    if (password_verify($testPass, $user['password'])) {
        echo "<p style='color:green;'>✅ Password Verification passed! You can login with <b>admin123</b></p>";
    } else {
        echo "<p style='color:orange;'>⚠️ Password hash mismatch found. Fixing it now...</p>";
        $newHash = password_hash($testPass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->bind_param("si", $newHash, $user['id']);
        if ($update->execute()) {
            echo "<p style='color:green;'>✅ Password hash updated! You can now login with <b>admin123</b></p>";
        } else {
            echo "<p style='color:red;'>❌ Failed to update password hash: " . $conn->error . "</p>";
        }
    }
}

echo "<hr>";
echo "<p><a href='login.php'>Go to Login Page</a></p>";
