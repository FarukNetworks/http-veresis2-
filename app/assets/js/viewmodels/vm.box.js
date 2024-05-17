var boxViewModel = function () {

	var boxesGridViewModel = ko.observable(null);
	var boxesGrid;
	var selectedCustomerId = ko.observable();
	var customerList = ko.observableArray();
    var canViewSubUnits = ko.observable();

	var filters = function () {
		return {
			customerId: selectedCustomerId()
		}
	}

    var changeCustomer = function () {
        loadBoxes();
    };

    
    var getCustomers = function () {
        requesthandler.defineRequest('customers','getcustomers');
        amplify.request({
            resourceId: 'customers',
            success: function (data) {
                customerList(data.customers);
                canViewSubUnits(data.canViewSubUnits);
                loadBoxes();
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

	var openDetail = function (box) {
        window.location.href = requesthandler.getCorrectSiteUrl('skatle/' + box.Id);
	}

	var initBoxGridviewModel = function (requestUrl, gridDataProperty, gridDataTotalRows) {
        var columns = [
            { field: "Code", title: "Koda škatle", sort: true },
            { field: "Date", title: "Datum", sort: true },
            { field: "IsCustom", title: "Custom", sort: true },
            { field: "BoxTypeDescription", title: "Škatla", sort: true },
            { field: "CustomerName", title: "Stranka", sort: true },
            { field: "BoxState", title: "Stanje", sort: true }
        ];

        boxesGrid = new dataGridViewModel().init("#listGrid", columns, requestUrl, gridDataProperty, 20, filters(), gridDataTotalRows, false);

        boxesGridViewModel(boxesGrid);

    };

    amplify.subscribe('gridDataIsLoaded', function (data) {
        $("#preloader").delay(400).fadeOut(600);
    });


    var loadBoxes = function () {
        initBoxGridviewModel("getboxes", "ListBoxes", "TotalRows");
    };

    return {
        init: init,
        boxesGridViewModel: boxesGridViewModel,
        openDetail: openDetail,
        selectedCustomerId: selectedCustomerId,
        customerList: customerList,
        changeCustomer: changeCustomer,
        canViewSubUnits: canViewSubUnits
    }
}