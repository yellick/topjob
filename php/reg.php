<?php
    require_once 'db_conn.php';
    header('Content-Type: application/json');

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['pass']);

    $response = [];

    $stmt = $db->prepare("SELECT * FROM `applicants` WHERE `email` = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response = [ 
            "status" => false,
            "message" => "A user with such an email already exists",
            "code" => 1
        ];
    } else {
        $stmt = $db->prepare("
            INSERT INTO `applicants` (
                `user_name`,
                `reg_date`,
                `email`,
                `pass`
            ) VALUES (?, NOW(), ?, ?)
        ");

        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $response = [
                'status' => true,
                'message' => 'Registration successful',
                'code' => 0
            ];


            setcookie('user', $stmt->insert_id, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            
            //$db->query("TRUNCATE TABLE `applicants`");
        } else {
            $response = [
                'status' => false,
                'message' => 'Registration error: ' . $db->error,
                "code" => 2
            ];
        }
    }

    $stmt->close();
    $db->close();
    echo json_encode($response);
    exit();
?>