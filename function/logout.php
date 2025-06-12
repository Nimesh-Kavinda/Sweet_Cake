<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Destroy all session data
session_unset();
session_destroy();

// Clear any remember me cookies if they exist
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, '/');
}

// Redirect to index page
header('Location: ../index.php');
exit();
?>
