$(document).ready(function() {
    
    $('.city-select').select2({
        placeholder: "Выберите город",
        width: '100%',
        dropdownAutoWidth: true,
        language: "ru"
    });

    
    $('#about').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const text = $(this).val();
            
            
            $(this).val(text.substring(0, start) + '\n' + text.substring(end));
            
            
            this.selectionStart = this.selectionEnd = start + 1;
        }
    });

    
    $('#add-experience').click(function() {
        $('#experience-list').append(`
            <div class="list-item">
                <input type="text" placeholder="Добавьте опыт работы">
                <button type="button" class="remove-btn">×</button>
            </div>
        `);
    });

    
    $('#add-skill').click(function() {
        $('#skills-list').append(`
            <div class="list-item">
                <input type="text" placeholder="Добавьте навык">
                <button type="button" class="remove-btn">×</button>
            </div>
        `);
    });

    
    $(document).on('click', '.remove-btn', function() {
        if ($(this).closest('#experience-list, #skills-list').find('.list-item').length > 1) {
            $(this).parent().remove();
        }
    });

    
    $('#edit-form').on('submit', function(e) {
        e.preventDefault();
    
        const aboutText = $('#about').val();
        const formattedAbout = aboutText.replace(/\n/g, '<br>');
        

        if (!$('#user-name').val().trim() || !$('#email').val().trim()) {
            showNotification(1, ["Пожалуйста, заполните обязательные поля (ФИО и Email)"]);
            return;
        }
    

        const experience = [];
        const skills = [];
        
        $('#experience-list input[type="text"]').each(function() {
            if ($(this).val().trim()) {
                experience.push($(this).val().trim());
            }
        });
    
        $('#skills-list input[type="text"]').each(function() {
            if ($(this).val().trim()) {
                skills.push($(this).val().trim());
            }
        });
    

        const formData = {
            user_name: $('#user-name').val().trim(),
            birth_date: $('#birth-date').val(),
            city: $('#city').val(),
            email: $('#email').val().trim(),
            phone: $('#phone').val().trim(),
            about: formattedAbout,
            experience_json: JSON.stringify(experience),
            skills_json: JSON.stringify(skills)
        };
    
        console.log(formData)

        callApi(
            'upd_user.php',
            formData,
            function(response) {
                if (response.status) {
                    $('#about').val(aboutText);
                } else {
                    console.error(response.message);
                }
                
                showNotification(response.code, [
                    "Данные успешно сохранены",
                    "Ошибка при сохранении данных",
                    "Ошибка базы данных",
                    "Не авторизован",
                    "Неверный ID пользователя",
                    "Неверные данные"
                ]);
            }
        );
    });
});