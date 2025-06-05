<?php
require_once '../../../../php/db_conn.php';

header('Content-Type: application/json');

$company_id = $_COOKIE['company_user'] ?? null;
if (!$company_id) {
    echo json_encode([
        'status' => false,
        'message' => 'Требуется авторизация',
        'code' => 3
    ]);
    exit();
}

try {
    // Получаем параметры фильтрации
    $vacancy_id = isset($_GET['vacancy_id']) ? (int)$_GET['vacancy_id'] : 0;
    $status = isset($_GET['status']) ? (int)$_GET['status'] : -1;

    // Базовый запрос
    $query = "SELECT 
                orders.id, 
                orders.u_id,
                orders.vacancy_id,
                vacancies.vacancy_name, 
                applicants.user_name, 
                orders.create_date, 
                orders.status 
              FROM orders
              INNER JOIN applicants ON orders.u_id = applicants.id
              INNER JOIN vacancies ON orders.vacancy_id = vacancies.id
              INNER JOIN companies ON vacancies.company_id = companies.id
              WHERE companies.id = ? AND orders.status != 2 ";

    // Добавляем условия фильтрации
    $params = [$company_id];
    $types = "i";

    if ($vacancy_id > 0) {
        $query .= " AND vacancies.id = ?";
        $params[] = $vacancy_id;
        $types .= "i";
    }

    if ($status > -1) {
        $query .= " AND orders.status = ?";
        $params[] = $status;
        $types .= "i";
    }

    $query .= " ORDER BY orders.status ASC";

    // Выполняем запрос
    $stmt = $db->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $applications = [];
    while ($row = $result->fetch_assoc()) {
        $applications[] = [
            'id' => $row['id'],
            'u_id' => $row['u_id'], // Добавлено
            'vacancy_id' => $row['vacancy_id'], // Добавлено
            'vacancy_name' => $row['vacancy_name'],
            'user_name' => $row['user_name'],
            'create_date' => date('d.m.Y', strtotime($row['create_date'])),
            'status' => $row['status']
        ];
    }

    echo json_encode([
        'status' => true,
        'data' => $applications,
        'code' => 0
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => false,
        'message' => 'Ошибка при загрузке откликов: ' . $e->getMessage(),
        'code' => 2
    ]);
}
?>