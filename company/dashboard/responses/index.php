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
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Job - Отклики</title>
    <?php require '../../../modules/def_links.php'; ?>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/modal.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Управление компанией</h1>
        </div>

        <a class="go-back" href="../">Назад</a>
    </header>

    <section class="modal_wrap" id="modal_wrap">
        
    </section>

    <main>
        <div class="content">
            <div class="title">
                <h1>Отклики на вакансии</h1>
            </div>

            <section class="applications-section">
                <div class="filters">
                    <select name="select-vacancy" id="select-vacancy">
                        <option value="0" selected>Все вакансии</option>
                        <?php
                            $stmt = $db->prepare("SELECT `id`, `vacancy_name` FROM `vacancies` WHERE company_id = ? ");
                            $stmt->bind_param("i", $company_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['id']}'>{$row['vacancy_name']}</option>";
                            }
                        ?>
                    </select>
                    <select name="order-status" id="order-status">
                        <option value="-1" selected>Все статусы</option>
                        <option value="0">Собеседование</option>
                        <option value="1">На рассмотрении</option>
                        <!-- <option value="2">Отклоненные</option> -->
                    </select>
                </div>

                <div class="applications-container"></div>
            </section>
        </div>
    </main>
    
    <?php require '../../../modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>