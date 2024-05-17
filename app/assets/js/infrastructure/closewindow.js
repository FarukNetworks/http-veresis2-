
/*
var validNavigation = false;

function wireUpEvents() {
    var dont_confirm_leave = 0; 
    var leave_message = "";
    
    function goodbye(e) {
        
        var cartCounter = $(".cartCounter").text();
        leave_message = "Število znakov na seznamu: " + cartCounter + ". Ali ste prepričani, da želite zapustiti to stran ?"
                
        if (!validNavigation && cartCounter != '0') {
            if (dont_confirm_leave !== 1) {
                if (!e) e = window.event;
                e.cancelBubble = true;
                e.returnValue = leave_message;
                //e.stopPropagation works in Firefox.
                if (e.stopPropagation) {
                    e.stopPropagation();
                    e.preventDefault();
                }
       
                //return works for Chrome and Safari
                return leave_message;
            }
        }
    }
    
    function endsession() {
        console.log(window.location.href);

        if (!validNavigation) {
            $.ajax({
                type: 'POST',
                async: false,
                url: siteUrl + '/userclosewindow',
                success: function ()
                {
                    validNavigation = true;
                }
            });
        }        
    }

    window.onclose = function () {
        console.log("test");
    };

    window.onbeforeunload = goodbye;
    window.onunload = endsession;
    

    document.onkeydown = function () {
        switch (event.keyCode || e.which) {
            case 116 : //F5 button
            validNavigation = true;
            case 114 : //F5 button
            validNavigation = true;
            case 82 : //R button
            if (event.ctrlKey) {
            validNavigation = true;
            }
            case 13 : //Press enter
            validNavigation = true;
        }
    }
    // Attach the event click for all links in the page
    $("a").bind("click", function () {
    validNavigation = true;
    });
    
    $("button").bind("click", function (e) {
        //e.preventDefault();
        validNavigation = true;
    });
    
    // Attach the event submit for all forms in the page
    $("form").bind("submit", function () {
    validNavigation = true;
    });
    
    // Attach the event click for all inputs in the page
    $("input[type=submit]").bind("click", function () {
    validNavigation = true;
    });
}

// Wire up the events as soon as the DOM tree is ready
$(document).ready(function () {

    wireUpEvents();
});*/
