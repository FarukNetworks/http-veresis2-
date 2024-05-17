var dataGridViewModel = function() {
    
    var gridData = ko.observable();
    var gridDataProperty = ko.observable();
    var gridDataTotalRowsProperty = ko.observable();
    var gridColumns = ko.observableArray();
    var requestUrl = ko.observable();

    var pageIndex = ko.observable();
    var pageSize = ko.observable(10);
    var pageSizeOptions = [5, 10, 20, 30, 50, 100];
    var totalRows = ko.observable(0);
    var totalPages = ko.observable(0);
    var requestedPage = ko.observable(0);
    //var selectedPageSizeOption = ko.observable(10);
    var selectedPageSizeOption = ko.observable();

    var sortField = ko.observable("");
    var sortOrder = ko.observable("ASC");
    
    var filterModel = ko.observable();

    //-----------------------------------------//
    // expand option
    //-----------------------------------------//
    var isExpandableGrid = ko.observable();

    var gridParams = function () {
        if (filterModel() !== undefined || filterModel() == null) {
            //extend filter model with paging and sorting
            var model = filterModel();
            model.pageIndex = pageIndex(),
            model.pageSize = pageSize(),
            model.sortField = sortField(),
            model.sortOrder = sortOrder();
            return model;
        } else {
            return {
                pageIndex: pageIndex(),
                pageSize: pageSize(),
                sortField: sortField(),
                sortOrder: sortOrder()
            }
        }
    };
    
    var refreshGrid = function () {
        //console.log(ko.toJSON(gridParams()));
        requesthandler.defineRequest("datagridresource", requestUrl());
        amplify.request({
            resourceId: "datagridresource",
            data: gridParams(),
            success: function (griddata) {
                if (isExpandableGrid()) {
                    var arrayMap = [];
                    arrayMap = ko.utils.arrayMap(griddata[gridDataProperty()], function(item) {
                        var obj = item;
                        obj.selectedDetailPanelObject = ko.observable(null);
                        obj.isExpanded = ko.observable("");
                        return obj;
                    });
                    gridData(arrayMap);
                } else {
                    gridData(griddata[gridDataProperty()]);
                }
                
                totalRows(griddata[gridDataTotalRowsProperty()]);
                updatePageCounters();

                amplify.publish('gridDataIsLoaded', griddata[gridDataProperty()]);

            },
            error: function (error) {
                
                amplify.publish('onDataGridError', error.error_message);

                // toastr.error(error.error_message);
            }
        });
    };

    var updatePageCounters = function () {
        totalPages(Math.ceil(totalRows() / pageSize()));
    };

    var sortGrid = function (obj) {
        if (obj.sort == undefined) {
            return;
        };

        ko.utils.arrayForEach(gridColumns(), function(item) {
            item.isSort(false);
            if (item.sort) {
                item.cssClass("sorting");
            } else {
                item.cssClass("");
            }
        });

        if (obj.sort) {
            var prop = obj.field;
            if (sortField() === prop) {
                if (sortOrder() === "ASC") {
                    sortOrder("DESC");
                    obj.cssClass("sorting_desc");
                } else {
                    sortOrder("ASC");
                    obj.cssClass("sorting_asc");
                }
                obj.isSort(true);
            } else {
                sortOrder("ASC");
                obj.cssClass("sorting_asc");
                sortField(prop);
                obj.isSort(true);
            }
            refreshGrid();
        }
    };

    /// adds colour to the whole row depending on the status of the ticket
    var addColorToRow = function (obj) {
        var color = "";

        if (obj.TicketStatusId === 1) {
            color = "active";
        } else if (obj.TicketStatusId === 2) {
            color = "info";
        } else if (obj.TicketStatusId === 3) {
            color = "warning";
        } else if (obj.TicketStatusId === 4) {
            color = "success";
        } else {
            color = "";
        }

        return color;
    };

    /// calculates days and hours from when ticket was created
    var daysFromTicketCreated = function (obj) {
        var today = new Date();
        var date = parseInt(obj.SubmissionDate.substring(obj.SubmissionDate.indexOf('(') + 1, obj.SubmissionDate.indexOf(')')));

        var sec = (today / 1000) - (date / 1000);
        var t = parseInt(sec);
        var days;
        if (t > 86400) {
            days = parseInt(t / 86400); t = t - (days * 86400);
        }
        var hours = parseInt(t / 3600);
        t = t - (hours * 3600);

        var daysStr = "d";
        var hoursStr = "h";

        var content = "";
        if (days) { content += days + daysStr; }
        if (hours || days && hours !== 0) { if (content) content += " "; content += hours + hoursStr; }
        if (hours < 1) { content += "<1 " + hoursStr; }
        if (days > 0 && hours < 1) { content = ""; content += days + daysStr; }

        return content;
    };

    /// calculates days and hours from when ticket was updated
    var daysFromTicketUpdated = function(obj) {
        var today = new Date();
        var date = parseInt(obj.UpdatedDate.substring(obj.UpdatedDate.indexOf('(') + 1, obj.UpdatedDate.indexOf(')')));

        var sec = (today / 1000) - (date / 1000);
        var t = parseInt(sec);
        var days;
        if (t > 86400) {
            days = parseInt(t / 86400); t = t - (days * 86400);
        }
        var hours = parseInt(t / 3600);
        t = t - (hours * 3600);

        var daysStr = "d";
        var hoursStr = "h";

        var content = "";
        if (days) { content += days + daysStr; }
        if (hours || days && hours !== 0) { if (content) content += " "; content += hours + hoursStr; }
        if (hours < 1) { content += "<1 " + hoursStr; }
        if (days > 0 && hours < 1) { content = ""; content += days + daysStr; }

        return content;
    }

    var goToFirstPage = function() {
        pageIndex(1);
        requestedPage(1);

        refreshGrid();
    };

    var goToPrevPage = function() {
        if (requestedPage() > 1) {
            var newPage = requestedPage() - 1;
            pageIndex(newPage);
            requestedPage(newPage);
            refreshGrid();
        }
    }

    var goToNextPage = function() {
        if (requestedPage() < totalPages()) {
            var newPage = requestedPage() + 1;
            pageIndex(newPage);
            requestedPage(newPage);
            refreshGrid();
        }
    };

    var goToLastPage = function() {
        pageIndex(totalPages());
        requestedPage(totalPages());

        refreshGrid();
    }

    var goToPage = function () {
        var ri = parseInt(requestedPage(), 10);

        if (ri === NaN) {
            requestedPage(pageIndex());
        }

        if (ri > 0 && ri <= totalPages()) {
            pageIndex(ri);
        }

        requestedPage(pageIndex());
        refreshGrid();
    };

    var changePageSize = function () {
        if (pageSize() !== selectedPageSizeOption()) {
            pageSize(selectedPageSizeOption());
            pageIndex(1);
            requestedPage(1);
            refreshGrid();
        }
    };

    selectedPageSizeOption.subscribe(changePageSize);

    var bindFromQueryString = function(pgSize, filters) {
        if (location.search == null || location.search !== "") {
            var obj = util.queryStringToJsObject();
            pageSize(parseInt(obj.pageSize));
            pageIndex(parseInt(obj.pageIndex));
            sortField(obj.sortField);
            sortOrder(obj.sortOrder);
            requestedPage(parseInt(obj.pageIndex));
            filterModel(filters);
            selectedPageSizeOption(parseInt(obj.pageSize));
        } else {
            pageSize(pgSize);
            pageIndex(1);
            requestedPage(1);
            filterModel(filters);
            selectedPageSizeOption(pgSize);
        }
    };

    //check if null or empty
    var isEmpty = function (str) {
        var ret = false;
        if (typeof str == "undefined") {
            ret = true;
        } else if (str == null) {
            ret = true;
        } else if (str === NaN && str === 0) {
            ret = true;
        } else if (typeof str == "string") {
            ret = str.trim().length === 0;
        }
        return ret;
    };

    var init = function (container, columns, url, gridRowsProperty, pgSize, filters, gridTotalRowsProperty, expandable) {
        var cols = [];

        ko.utils.arrayForEach(columns, function (item) {
            var newCol = item;
            if (newCol.sort) {
                newCol.cssClass = ko.observable("sorting");
            } else {
                newCol.cssClass = ko.observable("");
            }
            newCol.isSort = ko.observable(false);
            cols.push(newCol);
        });

        gridColumns(cols);
        requestUrl(url);
        gridDataProperty(gridRowsProperty);

        if (isEmpty(gridTotalRowsProperty)) {
            gridDataTotalRowsProperty("TotalRows");
        } else {
            gridDataTotalRowsProperty(gridTotalRowsProperty);
        }

        if (isEmpty(expandable))
            isExpandableGrid(false);
        else {
            isExpandableGrid(expandable);
        }

        filterModel(filters);

        if (selectedPageSizeOption() == undefined) {
            selectedPageSizeOption(pgSize);
        }
        //bindFromQueryString(pgSize, filters);
        bindFromQueryString(selectedPageSizeOption(), filters);
        refreshGrid();

        return this;
    };

    return {
        init: init,
        gridData: gridData,
        gridParams: gridParams,
        pageSizeOptions: pageSizeOptions,
        changePageSize: changePageSize,
        totalPages: totalPages,
        requestedPage: requestedPage,
        sortGrid: sortGrid,
        goToPage: goToPage,
        pageIndex: pageIndex,
        goToFirstPage: goToFirstPage,
        goToPrevPage: goToPrevPage,
        goToNextPage: goToNextPage,
        goToLastPage: goToLastPage,
        selectedPageSizeOption: selectedPageSizeOption,
        gridColumns: gridColumns,
        filterModel: filterModel,
        addColorToRow: addColorToRow,
        daysFromTicketCreated: daysFromTicketCreated,
        isExpandableGrid: isExpandableGrid,
        daysFromTicketUpdated: daysFromTicketUpdated
    };
};
