function main() {
    $('#city').select2({
        placeholder: "Выберите город",
        width: '100%',
        dropdownAutoWidth: true,
        language: "ru"
    });

    $('#short-description, #full-description').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const text = $(this).val();
            
            $(this).val(text.substring(0, start) + '\n' + text.substring(end));
            
            this.selectionStart = this.selectionEnd = start + 1;
        }
    });

    $('#reg-form').on("submit", function(event) {
        event.preventDefault();

        // Сохраняем значения сразу в объект
        const formData = {
            email: $('#email').val().trim(),
            pass: $('#pass').val(),
            repPass: $('#rep-pass').val(),
            name: $('#name').val().trim(),
            city: $('#city').val(),
            shortDescription: $('#short-description').val().trim().replace(/\n/g, '<br><br>'),
            fullDescription: $('#full-description').val().trim().replace(/\n/g, '<br><br>')
        };

        callApi(
            'reg.php',
            formData,
            function(data) {
                const notif = new Notification();
                notif.settings['messages'] = [
                    "Регистрация прошла успешно, осталось войти!",
                    "Компания с такой почтой уже существует",
                    "Ошибка регистрации, попробуйте позже"
                ];

                if (typeof data === "string") {
                    try { data = JSON.parse(data); } catch (e) {}
                }
                
                notif.create(data.code);
                notif.show();
            }
        );
    });
}

$(document).ready(main());