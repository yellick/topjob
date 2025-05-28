<?php
require_once '../../../../php/db_conn.php';
header('Content-Type: application/json');

// Инициализация ответа
$response = [
    'status' => false,
    'message' => 'Ошибка обновления данных',
    'code' => 2,
];

// Проверка авторизации компании
$company_id = $_COOKIE['company_user'] ?? null;
if (!$company_id) {
    $response['message'] = 'Требуется авторизация';
    echo json_encode($response);
    exit();
}

// Получение данных из формы
$email = trim($_POST['email'] ?? '');
$name = trim($_POST['name'] ?? '');
$city = $_POST['city'] ?? '';
$shortDescription = $_POST['shortDescription'] ?? '';
$fullDescription = $_POST['fullDescription'] ?? '';

// 1. Сначала получаем текущие данные компании
$SQL_get_current = "SELECT * FROM companies WHERE id = ?";
$stmt = $db->prepare($SQL_get_current);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$current_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

// 2. Проверяем, изменились ли email или название
$email_changed = ($current_data['email'] !== $email);
$name_changed = ($current_data['name'] !== $name);

// 3. Проверяем уникальность только если данные изменились
if ($email_changed || $name_changed) {
    $conditions = [];
    $types = "";
    $params = [];
    
    if ($email_changed) {
        $conditions[] = "email = ?";
        $types .= "s";
        $params[] = $email;
    }
    
    if ($name_changed) {
        $conditions[] = "name = ?";
        $types .= "s";
        $params[] = $name;
    }
    
    $SQL_check = "SELECT id, email, name FROM companies WHERE (" . implode(" OR ", $conditions) . ") AND id != ?";
    $types .= "i";
    $params[] = $company_id;
    
    $stmt = $db->prepare($SQL_check);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($email_changed && $row['email'] === $email) {
            $response = [
                'status' => false,
                'message' => 'Компания с такой почтой уже существует',
                'code' => 1,
            ];
        } elseif ($name_changed && $row['name'] === $name) {
            $response = [
                'status' => false,
                'message' => 'Компания с таким названием уже существует',
                'code' => 3,
            ];
        }
        echo json_encode($response);
        exit();
    }
    $stmt->close();
}

// Обновление данных компании
$SQL_update = "UPDATE companies SET 
              name = ?, 
              email = ?, 
              city_id = ?, 
              short_desc = ?, 
              full_desc = ? 
              WHERE id = ?";

$stmt = $db->prepare($SQL_update);
$stmt->bind_param("sssssi", $name, $email, $city, $shortDescription, $fullDescription, $company_id);

if ($stmt->execute()) {
    $response = [
        'status' => true,
        'message' => 'Данные успешно обновлены',
        'code' => 0,
        'data' => [
            'name' => $name,
            'email' => $email,
            'city_id' => $city
        ]
    ];
} else {
    $response = [
        'status' => false,
        'message' => 'Ошибка обновления: ' . $db->error,
        'code' => 2,
    ];
}

$stmt->close();
$db->close();

echo json_encode($response);
exit();
?>