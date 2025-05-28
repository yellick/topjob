function main () {
    $('#auth-form').on('submit', function(event) {
        event.preventDefault();

        const formData = {
            email: $('#email').val().trim(),
            password: $('#pass').val()
        };

        if (!formData.email || !formData.password) {
            const notif = new Notification();
            notif.settings['messages'] = ["Пожалуйста, заполните все поля!"];
            notif.create(0);
            notif.show();
            return;
        }

        callApi(
            'auth.php',
            formData,
            function(data) {
                if (typeof data === "string") {
                    try { data = JSON.parse(data); } catch (e) {}
                }
                
                const notif = new Notification();
                notif.settings['messages'] = [
                    "Авторизация успешна",
                    "Неправильный логин или пароль",
                    "Ошибка сервера"
                ];

                if (data.status) {
                    window.location.href = "dashboard/";
                }
                
                notif.create(data.code);
                notif.show();
            }
        );
    });
}

$(document).ready(main);