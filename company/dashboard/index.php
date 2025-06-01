<?php
    require_once '../../get_src.php';
    require_once '../../php/db_conn.php';
    require_once '../../php/get_cities.php';

    $company_id = $_COOKIE['company_user'] ?? null;
    if (!$company_id) {
        header("Location: ../");
        exit();
    }

    $stmt = $db->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!($result && $result->num_rows > 0)) {
        header("Location: ../");
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
    <title>Top Job - Управление компанией</title>
    <?php require '../../modules/def_links.php'; ?>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Управление компанией</h1>
        </div>

        <button id="logout-btn">
            Выйти
        </button>
    </header>

    <main>
        <div class="content">
            <div class="content-sect">
                <div class="title">
                    <h1><?=$comp_name?></h1>
                </div>
                <hr>
                <div class="details">
                    <div class="city">
                        <?=$city_name?>
                    </div>

                    <div class="email">
                        <?=$comp_email?>
                    </div>
                </div>
            </div>

            <div class="content-sect">
                <a href="edit/" class="link-btn">Редактировать данные</a>
            </div>


            <div class="content-sect">
                <div class="title">
                    <h1>Вакансии</h1>
                </div>

                <div class="vacancies">
                    <a href="new_vacancy/" class="link-btn">Создать вакансию</a>

                    <div class="vacancies-list">
                        <a href="../vacancy/" class="vacancy">
                            <div class="vacancy-title">
                                <h1>Front-end developer</h1>
                            </div>

                            <div class="vacancy-salary">
                                <p>От 50000 ₽ за месяц, на руки</p>
                            </div>

                            <div class="vacancy-company">
                                <p>ООО ПрограмЛаб</p>
                            </div>

                            <div class="vacancy-location">
                                <p>Челябинск</p>
                            </div>
                        </a>
                        
                        <a href="../vacancy/" class="vacancy">
                            <div class="vacancy-title">
                                <h1>Front-end developer</h1>
                            </div>

                            <div class="vacancy-salary">
                                <p>От 50000 ₽ за месяц, на руки</p>
                            </div>

                            <div class="vacancy-company">
                                <p>ООО ПрограмЛаб</p>
                            </div>

                            <div class="vacancy-location">
                                <p>Челябинск</p>
                            </div>
                        </a>
                        
                        <a href="../vacancy/" class="vacancy">
                            <div class="vacancy-title">
                                <h1>Front-end developer</h1>
                            </div>

                            <div class="vacancy-salary">
                                <p>От 50000 ₽ за месяц, на руки</p>
                            </div>

                            <div class="vacancy-company">
                                <p>ООО ПрограмЛаб</p>
                            </div>

                            <div class="vacancy-location">
                                <p>Челябинск</p>
                            </div>
                        </a>
                        
                        <a href="../vacancy/" class="vacancy">
                            <div class="vacancy-title">
                                <h1>Front-end developer</h1>
                            </div>

                            <div class="vacancy-salary">
                                <p>От 50000 ₽ за месяц, на руки</p>
                            </div>

                            <div class="vacancy-company">
                                <p>ООО ПрограмЛаб</p>
                            </div>

                            <div class="vacancy-location">
                                <p>Челябинск</p>
                            </div>
                        </a>
                        
                        <a href="../vacancy/" class="vacancy">
                            <div class="vacancy-title">
                                <h1>Front-end developer</h1>
                            </div>

                            <div class="vacancy-salary">
                                <p>От 50000 ₽ за месяц, на руки</p>
                            </div>

                            <div class="vacancy-company">
                                <p>ООО ПрограмЛаб</p>
                            </div>

                            <div class="vacancy-location">
                                <p>Челябинск</p>
                            </div>
                        </a>
                        
                        <a href="../vacancy/" class="vacancy">
                            <div class="vacancy-title">
                                <h1>Front-end developer</h1>
                            </div>

                            <div class="vacancy-salary">
                                <p>От 50000 ₽ за месяц, на руки</p>
                            </div>

                            <div class="vacancy-company">
                                <p>ООО ПрограмЛаб</p>
                            </div>

                            <div class="vacancy-location">
                                <p>Челябинск</p>
                            </div>
                        </a>
                        
                        <a href="../vacancy/" class="vacancy">
                            <div class="vacancy-title">
                                <h1>Front-end developer</h1>
                            </div>

                            <div class="vacancy-salary">
                                <p>От 50000 ₽ за месяц, на руки</p>
                            </div>

                            <div class="vacancy-company">
                                <p>ООО ПрограмЛаб</p>
                            </div>

                            <div class="vacancy-location">
                                <p>Челябинск</p>
                            </div>
                        </a>
                        
                        <a href="../vacancy/" class="vacancy">
                            <div class="vacancy-title">
                                <h1>Front-end developer</h1>
                            </div>

                            <div class="vacancy-salary">
                                <p>От 50000 ₽ за месяц, на руки</p>
                            </div>

                            <div class="vacancy-company">
                                <p>ООО ПрограмЛаб</p>
                            </div>

                            <div class="vacancy-location">
                                <p>Челябинск</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php require '../../modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>