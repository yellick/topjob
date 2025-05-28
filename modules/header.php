<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/header.css">
</head>
<body>
    <script>
    window.APP_CONFIG = {
        baseUrl: '<?= BASE_URL ?>',
        apiPath: 'php/'
    };
    </script>
    <header>
        <a href="<?= BASE_URL ?>/" class="logo">
            <div class="logo-circle">
                <h1>TJ</h1>
            </div>
            <div class="logo-name">
                <h1>Top Job</h1>
            </div>
        </a>

        <div class="profile">
            <?php if ($is_authenticated):?> 
                <a href="<?= BASE_URL ?>profile/" class="user" target="_blank">
                    <p><?=$initials?></p>
                </a>
            <?php else: ?>

                <button id="signin-btn">
                    Войти
                </button>
            <?php endif; ?>
        </div>
    </header>
</body>