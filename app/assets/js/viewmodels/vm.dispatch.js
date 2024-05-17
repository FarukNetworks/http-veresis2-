var dispatchViewModel = function () {

	var dispatchesGridViewModel = ko.observable(null);
	var dispatchesGrid;
	var selectedCustomerId = ko.observable();
	var customerList = ko.observableArray();
    var canViewSubUnits = ko.observable();

	var filters = function () {
		return {
			customerId: selectedCustomerId()
		}
	}

    var changeCustomer = function () {
        loadDispatches();
    };

    
    var getCustomers = function () {
        requesthandler.defineRequest('customers','getcustomers');
        amplify.request({
            resourceId: 'customers',
            success: function (data) {
                customerList(data.customers);
                canViewSubUnits(data.canViewSubUnits);
                loadDispatches();
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

	var openDetail = function (dispatch) {
        window.location.href = requesthandler.getCorrectSiteUrl('odpreme/' + dispatch.Id);
	}

	var initDispatchGridviewModel = function (requestUrl, gridDataProperty, gridDataTotalRows) {
        var columns = [
            { field: "DispatchNumber", title: "Št. odpreme", sort: true },
            { field: "DispatchDate", title: "Datum", sort: true },
            { field: "CustomerName", title: "Naročnik", sort: true },
            { field: "State", title: "Stanje", sort: true },
            { field: "DeliveryNoteErpCode", title: "Dobavnica", sort: true },
            { field: "DispatchByPost", title: "Odprema po pošti", sort: true }
        ];

        dispatchesGrid = new dataGridViewModel().init("#listGrid", columns, requestUrl, gridDataProperty, 20, filters(), gridDataTotalRows, false);

        dispatchesGridViewModel(dispatchesGrid);
    };

    amplify.subscribe('gridDataIsLoaded', function (data) {
        $("#preloader").delay(400).fadeOut(600);
    });

    var loadDispatches = function () {
        initDispatchGridviewModel("getdispatches", "ListDispatches", "TotalRows");
    };

    return {
        init: init,
        dispatchesGridViewModel: dispatchesGridViewModel,
        openDetail: openDetail,
        selectedCustomerId: selectedCustomerId,
        customerList: customerList,
        changeCustomer: changeCustomer,
        canViewSubUnits: canViewSubUnits
    }
}