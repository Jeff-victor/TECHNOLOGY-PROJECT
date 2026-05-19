<?php
require_once __DIR__ . '/functions.php';
startSecureSession();
if (!empty($_SESSION['user_id'])) {
    auditLog($_SESSION['user_id'], 'LOGOUT');
}
session_destroy();
header('Location: login.php');
exit;
