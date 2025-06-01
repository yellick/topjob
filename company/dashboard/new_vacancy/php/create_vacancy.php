<?php
    require_once '../../../../php/db_conn.php';
    header('Content-Type: application/json');

    // Инициализация ответа
    $response = [
        'status' => false,
        'message' => 'Ошибка создания вакансии',
        'code' => 2,
    ];

    // Проверка авторизации компании
    $company_id = $_COOKIE['company_user'] ?? null;
    if (!$company_id) {
        $response['message'] = 'Компания не авторизована';
        $response['code'] = 4; // Код для неавторизованного доступа
        echo json_encode($response);
        exit();
    }

    // Получение данных из формы
    $vacancy_name = $_POST['title'] ?? '';
    $salary = $_POST['salary'] ?? 0;
    $description = $_POST['description'] ?? '';
    $responsibilities = $_POST['responsibilities'] ?? '[]';
    $requirements = $_POST['requirements'] ?? '[]';
    $conditions = $_POST['conditions'] ?? '[]';

    // Валидация обязательных полей
    if (empty($vacancy_name)) {
        $response['message'] = 'Название вакансии обязательно для заполнения';
        $response['code'] = 1;
        echo json_encode($response);
        exit();
    }

    try {
        // Преобразование JSON строк в массивы для проверки
        $responsibilities_arr = json_decode($responsibilities, true);
        $requirements_arr = json_decode($requirements, true);
        $conditions_arr = json_decode($conditions, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Ошибка формата данных');
        }

        // Подготовка и выполнение запроса
        $stmt = $db->prepare("INSERT INTO `vacancies` 
                            (`company_id`, `vacancy_name`, `salary`, `description`, `responsibilities`, `requirements`, `conditions`) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            "issssss", 
            $company_id, 
            $vacancy_name, 
            $salary, 
            $description,
            $responsibilities,
            $requirements,
            $conditions
        );

        if ($stmt->execute()) {
            $response = [
                'status' => true,
                'message' => 'Вакансия успешно создана',
                'code' => 0,
                'vacancy_id' => $stmt->insert_id
            ];
        } else {
            throw new Exception('Ошибка базы данных: ' . $db->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        $response['code'] = 2;
    }

    $db->close();
    echo json_encode($response);
    exit();
?>