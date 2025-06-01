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
                        <h1>${vacancy.title}</h1>
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
});