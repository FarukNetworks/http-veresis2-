$(document).ready(function () {
	
	/** cookies */
	if (!$(".cookies").hasClass("ne-pokazi")) {
		$(".cookies").fadeIn(400);
	}

	/* preloader */
	setTimeout(function(){
		$("#preloader-first").fadeOut(600);
	}, 1600);

	// setTimeout(function(){
	// 	$("#preloader").fadeOut(600);
	// }, 800);

	setTimeout(function(){
		$(".footer").slideDown();
	}, 800);

	/* search */

	$('.navbar-form').submit(function(e) {

		if($('#searchValue').val() == "") {
			e.preventDefault();
		}

	});

	$('#searchValue').keyup(function() {
		if($('#searchValue').val() !== "") {
			$('.searchButton').fadeIn(400);
		}
		if($('#searchValue').val() == "") {
			$('.searchButton').fadeOut(100);
		}
	});

	/* sidebar menu show hide */

	$('.sidebar-button').click(function() {
		$('.sidebar').toggle(1);
		$('.main').toggleClass("toggled");
		$('.footer').toggleClass("toggled");
		$('.sidebar-button').toggleClass("glyphicon-circle-arrow-right");
		$('.sidebar-button').toggleClass("glyphicon-circle-arrow-left");
	});

	$(window).resize(function(){
	// odstrani classe ob širini
		if ($(window).width() > 768) {
			$('.main').removeClass('toggled');
			$('.footer').removeClass('toggled');
			$('.sidebar').css('display','block');

			if ($('.main').hasClass('toggled-main')) {
				$('.main').removeClass('toggled-main');
			}
		}
		if ($(window).width() < 768) {
			$('.sidebar').css('display','none');
			$('.main').addClass('toggled-main');
			$('.sidebar-button').removeClass('glyphicon-circle-arrow-left');
			$('.sidebar-button').addClass('glyphicon-circle-arrow-right');
		}
	});

	$(window).load(function(){
	// odstrani classe ob širini
		if ($(window).width() > 768) {
			if ($('.main').hasClass('toggled-main')) {
				$('.main').removeClass('toggled-main');
			}
		}
		if ($(window).width() < 768) {
			$('.main').addClass('toggled-main');
		}
	});

	// validiraj password
	function validatePassword(password) {
		var pattern = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
		return pattern.test(password);
	}

	// validiraj email
    function isValidEmailAddress(email) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(email);
    }

	function preveriPassword() {
		var password1 = $('#password').val();
		var password2 = $('#password2').val();

		if (password1 !== password2) {
			$('.clientNapisErrorNoMatch').show();
			$('.clientNapisErrorNoMatch').text('Gesli se ne ujemata.');
		} else {
			$('.clientNapisErrorNoMatch').hide();
		}
	}

	/* validacija pozabljenega gesla -> emaila preko mail notifikacija */

	// email)

    // $("#email").change(function() {

    // if (!isValidEmailAddress($('#email').val())) {
    //         $('.clientNapisError').show();
    //         $('.clientNapisError').text('Pravilno vnesite elektronski naslov.');
    //     } else {
    //         $('.clientNapisError').hide();
    //     }
    // });

    $("#sendMailNotification").submit(function(e) {

		if (!isValidEmailAddress($('#email').val())) {
            $('.clientNapisError').show();
            $('.clientNapisError').text('Pred potrditvijo pravilno vnesite elektronski naslov.');
        	e.preventDefault();
        } else {
            $('.clientNapisError').hide();
        }

	});

	$("#changePasswordForm").submit(function(e) {

		var password1 = $('#password').val();
		var password2 = $('#password2').val();

		if (!validatePassword(password1)) {
			$('.clientNapisError').show();
			$('.clientNapisError').text('Geslo mora biti dolgo minimalno 6 znakov. Vsebovati mora: 1 veliko črko, 1 majhno črko in 1 številko.');
			e.preventDefault();
		} else {
			$('.clientNapisError').hide();
		}

		if (!validatePassword(password2)) {
			$('.clientNapisError').show();
			$('.clientNapisError').text('Geslo mora biti dolgo minimalno 6 znakov. Vsebovati mora: 1 veliko črko, 1 majhno črko in 1 številko.');
			e.preventDefault();
		} else {
			$('.clientNapisError').hide();
		}

		if (password1 !== password2) {
			$('.clientNapisErrorNoMatch').show();
			$('.clientNapisErrorNoMatch').text('Gesli se ne ujemata.');
			e.preventDefault();
		} else {
			$('.clientNapisErrorNoMatch').hide();
		}

	});

	// logout
	$(".logout").click(function (e) {
		bootbox.dialog({
			title: "Potrditev odjave",
			message: "Ali ste prepričani, da se želite odjaviti?",
			buttons: {
				cancel: {
					label: "&nbsp;Ne&nbsp;",
					className: "btn-default",
				},
				confirm: {
					label: "&nbsp;Da, odjavi me&nbsp;",
					className: "btn-primary",
					callback: function () {
						location.href= siteUrl + '/odjava';
					}
				},
			}
		});
	});

	// // confirm mail send
	// $(".poslji").click(function (e) {
	// 	bootbox.dialog({
	// 		title: "Potrditev pošiljanja",
	// 		message: "Število izbranih tablic: " + $('input:checkbox:checked').val('poslji-po-posti').length + ".<br> Vsaka tablica bo poslana posebej. Ste prepričani, da želite nadaljevati?",
	// 		buttons: {
	// 			cancel: {
	// 				label: "&nbsp;Ne&nbsp;",
	// 				className: "btn-default",
	// 			},
	// 			confirm: {
	// 				label: "&nbsp;Da&nbsp;",
	// 				className: "btn-primary",
	// 				callback: function () {
	// 					location.href= siteUrl + '/odjava';
	// 				}
	// 			},
	// 		}
	// 	});
	// });
});


$(document).ajaxComplete(function () {

	/* checkbox poslji - unicene tablice */
	$('.poslji-po-posti').click(function() {
		if ($('input:checkbox').is(':checked')) {
			$(".poslji").show();
		} else {
			$(".poslji").hide();
		}
	});


	
    /* next - prev */
    $('.prev').on('click', function() {
    	// $("#preloaderImage").show();
    	// alert("dela");
 //        $('.table-unicene').addClass('animated fadeOut');
 //        $('.registrationPicture').addClass('animated fadeOut');

 //        setTimeout(function(){
	// 	$('.table-unicene').removeClass('animated fadeOut');
 //        $('.registrationPicture').removeClass('animated fadeOut');
	// }, 800);
		$("#preloaderImage").show();
    });

    $('.next').on('click', function() {
    	// $("#preloaderImage").show();
    	// alert("dela");
 //        $('.table-unicene').addClass('animated fadeOut');
 //        $('.registrationPicture').addClass('animated fadeOut');

 //        setTimeout(function(){
	// 	$('.table-unicene').removeClass('animated fadeOut');
 //        $('.registrationPicture').removeClass('animated fadeOut');
	// }, 800);
		$("#preloaderImage").show();
    });
  

    $(".showPlate").on('click', function() {
    	$("#preloaderImage").show();
    });
});