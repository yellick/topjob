<?php
require_once '../php/db_conn.php';
header('Content-Type: application/json');

$response = [
    'status' => false,
    'message' => 'Ошибка получения вакансий',
    'vacancies' => [],
    'filters' => []
];

$input = $_POST;

try {
    // Сохраняем переданные параметры в ответ
    $response['filters'] = [
        'search' => $input['search'] ?? '',
        'amount' => $input['amount'] ?? 0,
        'city' => $input['city'] ?? null
    ];

    // Базовый запрос
    $sql = "SELECT vacancies.id, vacancies.vacancy_name as title, vacancies.salary, 
                   companies.name as company, cities.city_name as location
            FROM `vacancies`
            INNER JOIN companies ON vacancies.company_id = companies.id
            INNER JOIN cities ON companies.city_id = cities.id
            WHERE 1=1";
    
    $params = [];
    $types = '';
    
    // Фильтр по поиску
    if (!empty($input['search'])) {
        $sql .= " AND vacancies.vacancy_name LIKE ?";
        $params[] = '%' . $input['search'] . '%';
        $types .= 's';
    }
    
    // Фильтр по зарплате
    if (!empty($input['amount']) && $input['amount'] > 0) {
        $sql .= " AND vacancies.salary > ?";
        $params[] = (int)$input['amount'];
        $types .= 'i';
    }
    
    // Фильтр по городу
    if (!empty($input['city'])) {
        $sql .= " AND companies.city_id = ?";
        $params[] = (int)$input['city'];
        $types .= 'i';
    }
    
    $stmt = $db->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $response['vacancies'][] = $row;
    }
    
    $response['status'] = true;
    $response['message'] = 'Успешно';
    
} catch (Exception $e) {
    $response['message'] = 'Ошибка: ' . $e->getMessage();
}

$db->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>