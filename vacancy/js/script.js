function main() {
    const respondBtn = $("#respond-btn");
    
    respondBtn.on('click', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const vacancyId = urlParams.get('vacancy');
        
        if (!vacancyId) {
            alert('Не удалось определить вакансию');
            return;
        }
        
        $.ajax({
            url: '../php/apply.php',
            type: 'POST',
            dataType: 'json',
            data: {
                vacancy_id: vacancyId
            },
            success: function(response) {
                if (response.status) {
                    // Успешный отклик
                    alert(response.message);
                    // Можно обновить кнопку или показать уведомление
                    respondBtn.text('Отклик отправлен');
                    respondBtn.prop('disabled', true);
                } else {
                    // Ошибка
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Произошла ошибка при отправке отклика: ' + error);
            }
        });
    });
}

$(document).ready(main);