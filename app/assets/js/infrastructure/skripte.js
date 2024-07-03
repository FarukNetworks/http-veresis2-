$(document).ready(function () {

	// toastr setting to be very fast

	toastr.options = {
		"closeButton": false,
		"debug": false,
		"newestOnTop": true,
		"progressBar": true,
		"positionClass": "toast-top-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "1500",
		"hideDuration": "2000",
		"timeOut": "1000"
	};

	const k_obcine_list = [

		// abbr: "LJ", cerknica, domzale, hrastnik, kočevje, ribnica, trbovlje, zagorje, litija, grosuplje, kamnik, ljubljana, logatec, vrhnika
		{ "value": "cerknica", "name": "Cerknica", "img": "cerknica@2x.png", "abbr": "LJ" },
		{ "value": "domzale", "name": "Domžale", "img": "domzale@2x.png", "abbr": "LJ" },
		{ "value": "grosuplje", "name": "Grosuplje", "img": "grosuplje@2x.png", "abbr": "LJ" },
		{ "value": "kamnik", "name": "Kamnik", "img": "kamnik@2x.png", "abbr": "LJ" },
		{ "value": "ljubljana", "name": "Ljubljana", "img": "ljubljana@2x.png", "abbr": "LJ" },
		{ "value": "logatec", "name": "Logatec", "img": "logatec@2x.png", "abbr": "LJ" },
		{ "value": "vrhnika", "name": "Vrhnika", "img": "vrhnika@2x.png", "abbr": "LJ" },
		{ "value": "hrastnik", "name": "Hrastnik", "img": "hrastnik@2x.png", "abbr": "LJ" },
		{ "value": "kocevje", "name": "Kočevje", "img": "kocevje@2x.png", "abbr": "LJ" },
		{ "value": "litija", "name": "Litija", "img": "litija@2x.png", "abbr": "LJ" },
		{ "value": "ribnica", "name": "Ribnica", "img": "ribnica@2x.png", "abbr": "LJ" },
		{ "value": "trbovlje", "name": "Trbovlje", "img": "trbovlje@2x.png", "abbr": "LJ" },
		{ "value": "zagorje", "name": "Zagorje", "img": "zagorje@2x.png", "abbr": "LJ" },

		// ptuj, lenart, maribor, ormož, ruše, slovenska bistrica, pesnica

		{ "value": "maribor", "name": "Maribor", "img": "maribor@2x.png", "abbr": "MB" },
		{ "value": "ormoz", "name": "Ormož", "img": "ormoz@2x.png", "abbr": "MB" },
		{ "value": "pesnica", "name": "Pesnica", "img": "pesnica@2x.png", "abbr": "MB" },
		{ "value": "ptuj", "name": "Ptuj", "img": "ptuj@2x.png", "abbr": "MB" },
		{ "value": "lenart", "name": "Lenart", "img": "lenart@2x.png", "abbr": "MB" },
		{ "value": "ruše", "name": "Ruše", "img": "ruse@2x.png", "abbr": "MB" },
		{ "value": "slovenska_bistrica", "name": "Slovenska Bistrica", "img": "slovenska_bistrica@2x.png", "abbr": "MB" },

		// GO
		// ajdovščina, idrija, nova gorica, tolmin

		{ "value": "ajdovscina", "name": "Ajdovščina", "img": "ajdovscina@2x.png", "abbr": "GO" },
		{ "value": "idrija", "name": "Idrija", "img": "idrija@2x.png", "abbr": "GO" },
		{ "value": "nova_gorica", "name": "Nova gorica", "img": "nova_gorica@2x.png", "abbr": "GO" },
		{ "value": "tolmin", "name": "Tolmin", "img": "tolmin@2x.png", "abbr": "GO" },

		// CE
		// celje, laško, mozirje, slovenske konjice, velenje, žalec, Šentjur pri Celju, Šmarje pri Jelšah,

		{ "value": "celje", "name": "Celje", "img": "celje@2x.png", "abbr": "CE" },
		{ "value": "lasko", "name": "Laško", "img": "lasko@2x.png", "abbr": "CE" },
		{ "value": "mozirje", "name": "Mozirje", "img": "mozirje@2x.png", "abbr": "CE" },
		{ "value": "slovenske_konjice", "name": "Slovenske konjice", "img": "slovenske_konjice@2x.png", "abbr": "CE" },
		{ "value": "velenje", "name": "Velenje", "img": "velenje@2x.png", "abbr": "CE" },
		{ "value": "zalec", "name": "Žalec", "img": "zalec@2x.png", "abbr": "CE" },
		{ "value": "sentjur", "name": "Šentjur", "img": "sentjur@2x.png", "abbr": "CE" },
		{ "value": "smarje_pri_jelsah", "name": "Šmarje pri Jelšah", "img": "smarje_pri_jelsah@2x.png", "abbr": "CE" },

		// PO
		// postojna

		{ "value": "postojna", "name": "Postojna", "img": "postojna@2x.png", "abbr": "PO" },

		// KR

		// jesenice, kranj, radovljica, škofja loka, tržič

		{ "value": "jesenice", "name": "Jesenice", "img": "jesenice@2x.png", "abbr": "KR" },
		{ "value": "kranj", "name": "Kranj", "img": "kranj@2x.png", "abbr": "KR" },
		{ "value": "radovljica", "name": "Radovljica", "img": "radovljica@2x.png", "abbr": "KR" },
		{ "value": "skofja_loka", "name": "Škofja Loka", "img": "skofja_loka@2x.png", "abbr": "KR" },
		{ "value": "trzic", "name": "Tržič", "img": "trzic@2x.png", "abbr": "KR" },

		// PO

		// postojna

		{ "value": "postojna", "name": "Postojna", "img": "postojna@2x.png", "abbr": "PO" },

		// MS

		// gornja radgona, ljutomer, murska sobota, lendava

		{ "value": "gornja_radgona", "name": "Gornja Radgona", "img": "gornja_radgona@2x.png", "abbr": "MS" },
		{ "value": "ljutomer", "name": "Ljutomer", "img": "ljutomer@2x.png", "abbr": "MS" },
		{ "value": "murska_sobota", "name": "Murska Sobota", "img": "murska_sobota@2x.png", "abbr": "MS" },
		{ "value": "lendava", "name": "Lendava", "img": "lendava@2x.png", "abbr": "MS" },

		// KP

		// koper, piran, izola, ilirska bistrica, sežana,

		{ "value": "koper", "name": "Koper", "img": "koper@2x.png", "abbr": "KP" },
		{ "value": "piran", "name": "Piran", "img": "piran@2x.png", "abbr": "KP" },
		{ "value": "izola", "name": "Izola", "img": "izola@2x.png", "abbr": "KP" },
		{ "value": "ilirska_bistrica", "name": "Ilirska Bistrica", "img": "ilirska_bistrica@2x.png", "abbr": "KP" },
		{ "value": "sezana", "name": "Sežana", "img": "sezana@2x.png", "abbr": "KP" },

		// SG

		// dravograd, radlje ob dravi, ravne na koroškem, slovenj gradec

		{ "value": "dravograd", "name": "Dravograd", "img": "dravograd@2x.png", "abbr": "SG" },
		{ "value": "radlje_ob_dravi", "name": "Radlje ob Dravi", "img": "radlje_ob_dravi@2x.png", "abbr": "SG" },
		{ "value": "ravne_na_koroskem", "name": "Ravne na Koroškem", "img": "ravne_na_koroskem@2x.png", "abbr": "SG" },
		{ "value": "slovenj_gradec", "name": "Slovenj Gradec", "img": "slovenj_gradec@2x.png", "abbr": "SG" },

		// NM

		// novo mesto, metlika, črnomelj, trebnje

		{ "value": "novo_mesto", "name": "Novo mesto", "img": "novo_mesto@2x.png", "abbr": "NM" },
		{ "value": "metlika", "name": "Metlika", "img": "metlika@2x.png", "abbr": "NM" },
		{ "value": "crnomelj", "name": "Črnomelj", "img": "crnomelj@2x.png", "abbr": "NM" },
		{ "value": "trebnje", "name": "Trebnje", "img": "trebnje@2x.png", "abbr": "NM" },

		// KK 

		{ "value": "krsko", "name": "Krško", "img": "krsko@2x.png", "abbr": "KK" },
		{ "value": "brezice", "name": "Brežice", "img": "brezice@2x.png", "abbr": "KK" },
		{ "value": "sevnica", "name": "Sevnica", "img": "sevnica@2x.png", "abbr": "KK" },
		{ "value": "krsko", "name": "Krško", "img": "krsko@2x.png", "abbr": "KK" },

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
		},
		{
			"value": "tip_moped_rdec",
			"img": "Scooter-tablica.jpg",
			"class": "moped-font-rdec",
			"holder": "tablica-moped"

		},
	];

	// Add options to select id k_obcine from const k_obcine_list
	$.each(k_obcine_list, function (index, value) {
		$('#k_obcine').append('<option abbr="' + value.abbr + '" value="' + value.value + '">' + value.name + ' (' + value.abbr + ')' + '</option>');
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
			// var pillsHtml = `
			// <div class="v-pills">
			//   <span class="pill">Kraj: ${k_obcine}</span>
			//   <span class="pill">Tip tablice: ${k_tip_value}</span>
			//   <span class="pill">Ime: ${k_ime_tablice}</span>
			//   <span class="pill">Okrajšava: ${k_obcine_abbr}</span> 
			//   <span class="pill">Tip class: ${k_tip_class}</span>
			//   <span class="pill">Tablica template: ${k_holder_tablica}</span>
			// </div>
			// `;

			// $('#logger-result').html(pillsHtml);

			// change the name of the url in tab-grb class - images are located in 2 folder above and img/grbi and value
			$('.tab-grb img').attr('src', 'app/assets/img/grbi/' + k_grb_img);

			// input abbr to the span in tab-okr class
			$('.tab-okr span').text(k_obcine_abbr);

			// input is put to tab-text-1
			$('.tab-text-1').text(k_input.toUpperCase());

			// remove default class and add class from k_tip_class
			$('.tab-wrapper').removeClass('avto-font avto-oz traktor-font moped-font-rdec moped-font r4-font r4-oz');
			$('.tab-wrapper').addClass(k_tip_class);

			// switch id based on k_holder_tablica

			$('.tablice-holder').attr('id', k_holder_tablica);

		}

	});

	// I need to print the tablica holder - tablice-holder

	function printDiv(divName = ".tablice-holder") {


		html2canvas(document.querySelector(divName)).then(canvas => {
			var dataUrl = canvas.toDataURL();

			// Get text input #k_input_text
			var k_input_text = $('#k_input_text').val();
			var k_obcine = $('#k_obcine option:selected').text();

			// select field - get the text in option not value
			var k_tip_value = $('#k_tip_tablice option:selected').text();

			// date with time, day, month, year (hours, minutes, seconds)
			var today = new Date();
			var date = today.getDate() + '.' + (today.getMonth() + 1) + '.' + today.getFullYear();

			// display time like this 11:01 - we have have also 0 if minutes are less than 10

			var time = today.getHours() + ":" + today.getMinutes();

			// get value from class nav-user
			var uporabnik = $('.nav-user').text();


			var windowContent = '<!DOCTYPE html>';
			windowContent += '<html>'
			windowContent += '<head><title>Informativni prikaz registrske tablice za ' + k_input_text + ' ('+ uporabnik + ')</title>';
			// Add CSS for A4 landscape
			windowContent += '<style>@page { size: A4 portrait; } body {font-family: "Arial"; padding-left: 2%; padding-right: 2%;} h1 {font-size: 25px;} p {margin-top: 0; margin-bottom: 5px;} .info-holder {border-radius: 20px; margin-top: 10px; display: inline-block; border: 1px solid #ddd; padding: 10px;} .info-holder p {font-size: 12px; color: #ddd;} .signature-holder {margin-top: 60px;} .signature-holder span {margin-right: 10px;}</style>';
			windowContent += '</head>';
			windowContent += '<body>'
			windowContent += '<h1>Informativni prikaz registrske tablice</h1>';
			windowContent += '<img src="' + dataUrl + '" style="width: 90%; max-width: 100%;">';
			
			windowContent += '<div class="info-holder">';
			windowContent += '<p>Tip tablice: ' + k_tip_value + '</p>';
			windowContent += '<p>Upravna enota (grb): ' + k_obcine + '</p>';
			windowContent += '<p>Vsebina tablice: ' + k_input_text + '</p>';
			windowContent += '</div>';

			windowContent += '<p class="signature-holder"><span>Uporabnik: <strong>' + uporabnik + '</strong></span>';
			windowContent += '<span>Datum: <strong>' + date + ' (' + time + ')</strong></span>';
			windowContent += '</p>';
			windowContent += '<p class="signature-holder">';
			windowContent += '<span>Potrditev seznanitve z informativnim prikazom:</span>';
			windowContent += '</p>';

			windowContent += '<p class="signature-holder">';
			windowContent += '<span>_____________________________________________________</span>';
			windowContent += '</p>';

			windowContent += '</body>';
			windowContent += '</html>';
	
			var printWin = window.open('', '');
			printWin.document.open();
			printWin.document.write(windowContent);
	
			setTimeout(function() {
				try {
					printWin.focus();
					printWin.print();
					printWin.document.close();
				} catch (e) {
					console.error("Error triggering print dialog:", e);
				}
			}, 1000); // Adjust the timeout as needed
		});
	}


	$('#print-btn').on('click', function () {
		printDiv();
	});



	/** cookies */
	if (!$(".cookies").hasClass("ne-pokazi")) {
		$(".cookies").fadeIn(400);
	}

	/* preloader */
	setTimeout(function () {
		$("#preloader-first").fadeOut(600);
	}, 1600);

	// setTimeout(function(){
	// 	$("#preloader").fadeOut(600);
	// }, 800);

	setTimeout(function () {
		$(".footer").slideDown();
	}, 800);

	/* search */

	$('.navbar-form').submit(function (e) {

		if ($('#searchValue').val() == "") {
			e.preventDefault();
		}

	});

	$('#searchValue').keyup(function () {
		if ($('#searchValue').val() !== "") {
			$('.searchButton').fadeIn(400);
		}
		if ($('#searchValue').val() == "") {
			$('.searchButton').fadeOut(100);
		}
	});

	/* sidebar menu show hide */

	$('.sidebar-button').click(function () {
		$('.sidebar').toggle(1);
		$('.main').toggleClass("toggled");
		$('.footer').toggleClass("toggled");
		$('.sidebar-button').toggleClass("glyphicon-circle-arrow-right");
		$('.sidebar-button').toggleClass("glyphicon-circle-arrow-left");
	});

	$(window).resize(function () {
		// odstrani classe ob širini
		if ($(window).width() > 768) {
			$('.main').removeClass('toggled');
			$('.footer').removeClass('toggled');
			$('.sidebar').css('display', 'block');

			if ($('.main').hasClass('toggled-main')) {
				$('.main').removeClass('toggled-main');
			}
		}
		if ($(window).width() < 768) {
			$('.sidebar').css('display', 'none');
			$('.main').addClass('toggled-main');
			$('.sidebar-button').removeClass('glyphicon-circle-arrow-left');
			$('.sidebar-button').addClass('glyphicon-circle-arrow-right');
		}
	});

	$(window).load(function () {
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

	$("#sendMailNotification").submit(function (e) {

		if (!isValidEmailAddress($('#email').val())) {
			$('.clientNapisError').show();
			$('.clientNapisError').text('Pred potrditvijo pravilno vnesite elektronski naslov.');
			e.preventDefault();
		} else {
			$('.clientNapisError').hide();
		}

	});

	$("#changePasswordForm").submit(function (e) {

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
						location.href = siteUrl + '/odjava';
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
	$('.poslji-po-posti').click(function () {
		if ($('input:checkbox').is(':checked')) {
			$(".poslji").show();
		} else {
			$(".poslji").hide();
		}
	});



	/* next - prev */
	$('.prev').on('click', function () {
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

	$('.next').on('click', function () {
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


	$(".showPlate").on('click', function () {
		$("#preloaderImage").show();
	});


});
