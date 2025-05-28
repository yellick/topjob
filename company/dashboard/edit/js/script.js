function main() {
    // Инициализация Select2 для выбора города
    $('#city').select2({
        placeholder: "Выберите город",
        width: '100%',
        dropdownAutoWidth: false, // Изменено на false
        language: "ru",
        dropdownParent: $('#edit-form'), // Указываем родительский элемент
        dropdownCssClass: 'select2-dropdown-custom' // Добавляем кастомный класс
    });

    // Обработка переноса строк в textarea
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

    // Обработка отправки формы
    $('#edit-form').on("submit", function(event) {
        event.preventDefault();

        const formData = {
            email: $('#email').val().trim(),
            name: $('#name').val().trim(),
            city: $('#city').val(),
            shortDescription: $('#short-description').val().trim().replace(/\n/g, '<br><br>'),
            fullDescription: $('#full-description').val().trim().replace(/\n/g, '<br><br>')
        };

        callApi(
            'update_company.php',
            formData,
            function(data) {
                const notif = new Notification();
                notif.settings['messages'] = [
                    "Изменения успешно сохранены!",
                    "Ошибка при сохранении изменений",
                    "Ошибка сервера, попробуйте позже"
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

$(document).ready(main);