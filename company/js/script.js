$(document).ready(function() {
    $('#short-description, #full-description').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const text = $(this).val();
            
            $(this).val(text.substring(0, start) + '\n' + text.substring(end));
            
            this.selectionStart = this.selectionEnd = start + 1;
        }
    });
});