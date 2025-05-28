function main () {
    $('#logout-btn').on('click', function() {
        if (confirm('Вы действительно хотите выйти?')) {
            callApi(
                'logout.php',
                {},
                function(response) {
                    if (response.status) {
                        location.reload();
                    } else {
                        alert('Ошибка выхода');
                    }
                }
            );
        }
    });
}

$(document).ready(main);