<?php 
    require_once '../get_src.php';
    require_once '../php/db_conn.php';

    $company_id = $_COOKIE['company_user'] ?? null;
    if ($company_id) {
        $stmt = $db->prepare("SELECT id FROM companies WHERE id = ?");
        $stmt->bind_param("i", $company_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            header("Location: dashboard/");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Job - Компания</title>
    <?php require '../modules/def_links.php'; ?>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <main>
        <form id="auth-form">
            <h1 class="title">Управление компанией</h1>
            <p class="subtitle">Вход</p>

            <div class="inputs">
                <div class="input-wrap">
                    <label for="email">Почта</label>
                    <input id="email" name="email" type="email" placeholder="example@mail.ru" required>
                </div>
                <div class="input-wrap">
                    <label for="pass">Пароль</label>
                    <input id="pass" name="pass" type="password" placeholder="············" required>
                </div>
            </div>

            <button id="auth-btn">Войти</button>
        </form>

        <div class="link">
            Впервые у нас? Тогда вам
            <a href="registration.php">Сюда!</a>
        </div>
    </main>
    
    <?php require '../modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>