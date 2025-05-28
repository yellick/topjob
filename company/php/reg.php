<?php
    require_once '../../php/db_conn.php';
    header('Content-Type: application/json');

    $response = [
        'status' => false,
        'message' => 'Ошибка регистрации',
        'code' => 2,
    ];

    // Получение данных из формы
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $city = $_POST['city'] ?? '';
    $shortDescription = $_POST['shortDescription'] ?? '';
    $fullDescription = $_POST['fullDescription'] ?? '';

    // Проверка на уникальность email и названия компании
    $SQL_check_company = "SELECT * FROM `companies` WHERE `email` = ? OR `name` = ?";
    $stmt = $db->prepare($SQL_check_company);
    $stmt->bind_param("ss", $email, $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['email'] === $email) {
            $response = [
                'status' => false,
                'message' => 'Компания с такой почтой уже существует',
                'code' => 1,
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Компания с таким названием уже существует',
                'code' => 3,
            ];
        }
    } else {
        // Вставка новой компании
        $SQL_insert = "INSERT INTO `companies` (`name`, `email`, `password`, `city_id`, `short_desc`, `full_desc`)
                    VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($SQL_insert);
        $passwordHash = md5($pass);
        $stmt->bind_param("ssssss", $name, $email, $passwordHash, $city, $shortDescription, $fullDescription);

        if ($stmt->execute()) {
            $response = [
                'status' => true,
                'message' => 'Registration successful',
                'code' => 0,
                'data' => $_POST 
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Ошибка регистрации: ' . $db->error,
                'code' => 2,
            ];
        }
    }

    $stmt->close();
    $db->close();

    echo json_encode($response);
    exit();
?>