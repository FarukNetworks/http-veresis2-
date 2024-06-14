$(document).ready(function () {
	
	console.log('ready');

	// toastr setting to be very fast

	toastr.options = {
		"closeButton": false,
		"debug": false,
		"newestOnTop": true,
		"progressBar": true,
		"positionClass": "toast-top-right",
		"preventDuplicates": true,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "100",
		"timeOut": "50"
	};

	const k_obcine_list = [

		// abbr: "LJ", cerknica, domzale, hrastnik, kočevje, ribnica, trbovlje, zagorje, litija, grosuplje, kamnik, ljubljana, logatec, vrhnika
		{ "value": "cerknica", "name": "cerknica", "img": "cerknica@2x.png", "abbr": "LJ" },
		{ "value": "domzale", "name": "domžale", "img": "domzale@2x.png", "abbr": "LJ" },
		{ "value": "grosuplje", "name": "grosuplje", "img": "grosuplje@2x.png", "abbr": "LJ" },
		{ "value": "kamnik", "name": "kamnik", "img": "kamnik@2x.png", "abbr": "LJ" },
		{ "value": "ljubljana", "name": "ljubljana", "img": "ljubljana@2x.png", "abbr": "LJ" },
		{ "value": "logatec", "name": "logatec", "img": "logatec@2x.png", "abbr": "LJ" },
		{ "value": "vrhnika", "name": "vrhnika", "img": "vrhnika@2x.png", "abbr": "LJ" },
		{ "value": "hrastnik", "name": "hrastnik", "img": "hrastnik@2x.png", "abbr": "LJ" },
		{ "value": "kocevje", "name": "kočevje", "img": "kocevje@2x.png", "abbr": "LJ" },
		{ "value": "litija", "name": "litija", "img": "litija@2x.png", "abbr": "LJ" },
		{ "value": "ribnica", "name": "ribnica", "img": "ribnica@2x.png", "abbr": "LJ" },
		{ "value": "trbovlje", "name": "trbovlje", "img": "trbovlje@2x.png", "abbr": "LJ" },
		{ "value": "zagorje", "name": "zagorje", "img": "zagorje@2x.png", "abbr": "LJ" },

		// ptuj, lenart, maribor, ormož, ruše, slovenska bistrica, pesnica

		{ "value": "maribor", "name": "maribor", "img": "maribor@2x.png", "abbr": "MB" },
		{ "value": "ormoz", "name": "ormož", "img": "ormoz@2x.png", "abbr": "MB" },
		{ "value": "pesnica", "name": "pesnica", "img": "pesnica@2x.png", "abbr": "MB" },
		{ "value": "ptuj", "name": "ptuj", "img": "ptuj@2x.png", "abbr": "MB" },
		{ "value": "lenart", "name": "lenart", "img": "lenart@2x.png", "abbr": "MB" },
		{ "value": "ruše", "name": "ruše", "img": "ruse@2x.png", "abbr": "MB" },
		{ "value": "slovenska_bistrica", "name": "slovenska bistrica", "img": "slovenska_bistrica@2x.png", "abbr": "MB" },

		// GO
		// ajdovščina, idrija, nova gorica, tolmin

		{ "value": "ajdovscina", "name": "ajdovščina", "img": "ajdovscina@2x.png", "abbr": "GO" },
		{ "value": "idrija", "name": "idrija", "img": "idrija@2x.png", "abbr": "GO" },
		{ "value": "nova_gorica", "name": "nova gorica", "img": "nova_gorica@2x.png", "abbr": "GO" },
		{ "value": "tolmin", "name": "tolmin", "img": "tolmin@2x.png", "abbr": "GO" },

		// CE
		// celje, laško, mozirje, slovenske konjice, velenje, žalec, Šentjur pri Celju, Šmarje pri Jelšah,
		
		{ "value": "celje", "name": "celje", "img": "celje@2x.png", "abbr": "CE" },
		{ "value": "lasko", "name": "laško", "img": "lasko@2x.png", "abbr": "CE" },
		{ "value": "mozirje", "name": "mozirje", "img": "mozirje@2x.png", "abbr": "CE" },
		{ "value": "slovenske_konjice", "name": "slovenske konjice", "img": "slovenske_konjice@2x.png", "abbr": "CE" },
		{ "value": "velenje", "name": "velenje", "img": "velenje@2x.png", "abbr": "CE" },
		{ "value": "zalec", "name": "žalec", "img": "zalec@2x.png", "abbr": "CE" },
		{ "value": "sentjur", "name": "šentjur", "img": "sentjur@2x.png", "abbr": "CE" },
		{ "value": "smarje_pri_jelsah", "name": "šmarje pri jelšah", "img": "smarje_pri_jelsah@2x.png", "abbr": "CE" },
		
		// PO
		// postojna

		{ "value": "postojna", "name": "postojna", "img": "postojna@2x.png", "abbr": "PO" },

		// KR

		// jesenice, kranj, radovljica, škofja loka, tržič

		{ "value": "jesenice", "name": "jesenice", "img": "jesenice@2x.png", "abbr": "KR" },
		{ "value": "kranj", "name": "kranj", "img": "kranj@2x.png", "abbr": "KR" },
		{ "value": "radovljica", "name": "radovljica", "img": "radovljica@2x.png", "abbr": "KR" },
		{ "value": "skofja_loka", "name": "škofja loka", "img": "skofja_loka@2x.png", "abbr": "KR" },
		{ "value": "trzic", "name": "tržič", "img": "trzic@2x.png", "abbr": "KR" },

		// PO

		// postojna

		{ "value": "postojna", "name": "postojna", "img": "postojna@2x.png", "abbr": "PO" },

		// MS

		// gornja radgona, ljutomer, murska sobota, lendava

		{ "value": "gornja_radgona", "name": "gornja radgona", "img": "gornja_radgona@2x.png", "abbr": "MS" },
		{ "value": "ljutomer", "name": "ljutomer", "img": "ljutomer@2x.png", "abbr": "MS" },
		{ "value": "murska_sobota", "name": "murska sobota", "img": "murska_sobota@2x.png", "abbr": "MS" },
		{ "value": "lendava", "name": "lendava", "img": "lendava@2x.png", "abbr": "MS" },

		// KP

		// koper, piran, izola, ilirska bistrica, sežana,

		{ "value": "koper", "name": "koper", "img": "koper@2x.png", "abbr": "KP" },
		{ "value": "piran", "name": "piran", "img": "piran@2x.png", "abbr": "KP" },
		{ "value": "izola", "name": "izola", "img": "izola@2x.png", "abbr": "KP" },
		{ "value": "ilirska_bistrica", "name": "ilirska bistrica", "img": "ilirska_bistrica@2x.png", "abbr": "KP" },
		{ "value": "sezana", "name": "sežana", "img": "sezana@2x.png", "abbr": "KP" },

		// SG

		// dravograd, radlje ob dravi, ravne na koroškem, slovenj gradec

		{ "value": "dravograd", "name": "dravograd", "img": "dravograd@2x.png", "abbr": "SG" },
		{ "value": "radlje_ob_dravi", "name": "radlje ob dravi", "img": "radlje_ob_dravi@2x.png", "abbr": "SG" },
		{ "value": "ravne_na_koroskem", "name": "ravne na koroškem", "img": "ravne_na_koroskem@2x.png", "abbr": "SG" },
		{ "value": "slovenj_gradec", "name": "slovenj gradec", "img": "slovenj_gradec@2x.png", "abbr": "SG" },

		// NM

		// novo mesto, metlika, črnomelj, trebnje
		
		{ "value": "novo_mesto", "name": "novo mesto", "img": "novo_mesto@2x.png", "abbr": "NM" },
		{ "value": "metlika", "name": "metlika", "img": "metlika@2x.png", "abbr": "NM" },
		{ "value": "crnomelj", "name": "črnomelj", "img": "crnomelj@2x.png", "abbr": "NM" },
		{ "value": "trebnje", "name": "trebnje", "img": "trebnje@2x.png", "abbr": "NM" },

		// KK 

		// krško, brežice, sevnica, krško

		{ "value": "krsko", "name": "krško", "img": "krsko@2x.png", "abbr": "KK" },
		{ "value": "brezice", "name": "brežice", "img": "brezice@2x.png", "abbr": "KK" },
		{ "value": "sevnica", "name": "sevnica", "img": "sevnica@2x.png", "abbr": "KK" },
		{ "value": "krsko", "name": "krško", "img": "krsko@2x.png", "abbr": "KK" },

	];

	const k_tip_tablice = [
		{
			"value": "tip_avto",
			"img": "avto.jpg",
			"class": "avto-font",
			"holder": "tablica-avto"
		},
		{
			"value": "tip_avto_o",
			"img": "avto.jpg",
			"class": "avto-oz",
			"holder": "tablica-avto"
		},
		{
			"value": "tip_moped",
			"img": "Scooter-tablica.jpg",
			"class": "moped-font",
			"holder": "tablica-moped"

		},
		{
			"value": "tip_r4",
			"img": "R4-tablica.jpg",
			"class": "r4-font",
			"holder": "r4-katrca"
		},
		{
			"value": "tip_r4_o",
			"img": "R4-tablica.jpg",
			"class": "r4-oz",
			"holder": "r4-katrca"
		},
		{
			"value": "tip_traktor",
			"img": "traktor.jpg",
			"class": "traktor-font",
			"holder": "tablica-traktor"
		}
	];

	// Add options to select id k_obcine from const k_obcine_list
	$.each(k_obcine_list, function (index, value) {
		$('#k_obcine').append('<option abbr="'+ value.abbr +'" value="' + value.value + '">' + value.name + ' (' + value.abbr + ')' + '</option>');
	});

	function validateInput(input) {
		// Check if input contains at least one letter
		if (!/[a-zA-Z]/.test(input)) {
			return false;
		}
	
		// Check if input contains at most one dash and not at the beginning or the end
		if ((input.indexOf('-') !== input.lastIndexOf('-')) || input.startsWith('-') || input.endsWith('-')) {
			return false;
		}
	
		return true;
	}

	// on change of k_input and if k_vanity to true put a minus after 3 character of k_input

	$('#k_input_text').on('input change', function () {
		var input = $(this).val();
	
		// if vanity is false put a minus after 3 character of k_input
		// if vanity is true minus is not needed
		// if (!$('#k_vanity').is(':checked')) {
		// 	if (input.length === 3 || input.length === 4) {
		// 		input += '-';
		// 	}
		// }
	
		$(this).val(input);
	
		if (!validateInput(input)) {
			toastr.error('Vnesite veljavno registrsko oznako!');
		} else {
			toastr.success('Registrska oznaka je veljavna!');
		}
	});

	// switch placeholder based on k_tip_tablice

	// tip_avto, tip_avto_o - placeholder: AB-1234, AB-1234 
	// tip_traktor - AB-12
	

	// tip_moped, tip_r4, tip_r4_o, tip_traktor

	$('.k_tip_tablice').on('change', function () {

		var k_tip_value = $('#k_tip_tablice').val();

		if (k_tip_value === 'tip_avto' || k_tip_value === 'tip_avto_o') {
			$('.k-input-text').val('12-EAV');
		} else if (k_tip_value === 'tip_traktor') {
			$('.k-input-text').val('AB-12');
		} else if (k_tip_value === 'tip_moped') {
			$('.k-input-text').val('AB-12');
		} else if(k_tip_value === 'tip_r4' || k_tip_value === 'tip_r4_o') {
			$('.k-input-text').val('12-EAV');
		} else {
			$('.k-input-text').val('ABC-122');
		}

	});


	// if vanity is check or uncheck always clear input k_input_text

	$('#k_vanity').on('change', function () {
		$('#k_input_text').val('');
	});

	$('#k_configurator').on('submit', function (e) {
		// Prevent default form submission in both cases
		e.preventDefault();

		function getValueFromArray(array, value) {
			return array.find(function (element) {
				return element.value === value;
			});
		}
	
		var isValid = true;

		$('input[type="text"]', this).each(function () {
			if ($(this).val() === '') {
				isValid = false;
				console.log('empty field');
				// make an error toastr
				toastr.error('Vnesite obvezna polja!');

				return false; // break the loop
			}
		});

		if (isValid) {

			// get values from form fields and store them in variables

			var k_obcine = $('#k_obcine').val();
			var k_grb_img = getValueFromArray(k_obcine_list, k_obcine).img;
			var k_tip_value = $('#k_tip_tablice').val();
			var k_ime_tablice = $('#k_input_text').val();
			

			var k_obcine_abbr = $('#k_obcine option:selected').attr('abbr');
			var k_vanity = $('#k_vanity').val();
			var k_input = $('#k_input_text').val().toUpperCase();
			var k_tip_class = getValueFromArray(k_tip_tablice, k_tip_value).class;
			var k_holder_tablica = getValueFromArray(k_tip_tablice, k_tip_value).holder;

			// console log values
			console.log(k_obcine);
			console.log(k_obcine_abbr);
			console.log(k_grb_img);
			console.log(k_tip_value);
			console.log(k_ime_tablice);
			console.log("k_holder_tablica: " + k_holder_tablica);

			// id "logger-result" i need to display every choosen variable
			var pillsHtml = `
			<div class="v-pills">
			  <span class="pill">Kraj: ${k_obcine}</span>
			  <span class="pill">Tip tablice: ${k_tip_value}</span>
			  <span class="pill">Ime: ${k_ime_tablice}</span>
			  <span class="pill">Okrajšava: ${k_obcine_abbr}</span> 
			  <span class="pill">Tip class: ${k_tip_class}</span>
			  <span class="pill">Tablica template: ${k_holder_tablica}</span>
			</div>
			`;
			
			// if k_input has "-" inside - put <span class="dash">-</span> in k_input
			if (k_input.includes('-')) {
				$('.tab-text-1').html(k_input.replace('-', '<span class="dash">-</span>'));
			}

			$('#logger-result').html(pillsHtml);

			// change the name of the url in tab-grb class - images are located in 2 folder above and img/grbi and value
			$('.tab-grb img').attr('src', 'app/assets/img/grbi/' + k_grb_img);

			// input abbr to the span in tab-okr class
			$('.tab-okr span').text(k_obcine_abbr);

			// input is put to tab-text-1
			$('.tab-text-1').text(k_input.toUpperCase());

			// remove default class and add class from k_tip_class
			$('.tab-wrapper').removeClass('avto-font avto-oz traktor-font moped-font r4-font r4-oz');
			$('.tab-wrapper').addClass(k_tip_class);

			// switch id based on k_holder_tablica

			$('.tablice-holder').attr('id', k_holder_tablica);

		}

	});

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
