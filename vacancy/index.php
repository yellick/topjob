<?php
    require_once '../get_src.php';
    require_once '../php/db_conn.php';

    $user_id = $_COOKIE['user'] ?? null;
    $is_authenticated = false;

    if ($user_id) {
        $user_id = filter_var($user_id, FILTER_VALIDATE_INT);
        if ($user_id !== false && $user_id > 0) {
            try {
                $stmt = $db->prepare("SELECT * FROM `applicants` WHERE `id` = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $is_authenticated = true;

                    $name_parts = explode(' ', $user['user_name']);
                    $initials = '';
                    if (isset($name_parts[1])) {
                        $initials .= mb_substr($name_parts[1], 0, 1, 'UTF-8');
                    }
                    if (isset($name_parts[2])) {
                        $initials .= mb_substr($name_parts[2], 0, 1, 'UTF-8');
                    }
                }
            } catch (Exception $e) {
                error_log("Database error: " . $e->getMessage());
                header("Location: " . BASE_URL . "error.php");
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Job - </title>
    <?php require '../modules/def_links.php'; ?>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php require '../modules/modal.php'; ?>
    <?php require '../modules/header.php'; ?>

    <main>
        <div class="section-column">
            <section id="vacancy">
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
            </section>

            <section id="vacancy-detail">
                <h2 class="vacancy-title">Вакансия: Front-end Developer</h2>
    
                <div class="company-info">
                    <h3 class="vacancy-section-title">ООО ПрограмЛаб</h3>
                    <p>Компания ООО ПрограмЛаб основана в Челябинске в 2015 году. Мы специализируемся на разработке современных веб-приложений и цифровых решений для бизнеса.</p>
                    <p>Наша команда объединяет опытных разработчиков, дизайнеров и аналитиков, работающих над созданием качественных IT-продуктов. Мы уделяем особое внимание чистоте кода, производительности и удобству пользователей.</p>
                    <p>В работе используем современные технологии и методологии разработки. Предлагаем комфортные условия труда, гибкий график и возможности для профессионального роста.</p>
                </div>

                <div class="vacancy-section">
                    <h3 class="vacancy-section-title">Обязанности:</h3>
                    <ul>
                        <li>Разработка пользовательских интерфейсов на React/Vue</li>
                        <li>Оптимизация производительности веб-приложений</li>
                        <li>Верстка адаптивных и кросс-браузерных интерфейсов</li>
                        <li>Интеграция с REST API</li>
                        <li>Тестирование и отладка кода</li>
                    </ul>
                </div>
                
                <div class="vacancy-section">
                    <h3 class="vacancy-section-title">Требования:</h3>
                    <ul>
                        <li>Опыт разработки на JavaScript/TypeScript от 2 лет</li>
                        <li>Знание React.js/Vue.js и их экосистем</li>
                        <li>Опыт работы с CSS-препроцессорами (SASS/LESS)</li>
                        <li>Понимание принципов REST API</li>
                        <li>Знание Git и процессов CI/CD</li>
                    </ul>
                </div>
                
                <div class="vacancy-section">
                    <h3 class="vacancy-section-title">Стек технологий:</h3>
                    <ul>
                        <li><strong>Основные:</strong> JavaScript/TypeScript, React/Vue</li>
                        <li><strong>Стилизация:</strong> CSS3, SASS, Tailwind</li>
                        <li><strong>Инструменты:</strong> Webpack, Vite, Git</li>
                        <li><strong>Тестирование:</strong> Jest, Cypress</li>
                    </ul>
                </div>
                
                <div class="vacancy-section">
                    <h3 class="vacancy-section-title">Условия:</h3>
                    <ul>
                        <li>Удаленный или гибридный формат работы</li>
                        <li>Гибкий график</li>
                        <li>Официальное трудоустройство</li>
                        <li>Конкурентная заработная плата (обсуждается индивидуально)</li>
                        <li>Профессиональный рост и обучение</li>
                    </ul>
                </div>
            </section>
        </div>

        <div class="section-column">
            <section id="employer">
                <div class="employer-name">
                    <p>ООО ПрограмЛаб</p>
                </div>

                <p class="description">
                    IT-компания из Челябинска, разрабатывающая 
                    современные веб-приложения и цифровые решения 
                    для бизнеса с 2015 года. Специализируемся на 
                    full-cycle разработке, создавая производительные 
                    и удобные продукты.
                </p>
            </section>

            <section id="respond-block">
                <p class="respond-text">
                    Заинтересовала вакансия?
                    <br>
                    Оставьте отклик и работодатель свяжется с вами!
                </p>
                <button class="respond-btn">Откликнуться</button>
            </section>
        </div>
    </main>

    <?php require '../modules/footer.php'; ?>
    
    <?php require '../modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>