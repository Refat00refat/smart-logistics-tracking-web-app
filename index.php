<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
$pageTitle = "Home";
require_once 'includes/header.php';
?>

<div class="panel">
    <h1>Smart Logistics Tracking Web Application</h1>
    <p>A simple system to create shipments, update their status and let customers track them online.</p>
    <a href="track_shipment.php" class="btn">Track a Shipment</a>
    <a href="login.php" class="btn btn-secondary">Admin Login</a>
</div>

<?php require_once 'includes/footer.php'; ?>
