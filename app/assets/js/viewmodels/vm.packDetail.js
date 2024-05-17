var packDetailViewModel = function () {
    var packages = [];
    var packDetailGridViewModel = ko.observable(); 
    var packageId;
    var plateDetail = ko.observable();

    var cdp = new commonDestroyedPlate();

    var filters = function() {
        return {
            id: packageId
        }
    };

    var clearItems = function(array) {
        while (array.length) {
            array.pop();
        }
    };

    var getPackDetail = function (packId) {
        initPackGridviewModel('getDestroyedPackageDetails', "ListPacs", "TotalRows");    
    };

    var initPackGridviewModel = function (requestUrl, gridDataProperty, gridDataTotalRows) {
        var columns = [
            { field: "destructionDateTime", title: "Datum uničenja", sort: true },
            { field: "asciiPlateNumber", title: "Registracija", sort: true },
            { field: "", title: "Podrobnosti", sort: false },
        ];

        var packsGrid = new dataGridViewModel().init("#listGrid", columns, requestUrl, gridDataProperty, 20, filters(), gridDataTotalRows, false);
        packDetailGridViewModel(packsGrid);
    };

    amplify.subscribe('onDataGridError', function (errorMessage) {

        if(errorMessage.includes('Dostop zavrnjen')); {
            window.location = siteUrl + 'notfound';
        }

    });

    amplify.subscribe('gridDataIsLoaded', function (griddata) {
        // console.log(griddata);
        // if(data.status == 'error') {
        //     windows.location(siteUrl + 'notfound');
        // }

        // if(data.status == 'error') {
        // windows.location(siteUrl + 'notfound');
        // }

        clearItems(packages);
        var i = 0;
        griddata.forEach(function (item) {
            var dummy = item;
            dummy.pos = i;
            dummy.picture = null;
            dummy.totalNumber = griddata.length; 
            packages.push(dummy);
            i++;
        });

        $("#preloader").delay(400).fadeOut(600);
    });
    
    amplify.subscribe('plateHasBeenSet', function (plate) {
        plateDetail(plate);
        $(".registrationPicture").on('load', function() {
            $("#preloaderImage").delay(800).fadeOut(600);
        });
    });
    
    var showPlateDetail = function (plate) {
        cdp.setPackages(packages);
        amplify.publish('plateHasBeenShowed', plate);
    };
    
    var previusPlate = function (plate) {
        amplify.publish('showPreviusPlate', plate);
    };
    
    var nextPlate = function (plate) {
        amplify.publish('showNextPlate', plate);
    };

    var sendViewedPlate = ko.asyncCommand({
        execute: function (plate, complete) {

            var plateIds = [];
            var plateCustomerIds = [];

            plateIds.push(plate.id);
            plateCustomerIds.push(plate.customerId);
            
            cdp.showConfirmBeforeSend(complete, plateIds, plateCustomerIds);                      
        },
        canExecute: function(isExecuting) {
            return !isExecuting;
        }
    });


	var init = function (packId) {
		ko.applyBindings(this);

        packageId = packId;

        getPackDetail(packId);
        
	};

    return {
        init: init,
        packDetailGridViewModel: packDetailGridViewModel,
        packages: packages,
        showPlateDetail: showPlateDetail,
        plateDetail: plateDetail,
        previusPlate: previusPlate,
        nextPlate: nextPlate,
        preloader: preloader,
        sendViewedPlate: sendViewedPlate
    }
}