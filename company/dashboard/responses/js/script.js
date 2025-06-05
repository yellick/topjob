function main() {
    updateApplications();
    
    $('#order-status').change(updateApplications);
    $('#select-vacancy').change(updateApplications);

    // Обработчик закрытия модального окна
    $('#modal_wrap').click(function(e) {
        if ($(e.target).closest('.modal').length === 0) {
            closeModal();
        }
    });
}

function updateApplications() {
    const selectedValues = {
        vacancy_id: $('#select-vacancy').val(),
        status: $('#order-status').val()
    };

    $.ajax({
        url: 'php/get_orders.php',
        type: 'GET',
        dataType: 'json',
        data: selectedValues,
        success: function(response) {
            if (response.status) {
                renderApplications(response.data);
            } else {
                showError(response.message);
            }
        },
        error: function(xhr, status, error) {
            showError('Ошибка при загрузке откликов: ' + error);
        }
    });
}

function renderApplications(applications) {
    const $container = $('.applications-container');
    $container.empty();
    
    if (applications.length === 0) {
        $container.append('<div class="order empty">Нет откликов по выбранным критериям</div>');
        return;
    }
    
    applications.forEach(app => {
        const statusClass = getStatusClass(app.status);
        const statusText = getStatusText(app.status);
        const formattedDate = formatDate(app.create_date);
        const initials = getInitials(app.user_name);
         
        const $card = $(`
            <article class="application-card" data-id="${app.id}">
                <header class="application-header">
                    <div class="application-meta">
                        <h3 class="application-title" title="${app.vacancy_name}">${app.vacancy_name}</h3>
                        <time class="application-date">${formattedDate}</time>
                    </div>
                    <div class="application-status ${statusClass}">
                        <span class="status-icon"></span>
                        <span class="status-text">${statusText}</span>
                    </div>
                </header>
                
                <div class="application-body">
                    <div class="applicant-info">
                        <div class="applicant-avatar">
                            <div class="avatar-placeholder">${initials}</div>
                        </div>
                        <div class="applicant-details">
                            <h4 class="applicant-name">${app.user_name}</h4>
                        </div>
                    </div>
                    
                    <div class="application-actions">
                        <button class="action-btn view-resume" 
                                data-applicant-id="${app.u_id}"  
                                data-vacancy-id="${app.vacancy_id}" 
                                data-application-id="${app.id}">
                                
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                            </svg>
                            Резюме
                        </button>
                    </div>
                </div>
            </article>
        `);
        
        $container.append($card);
    });

    // Навешиваем обработчики на кнопки просмотра резюме
    $('.view-resume').click(function() {
        const applicantId = $(this).data('applicant-id');
        const vacancyId = $(this).data('vacancy-id');
        const applicationId = $(this).data('application-id');
        
        openModal(applicantId, vacancyId, applicationId);
    });
}

function getStatusClass(status) {
    switch(status) {
        case 0: return 'status-pending';
        case 1: return 'status-accepted';
        case 2: return 'status-rejected';
        default: return 'status-pending';
    }
}

function getStatusText(status) {
    switch(status) {
        case 0: return 'На рассмотрении';
        case 1: return 'Собеседование';
        case 2: return 'Отклонена';
        default: return 'На рассмотрении';
    }
}

function formatDate(dateString) {
    const months = [
        'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
        'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
    ];
    
    const parts = dateString.split('.');
    if (parts.length === 3) {
        const day = parts[0];
        const monthIndex = parseInt(parts[1]) - 1;
        const year = parts[2];
        
        if (monthIndex >= 0 && monthIndex < 12) {
            return `${day} ${months[monthIndex]} ${year}`;
        }
    }
    return dateString;
}

function getInitials(fullName) {
    const parts = fullName.split(' ');
    let initials = '';
    
    if (parts.length > 0) initials += parts[0][0];
    if (parts.length > 1) initials += parts[1][0];
    
    return initials.toUpperCase();
}

