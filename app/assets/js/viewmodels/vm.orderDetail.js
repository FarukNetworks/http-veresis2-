var orderDetailViewModel = function () {
    var order = ko.observableArray();
    var productionOrders = ko.observableArray();
    var dispatches = ko.observableArray();
    var orderCode = ko.observable();
    var orderExternal = ko.observable();
	   
    var openDispatchDetail = function (dispatch) {
        window.location.href = requesthandler.getCorrectSiteUrl('odpreme/' + dispatch.Id);
    }



    var getOrderDetail = function (orderId) {
        requesthandler.defineRequest('orderdetail','getorder/' + orderId);
        amplify.request({
            resourceId: 'orderdetail',
            success: function (data) {
                if (data == null) return;
                order(data.orders[0]);
                if (!util.isEmpty(data.orders[0].ProductionOrders))
                    productionOrders(JSON.parse(data.orders[0].ProductionOrders));
                else {
                    productionOrders([]);
                }
                dispatches(data.dispatches);
                orderCode(data.orders[0].OrderNumber);
                orderExternal(data.orders[0].ExternalCode);
                $("#preloader").delay(400).fadeOut(600);
            },
            error: function (msg) {
                toastr.error(msg);
                $("#preloader").delay(400).fadeOut(600);
            }
        });   
    } 

	var init = function (orderId) {
		ko.applyBindings(this);

        getOrderDetail(orderId);
	}    

    return {
        init: init,
        productionOrders: productionOrders,
        dispatches: dispatches,
        order: order,
        openDispatchDetail: openDispatchDetail,
        orderCode: orderCode,
        orderExternal: orderExternal
    }
}