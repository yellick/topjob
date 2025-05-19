<?php require_once '../get_src.php';

    $user_id = $_COOKIE['user'] ?? null;
    if ($user_id) {
        //
    } else {
        //
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Job - Компания</title>
    <?php require '../modules/def_links.php'; ?>
    <link rel="stylesheet" href="css/regStyles.css">
</head>
<body>

    <main>
        <form id="reg-form">
            <h1 class="title">Управление компанией</h1>
            <p class="subtitle">Регистрация</p>

            <div class="inputs">
                <hr>

                <div class="inputs-title">
                    <p>Данные для входа</p>
                </div>

                <div class="input-wrap">
                    <label for="email">Почта</label>
                    <input id="email" name="email" type="email" placeholder="example@mail.ru" required>
                </div>
                <div class="input-wrap">
                    <label for="pass">Придумайте пароль</label>
                    <input id="pass" name="pass" type="password" placeholder="············" required>
                </div>
                <div class="input-wrap">
                    <label for="rep-pass">Повторите пароль</label>
                    <input id="rep-pass" name="rep-pass" type="password" placeholder="············" required>
                </div>

                <hr>

                <div class="inputs-title">
                    <p>О компании</p>
                </div>

                <div class="input-wrap">
                    <label for="email">Название компании</label>
                    <input id="name" name="name" type="email" placeholder="ООО Сайты" required>
                </div>
                <div class="input-wrap">
                    <label for="short-description">Краткое описание компании</label>
                    <textarea id="short-description" rows="3" placeholder="Кратко опишите вашу компанию (2-3 предложения)"></textarea>
                </div>
                <div class="input-wrap">
                    <label for="full-description">Полное описание компании</label>
                    <textarea id="full-description" rows="10" placeholder="Подробно расскажите о вашей компании"></textarea>
                </div>
            </div>

            <button id="reg-btn">Войти</button>
        </form>

        <div class="link">
            Уэе есть аккаут? Тогда вам
            <a href="index.php">Сюда!</a>
        </div>
    </main>
    
    <?php require '../modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>