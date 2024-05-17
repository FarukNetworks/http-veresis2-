var boxDetailViewModel = function () {
    var box = ko.observableArray();
    var inbox = ko.observableArray();
	var boxCode = ko.observable(); 

    var getBoxDetail = function (boxId) {
        requesthandler.defineRequest('boxdetail','getbox/' + boxId);
        amplify.request({
            resourceId: 'boxdetail',
            success: function (data) {
                if (data == null) return;
                box(data);
                boxCode(data[0].Code);
                inbox(JSON.parse(data[0].BoxContent));
                $("#preloader").delay(400).fadeOut(600);
            },
            error: function (msg) {
                toastr.error(msg);
                $("#preloader").delay(400).fadeOut(600);
            }
        });    
    } 

	var init = function (boxId) {
		ko.applyBindings(this);

        getBoxDetail(boxId);
	}    

    return {
        init: init,
        inbox: inbox,
        box: box,
        boxCode: boxCode
    }
}