<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
requireLogin();

$total = $pdo->query("SELECT COUNT(*) FROM shipments")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM shipments WHERE status='Pending'")->fetchColumn();
$transit = $pdo->query("SELECT COUNT(*) FROM shipments WHERE status='In Transit'")->fetchColumn();
$delivered = $pdo->query("SELECT COUNT(*) FROM shipments WHERE status='Delivered'")->fetchColumn();

$shipments = $pdo->query("SELECT * FROM shipments ORDER BY created_at DESC LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);

function statusClass($status) {
    return 'status-' . strtolower(str_replace(' ', '-', $status));
}

$pageTitle = "Dashboard";
require_once 'includes/header.php';
?>

<h1>Dashboard</h1>

<div class="stat-strip">
    <div class="stat-box"><div class="num"><?php echo $total; ?></div><div class="lbl">Total</div></div>
    <div class="stat-box"><div class="num"><?php echo $pending; ?></div><div class="lbl">Pending</div></div>
    <div class="stat-box"><div class="num"><?php echo $transit; ?></div><div class="lbl">In Transit</div></div>
    <div class="stat-box"><div class="num"><?php echo $delivered; ?></div><div class="lbl">Delivered</div></div>
</div>

<div class="panel">
    <h2>Recent Shipments</h2>

    <?php if (count($shipments) == 0) { ?>
        <p class="small">No shipments yet. <a href="create_shipment.php">Add one</a></p>
    <?php } else { ?>
    <table>
        <tr>
            <th>Tracking ID</th>
            <th>Sender</th>
            <th>Receiver</th>
            <th>Route</th>
            <th>Status</th>
            <th></th>
        </tr>
        <?php foreach ($shipments as $s) { ?>
        <tr>
            <td><span class="tracking-id"><?php echo $s['tracking_id']; ?></span></td>
            <td><?php echo htmlspecialchars($s['sender_name']); ?></td>
            <td><?php echo htmlspecialchars($s['receiver_name']); ?></td>
            <td><?php echo htmlspecialchars($s['origin']); ?> -> <?php echo htmlspecialchars($s['destination']); ?></td>
            <td><span class="status <?php echo statusClass($s['status']); ?>"><?php echo $s['status']; ?></span></td>
            <td><a href="update_status.php?id=<?php echo $s['id']; ?>" class="btn btn-secondary">Update</a></td>
        </tr>
        <?php } ?>
    </table>
    <?php } ?>
</div>

<?php require_once 'includes/footer.php'; ?>
