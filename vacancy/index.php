<?php
    require_once '../get_src.php';
    require_once '../php/db_conn.php';

    if (!empty($_GET['vacancy'])) {
        $vacancy = $_GET['vacancy'];
        $vacancy = filter_var($vacancy, FILTER_VALIDATE_INT);
        if ($vacancy !== false && $vacancy > 0) {
            try {
                $stmt = $db->prepare("SELECT vacancies.id as id, 
                                             vacancies.vacancy_name as name, 
                                             vacancies.salary as salary, 
                                             vacancies.description as description, 
                                             vacancies.responsibilities as responsibilities, 
                                             vacancies.requirements as requirements, 
                                             vacancies.conditions as conditions, 
                                             companies.name as company, 
                                             companies.short_desc as c_s_description, 
                                             companies.full_desc as c_f_description,
                                             cities.city_name as location
                                    FROM `vacancies`
                                        INNER JOIN companies ON vacancies.company_id = companies.id
                                        INNER JOIN cities ON companies.city_id = cities.id
                                    WHERE vacancies.id = ?");

                $stmt->bind_param("i", $vacancy);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $vacancy = $result->fetch_assoc();

                    $v_id = $vacancy['id'];
                    $v_name = $vacancy['name'];
                    $v_description = $vacancy['description'];
                    $v_salary = $vacancy['salary'];
                    $v_responsibilities = json_decode($vacancy['responsibilities'] ?? '[]', true) ?: [];
                    $v_requirements = json_decode($vacancy['requirements'] ?? '[]', true) ?: [];
                    $v_conditions = json_decode($vacancy['conditions'] ?? '[]', true) ?: [];
                    $company = $vacancy['company'];
                    $c_s_descriptions = $vacancy['c_s_description'];
                    $c_f_descriptions = $vacancy['c_f_description'];
                    $location = $vacancy['location'];
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
    <title>Вакансия - <?=$v_name?></title>
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
                    <h1><?=$v_name?></h1>
                </div>

                <div class="vacancy-salary">
                    <p>От <?=$v_salary?> ₽ за месяц, на руки</p>
                </div>

                <div class="vacancy-company">
                    <p><?=$company?></p>
                </div>

                <div class="vacancy-location">
                    <p><?=$location?></p>
                </div>
            </section>

            <section id="vacancy-detail">
                <div class="company-info">
                    <h3 class="vacancy-section-title">Работодатель - <?=$company?></h3>
                    <p><?=$c_f_descriptions?></p>
                </div>

                <div class="company-info">
                    <h3 class="vacancy-section-title">Описание вакансии</h3>
                    <p><?=$v_description?></p>
                </div>

                <div class="vacancy-section">
                    <h3 class="vacancy-section-title">Обязанности:</h3>
                    <ul>
                        <?php foreach ($v_responsibilities as $el): ?>
                            <li><?= htmlspecialchars($el) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="vacancy-section">
                    <h3 class="vacancy-section-title">Требования:</h3>
                    <ul>
                        <?php foreach ($v_requirements as $el): ?>
                            <li><?= htmlspecialchars($el) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="vacancy-section">
                    <h3 class="vacancy-section-title">Условия:</h3>
                    <ul>
                        <?php foreach ($v_conditions as $el): ?>
                            <li><?= htmlspecialchars($el) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        </div>

        <div class="section-column">
            <section id="employer">
                <div class="employer-name"><p><?=$company?></p></div>

                <p class="description">
                    <?=$c_s_descriptions?>
                </p>
            </section>

            <section id="respond-block" class="<?= $is_authenticated ? 'authenticated' : '' ?>">
                <div class="respond-content">
                    <?php
                    if ($is_authenticated) {
                        $stmt = $db->prepare("SELECT * FROM `orders` WHERE u_id = ? AND vacancy_id = ? AND status != 2");
                        $stmt->bind_param("ii", $user_id, $v_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) { ?> 
                            <p class="respond-text">
                                Ваш отклик находится на рассмотрении
                            </p>
                        <?php
                        } else { ?>

                            <div class="respond-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 16V19C22 20.6569 20.6569 22 19 22H5C3.34315 22 2 20.6569 2 19V16M22 16L12 9L2 16M22 16V5C22 3.34315 20.6569 2 19 2H5C3.34315 2 2 3.34315 2 5V16" stroke="#4A83FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <h3 class="respond-title">Откликнуться на вакансию</h3>
                            <p class="respond-text">
                                Заинтересовала вакансия? Оставьте отклик и работодатель свяжется с вами!
                            </p>
                            <button id="<?= $is_authenticated ? 'respond-btn' : 'login-btn' ?>" class="respond-btn" <?= !$is_authenticated ? 'data-modal="auth"' : '' ?>>
                                <?= $is_authenticated ? 'Откликнуться' : 'Войти и откликнуться' ?>
                            </button>
                            <?php if ($is_authenticated): ?>
                                <p class="respond-note">Работодатель увидит отклик и свяжется с вами</p>
                            <?php endif; ?>
                        <?php
                        }
                    }
                    ?>
                    
                </div>
            </section>
        </div>
    </main>

    <?php require '../modules/footer.php'; ?>
    
    <?php require '../modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>