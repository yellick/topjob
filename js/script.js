function main(){
    searchBtn = $('#form-submit');
    
    searchBtn.on("click", () => {
        window.location.href = "vacancies/"
        return;
    })
}

$(document).ready(main());