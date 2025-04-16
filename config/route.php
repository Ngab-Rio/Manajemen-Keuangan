<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        require 'login.php';
        break;
    case '/admin':
        require '/admin/dashboard.php';
        break;
    case '/add_transaction':
        require '/admin/add_transaction.php';
        break;
    case '/edit_transaction':
        require '/admin/edit_transaction.php';
        break;
    case '/transactions':
        require '/admin/transactions.php';
        break;
    default:
        http_response_code(404);
        require '404.php';
        break;
}
?>
