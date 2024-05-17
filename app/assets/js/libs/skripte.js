$(document).ready(function(){
    $("div").scroll(function(){
    	
	});
});


// funkcija, spremenljivke
function AddClassScroll() {
	$elem = $('.naslov');
	
	// če je width manjši od 1140
	if ($(window).width() < 1140) {
		$elem.addClass('pull-right');
	}
}

// zazeni funkcijo ob vidnem polju
$(window).scroll(function(){
    checkAnimation();
});