var searchFakePlateViewModel = function () {

    var packages = [];
    var searchString = ko.observable();
    var plateDetail = ko.observable();

    var platesCount = ko.observable();
    var plates = ko.observableArray();

    var selectedPlatesItems = [];
    var canShowButtonSend = ko.observable();

    var cdp = new commonDestroyedPlate();

    var clearItems = function(array) {
        while (array.length) {
            array.pop();
        }
    };

	var fakePlatesGridViewModel = ko.observable(null);
	var fakePlatesGrid;
	var selectedCustomerId = ko.observable();
	var customerList = ko.observableArray();
    var canViewSubUnits = ko.observable();
    var customerUserId;
    var userCustomerId;

	var filters = function () {
		return {
            customerId: selectedCustomerId(),
            customerUserId: customerUserId,
            asciiPlateNumber: (searchString()) ? searchString : ''
		}
	}

    var changeCustomer = function () {
        if ($("#searchValue").val()) {
            doSearchFake();
        } else {
            loadFakePlates();
            $(".searchText").text("Vnesite registracijo.");
            $(".searchPlateText").hide();
        }
    };
    
    var getCustomers = function () {
        requesthandler.defineRequest('customers','getcustomers');
        amplify.request({
            resourceId: 'customers',
            success: function (data) {
                customerList(data.customers);
                canViewSubUnits(data.canViewSubUnits);
                customerUserId = data.customerUserId;
                userCustomerId = data.userCustomerId;
                loadFakePlates();
                $("#preloader").delay(1000).fadeOut(600);
            },
            error: function (msg) { 
                toastr.error(msg);
            }
        });    
    } 


	// var openDetail = function (pack) {
 //        window.location.href = requesthandler.getCorrectSiteUrl('paketi/' + pack.id + '/' + pack.serialNumber);
        
	// }

	var initFakePlatesviewModel = function (requestUrl, gridDataProperty, gridDataTotalRows) {
        var columns = [
            { field: "destructionDateTime", title: "Datum uničenja", sort: true },
            { field: "destroyedPackageSerialNumber", title: "Paket", sort: true },
            { field: "customerName", title: "Naročnik", sort: true },
            { field: "asciiPlateNumber", title: "Registracija", sort: true },
            { field: "", title: "Prepoznava", sort: false }
        ];

        fakePlatesGrid = new dataGridViewModel().init("#listGrid", columns, requestUrl, gridDataProperty, 20, filters(), gridDataTotalRows, false);

        fakePlatesGridViewModel(fakePlatesGrid);
    };

    amplify.subscribe('gridDataIsLoaded', function (griddata) {
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

    var loadFakePlates = function () {
        initFakePlatesviewModel("GetFakeDestroyedPlates", "ListFakePlates", "TotalRows");
    };

    var doSearchFake = ko.asyncCommand({
        execute: function (complete) {

            // show preloader

            $("#preloaderSearch").show();

            //var reqobj = ko.toJS(fakePlatesGrid.gridParams());
            //reqobj.asciiPlateNumber = searchString();

            requesthandler.defineRequest('searchFakePlates', 'GetFakeDestroyedPlates');
            amplify.request({
                resourceId: 'searchFakePlates',
                data: filters(),
                success: function (data) {
                    clearItems(packages);
                    if (data.ListFakePlates.length > 0) {
                        var i = 0;
                        data.ListFakePlates.forEach(function (item) {
                            var dummy = item;
                            dummy.packageSerialNumber = item.destroyedPackageSerialNumber;
                            dummy.pos = i;
                            dummy.picture = null;
                            dummy.totalNumber = data.ListFakePlates.length;
                            dummy.isSelected = ko.observable(false);
                            packages.push(dummy);
                            i++;
                        });
                        plates(packages);   
                    }
                    platesCount(data.TotalRows);
                    
                    loadFakePlates();
                    complete();

                    // hide preloader
                    $(".searchText").html("Iskanje registracije '" + $("#searchValue").val() + "'.");
                    $(".tableSearch").show();
                    setTimeout(function(){ $(".searchPlateText").show(); }, 600);
                    $("#preloaderSearch").delay(4500).fadeOut(600);
                },
                error: function (msg) {
                    toast.error(msg);
                    loadFakePlates();
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
        $(".registrationPicture").on('load', function() {
            $("#preloaderImage").delay(400).fadeOut(600);
        });
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
            data.push(plate.id);
            cdp.showConfirmBeforeSend(complete, data);                      
        },
        canExecute: function(isExecuting) {
            return !isExecuting;
        }
    });

    // --------------------------------------------------------------//
    // action on selected checkboxes
    // ---------------------------------------------------------------//
    var refreshSelectedPlates = function () {
        clearItems(selectedPlatesItems);
        if (packages.length == 0) return;
        packages.forEach(function (item) {
            if (item.isSelected()) {
                selectedPlatesItems.push(item.id);
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
             cdp.showConfirmBeforeSend(complete, selectedPlatesItems);                      
        },
        canExecute: function(isExecuting) {
            return !isExecuting;
        }
    });

    var init = function () {
        ko.applyBindings(this);

        getCustomers();
    }

    return {
        init: init,
        fakePlatesGridViewModel: fakePlatesGridViewModel,
        // openDetail: openDetail,
        selectedCustomerId: selectedCustomerId,
        customerList: customerList,
        changeCustomer: changeCustomer,
        canViewSubUnits: canViewSubUnits,
        loadFakePlates: loadFakePlates,
        doSearchFake: doSearchFake,
        showPlateDetail: showPlateDetail,
        previusPlate: previusPlate,
        nextPlate: nextPlate,
        sendViewedPlate: sendViewedPlate,
        refreshSelectedPlates: refreshSelectedPlates,
        selectPlate: selectPlate,
        doSendSelectedPlates: doSendSelectedPlates,
        searchString: searchString,
        plateDetail: plateDetail,
        platesCount: platesCount,
        doSearchFake: doSearchFake

    }
}