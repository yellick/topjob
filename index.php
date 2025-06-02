<?php
    require_once 'get_src.php';
    require_once 'php/db_conn.php';

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
    <title>Top Job</title>
    <?php require 'modules/def_links.php'; ?>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php require 'modules/modal.php'; ?>
    <?php require 'modules/header.php'; ?>

    <main>
        <div class="main-content">
            <h1>Ищите работу на Top Job!</h1>

            <form id="search-form" action="vacancies/" method="get">
                <input type="text" name="job" placeholder="Вакансия">
                <button id="form-submit">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </form>

            <div class="company_login">
                <span>Ищите соискателей? </span>
                <a href="company/" target="_blank">Тогда вам сюда!</a>
            </div>
        </div>
    </main>
    
    <?php //require 'modules/footer.php'; ?>
    
    <?php require 'modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>