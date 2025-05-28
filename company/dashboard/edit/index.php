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
    <title>Top Job - Редактирование компании</title>
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
            <form id="edit-form">
                <h2>Редактирование профиля компании</h2>
                
                <div class="inputs">
                    <div class="input-wrap">
                        <label for="email">Почта</label>
                        <input id="email" name="email" type="email" value="<?= htmlspecialchars($comp_email) ?>" required>
                    </div>

                    <div class="input-wrap">
                        <label for="name">Название компании</label>
                        <input id="name" name="name" type="text" value="<?= htmlspecialchars($comp_name) ?>" required>
                    </div>
                    
                    <div class="input-wrap">
                        <label for="city">Город</label>
                        <select id="city" name="city" class="city-select">
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= htmlspecialchars($city['id']) ?>"
                                    <?= ($city['id'] == $result["city_id"]) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($city['city_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="input-wrap">
                        <label for="short-description">Краткое описание компании</label>
                        <textarea id="short-description" rows="3" required><?= str_replace('<br>', "\n", $comp_s_desc) ?></textarea>
                    </div>
                    
                    <div class="input-wrap">
                        <label for="full-description">Полное описание компании</label>
                        <textarea id="full-description" rows="10" required><?= str_replace('<br>', "\n", $comp_f_desc) ?></textarea>
                    </div>
                </div>

                <button type="submit" id="save-btn">Сохранить изменения</button>
            </form>
        </div>
    </main>
    
    <?php require '../../../modules/def_scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>