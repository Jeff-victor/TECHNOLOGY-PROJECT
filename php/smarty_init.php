<?php
/**
 * smarty_init.php
 * Initialises Smarty and loads shared helpers.
 * Every page requires this file instead of functions.php directly.
 */

require_once __DIR__ . '/../libs/Smarty.class.php';

require_once __DIR__ . '/functions.php';

$smarty = new \Smarty\Smarty();

// Directories
$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');


// Global variables available in every template
startSecureSession();
$smarty->assign('is_logged_in', !empty($_SESSION['user_id']));
$smarty->assign('is_admin',     !empty($_SESSION['is_admin']));
$smarty->assign('session_user', [
    'id'       => $_SESSION['user_id']  ?? 0,
    'username' => $_SESSION['username'] ?? '',
]);
