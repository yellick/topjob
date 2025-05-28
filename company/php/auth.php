<?php
    require_once '../../php/db_conn.php';
    header('Content-Type: application/json');

    $email = $_POST['email'] ?? '';
    $password = md5($_POST['password'] ?? '');

    $response = [];

    try {
        $stmt = $db->prepare("SELECT * FROM `companies` WHERE `email` = ? AND `password` = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $company = $result->fetch_assoc();

            setcookie('company_user', $company['id'], [
                'samesite' => 'Strict',
                'path' => '/',
            ]);

            $response = [
                'status' => true,
                'message' => 'Авторизация успешна',
                'code' => 0
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Неверная почта или пароль',
                'code' => 1
            ];
        }
    } catch (Exception $e) {
        $response = [
            'status' => false,
            'message' => 'Ошибка сервера',
            'code' => 2
        ];
    }

    $stmt->close();
    $db->close();
    echo json_encode($response);
    exit();
?>