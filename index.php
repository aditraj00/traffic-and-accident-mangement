
<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Handle routing
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Check if user is logged in for protected pages
$protected_pages = ['dashboard', 'traffic-status', 'submit-feedback', 'report-incident', 'violations', 'users', 'reports', 'statistics', 'settings', 'safety-tips'];

if (in_array($page, $protected_pages) && !isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

// Role-based access control
$admin_pages = ['users', 'reports', 'statistics', 'settings'];
$police_pages = ['report-incident', 'violations'];

if (isset($_SESSION['role'])) {
    if (in_array($page, $admin_pages) && $_SESSION['role'] !== 'admin') {
        header('Location: index.php?page=dashboard');
        exit();
    }
    
    if (in_array($page, $police_pages) && $_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'police') {
        header('Location: index.php?page=dashboard');
        exit();
    }
}

// Include header
include 'includes/header.php';

// Load the appropriate page
switch ($page) {
    case 'home':
        include 'pages/home.php';
        break;
    case 'login':
        include 'pages/login.php';
        break;
    case 'register':
        include 'pages/register.php';
        break;
    case 'dashboard':
        include 'pages/dashboard.php';
        break;
    case 'traffic-status':
        include 'pages/traffic-status.php';
        break;
    case 'safety-tips':
        include 'pages/safety-tips.php';
        break;
    case 'submit-feedback':
        include 'pages/submit-feedback.php';
        break;
    case 'report-incident':
        include 'pages/report-incident.php';
        break;
    case 'violations':
        include 'pages/violations.php';
        break;
    case 'users':
        include 'pages/users.php';
        break;
    case 'reports':
        include 'pages/reports.php';
        break;
    case 'statistics':
        include 'pages/statistics.php';
        break;
    case 'settings':
        include 'pages/settings.php';
        break;
    default:
        include 'pages/404.php';
        break;
}

// Include footer
include 'includes/footer.php';
?>
