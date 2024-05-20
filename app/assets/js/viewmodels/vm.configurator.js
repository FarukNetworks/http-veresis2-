var configuratorModel = (function () {
    // Preloader related variable
    var preloader = document.getElementById('preloader');

    // Initialization function
    var init = function() {
        // Initialization code goes here
        if (preloader) {
            preloader.style.display = 'none'; // Hide preloader once initialization is done
        }
    }

    // Public API
    return {
        init: init
    }
})();