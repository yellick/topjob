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


    $stmt = $db->prepare("SELECT COUNT(orders.id) as orders_count FROM orders
                            INNER JOIN vacancies ON orders.vacancy_id = vacancies.id
                            INNER JOIN companies ON vacancies.company_id = companies.id
                          WHERE companies.id = ?");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders_count = $result->fetch_assoc()['orders_count'];
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

        <div class=""></div>

        <a href="responses/">
            Отклики на вакансии

            <?php
                if ($orders_count > 0) {
                    if($orders_count < 10) {
                        echo '<span class="count">'.$orders_count.'</span>';
                    } else {
                        echo '<span class="count">9+</span>';
                    }
                }
            ?>
        </a>

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
                    <div class="table-wrap">
                        <table>
                            <caption>Список вакансий</caption>
                            <thead>
                                <tr>
                                    <th>Вакансия</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $stmt = $db->prepare("SELECT `id`, `vacancy_name`, `status` FROM `vacancies` WHERE company_id = ? ORDER BY status ASC");
                                    $stmt->bind_param("i", $company_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    $statuses = [
                                        '<td style="color: #ff8316;">Активная</td>',
                                        '<td style="color: #00ff1a;">Закрыта</td>',
                                        '<td style="color:rgb(5, 19, 124);">Заморожена</td>'
                                    ];

                                    while ($row = $result->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><?=$row['vacancy_name']?></td>

                                        <?=$statuses[$row['status']]?>
                                        <td>
                                            <a href="upd_vacancy/?vacancy=<?=$row['id']?>">Изменить</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php require '../../modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>