

function CookiesConfNo()

{

    ClearCookies();

    setCookie('veresisCookie', 'disabletrack', 186);        

    jQuery(".cookies").hide();

    //window.location.reload();

}

function CookiesConfYes()

{

    setCookie('veresisCookie', 'enabletrack', 186);

    jQuery(".cookies").hide();

    //window.location.reload();

}

function OpenCookieConfDialog()

{

    //first

    

    

    var testcookie = getCookie('veresisCookie');

    if (!testcookie)

    {

        //setCookie('aoCookie', 'disabletrack', 0);

        jQuery(".cookies").show();

    }

    else

    {

        jQuery(".cookies").hide();

    }   

}



function ClearExistingCookie(key)

{

    date = new Date();

    date.setDate(date.getDate() -1);

	document.cookie = escape(key) + '=; path=/; expires=' + date;

}



function ClearCookies()

{

    //clear google cookies

    var cookies = document.cookie.split('; ');

    //console.log(cookies);

    for (var i = 0; i < cookies.length; i++) {

        var parts = cookies[i].split('=');

        var name = parts[0];

        //console.log(name);

        

        jQuery.removeCookie("__utma", {

            domain: location.host,

            path: '/'

        });

        jQuery.removeCookie("__utmb", {

            domain: location.host,

            path: '/'

        });

        jQuery.removeCookie("__utmc", {

            domain: location.host,

            path: '/'

        });

        jQuery.removeCookie("__utmz", {

            domain: location.host,

            path: '/'

        });

        jQuery.removeCookie("_ga", {

            domain: location.host,

            path: '/'

        });

        jQuery.removeCookie("_gat", {

            domain: location.host,

            path: '/'

        });

        jQuery.removeCookie("_gid", {

            domain: location.host,

            path: '/'

        });

        

        jQuery.removeCookie("__utma", {

            domain: location.host.replace('veresis.', ''),

            path: '/'

        });

        jQuery.removeCookie("__utmb", {

            domain: location.host.replace('veresis.', ''),

            path: '/'

        });

        jQuery.removeCookie("__utmc", {

            domain: location.host.replace('veresis.', ''),

            path: '/'

        });

        jQuery.removeCookie("__utmz", {

            domain: location.host.replace('veresis.', ''),

            path: '/'

        });

        jQuery.removeCookie("_ga", {

            domain: location.host.replace('veresis.', ''),

            path: '/'

        });

        jQuery.removeCookie("_gat", {

            domain: location.host.replace('veresis.', ''),

            path: '/'

        });

        jQuery.removeCookie("_gid", {

            domain: location.host.replace('veresis.', ''),

            path: '/'

        });
	jQuery.removeCookie("_gat_gtag_UA_164334004_2", {
            domain: location.host.replace('veresis.', ''),
            path: '/'
        });   

	}

    /*

    jQuery.removeCookie('__utma', { domain: '.randy.agencija-oskar.si' });

    jQuery.removeCookie('__utmb', { domain: '.randy.agencija-oskar.si' });

    jQuery.removeCookie('__utmc', { domain: '.randy.agencija-oskar.si' });

    jQuery.removeCookie('__utmz', { domain: '.randy.agencija-oskar.si' });*/

}





jQuery(document).ready(function(){

    

    var popbtnClick = false;

    jQuery(".cookies").hide();

    

    // if aoCookie not exists

    OpenCookieConfDialog();

    

    //-- hadling mouse klik event with cookie confirm action to yes

    

    

    //-- handle if use click Se strinjam

    jQuery(".strinjam-se").click(function() {

        CookiesConfYes();

        popbtnClick = true;

    });



    //-- handle if use click Zapri

    jQuery(".zapriCookies").click(function() {

        CookiesConfYes();

        popbtnClick = true;

    });



    //-- handle if use click Se ne strinjam

    jQuery(".se-ne-strinjam").click(function() {

              

        CookiesConfNo();

        window.location.reload(true);

        popbtnClick = true;

    });

    

    //-- on page Piskotni da ne -----------------

    jQuery(".strinjam-seModal").click(function() {

        CookiesConfYes();

        window.location.reload(true);

        

        

    });

    

    jQuery(".se-ne-strinjamModal").click(function() {

        //ClearExistingCookie('aoCookie');

        //ClearCookies();

        CookiesConfNo();

        window.location.reload(true);

    });

    

    // jQuery('body').click(function(){

    //     if (!popbtnClick)

    //     {

    //         var testcookie = getCookie('mebloCookie');

    //         if (testcookie === null || testcookie !="disabletrack")

    //         {

    //             CookiesConfYes();

    //         }

    //     }       

        

    // }); 



});



function setCookie(name,value,days) {

    if (days) {

        var date = new Date();

        date.setTime(date.getTime()+(days*24*60*60*1000));

        var expires = "; expires="+date.toGMTString();

    }

    else var expires = "";

    document.cookie = name+"="+value+expires+"; path=/";

}



function getCookie(name) {

    var nameEQ = name + "=";

    var ca = document.cookie.split(';');

    for(var i=0;i < ca.length;i++) {

        var c = ca[i];

        while (c.charAt(0)==' ') c = c.substring(1,c.length);

        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);

    }

    return null;

}