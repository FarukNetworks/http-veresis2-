var dispatchDetailViewModel = function () {
    var dispatch = ko.observableArray();
    var boxes = ko.observableArray();
    var productionOrders = ko.observableArray();
    var dispatchCode = ko.observable(); 
	   

    var getDispatchDetail = function (dispatchId) {
        requesthandler.defineRequest('dispatchdetail','getdispatch/' + dispatchId);
        amplify.request({
            resourceId: 'dispatchdetail',
            success: function (data) {
                if (data == null) return;
                dispatch(data);
                boxes(JSON.parse(data[0].BoxList));
                productionOrders(JSON.parse(data[0].ProductionOrdersList));
                dispatchCode(data[0].DispatchNumber);
                $("#preloader").delay(400).fadeOut(600);
            },
            error: function (msg) {
                toastr.error(msg);
                $("#preloader").delay(400).fadeOut(600);
            }
        });
    } 

	var init = function (dispatchId) {
		ko.applyBindings(this);

        getDispatchDetail(dispatchId);
	}    

    return {
        init: init,
        boxes: boxes,
        productionOrders: productionOrders,
        dispatch: dispatch,
        dispatchCode: dispatchCode
    }
}