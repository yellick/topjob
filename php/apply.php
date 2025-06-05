<?php
    require_once 'db_conn.php';
    header('Content-Type: application/json');

    // Проверяем авторизацию
    if (!isset($_COOKIE['user'])) {
        echo json_encode([
            'status' => false,
            'message' => 'Требуется авторизация',
            'code' => 3
        ]);
        exit();
    }

    $user_id = (int)$_COOKIE['user'];
    $vacancy_id = (int)$_POST['vacancy_id'];

    $response = [];

    // Проверяем, не отправлял ли пользователь уже отклик на эту вакансию
    $stmt = $db->prepare("SELECT * FROM `orders` WHERE `u_id` = ? AND `vacancy_id` = ?");
    $stmt->bind_param("ii", $user_id, $vacancy_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response = [ 
            "status" => false,
            "message" => "Вы уже откликались на эту вакансию",
            "code" => 1
        ];
    } else {
        // Добавляем новый отклик
        $stmt = $db->prepare("
            INSERT INTO `orders` (
                `u_id`,
                `vacancy_id`,
                `create_date`,
                `status`
            ) VALUES (?, ?, NOW(), 1)
        ");
        
        $stmt->bind_param("ii", $user_id, $vacancy_id);

        if ($stmt->execute()) {
            $response = [
                'status' => true,
                'message' => 'Ваш отклик успешно отправлен',
                'code' => 0
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Ошибка при отправке отклика: ' . $db->error,
                "code" => 2
            ];
        }
    }

    $stmt->close();
    $db->close();
    echo json_encode($response);
    exit();
?>