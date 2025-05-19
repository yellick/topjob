<?php
    require_once 'db_conn.php';

    header('Content-Type: application/json');


    $user_id = $_COOKIE['user'] ?? null;
    if (!$user_id) {
        echo json_encode(['status' => false, 'message' => 'Not authorized', 'code' => 3]);
        exit();
    }


    $user_id = filter_var($user_id, FILTER_VALIDATE_INT);
    if ($user_id === false || $user_id <= 0) {
        echo json_encode(['status' => false, 'message' => 'Incorrect u_id', 'code' => 4]);
        exit();
    }


    $data = $_POST;

    if (empty($data)) {
        echo json_encode(['status' => false, 'message' => 'Неверные данные', 'code' => 5]);
        exit();
    }


    
    $stmt = $db->prepare("UPDATE `applicants` SET 
        `user_name` = ?,
        `birth_date` = ?,
        `city` = ?,
        `email` = ?,
        `phone` = ?,
        `about` = ?,
        `experience_json` = ?,
        `skills_json` = ?
        WHERE `id` = ?");

    try {
        $stmt->bind_param(
            "ssssssssi",
            $data['user_name'],
            $data['birth_date'],
            $data['city'],
            $data['email'],
            $data['phone'],
            $data['about'],
            $data['experience_json'],
            $data['skills_json'],
            $user_id
        );
        

        $success = $stmt->execute();
        
        if ($success) {
            $response = [
                'status' => true,
                'message' => 'Success',
                'code' => 0
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Data update error',
                'code' => 1
            ];
        }
    } catch (Exception $e) {
        $response = [
            'status' => false,
            'message' => 'DB error: ' . $e->getMessage(),
            'code' => 2
        ];
    }

    $stmt->close();
    $db->close();
    echo json_encode($response);
    exit();
?>