<?php
    setcookie('company_user', "0", time(), "/");

    $response = [
        'status' => true,
        'message' => 'Logout successful',
        'code' => 0
    ];
    echo json_encode($response);
?>