<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$trackingId = trim(isset($_GET['tracking_id']) ? $_GET['tracking_id'] : '');
$shipment = null;
$history = [];
$notFound = false;

if ($trackingId != "") {
    $stmt = $pdo->prepare("SELECT * FROM shipments WHERE tracking_id = ?");
    $stmt->execute([$trackingId]);
    $shipment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($shipment) {
        $h = $pdo->prepare("SELECT * FROM status_history WHERE shipment_id = ? ORDER BY updated_at DESC");
        $h->execute([$shipment['id']]);
        $history = $h->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $notFound = true;
    }
}

function statusClass($status) {
    return 'status-' . strtolower(str_replace(' ', '-', $status));
}

$pageTitle = "Track Shipment";
require_once 'includes/header.php';
?>

<h1>Track Your Shipment</h1>

<div class="panel" style="max-width:450px;">
    <form method="get">
        <label>Tracking ID</label>
        <input type="text" name="tracking_id" value="<?php echo htmlspecialchars($trackingId); ?>" placeholder="e.g. SL26A1B2C" required>
        <button type="submit">Track</button>
    </form>
</div>

<?php if ($notFound) { ?>
    <div class="alert alert-error">No shipment found with that tracking ID.</div>
<?php } ?>

<?php if ($shipment) { ?>
<div class="panel">
    <h2><span class="tracking-id"><?php echo $shipment['tracking_id']; ?></span>
    <span class="status <?php echo statusClass($shipment['status']); ?>"><?php echo $shipment['status']; ?></span></h2>

    <p>From: <?php echo htmlspecialchars($shipment['origin']); ?><br>
    To: <?php echo htmlspecialchars($shipment['destination']); ?></p>

    <?php if ($shipment['package_details']) { ?>
        <p class="small">Package: <?php echo htmlspecialchars($shipment['package_details']); ?></p>
    <?php } ?>

    <h2>History</h2>
    <ul class="timeline">
        <?php foreach ($history as $h2) { ?>
        <li>
            <b><?php echo $h2['status']; ?></b><br>
            <span class="small"><?php echo date('d M Y, g:i A', strtotime($h2['updated_at'])); ?></span>
            <?php if ($h2['remarks']) { ?>
                <div><?php echo htmlspecialchars($h2['remarks']); ?></div>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
</div>
<?php } ?>

<?php require_once 'includes/footer.php'; ?>
