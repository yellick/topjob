<?php
    setcookie('user', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    $response = [
        'status' => true,
        'message' => 'Logout successful',
        'code' => 0
    ];
    echo json_encode($response);
?>