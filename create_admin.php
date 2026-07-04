<?php
// run this once to create your first admin login, then delete this file
require_once '../includes/config.php';

$message = "";
$done = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $fullName = trim($_POST['full_name']);

    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$username]);

    if ($check->fetch()) {
        $message = "That username already exists";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, role) VALUES (?,?,?,'admin')");
        $stmt->execute([$username, $hash, $fullName]);
        $done = true;
        $message = "Admin created! You can log in now. Delete this file.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Create Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="page-wrap">
<div class="panel" style="max-width:400px;margin:auto;">
    <h1>Create Admin Account</h1>

    <?php if ($message) { ?>
        <div class="alert <?php echo $done ? 'alert-success' : 'alert-error'; ?>"><?php echo $message; ?></div>
    <?php } ?>

    <?php if (!$done) { ?>
    <form method="post">
        <label>Full Name</label>
        <input type="text" name="full_name" required>

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Create</button>
    </form>
    <?php } else { ?>
        <a class="btn" href="../login.php">Go to Login</a>
    <?php } ?>
</div>
</div>
</body>
</html>
