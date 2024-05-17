var orderViewModel = function () {

	var ordersGridViewModel = ko.observable(null);
	var ordersGrid;
	var selectedCustomerId = ko.observable();
	var customerList = ko.observableArray();
    var canViewSubUnits = ko.observable();

    //ordertypes filters
    var orderTypes = ko.observableArray();
    var orderTypeSelection = ko.observableArray();
    var fillOrderTypes = function () {
        orderTypes.push({ code: 1, name: formatters.orderTypeFormatterFromValue(1) });
        orderTypes.push({ code: 2, name: formatters.orderTypeFormatterFromValue(2) });
    };

    //order state filter
    var orderStates = ko.observableArray();
    var orderStateSelection = ko.observableArray();
    var fillOrderStates = function () {
        orderStates.push({ code: 1, name: formatters.orderStateFormatterFromValue(1) });
        orderStates.push({ code: 2, name: formatters.orderStateFormatterFromValue(2) });
        orderStates.push({ code: 3, name: formatters.orderStateFormatterFromValue(3) });
        orderStates.push({ code: 4, name: formatters.orderStateFormatterFromValue(4) });
        orderStates.push({ code: 6, name: formatters.orderStateFormatterFromValue(6) });
        orderStates.push({ code: 7, name: formatters.orderStateFormatterFromValue(7) });
        orderStates.push({ code: 8, name: formatters.orderStateFormatterFromValue(8) });
    };

    var clearFilters = function () {
        orderStateSelection([]);
        orderTypeSelection([]);
        loadOrders();
        history.pushState(null, null, requesthandler.getCorrectSiteUrl("narocila"));        
    };

    var executeFilters = function () {
        history.pushState(null, null, requesthandler.getCorrectSiteUrl("narocila?" + $.param(ordersGrid.gridParams())));
        loadOrders();
    };

	var filters = function () {
		return {
			customerId: selectedCustomerId(),
            orderStateSelection: orderStateSelection,
            orderTypeSelection: orderTypeSelection  
		}
	};

    var bindFilters = function(obj) {
        //selectedCustomerId(obj.customerId);
                
        if (obj.orderStateSelection != undefined) {
            if (obj.orderStateSelection.indexOf(",") !== -1) {
                orderStateSelection(obj.orderStateSelection.split(","));
            } else {
                orderStateSelection([obj.orderStateSelection]);
            }            
        }
        if (obj.orderTypeSelection != undefined) {
            if (obj.orderTypeSelection.indexOf(",") !== -1) {
                orderTypeSelection(obj.orderTypeSelection.split(","));
            } else {
                orderTypeSelection([obj.orderTypeSelection]);
            }            
        }


        loadOrders();
    };

    var changeCustomer = function () {
        loadOrders();
    };

    
    var getCustomers = function () {
        requesthandler.defineRequest('customers','getcustomers');
        amplify.request({
            resourceId: 'customers',
            success: function (data) {
                customerList(data.customers);
                canViewSubUnits(data.canViewSubUnits);

                //loadOrders();
            },
            error: function (msg) {
                toastr.error(msg);
            }
        });    
    } 

	var init = function () {
		ko.applyBindings(this);
        
        fillOrderTypes();
        fillOrderStates();
        
        getCustomers();

        if (!util.isEmpty(window.location.search)) {
            var obj = util.queryStringToJsObject(window.location.search
                .substr(1, window.location.search.length));
            bindFilters(obj);
        } else {
            clearFilters();
        }
	}

	var openDetail = function (order) {
        window.location.href = requesthandler.getCorrectSiteUrl('narocila/' + order.OrderId);
	}

	var initOrderGridviewModel = function (requestUrl, gridDataProperty, gridDataTotalRows) {
        var columns = [
            { field: "OrderNumber", title: "Št. naročila", sort: true },
            { field: "OrderDate", title: "Datum", sort: true },
            { field: "CustomerName", title: "Naročnik", sort: true },
            { field: "Quantity", title: "Količina", sort: true },
            { field: "ProductTypeName", title: "Produkt", sort: true },
            { field: "State", title: "Stanje", sort: true },
            { field: "OrderType", title: "Tip", sort: true },
            { field: "DistrictCode", title: "Območje", sort: true },
            { field: "AdministrativeUnitName", title: "Grb", sort: true },
            { field: "IntervalFrom", title: "Od", sort: true },
            { field: "IntervalTo", title: "Do", sort: true }            
        ];

        ordersGrid = new dataGridViewModel().init("#listGrid", columns, requestUrl, gridDataProperty, 20, filters(), gridDataTotalRows, false);

        ordersGridViewModel(ordersGrid);

    };

    amplify.subscribe('gridDataIsLoaded', function (data) {
        $("#preloader").delay(400).fadeOut(600);
    });

    var loadOrders = function () {
        initOrderGridviewModel("getorders", "ListOrders", "TotalRows");
    };

    return {
        init: init,
        ordersGridViewModel: ordersGridViewModel,
        openDetail: openDetail,
        selectedCustomerId: selectedCustomerId,
        customerList: customerList,
        changeCustomer: changeCustomer,
        canViewSubUnits: canViewSubUnits,
        orderTypes: orderTypes,
        orderStates: orderStates,
        clearFilters: clearFilters,
        executeFilters: executeFilters,
        orderTypeSelection: orderTypeSelection,
        orderStateSelection: orderStateSelection
    }
}