function openModal(applicantId, vacancyId, applicationId) {
    // Показываем загрузку
    $('#modal_wrap').html(`
        <div class="modal">
            <div class="modal-content">
                <div class="loading-spinner">
                    <div class="spinner"></div>
                    <p>Загрузка данных...</p>
                </div>
            </div>
        </div>
    `).css('display', 'flex');

    // Загружаем данные
    $.ajax({
        url: 'php/get_applicant_data.php',
        type: 'GET',
        dataType: 'json',
        data: { 
            applicant_id: applicantId,
            vacancy_id: vacancyId
        },
        success: function(response) {
            if (response.status) {
                response.data.applicationId = applicationId;
                renderModalContent(response.data);
            } else {
                showModalError(response.message);
            }
        },
        error: function() {
            showModalError('Ошибка при загрузке данных');
        }
    });
}

function renderModalContent(data) {
    const experienceList = data.experience.map(exp => `<li>${exp}</li>`).join('');
    const skillsList = data.skills.map(skill => `<li>${skill}</li>`).join('');
    
    $('#modal_wrap').html(`
        <div class="modal">
            <div class="modal-content">
                <div class="vacancy">
                    <div class="title">${data.vacancy_name}</div>
                    <div class="salary">${data.salary} рублей</div>
                    <button class="modal-close-btn">&times;</button>
                </div>

                <div class="profile">
                    <div class="title">Соискатель</div>

                    <div class="personal-data">
                        <div id="name">${data.user_name}</div>
                        <div class="birth-date">${data.birth_date}</div>
                        <div class="contacts">
                            <a href="mailto:${data.email}" class="email">${data.email}</a>
                            <a href="tel:${data.phone}" class="phone">${data.phone}</a>
                        </div>
                    </div>

                    <div class="resume">
                        <p class="subtitle">О себе</p>
                        <div class="about">
                            <p>${data.about || 'Нет информации'}</p>
                        </div>

                        <p class="subtitle">Опыт работы</p>
                        <div class="experience">
                            <ul>${experienceList || '<li>Нет информации</li>'}</ul>
                        </div>

                        <p class="subtitle">Навыки</p>
                        <div class="skills">
                            <ul>${skillsList || '<li>Нет информации</li>'}</ul>
                        </div>
                    </div>
                </div>
                <div class="actions">
                    <button id="apply">Принять</button>
                    <button id="deny">Отказать</button>
                </div>
            </div>
        </div>
    `).css('display', 'flex');

    $('#apply').click(function() {
        if (confirm('Подтвердите действие')) {
            applyApplicant(data.applicationId);
        }
    });

    $('#deny').click(function() {
        if (confirm('Вы уверены, что хотите отказать соискателю?')) {
            denyApplicant(data.applicationId);
        }
    });

    $('.modal-close-btn').click(closeModal);
}

function applyApplicant(id) {
    $.ajax({
        url: 'php/apply_applicant.php',
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        success: function(response) {
            closeModal();
            updateApplications();
            
            const notif = new Notification();
            notif.settings['messages'] = [
                response.notification_msg,
                "Не указан ID отклика",
                "Ошибка при обработке заявки",
                "Требуется авторизация компании"
            ];
            notif.create(response.code);
            notif.show();
        }
    });
}

function denyApplicant(id) {
    $.ajax({
        url: 'php/deny_applicant.php',
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        success: function(data) {
            const notif = new Notification();
            notif.settings['messages'] = [
                "Соискателю отправлен отказ",
                "Не указан ID отклика",
                "Ошибка при обработке отказа",
                "Требуется авторизация компании",
                "Произошла непредвиденная ошибка"
            ];
            notif.create(data.code);
            notif.show();

            closeModal();
            updateApplications();
        }
    });
}

function showModalError(message) {
    $('#modal_wrap').html(`
        <div class="modal">
            <div class="modal-content">
                <div class="error-message">
                    <svg width="48" height="48" fill="#d32f2f" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    <p>${message}</p>
                    <button class="modal-close-btn">Закрыть</button>
                </div>
            </div>
        </div>
    `).css('display', 'flex');

    $('.modal-close-btn').click(closeModal);
}

function closeModal() {
    $("#apply").off("click")
    $("#deny").off("click")
    $('#modal_wrap').css('display', 'none');
}

function showError(message) {
    const $container = $('.applications-container');
    $container.empty();
    $container.append(`<div class="order error">${message}</div>`);
}

$(document).ready(main);