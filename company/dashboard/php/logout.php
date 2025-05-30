<?php
    setcookie('company_user', '', 1);

    $response = [
        'status' => true,
        'message' => 'Logout successful',
        'code' => 0
    ];
    echo json_encode($response);
?>