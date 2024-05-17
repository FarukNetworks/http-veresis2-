var packViewModel = function () {

	var packsGridViewModel = ko.observable(null);
	var packsGrid;
	var selectedCustomerId = ko.observable();
	var customerList = ko.observableArray();
    var canViewSubUnits = ko.observable();
    var customerUserId;
    var userCustomerId;
    var selectedPacks = [];
    var canExportToExcel = ko.observable(false);

	var filters = function () {
		return {
            customerId: selectedCustomerId(),
            customerUserId: customerUserId
		}
	}

    var selectPack = function (pack) {
        var index = selectedPacks.indexOf(pack.id);
        if (index === -1) {
            selectedPacks.push(pack.id);
        } else {
            selectedPacks.splice(index, 1);
        }
        canExportToExcel((selectedPacks.length > 0) ? true : false);       
        return true;
    };

    var changeCustomer = function () {
        loadPacks();
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
                loadPacks();
                $("#preloader").delay(1000).fadeOut(600);
            },
            error: function (msg) {
                toastr.error(msg);
            }
        });    
    } 

	var init = function () {
		ko.applyBindings(this);

        getCustomers();
        
	}

	var openDetail = function (pack) {
        window.location.href = requesthandler.getCorrectSiteUrl('paketi/' + pack.id + '/' + pack.serialNumber);
        
	}

	var initPackGridviewModel = function (requestUrl, gridDataProperty, gridDataTotalRows) {
        var columns = [
            { field: "", title: "", sort: false },
            { field: "serialNumber", title: "Paket", sort: true },
            { field: "customerName", title: "Naročnik", sort: true },
            { field: "packageAssumedDate", title: "Datum prevzema", sort: true },
            { field: "state", title: "Stanje", sort: true },
            { field: "packageDestroyedDate", title: "Datum uničenja", sort: true },
        ];

        packsGrid = new dataGridViewModel().init("#listGrid", columns, requestUrl, gridDataProperty, 20, filters(), gridDataTotalRows, false);

        packsGridViewModel(packsGrid);
    };

    amplify.subscribe('gridDataIsLoaded', function (data) {
        $("#preloader").delay(400).fadeOut(600);
    });

    var loadPacks = function () {
        initPackGridviewModel("getDestroyedPackages", "ListPacs", "TotalRows");
    };

    return {
        init: init,
        packsGridViewModel: packsGridViewModel,
        openDetail: openDetail,
        selectedCustomerId: selectedCustomerId,
        customerList: customerList,
        changeCustomer: changeCustomer,
        canViewSubUnits: canViewSubUnits,
        exportPacksToExcel: exportPacksToExcel,
        selectPack: selectPack,
        selectedPacks: selectedPacks,
        canExportToExcel: canExportToExcel
    }
}