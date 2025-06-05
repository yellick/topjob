<?php
require_once '../../../../php/db_conn.php';
header('Content-Type: application/json');

$response = [
    'status' => false,
    'message' => 'Ошибка обновления статуса',
    'code' => 2, // Код ошибки по умолчанию
    'notification_msg' => 'Ошибка при обработке заявки' // Сообщение для уведомления
];

// Проверка авторизации и наличия ID
if (!isset($_COOKIE['company_user'])) {
    $response['message'] = 'Компания не авторизована';
    $response['code'] = 3;
    $response['notification_msg'] = 'Требуется авторизация компании';
    echo json_encode($response);
    exit();
}

$id = $_POST['id'] ?? null;
if (!$id) {
    $response['message'] = 'ID отклика не указан';
    $response['code'] = 1;
    $response['notification_msg'] = 'Не указан ID отклика';
    echo json_encode($response);
    exit();
}

try {
    // Получаем текущий статус
    $stmt = $db->prepare("SELECT `status` FROM `orders` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Отклик не найден');
    }
    
    $currentStatus = $result->fetch_assoc()['status'];
    $stmt->close();
    
    // Обработка в зависимости от статуса
    if ($currentStatus == 1) {
        // Удаляем если уже был принят
        $stmt = $db->prepare("DELETE FROM `orders` WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $response = [
                'status' => true,
                'message' => 'Соискатель принят на работу',
                'code' => 0,
                'notification_msg' => 'Соискатель принят на работу'
            ];
        }
    } else {
        // Обновляем статус
        $stmt = $db->prepare("UPDATE `orders` SET `status` = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $response = [
                'status' => true,
                'message' => 'Статус обновлён',
                'code' => 0,
                'notification_msg' => 'Соискатель приглашён на собеседование'
            ];
        }
    }
    
    if (!$response['status']) {
        throw new Exception('Ошибка базы данных: ' . $db->error);
    }
    
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['notification_msg'] = 'Ошибка при обработке заявки';
}

$db->close();
echo json_encode($response);
?>