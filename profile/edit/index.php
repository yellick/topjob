<?php 
    require_once '../../get_src.php';
    require_once '../../php/db_conn.php';
    require_once '../../php/get_cities.php';
    
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
    <title>Top Job - Редактирование профиля</title>
    <?php require '../../modules/def_links.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php require '../../modules/header.php'; ?>

    <main>
        <section id="profile-edit">
            <h1>Редактирование профиля</h1>
            
            <form id="edit-form">
                <div class="form-section">
                    <h2>Основные данные</h2>
                    <div class="form-group">
                        <label for="user-name">ФИО*</label>
                        <input type="text" id="user-name" value="<?= htmlspecialchars($user['user_name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="birth-date">Дата рождения</label>
                        <input type="date" id="birth-date" value="<?= htmlspecialchars($user['birth_date'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="city">Город</label> 
                        <select id="city" class="city-select">
                            <option value=""></option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= htmlspecialchars($city['id']) ?>" 
                                    <?= ($user['city'] ?? '') == $city['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($city['city_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Контактные данные</h2>
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" id="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>О себе</h2>
                    <div class="form-group">
                        <textarea id="about" rows="5" placeholder="Расскажите о себе"><?=str_replace('<br>', "\n", $user['about'] ?? '')?></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Опыт работы</h2>
                    <div id="experience-list">
                        <?php foreach ($experience as $item): ?>
                            <div class="list-item">
                                <input type="text" value="<?= htmlspecialchars($item) ?>">
                                <button type="button" class="remove-btn">×</button>
                            </div>
                        <?php endforeach; ?>
                        <div class="list-item">
                            <input type="text" placeholder="Опыт работы (3 года работы в Яндекс на позиции Font-end developer)">
                            <button type="button" class="remove-btn">×</button>
                        </div>
                    </div>
                    <button type="button" id="add-experience" class="add-btn">+ Добавить опыт</button>
                </div>
                
                <div class="form-section">
                    <h2>Навыки</h2>
                    <div id="skills-list">
                        <?php foreach ($skills as $skill): ?>
                            <div class="list-item">
                                <input type="text" value="<?= htmlspecialchars($skill) ?>">
                                <button type="button" class="remove-btn">×</button>
                            </div>
                        <?php endforeach; ?>
                        <div class="list-item">
                            <input type="text" placeholder="Навык (Python)">
                            <button type="button" class="remove-btn">×</button>
                        </div>
                    </div>
                    <button type="button" id="add-skill" class="add-btn">+ Добавить навык</button>
                </div>
                <div class="form-actions">
                    <a href="../" class="cancel-btn">Назад</a>
                    <button type="submit" id="save-btn">Сохранить изменения</button>
                </div>
            </form>
        </section>
    </main>
    
    <?php require '../../modules/footer.php'; ?>

    <?php require '../../modules/def_scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>