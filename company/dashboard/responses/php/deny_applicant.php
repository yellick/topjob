<?php
require_once '../../../../php/db_conn.php';
header('Content-Type: application/json');

$response = [
    'status' => false,
    'message' => 'Ошибка удаления отклика',
    'code' => 2 // Код ошибки по умолчанию
];

// Проверка авторизации и наличия ID
if (!isset($_COOKIE['company_user'])) {
    $response['message'] = 'Компания не авторизована';
    $response['code'] = 3;
    echo json_encode($response);
    exit();
}

$id = $_POST['id'] ?? null;
if (!$id) {
    $response['message'] = 'ID отклика не указан';
    $response['code'] = 1;
    echo json_encode($response);
    exit();
}

try {
    $stmt = $db->prepare("DELETE FROM `orders` WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $response = [
            'status' => true,
            'message' => 'Отклик успешно удален',
            'code' => 0
        ];
    } else {
        throw new Exception('Ошибка базы данных: ' . $db->error);
    }
    
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

$db->close();
echo json_encode($response);
?>