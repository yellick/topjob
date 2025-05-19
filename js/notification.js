class Notification {   
    
    constructor () {
        this.settings = {
            'msg_code': 0,
            'messages': [],
            'notifTime': 5000,
            
            // styles
            'notification': [
                ['width', '400px'],
                ['height', '100px'],
                ['background', '#fff'],
                ['box-shadow', '2px 3px 9px 2px rgba(34, 60, 80, 0.2)'],
                ['border', '1px solid rgba(34, 60, 80, 0.2)'],
                ['border-radius', '5px'],
                ['position', 'fixed'],
                ['top', '-200px'],
                ['left', 'calc(50% - 200px)'],
                ['font-family', 'sans-serif'],
                ['display', 'flex'],
                ['justify-content', 'center'],
                ['align-items', 'center'],
                ['box-sizing', 'border-box'],
                ['padding', '0 20px'],
                ['transition', '0.5s'],
                ['cursor', 'pointer'],
                ['z-index', '10'],
            ],
            
            'notification_icon': [
                ['font-size', '50px'],
                ['margin-right', '20px'],
            ],
            
            'notification_paragraph': [
                ['text-align', 'center'],
                ['color', '#787878'],
                ['font-size', '14px'],
                ['letter-spacing', '1px'],
                ['font-weight', '600'],
            ],
        }        
    }
    
    create(msg_code) {
        $("#notification").remove();


        $("body").append($('<div>', {
            'id': 'notification',
        }));
        
        $("#notification").append($('<i>', {
            'id': 'notification_icon',
            'class': 'fa'
        }));
        
        $("#notification").append($('<p>', {
            'id': 'notification_message',
        }));
        
        // set css
        $("body").css('position', 'relative');
        
        $.each(this.settings['notification'], function(index,value) {
            $("#notification").css(value[0], value[1]);
        });
        
        $.each(this.settings['notification_icon'], function(index,value) {
            $("#notification_icon").css(value[0], value[1]);
        });
        
        $.each(this.settings['notification_paragraph'], function(index,value) {
            $("#notification_message").css(value[0], value[1]);
        });
        
        
        $("#notification").on("click", () => { this.delete() })
        
        this.update(msg_code);
    }
    
    update(msg_code){
        
        this.settings['msg_code'] = msg_code;
            
        $("#notification_message").text(this.settings['messages'][msg_code]);
        
        if (!msg_code){
            
            $("#notification_icon").removeClass("fa-times");
            $("#notification_icon").addClass('fa-check');
            $("#notification_icon").css('color', '#8cff98');
            
        } else {
            
            $("#notification_icon").removeClass("fa-check");
            $("#notification_icon").addClass('fa-times');
            $("#notification_icon").css('color', '#ff9191');
            
        }
    }
    
    show() {
        setTimeout(() => {
            $("#notification").css("top", "30px");
        
        }, 300 );

        setTimeout(() => {
            this.delete();
        }, this.settings['notifTime']);
    }
    
    delete() {
        $("#notification").css("top", "-200px");

        setTimeout(() => {
            
            $("#notification").remove();
            delete this;
        
        }, 500 );
    }
}