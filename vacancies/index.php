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
    <title>Top Job - Поиск вакансий</title>
    <?php require '../modules/def_links.php'; ?>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php require '../modules/modal.php'; ?>
    <?php require '../modules/header.php'; ?>

    <main>
        <section id="search">
            <input type="text" id="job-search">
            <button id="search-btn">Найти</button>
        </section>

        <div class="vacancies-wrap">
            <section id="filters">
                <div class="filter">
                    <div class="filter-title">
                        <p>Уровень дохода</p> 
                    </div>
            
                    <div class="filters-list">
                        <ul>
                            <li>
                                <label>
                                    <input type="radio" name="amount" value="0" checked>
                                    <span>не имеет значения</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="amount" value="25000">
                                    <span>от 25 000 ₽</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="amount" value="75000">
                                    <span>от 75 000 ₽</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="amount" value="125000">
                                    <span>от 125 000 ₽</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="amount" value="175000">
                                    <span>от 175 000 ₽</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="amount" value="225000">
                                    <span>от 225 000 ₽</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="amount" value="275000">
                                    <span>от 275 000 ₽</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
    
            <section id="vacancies">
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
            </section>
        </div>
    </main>
    
    <?php require '../modules/footer.php'; ?>

    <?php require '../modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>