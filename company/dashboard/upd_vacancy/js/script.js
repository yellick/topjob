$(document).ready(function() {
    // Добавление новой обязанности
    $('#add-responsibility').click(function() {
        const newItem = $(`
            <div class="list-item">
                <input type="text" name="responsibilities[]" placeholder="Например: Разработка новых функций">
                <button type="button" class="remove-btn">×</button>
            </div>
        `);
        
        $('#responsibilities-list').append(newItem);
        newItem.find('input').focus();
    });

    // Добавление нового требования
    $('#add-requirement').click(function() {
        const newItem = $(`
            <div class="list-item">
                <input type="text" name="requirements[]" placeholder="Например: Опыт работы от 1 года">
                <button type="button" class="remove-btn">×</button>
            </div>
        `);
        
        $('#requirements-list').append(newItem);
        newItem.find('input').focus();
    });

    // Добавление нового условия
    $('#add-condition').click(function() {
        const newItem = $(`
            <div class="list-item">
                <input type="text" name="conditions[]" placeholder="Например: Гибкий график">
                <button type="button" class="remove-btn">×</button>
            </div>
        `);
        
        $('#conditions-list').append(newItem);
        newItem.find('input').focus();
    });

    // Удаление поля (обязанности/требования/условия)
    $(document).on('click', '.remove-btn', function() {
        const list = $(this).closest('#responsibilities-list, #requirements-list, #conditions-list');
        const items = list.find('.list-item');
        
        if (items.length > 1) {
            $(this).parent().remove();
        } else {
            // Если последнее поле - очищаем его вместо удаления
            items.find('input').val('');
        }
    });

    // Обработка отправки формы
    $('#edit-form').on('submit', function(e) {
        e.preventDefault();
    
        submit_form()
    });
});

function submit_form() {
    // Подготовка уведомления
    const notif = new Notification();
    notif.settings['messages'] = [
        "Вакансия успешно обновлена",         // code 0 - успех
        "Название вакансии обязательно",      // code 1 - ошибка валидации
        "Ошибка при обновлении вакансии",     // code 2 - ошибка сервера
        "Неверный формат данных",             // code 3 - ошибка JSON
        "Компания не авторизована"            // code 4 - ошибка авторизации
    ];

    // Валидация обязательных полей
    if (!$('#vacancy-title').val().trim()) {
        notif.create(1);
        notif.show();
        $('#vacancy-title').focus();
        return;
    }

    // Сбор данных обязанностей
    const responsibilities = [];
    $('#responsibilities-list input[type="text"]').each(function() {
        const value = $(this).val().trim();
        if (value) responsibilities.push(value);
    });
        
    // Сбор данных требований
    const requirements = [];
    $('#requirements-list input[type="text"]').each(function() {
        const value = $(this).val().trim();
        if (value) requirements.push(value);
    });
        
    // Сбор данных условий
    const conditions = [];
    $('#conditions-list input[type="text"]').each(function() {
        const value = $(this).val().trim();
        if (value) conditions.push(value);
    });
    
    // Подготовка данных для отправки
    const formData = {
        id: $('#vacancy-id').val(),
        status: $('#vacancy-status').val(),
        title: $('#vacancy-title').val().trim(),
        salary: $('#salary').val().trim(),
        description: $('#description').val().trim(),
        responsibilities: JSON.stringify(responsibilities),
        requirements: JSON.stringify(requirements),
        conditions: JSON.stringify(conditions)
    };

    console.log(formData)
    
    
    // Отправка данных на сервер
    callApi(
        'upd_vacancy.php',
        formData,
        function(response) {
            if (response.status) {
                // Успешное создание
                notif.create(0);
                notif.show();
                
                window.open('../../../vacancy/?vacancy=' + formData['id'], '_blank');
                window.location.href = "../";
            } else {
                // Ошибка с сервера
                notif.settings['messages'][2] = response.message || notif.settings['messages'][2];
                notif.create(response.code || 2);
                notif.show();
               
                // Для ошибки авторизации - перенаправляем
                if (response.code === 4) {
                    setTimeout(() => {
                        window.location.href = "../../";
                    }, 2000);
                }
            }
        }
    );
}