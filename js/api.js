function callApi(endpoint, data, onSuccess) {
    const baseUrl = window.APP_CONFIG?.baseUrl || '';
    
    $.ajax({
        type: "POST",
        url: `${baseUrl}php/${endpoint}`,
        dataType: 'json',
        data: data,
        success: function(response) {
            if (response && typeof response.status !== 'undefined') {
                onSuccess(response);
            } else {
                showNotification(2, ["Ошибка формата ответа"]);
            }
        },
        error: function() {
            showNotification(2, ["Ошибка соединения"]);
        }
    });
}

function showNotification(code, messages) {
    const notif = new Notification();
    notif.settings['messages'] = messages;
    notif.create(code);
    notif.show();
}