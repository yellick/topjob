<?php
    require_once '../../../../php/db_conn.php';
    header('Content-Type: application/json');

    // Инициализация ответа
    $response = [
        'status' => false,
        'message' => 'Ошибка обновления вакансии',
        'code' => 2,
    ];

    // Проверка авторизации компании
    $company_id = $_COOKIE['company_user'] ?? null;
    if (!$company_id) {
        $response['message'] = 'Компания не авторизована';
        $response['code'] = 4;
        echo json_encode($response);
        exit();
    }

    // Получение данных из формы
    $vacancy_id = $_POST['id'] ?? 0;
    $vacancy_name = $_POST['title'] ?? '';
    $salary = $_POST['salary'] ?? '';
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

    // Проверка что вакансия принадлежит компании
    try {
        // Сначала проверяем принадлежность вакансии компании
        $check_stmt = $db->prepare("SELECT id FROM vacancies WHERE id = ? AND company_id = ?");
        $check_stmt->bind_param("ii", $vacancy_id, $company_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows === 0) {
            throw new Exception('Вакансия не найдена или не принадлежит компании');
        }

        // Преобразование JSON строк в массивы для проверки
        $responsibilities_arr = json_decode($responsibilities, true);
        $requirements_arr = json_decode($requirements, true);
        $conditions_arr = json_decode($conditions, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Ошибка формата данных');
        }

        // Подготовка и выполнение запроса на обновление
        $stmt = $db->prepare("UPDATE vacancies SET 
                                vacancy_name = ?,
                                salary = ?,
                                description = ?,
                                responsibilities = ?,
                                requirements = ?,
                                conditions = ?
                            WHERE id = ? AND company_id = ?");
        
        $stmt->bind_param(
            "ssssssii", 
            $vacancy_name, 
            $salary, 
            $description,
            $responsibilities,
            $requirements,
            $conditions,
            $vacancy_id,
            $company_id
        );

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response = [
                    'status' => true,
                    'message' => 'Вакансия успешно обновлена',
                    'code' => 0,
                    'vacancy_id' => $vacancy_id
                ];
            } else {
                throw new Exception('Данные не были изменены');
            }
        } else {
            throw new Exception('Ошибка базы данных: ' . $db->error);
        }
        
        $stmt->close();
        $check_stmt->close();
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        $response['code'] = 2;
    }

    $db->close();
    echo json_encode($response);
    exit();
?>