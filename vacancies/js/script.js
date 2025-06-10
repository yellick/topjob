$(document).ready(function() {
    // Загрузка вакансий при загрузке страницы
    loadVacancies();

    // Инициализация Select2
    $('#city-select').select2({
        placeholder: "Выберите город",
        allowClear: true,
        language: "ru",
        width: '100%',
        dropdownAutoWidth: false,
        dropdownParent: $('#filters .filter:last')
    });

    // Обработчик изменения города
    $('#city-select').on('change', function() {
        loadVacancies();
    });
    
    // Обработчик поиска
    $('#search-btn').click(function() {
        loadVacancies();
    });
    
    // Обработчик фильтров
    $('input[name="amount"]').change(function() {
        loadVacancies();
    });
    
    // Функция загрузки вакансий
    function loadVacancies() {
        const searchText = $('#job-search').val().trim();
        const minSalary = $('input[name="amount"]:checked').val();
        const cityId = $('#city-select').val();
        
        callApi(
            'vacancies_api.php',
            {
                search: searchText,
                amount: minSalary,
                city: cityId
            },
            function(response) {
                console.log('Received response:', response);
                if (response.status) {
                    renderVacancies(response.vacancies);
                } else {
                    const notif = new Notification();
                    notif.settings['messages'] = [
                        "",
                        response.message || "Ошибка при загрузке вакансий",
                        ""
                    ];
                    notif.create(1);
                    notif.show();
                }
            }
        );
    }
    
    // Функция отрисовки вакансий
    function renderVacancies(vacancies) {
        const $vacanciesContainer = $('#vacancies');
        $vacanciesContainer.empty();
        
        if (vacancies.length === 0) {
            $vacanciesContainer.append('<div class="no-vacancies"><p>По вашему запросу нет вакансий</p><span>Попробуйте найти что нибудь другое</span></div>');
            return;
        }
        
        vacancies.forEach(vacancy => {
            const salaryText = vacancy.salary > 0 
                ? `От ${vacancy.salary.toLocaleString()} ₽ за месяц, на руки` 
                : 'Зарплата не указана';
            
            const vacancyElement = $(`
                <a href="../vacancy/?vacancy=${vacancy.id}" class="vacancy">
                    <div class="vacancy-title">
                        <h1 title="${vacancy.title}">${vacancy.title}</h1>
                    </div>
                    <div class="vacancy-salary">
                        <p>${salaryText}</p>
                    </div>
                    <div class="vacancy-company">
                        <p>${vacancy.company}</p>
                    </div>
                    <div class="vacancy-location">
                        <p>${vacancy.location}</p>
                    </div>
                </a>
            `);
            
            $vacanciesContainer.append(vacancyElement);
        });
    }


    $('#filter-header').on('click', function() {
        // Проверяем, что мы на мобильном устройстве
        if ($(window).width() <= 768) {
            const $filters = $('#filters');
            const $content = $('.filter-content');
            const $icon = $(this).find('i');
            
            if ($filters.hasClass('collapsed')) {
                // Разворачиваем
                $filters.removeClass('collapsed');
                $content.slideDown(300, function() {
                    $(this).css('display', ''); // Убираем inline-стиль после анимации
                });
                $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            } else {
                // Сворачиваем
                $filters.addClass('collapsed');
                $content.slideUp(300);
                $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
        }
    });

    // Инициализация состояния
    if ($(window).width() <= 768) {
        $('#filters').addClass('collapsed');
        $('#toggle-filters i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        $('.filter-content').hide();
    }

    $(window).on('resize', function() {
        if ($(window).width() > 768) {
            // На десктопе всегда показываем контент и убираем свернутое состояние
            $('#filters').removeClass('collapsed');
            $('.filter-content').show();
            $('#toggle-filters i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        } else {
            // На мобильных проверяем состояние
            if ($('#filters').hasClass('collapsed')) {
                $('.filter-content').hide();
            } else {
                $('.filter-content').show();
            }
        }
    });
});