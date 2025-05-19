<?php
    require_once 'db_conn.php';

    header('Content-Type: application/json');

    $email = $_POST['email'] ?? '';
    $password = md5($_POST['password'] ?? '');

    $response = [];

    try {
        $stmt = $db->prepare("SELECT * FROM `applicants` WHERE `email` = ? AND `pass` = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            setcookie('user', $user['id'], [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            $response = [
                'status' => true,
                'message' => 'Login successful',
                'code' => 0
            ];
        } else {
            $response = [ 
                'status' => false,
                'message' => 'Invalid email or password',
                'code' => 1
            ];
        }
    } catch (Exception $e) {
        $response = [
            'status' => false,
            'message' => 'Login error: ' . $e->getMessage(),
            'code' => 2
        ];
    }

    $stmt->close();
    $db->close();
    echo json_encode($response);
    exit();
?>