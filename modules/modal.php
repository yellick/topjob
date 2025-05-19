<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/modal.css">
</head>
<body>
    <div class="modal-wrap" id="modal-login">
        <div class="modal">
            <div class="modal-content">
                <div class="switcher">
                    <button id="show-auth" class="active">Войти</button>
                    <button id="show-reg">Зарегестрироваться</button>
                </div>

                <div class="form-wrap">
                    <form id="auth-form">
                        <p class="form-title">Войти на Top Job</p>

                        <div class="input-wrap">
                            <label for="email">Почта</label>
                            <input type="email" id="login-email" placeholder="Ivan@mail.ru" required>
                        </div>
                        <div class="input-wrap">
                            <label for="password">Пароль</label>
                            <input type="password" id="login-password" placeholder="······" required>
                        </div>

                        <button class="form-btn" id="login-btn">
                            Войти
                        </button>
                    </form>

                    <form id="reg-form">
                        <p class="form-title">Регистрация на Top Job</p>

                        <div class="input-wrap">
                            <label for="email">Почта *</label>
                            <input type="email" id="reg-email" placeholder="Ваша почта" required>
                        </div>
                        <div class="input-wrap">
                            <label for="password">Пароль *</label>
                            <input type="password" id="reg-password" placeholder="Придумайте пароль" required>
                        </div>
                        <div class="input-wrap">
                            <label for="password">ФИО *</label>
                            <input type="password" id="reg-name" placeholder="Иванов Иван Иванович" required>
                        </div>

                        <button class="form-btn" id="reg-btn">
                            Регистрация
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

        