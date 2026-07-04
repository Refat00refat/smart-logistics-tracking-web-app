<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
requireLogin();

$error = "";
$newId = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_name = trim($_POST['sender_name']);
    $sender_phone = trim($_POST['sender_phone']);
    $receiver_name = trim($_POST['receiver_name']);
    $receiver_phone = trim($_POST['receiver_phone']);
    $origin = trim($_POST['origin']);
    $destination = trim($_POST['destination']);
    $package = trim($_POST['package_details']);

    if ($sender_name == "" || $receiver_name == "" || $origin == "" || $destination == "") {
        $error = "Please fill all required fields";
    } else {
        // generate a random tracking id like SL26XXXXX
        $newId = "SL" . date("y") . strtoupper(substr(md5(uniqid()), 0, 5));

        $stmt = $pdo->prepare("INSERT INTO shipments (tracking_id, sender_name, sender_phone, receiver_name, receiver_phone, origin, destination, package_details, status, created_by) VALUES (?,?,?,?,?,?,?,?,'Pending',?)");
        $stmt->execute([$newId, $sender_name, $sender_phone, $receiver_name, $receiver_phone, $origin, $destination, $package, $_SESSION['user_id']]);

        $shipmentId = $pdo->lastInsertId();

        $stmt2 = $pdo->prepare("INSERT INTO status_history (shipment_id, status, remarks) VALUES (?, 'Pending', 'Shipment created')");
        $stmt2->execute([$shipmentId]);
    }
}

$pageTitle = "New Shipment";
require_once 'includes/header.php';
?>

<h1>Create Shipment</h1>

<?php if ($error) { ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php } ?>

<?php if ($newId) { ?>
    <div class="alert alert-success">Shipment created! Tracking ID: <span class="tracking-id"><?php echo $newId; ?></span></div>
<?php } ?>

<div class="panel">
    <form method="post">
        <label>Sender Name *</label>
        <input type="text" name="sender_name" required>

        <label>Sender Phone</label>
        <input type="text" name="sender_phone">

        <label>Receiver Name *</label>
        <input type="text" name="receiver_name" required>

        <label>Receiver Phone</label>
        <input type="text" name="receiver_phone">

        <label>Origin *</label>
        <input type="text" name="origin" required>

        <label>Destination *</label>
        <input type="text" name="destination" required>

        <label>Package Details</label>
        <textarea name="package_details" rows="3"></textarea>

        <button type="submit">Create Shipment</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
