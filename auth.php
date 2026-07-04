<?php
// login check helpers

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function currentUserName() {
    return isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Guest';
}
