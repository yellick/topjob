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

$applicant_id = $_GET['applicant_id'] ?? null;
$vacancy_id = $_GET['vacancy_id'] ?? null;

if (!$applicant_id || !$vacancy_id) {
    echo json_encode([
        'status' => false,
        'message' => 'Неверные параметры запроса',
        'code' => 1
    ]);
    exit();
}

try {
    // Получаем данные соискателя
    $stmt = $db->prepare("SELECT * FROM `applicants` WHERE `id` = ?");
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'status' => false,
            'message' => 'Соискатель не найден',
            'code' => 2
        ]);
        exit();
    }

    $applicant = $result->fetch_assoc();
    
    // Получаем данные вакансии
    $stmt = $db->prepare("SELECT vacancy_name, salary FROM vacancies WHERE id = ? AND company_id = ?");
    $stmt->bind_param("ii", $vacancy_id, $company_id);
    $stmt->execute();
    $vacancy_result = $stmt->get_result();
    
    if ($vacancy_result->num_rows === 0) {
        echo json_encode([
            'status' => false,
            'message' => 'Вакансия не найдена',
            'code' => 3
        ]);
        exit();
    }
    
    $vacancy = $vacancy_result->fetch_assoc();

    // Форматируем данные
    $response = [
        'status' => true,
        'data' => [
            'user_name' => $applicant['user_name'],
            'birth_date' => $applicant['birth_date'] ? date('d.m.Y', strtotime($applicant['birth_date'])) : 'Не указана',
            'email' => $applicant['email'],
            'phone' => $applicant['phone'] ?: 'Не указан',
            'about' => $applicant['about'] ?? '',
            'experience' => json_decode($applicant['experience_json'] ?? '[]', true) ?: [],
            'skills' => json_decode($applicant['skills_json'] ?? '[]', true) ?: [],
            'vacancy_name' => $vacancy['vacancy_name'],
            'salary' => $vacancy['salary']
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'status' => false,
        'message' => 'Ошибка сервера: ' . $e->getMessage(),
        'code' => 4
    ]);
}
?>