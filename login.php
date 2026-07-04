<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Wrong username or password";
    }
}

$pageTitle = "Login";
require_once 'includes/header.php';
?>

<div class="panel" style="max-width:400px;margin:auto;">
    <h1>Admin Login</h1>

    <?php if ($error) { ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php } ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p class="small">First time? Run setup/create_admin.php to make an admin account.</p>
</div>

<?php require_once 'includes/footer.php'; ?>
