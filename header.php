<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo isset($pageTitle) ? $pageTitle . ' - Smart Logistics' : 'Smart Logistics'; ?></title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="topbar">
    <div><b>Smart Logistics Tracker</b></div>
    <div>
    <?php if (isLoggedIn()): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="create_shipment.php">New Shipment</a>
        <a href="track_shipment.php">Track</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="track_shipment.php">Track a Shipment</a>
        <a href="login.php">Admin Login</a>
    <?php endif; ?>
    </div>
</div>

<div class="page-wrap">
