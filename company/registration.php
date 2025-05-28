<?php 
    require_once '../get_src.php';
    require_once '../php/db_conn.php';
    require_once '../php/get_cities.php';

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
    <link rel="stylesheet" href="css/regStyles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
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
                    <label for="name">Название компании</label>
                    <input id="name" name="name" type="text" placeholder="ООО Сайты" required>
                </div>
                
                <div class="input-wrap">
                    <label for="city">Город</label>
                    <select id="city" name="city" class="city-select">
                        <option value=""></option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= htmlspecialchars($city['id']) ?>">
                                <?= htmlspecialchars($city['city_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="input-wrap">
                    <label for="short-description">Краткое описание компании</label>
                    <textarea id="short-description" rows="3" placeholder="Кратко опишите вашу компанию (2-3 предложения)" required></textarea>
                </div>
                <div class="input-wrap">
                    <label for="full-description">Полное описание компании</label>
                    <textarea id="full-description" rows="10" placeholder="Подробно расскажите о вашей компании" required></textarea>
                </div>
            </div>

            <button id="reg-btn">Зарегестрироваться</button>
        </form>

        <div class="link">
            Уэе есть аккаут? Тогда вам
            <a href="index.php">Сюда!</a>
        </div>
    </main>
    
    <?php require '../modules/def_scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="js/regScript.js"></script>
    <script>
        
    </script>
</body>
</html>