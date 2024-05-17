var searchDestroyedPlateViewModel = function () {
	var packages = [];
	var searchString = ko.observable();
	var plateDetail = ko.observable();

	var platesCount = ko.observable();
	var plates = ko.observableArray();

	var selectedPlatesItems = [];
    var selectedPlatesItemsCustomerIds = [];
    var selectedIsFake = [];
    var canShowButtonSend = ko.observable();

    var cdp = new commonDestroyedPlate();

	var clearItems = function(array) {
        while (array.length) {
            array.pop();
        }
    };

	var doSearch = ko.asyncCommand({
    	execute: function (complete) {
            // show preloader

            $("#preloaderSearch").show();

    		requesthandler.defineRequest('serachPlates', 'getDestroyedPlates');
    		amplify.request({
    			resourceId: 'serachPlates',
    			data: { asciiPlateNumber: searchString },
    			success: function (data) {
    				clearItems(packages);
			        if (data.destroyedPlates.length > 0) {
			        	var i = 0;
			        	data.destroyedPlates.forEach(function (item) {
			            	var dummy = item;
			            	dummy.packageSerialNumber = item.destroyedPackageSerialNumber;
			            	dummy.pos = i;
			            	dummy.picture = null;
                            dummy.customerId = item.customerId;
                            dummy.isFake = item.isFake;
                            dummy.totalNumber = data.destroyedPlates.length;
			            	dummy.isSelected = ko.observable(false);
			            	packages.push(dummy);
			            	i++;
			        	});
			        	plates(packages);	
			        }
			        platesCount(data.recordCount);
			        
    				complete();

                    // hide preloader
                    $(".searchText").html("Iskanje registracije '" + $("#searchValue").val() + "'.");
                    $(".tableSearch").show();
                    $(".searchPlateText").show();
                    $("#preloaderSearch").delay(400).fadeOut(600);
    			},
    			error: function (msg) {

    				toastr.error(msg);
    				complete();

                    // hide preloader
                    $(".tableSearch").show();
                    $(".searchPlateText").show();
                    $("#preloaderSearch").delay(400).fadeOut(600);
    			}
    		});
    	},
    	canExecute: function(isExecuting) {
            return !isExecuting;
        }
    });

    
   
    //-----------------------------------------------------------------//
    // action on modal plate detail
    //-----------------------------------------------------------------//                        
    var showPlateDetail = function (plate) {
        cdp.setPackages(packages);
        amplify.publish('plateHasBeenShowed', plate);
    };

    amplify.subscribe('plateHasBeenSet', function (plate) {
        plateDetail(plate);
        // $(".registrationPicture").on('load', function() {
            //     $(".registrationPicture").attr("src","app/assets/img/no-image.jpg");
            $("#preloaderImage").delay(400).fadeOut(600);
        // });
    });
    
    var previusPlate = function (plate) {
        amplify.publish('showPreviusPlate', plate);
    };
    
    var nextPlate = function (plate) {
        amplify.publish('showNextPlate', plate);
    };

    var sendViewedPlate = ko.asyncCommand({
        execute: function (plate, complete) {
            var data = [];
            var plateCustomerIds = [];
            var isFake = [];
            data.push(plate.id);
            plateCustomerIds.push(plate.customerId);
            isFake.push(plate.isFake);
            cdp.showConfirmBeforeSend(complete, data, plateCustomerIds, isFake);                      
        },
        canExecute: function(isExecuting) {
            return !isExecuting;
        }
    });

    //--------------------------------------------------------------//
    // action on selected checkboxes
    //---------------------------------------------------------------//
    var refreshSelectedPlates = function () {
    	clearItems(selectedPlatesItems);
        clearItems(selectedPlatesItemsCustomerIds);
        clearItems(selectedIsFake);
    	if (packages.length == 0) return;
    	packages.forEach(function (item) {
    		if (item.isSelected()) {
    			selectedPlatesItems.push(item.id);
                selectedPlatesItemsCustomerIds.push(item.customerId);
                selectedIsFake.push(item.isFake);
    		}
    	})
    	if (selectedPlatesItems.length > 0)
    		canShowButtonSend(true);
    	else
    		canShowButtonSend(false);
    }

    var selectPlate = function () {
    	refreshSelectedPlates();
    	return true;
    };

    var doSendSelectedPlates = ko.asyncCommand({
        execute: function (complete) {
             cdp.showConfirmBeforeSend(complete, selectedPlatesItems, selectedPlatesItemsCustomerIds, selectedIsFake);                      
        },
        canExecute: function(isExecuting) {
            return !isExecuting;
        }
    });

	var init = function () {
		ko.applyBindings(this);
	};

	return {
		init: init,
		searchString: searchString,
		doSearch: doSearch,
		platesCount: platesCount,
		plates: plates,
		showPlateDetail: showPlateDetail,
        plateDetail: plateDetail,
        previusPlate: previusPlate,
        nextPlate: nextPlate,
        canShowButtonSend: canShowButtonSend,
        selectPlate: selectPlate,
        doSendSelectedPlates: doSendSelectedPlates,
        sendViewedPlate: sendViewedPlate
	}
}