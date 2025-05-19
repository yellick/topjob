<?php
require_once '../get_src.php';
require_once '../php/db_conn.php';
require_once '../php/get_cities.php';

$user_id = $_COOKIE['user'] ?? null;
if (!$user_id) {
    header("Location: " . BASE_URL);
    exit();
}

$user_id = filter_var($user_id, FILTER_VALIDATE_INT);
if ($user_id === false || $user_id <= 0) {
    header("Location: " . BASE_URL);
    exit();
}

try {
    $stmt = $db->prepare("SELECT * FROM `applicants` WHERE `id` = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: " . BASE_URL);
        exit();
    }

    $user = $result->fetch_assoc();
    $experience = json_decode($user['experience_json'] ?? '[]', true) ?: [];
    $skills = json_decode($user['skills_json'] ?? '[]', true) ?: [];
    
    
    $city_name = '';
    foreach ($cities as $city) {
        if ($city['id'] == ($user['city'] ?? $user['city_id'])) {
            $city_name = $city['city_name'];
            break;
        }
    }
    
    // Форматируем даты
    $birth_date = $user['birth_date'] ? date('d.m.Y', strtotime($user['birth_date'])) : '';
    $birth_year = $user['birth_date'] ? date('Y', strtotime($user['birth_date'])) : '';
    $current_year = date('Y');
    $age = $birth_year ? ($current_year - $birth_year) : '';
    
    $reg_date = $user['reg_date'] ? date('d.m.Y', strtotime($user['reg_date'])) : '';
    
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    header("Location: " . BASE_URL . "error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Job - Профиль</title>
    <?php require '../modules/def_links.php'; ?>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php require '../modules/modal.php'; ?>
    <?php require '../modules/header.php'; ?>

    <main>
        <section id="user-profile">
            <div class="profile-actions">
                <a href="edit/" class="edit-button">Редактировать профиль</a>
                <button type="button" class="logout-btn">Выйти</button>
            </div>

            <div class="user-data">
                <p id="user-name"><?= htmlspecialchars($user['user_name'] ?? '') ?></p>
                <?php if ($age && $birth_date): ?>
                    <p id="birth-date"><?= $age ?> лет (<?= $birth_date ?>)</p>
                <?php endif; ?>
                <?php if ($reg_date): ?>
                    <p id="reg-date">На сайте с <?= $reg_date ?></p>
                <?php endif; ?>
                <?php if ($city_name): ?>
                    <p id="user-city"><?= htmlspecialchars($city_name) ?></p>
                <?php endif; ?>
            </div>
            <hr>
            
            <div class="contacts">
                <?php if ($user['email'] ?? ''): ?>
                    <p id="user-email"><?= htmlspecialchars($user['email']) ?></p>
                <?php endif; ?>
                <?php if ($user['phone'] ?? ''): ?>
                    <p id="user-phone"><?= htmlspecialchars($user['phone']) ?></p>
                <?php endif; ?>
            </div>
            
            <div class="resume">
                <?php if ($user['about'] ?? ''): ?>
                    <p class="sect-title">О себе</p>
                    <div class="about-user">
                        <p><?= nl2br(htmlspecialchars($user['about'])) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($experience)): ?>
                    <p class="sect-title">Опыт работы</p>
                    <div class="experience">
                        <?php foreach ($experience as $exp): ?>
                            <p><?= htmlspecialchars($exp) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($skills)): ?>
                    <p class="sect-title">Навыки</p>
                    <div class="skills">
                        <ul>
                            <?php foreach ($skills as $skill): ?>
                                <li><?= htmlspecialchars($skill) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php require '../modules/footer.php'; ?>
    
    <?php require '../modules/def_scripts.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>