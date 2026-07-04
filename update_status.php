<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
requireLogin();

$id = (int) (isset($_GET['id']) ? $_GET['id'] : $_POST['id']);

$stmt = $pdo->prepare("SELECT * FROM shipments WHERE id = ?");
$stmt->execute([$id]);
$shipment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$shipment) {
    header('Location: dashboard.php');
    exit;
}

$statuses = ['Pending', 'Picked Up', 'In Transit', 'Out for Delivery', 'Delivered', 'Delayed'];

// list of delay reasons - this is the new "delay reason" feature,
// most tracking apps just say "delayed" with no explanation
$delay_reasons = ['Traffic congestion', 'Bad weather', 'Customs hold', 'Warehouse backlog', 'Vehicle breakdown', 'Other'];

$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newStatus = $_POST['status'];
    $remarks = trim($_POST['remarks']);

    if ($newStatus == 'Delayed' && isset($_POST['delay_reason']) && $_POST['delay_reason'] != '') {
        $remarks = "Reason: " . $_POST['delay_reason'] . ($remarks != "" ? " - " . $remarks : "");
    }

    if (in_array($newStatus, $statuses)) {
        $up = $pdo->prepare("UPDATE shipments SET status=? WHERE id=?");
        $up->execute([$newStatus, $id]);

        $hist = $pdo->prepare("INSERT INTO status_history (shipment_id, status, remarks) VALUES (?,?,?)");
        $hist->execute([$id, $newStatus, $remarks]);

        $success = "Status updated to " . $newStatus;

        $stmt->execute([$id]);
        $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

$h = $pdo->prepare("SELECT * FROM status_history WHERE shipment_id=? ORDER BY updated_at DESC");
$h->execute([$id]);
$history = $h->fetchAll(PDO::FETCH_ASSOC);

function statusClass($status) {
    return 'status-' . strtolower(str_replace(' ', '-', $status));
}

$pageTitle = "Update Status";
require_once 'includes/header.php';
?>

<h1>Update Status</h1>
<p><span class="tracking-id"><?php echo $shipment['tracking_id']; ?></span> &mdash; <?php echo htmlspecialchars($shipment['sender_name']); ?> to <?php echo htmlspecialchars($shipment['receiver_name']); ?></p>

<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<div class="panel">
    <p>Current status: <span class="status <?php echo statusClass($shipment['status']); ?>"><?php echo $shipment['status']; ?></span></p>

    <form method="post" action="update_status.php?id=<?php echo $id; ?>" id="statusForm">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label>New Status</label>
        <select name="status" id="statusSelect" onchange="toggleDelayReason()">
            <?php foreach ($statuses as $s) { ?>
                <option value="<?php echo $s; ?>" <?php if ($s == $shipment['status']) echo 'selected'; ?>><?php echo $s; ?></option>
            <?php } ?>
        </select>

        <div id="delayBox" style="display:none;">
            <label>Delay Reason</label>
            <select name="delay_reason">
                <option value="">-- select reason --</option>
                <?php foreach ($delay_reasons as $r) { ?>
                    <option value="<?php echo $r; ?>"><?php echo $r; ?></option>
                <?php } ?>
            </select>
        </div>

        <label>Remarks (optional)</label>
        <input type="text" name="remarks" placeholder="e.g. left warehouse at 4pm">

        <button type="submit">Update</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
function toggleDelayReason() {
    var status = document.getElementById('statusSelect').value;
    var box = document.getElementById('delayBox');
    box.style.display = (status === 'Delayed') ? 'block' : 'none';
}
// run once on page load in case status is already "Delayed"
toggleDelayReason();
</script>

<div class="panel">
    <h2>History</h2>
    <ul class="timeline">
        <?php foreach ($history as $row) { ?>
        <li>
            <b><?php echo $row['status']; ?></b><br>
            <span class="small"><?php echo date('d M Y, g:i A', strtotime($row['updated_at'])); ?></span>
            <?php if ($row['remarks']) { ?>
                <div><?php echo htmlspecialchars($row['remarks']); ?></div>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
</div>

<?php require_once 'includes/footer.php'; ?>
