function main() {
    checkPage();
    
    if (!checkPage()) { 
        $('header .profile').remove();
    } else {
        let signInBtn = $("#signin-btn"),
            modal = $("#modal-login"),

            showAuthBtn = $("#show-auth"),
            showRegBtn = $("#show-reg"),
            authForm = $("#auth-form"),
            regForm = $("#reg-form"),

            body = $("body");


        signInBtn.on('click', () => {
            modal.css({ 
                'display': 'flex',
            })
            body.css({ 
                'overflow-y': 'hidden',
            })
        })

        modal.on('click', function(event) {
            if ($(event.target).closest('.modal').length === 0) {
                modal.css({
                    'display': 'none',
                });
                body.css({ 
                    'overflow-y': 'scroll',
                })
            }
            return;
        });

        showAuthBtn.on("click", () => {
            if (!(showAuthBtn.hasClass("active"))) {

                showRegBtn.removeClass("active");
                showAuthBtn.addClass("active");
                
                regForm.css({ 
                    'display': 'none',
                })
                authForm.css({
                    'display': 'block',
                })
            }
            return;
        })

        showRegBtn.on("click", () => {
            if (!(showRegBtn.hasClass("active"))) {

                showAuthBtn.removeClass("active");
                showRegBtn.addClass("active");
                
                authForm.css({
                    'display': 'none',
                })
                regForm.css({
                    'display': 'block',
                })
            }
            return;
        })


        authForm.on("submit", function(event) {
            event.preventDefault();
        
            callApi(
                'auth.php', 
                {
                    email: $("#login-email").val(),
                    password: $("#login-password").val()
                },
                function(data) {
                    if (data.status) {
                        window.location.reload();
                    } else {
                        showNotification(data.code, [
                            "Авторизация прошла успешно",
                            "Неверный email или пароль",
                            "Ошибка авторизации"
                        ]);
                    }
                }
            );
        });

        regForm.on("submit", function(event) {
            event.preventDefault();

            callApi(
                'reg.php',
                {
                    name: $("#reg-name").val(),
                    email: $("#reg-email").val(),
                    pass: $("#reg-password").val()
                },
                function(data) {
                    const notif = new Notification();
                    notif.settings['messages'] = [
                        "Регистрация прошла успешно",
                        "Пользователь с такой почтой уже существует",
                        "Ошибка регистрации, попробуйте позже"
                    ];

                    if (typeof data === "string") {
                        try { data = JSON.parse(data); } catch (e) {}
                    }

                    if (data.status) {
                        window.location.href = "profile/edit/";
                    } else {
                        notif.create(data.code);
                    }
                    notif.show();
                }
            );
        });
    }
}

function checkPage(){
    forbiddenPages = [
        'profile',
        'profile_edit',
        'company'
    ]

    const checker = UrlCheck.checkCurrentUrl(forbiddenPages);
    if (!checker) {
        return false
    }

    return true;
}





$(document).ready(main());