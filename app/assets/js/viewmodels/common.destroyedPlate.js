var commonDestroyedPlate = function () {

	var packages = [];

	var clearItems = function(array) {
        while (array.length) {
            array.pop();
        }
    };

	var callSendSelectedPlates = function (complete, selectedPlateIds, selectedPlateCustomerIds, isFake) {
    	// console.log(selectedPlateIds);
     //    console.log(selectedPlateCustomerIds);
    	requesthandler.definePostRequest('sendplates', 'sendDestroyedPlates');
    	amplify.request({
    		resourceId: 'sendplates',
    		data: ko.toJSON({ destroyedPlateIds: selectedPlateIds,
                              destroyedPlateCustomerIds: selectedPlateCustomerIds,
                              isFake: isFake                                   
                        }),
    		success: function () {
    		},
    		error: function (msg) {
    			toast.error(msg.error_message);
    		}
    	});

    	complete();
    };

    var showConfirmBeforeSend = function (complete, selectedPlateIds, selectedPlateCustomerIds, isFake) {
    	var msg = '';
    		if (selectedPlateIds.length == 1) {
    			msg = 'Izbrano tablico vam bomo poslali na mail. Ste prepričani, da želite nadaljevati?';
    	} else {
    			msg = "Število izbranih tablic: " + selectedPlateIds.length + 
    					".<br> Vsaka tablica bo poslana posebej. Ste prepričani, da želite nadaljevati?";
    	}
    	bootbox.dialog({
			title: "Potrditev pošiljanja",
			message: msg,
			buttons: {
				cancel: {
					label: "&nbsp;Ne&nbsp;",
					className: "btn-default",
					callback: function() {
						complete();
					}
				},
				confirm: {
					label: "&nbsp;Da&nbsp;",
					className: "btn-primary",
					callback: function () {
						callSendSelectedPlates(complete, selectedPlateIds, selectedPlateCustomerIds, isFake);
					}
				},
			}
		});
    };

    var getPlateImage = function(plate, selectedPlateIndex) {
        requesthandler.defineRequest('getPlateImage', 'getDestroyedPlateImage/' + plate.id);
        amplify.request({
            resourceId: 'getPlateImage',
            success: function(img) {
                if (img !== null && img !== "") {
                	if(img.cameraImage !== null && img.cameraImage !== ""){
                        var dto = {
                        image: img.cameraImage,
                        index: selectedPlateIndex,
                        customerId: img.customerId,
                        isFake: img.isFake
                        };
                        amplify.publish("imageisrecognized", dto);
                    }
                    else{
                        amplify.publish('plateHasBeenSet', plate);
                        $(".registrationPicture").attr("src", siteUrl + "app/assets/img/no-image.jpg");
                    }
                }
                else {
                    amplify.publish('plateHasBeenSet', plate);
                    $(".registrationPicture").attr("src", siteUrl + "app/assets/img/no-image.jpg");
                }
            },
            error: function(msg) {
                console.log(msg.error_message);
                toastr.error(msg.error_message);
            }  
        })
    };

    var refreshPlate = function (plate) {
        if (plate.picture == null) {
            getPlateImage(plate, plate.pos);
        } else {
        	amplify.publish('plateHasBeenSet', plate);
        }
    };

    var showPlateDetail = function (plate) {
        refreshPlate(plate);
    };
    
    var showPreviusPlate = function (plate) {
        var currentIndex = plate.pos;
        var next = (currentIndex > 0) ? currentIndex - 1 : packages.length -1;
        var nextPlate = packages[next]; 
        refreshPlate(nextPlate);
    };
    
    var showNextPlate = function (plate) {
        var currentIndex = plate.pos;
        var next = (currentIndex < packages.length-1) ? currentIndex + 1 : 0;
        var nextPlate = packages[next]; 
        refreshPlate(nextPlate);
    };

    var setPackages = function (data) {
    	packages = data;
    };

    amplify.subscribe('plateHasBeenShowed', function (plate) {
    	refreshPlate(plate);
    });

    amplify.subscribe('showPreviusPlate', function (plate) {
    	showPreviusPlate(plate);
    });

    amplify.subscribe('showNextPlate', function (plate) {
    	showNextPlate(plate);
    });

    amplify.subscribe('imageisrecognized', function(imgDto) {
        var plate = packages[imgDto.index];
        plate.picture = imgDto.image;   
        plate.customerId = imgDto.customerId; 
        plate.isFake = imgDto.isFake;     
        refreshPlate(plate);
    }); 

    return {
    	showConfirmBeforeSend: showConfirmBeforeSend,
    	getPlateImage: getPlateImage,
    	setPackages: setPackages 
    }
};