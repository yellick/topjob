function main() {
    let signInBtn = $("#signin-btn"),
        modal = $("#modal-login"),

        showAuthBtn = $("#show-auth"),
        showRegBtn = $("#show-reg"),
        authForm = $("#auth-form"),
        regForm = $("#reg-form");


    signInBtn.on('click', () => {
        modal.css({ 
            'display': 'flex',
        })
    })

    modal.on('click', function(event) {
        if ($(event.target).closest('.modal').length === 0) {
            modal.css({
                'display': 'none',
            });
        }
        return;
    });

    showAuthBtn.on("click", () => {
        if (!(showAuthBtn.hasClass("active"))) {

            showRegBtn.removeClass("active");
            showAuthBtn.addClass("active");
            
            regForm.css({ 
                'display': 'none',
            })
            authForm.css({
                'display': 'block',
            })
        }
        return;
    })

    showRegBtn.on("click", () => {
        if (!(showRegBtn.hasClass("active"))) {

            showAuthBtn.removeClass("active");
            showRegBtn.addClass("active");
            
            authForm.css({
                'display': 'none',
            })
            regForm.css({
                'display': 'block',
            })
        }
        return;
    })
}



$(document).ready(main());