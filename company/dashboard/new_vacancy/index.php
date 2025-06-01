<?php
    require_once '../../../get_src.php';
    require_once '../../../php/db_conn.php';
    require_once '../../../php/get_cities.php';

    $company_id = $_COOKIE['company_user'] ?? null;
    if (!$company_id) {
        header("Location: ../../");
        exit();
    }

    $stmt = $db->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!($result && $result->num_rows > 0)) {
        header("Location: ../../");
        exit();
    }

    $result = $result->fetch_assoc();

    $comp_name = $result["name"];
    $comp_s_desc = $result["short_desc"];
    $comp_f_desc = $result["full_desc"];
    $comp_email = $result["email"];

    $city_name = '';
    foreach ($cities as $city) {
        if ($city['id'] == ($result["city_id"] ?? $result["city_id"])) {
            $city_name = $city['city_name'];
            break;
        }
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Job - Создание вакансии</title>
    <?php require '../../../modules/def_links.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Управление компанией</h1>
        </div>

        <a class="go-back" href="../">Назад</a>
    </header>

    <main>
        <div class="content">
            <section id="vacancy-edit">
                <h1>Создание вакансии</h1>
                
                <form id="edit-form">
                    <!-- Основные поля -->
                    <div class="form-section">
                        <h2>Основная информация</h2>
                        <div class="form-group">
                            <label for="vacancy-title">Название вакансии*</label>
                            <input type="text" id="vacancy-title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="salary">Зарплата</label>
                            <input type="text" id="salary" name="salary" placeholder="Например: 100 000 руб.">
                        </div>
                    </div>
                    
                    <!-- Описание -->
                    <div class="form-section">
                        <h2>Описание вакансии</h2>
                        <div class="form-group">
                            <textarea id="description" name="description" rows="5" placeholder="Подробное описание вакансии"></textarea>
                        </div>
                    </div>
                    
                    <!-- Обязанности -->
                    <div class="form-section">
                        <h2>Обязанности</h2>
                        <div id="responsibilities-list">
                            <div class="list-item">
                                <input type="text" name="responsibilities[]" placeholder="Например: Разработка новых функций">
                                <button type="button" class="remove-btn">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-responsibility" class="add-btn">+ Добавить обязанность</button>
                    </div>
                    
                    <!-- Требования -->
                    <div class="form-section">
                        <h2>Требования</h2>
                        <div id="requirements-list">
                            <div class="list-item">
                                <input type="text" name="requirements[]" placeholder="Например: Опыт работы от 1 года">
                                <button type="button" class="remove-btn">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-requirement" class="add-btn">+ Добавить требование</button>
                    </div>
                    
                    <!-- Условия -->
                    <div class="form-section">
                        <h2>Условия</h2>
                        <div id="conditions-list">
                            <div class="list-item">
                                <input type="text" name="conditions[]" placeholder="Например: Гибкий график">
                                <button type="button" class="remove-btn">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-condition" class="add-btn">+ Добавить условие</button>
                    </div>
                    
                    <div class="form-actions">
                        <a href="../" class="cancel-btn">Отмена</a>
                        <button type="submit" id="save-btn">Опубликовать вакансию</button>
                    </div>
                </form>
            </section>
        </div>
    </main>
    
    <?php require '../../../modules/def_scripts.php'; ?>
    <script src="js\script.js"></script>
</body>
</html>