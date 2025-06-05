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

    if (!empty($_GET['vacancy'])) {
        $vacancy = $_GET['vacancy'];
        $vacancy = filter_var($vacancy, FILTER_VALIDATE_INT);
        if ($vacancy !== false && $vacancy > 0) {
            try {
                $stmt = $db->prepare("SELECT vacancies.id as id, 
                                             vacancies.vacancy_name as name, 
                                             vacancies.status as status, 
                                             vacancies.salary as salary, 
                                             vacancies.description as description, 
                                             vacancies.responsibilities as responsibilities, 
                                             vacancies.requirements as requirements, 
                                             vacancies.conditions as conditions
                                    FROM `vacancies`
                                    WHERE vacancies.id = ?");

                $stmt->bind_param("i", $vacancy);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $vacancy = $result->fetch_assoc();

                    $v_id = $vacancy['id'];
                    $v_name = $vacancy['name'];
                    $v_status = $vacancy['status'];
                    $v_description = $vacancy['description'];
                    $v_salary = $vacancy['salary'];
                    $v_responsibilities = json_decode($vacancy['responsibilities'] ?? '[]', true) ?: [];
                    $v_requirements = json_decode($vacancy['requirements'] ?? '[]', true) ?: [];
                    $v_conditions = json_decode($vacancy['conditions'] ?? '[]', true) ?: [];
                } else {
                    header("Location: " . BASE_URL);
                    exit();
                }
            } catch (Exception $e) {
                header("Location: " . BASE_URL);
                exit();
            }
        } else {
            header("Location: " . BASE_URL);
            exit();
        }
    } else {
        header("Location: " . BASE_URL);
        exit();
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Job - Изменение вакансии</title>
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
                <h1>Изменение вакансии</h1>
                
                <form id="edit-form">
                    <input type="hidden" id="vacancy-id" name="vacancy_id" value="<?= htmlspecialchars($v_id ?? '') ?>">
                    <!-- Основные поля -->
                    <div class="form-section">
                        <h2>Основная информация</h2>
                        <div class="form-group">
                            <label for="vacancy-title">Название вакансии*</label>
                            <input type="text" id="vacancy-title" name="title" required value="<?= htmlspecialchars($v_name ?? '') ?>">
                            <br>
                            <br>
                            <br>
                            <select name="vacancy_status" id="vacancy-status">
                                <?php
                                    $statuses = [
                                        "Закрыта (не отображается в поиске, кандидат уже найден)",
                                        "Активна (отображается в поиске)",
                                        "Заморожена (не отображается в поиске, кандидат не найден)"
                                    ];
                                    $code = 0;

                                    foreach ($statuses as $el) {
                                        if ($code == $v_status) {
                                            echo " <option value=" . $code . " selected>" . $el ."</option>";
                                        } else {
                                            echo " <option value=" . $code . ">" . $el ."</option>";
                                        }
                                        $code = $code + 1;
                                    }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="salary">Зарплата</label>
                            <input type="text" id="salary" name="salary" placeholder="Например: 100 000 руб." value="<?= htmlspecialchars($v_salary ?? '') ?>">
                        </div>
                    </div>
                    
                    <!-- Описание -->
                    <div class="form-section">
                        <h2>Описание вакансии</h2>
                        <div class="form-group">
                            <textarea id="description" name="description" rows="5" placeholder="Подробное описание вакансии"><?=str_replace('<br>', "\n", $v_description ?? '')?></textarea>
                        </div>
                    </div>
                    
                    <!-- Обязанности -->
                    <div class="form-section">
                        <h2>Обязанности</h2>
                        <div id="responsibilities-list">
                            <?php foreach ($v_responsibilities as $el): ?>
                                <div class="list-item">
                                    <input type="text" name="responsibilities[]" placeholder="Например: Разработка новых функций" value="<?= htmlspecialchars($el) ?>">
                                    <button type="button" class="remove-btn">×</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-responsibility" class="add-btn">+ Добавить обязанность</button>
                    </div>
                    
                    <!-- Требования -->
                    <div class="form-section">
                        <h2>Требования</h2>
                        <div id="requirements-list">
                            <?php foreach ($v_requirements as $el): ?>
                                <div class="list-item">
                                    <input type="text" name="requirements[]" placeholder="Например: Опыт работы от 1 года" value="<?= htmlspecialchars($el) ?>">
                                    <button type="button" class="remove-btn">×</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-requirement" class="add-btn">+ Добавить требование</button>
                    </div>
                    
                    <!-- Условия -->
                    <div class="form-section">
                        <h2>Условия</h2>
                        <div id="conditions-list">
                            <?php foreach ($v_conditions as $el): ?>
                                <div class="list-item">
                                    <input type="text" name="conditions[]" placeholder="Например: Гибкий график" value="<?= htmlspecialchars($el) ?>">
                                    <button type="button" class="remove-btn">×</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-condition" class="add-btn">+ Добавить условие</button>
                    </div>
                    
                    <div class="form-actions">
                        <a href="../" class="cancel-btn">Отмена</a>
                        <button type="submit" id="save-btn">Сохранить изменения вакансию</button>
                    </div>
                </form>
            </section>
        </div>
    </main>
    
    <?php require '../../../modules/def_scripts.php'; ?>
    <script src="js\script.js"></script>
</body>
</html>