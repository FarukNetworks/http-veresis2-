//define(['jquery', 'ko'], function($, ko) {
var formatters = function() {

    var timeFormatter = function(inTimeObject) {
        if (inTimeObject.Hours === 0 && inTimeObject.Minutes === 0) return "";
        var hour = (inTimeObject.Hours < 10) ? "0" + inTimeObject.Hours : inTimeObject.Hours;
        var min = (inTimeObject.Minutes < 10) ? "0" + inTimeObject.Minutes : inTimeObject.Minutes;
        return hour + ":" + min;
    }; 

    var dateFormatter = function(dateObject) {
        if (dateObject != undefined)
            return moment(dateObject).format('DD.MM.YYYY');
        else
            return "";
    };

    var dateTimeFormatter = function(dateTimeObject) {
        if (dateTimeObject != undefined)
            return moment(dateTimeObject).format('DD.MM.YYYY, HH:mm:ss');
        else
            return "";
    };

    var currentDate = function() {
        return moment().format("DD.MM.YYYY");
    };

    var currentDateTime = function() {
        return moment().format("DD.MM.YYYY, HH:mm");
    };

    var formatInputAsTime = function(object) {
        var str = object.value;
        if (/^[a-zA-Z0-9:,]*$/.test(str) == false) {
            toastr.error("Vnos ni pravilne oblike");
        }
    };

    var formatToTimeString = function(data) {
        if (data > 0) {
            var hours = parseInt(data);
            var minutes = parseFloat(data) * 60;
            minutes = Math.round(minutes);
            minutes = minutes - (hours * 60);
            return hours + "h " + minutes + "m";
        } else {
            return "0";
        }
    };

    var booleanFormatter = function (value) {
        if (value == 1)
            return "DA";

        return "NE";
    };

    var postBooleanFormatter = function (value) {
        if (value)
            return "Pošta";

        return "Lastna odprema";
    };

    var convertFloatToDateTime = function(value) {
        var koDate = value;
        if (value.indexOf("/Date(") !== -1) {
            var x = new Date(parseFloat(koDate.replace(/[^0-9]/g, '')));
            return moment(x).format("DD.MM.YYYY, HH:mm");
        } else {
            return value;
        }
    };

    var convertFloatToDate = function (value) {
        var koDate = value;
        if (value.indexOf("/Date(") !== -1) {
            var x = new Date(parseFloat(koDate.replace(/[^0-9]/g, '')));
            return moment(x).format("DD.MM.YYYY");
        } else {
            return value;
        }
    };

    var convertFloatToTime = function (value) {
        var koDate = value;
        if (value.indexOf("/Date(") !== -1) {
            var x = new Date(parseFloat(koDate.replace(/[^0-9]/g, '')));
            return moment(x).format("HH:mm");
        } else {
            return value;
        }
    };

    var stickerFormatter = function (row, cell, value, columnDef, dataContext) {
            if (value === null) return "";
            var s = '<img src="/service/api/sticker/24/' + dataContext.administrativeUnitStickerName + '" alt="' + value + '" title="' + value + '"  style="margin-top: -5px;"/>';
            s += "&nbsp;";
            s += value;
            return s;
        };

    var stickerFormatterFromValue = function (administrativeUnitName, administrativeUnitSticker) {
        if (administrativeUnitName === null || administrativeUnitSticker === null) return "";
        var s = '<img src="data:image/png;base64,' + administrativeUnitSticker + '" alt="' + administrativeUnitName + '" title="' + administrativeUnitName + '"  style="margin-top: -5px;"/>';
        s += "&nbsp;";
        s += administrativeUnitName;
        return s;
    };

    var orderNumberFormatter = function (obj) {
        var s = obj.OrderNumber;
        s += "&nbsp;-&nbsp;";
        s += obj.ExternalCode;
        return s;
    };
    var algorythmFormatterFromValue = function (value) {
        if (value == 1)
            //var x = language.processAlgorythmFormatter.standardProcess;
            return language.processAlgorythmFormatter.standardProcess;
        else if (value == 2)
            return language.processAlgorythmFormatter.selfAdhesiveProcess;
        return "-";
    };
    var algorythmFormatter = function (row, cell, value, columnDef, dataContext) {
        return algorythmFormatterFromValue(value);
    };
    var productionOrderStateFormatterFromValue = function(value) {
        if (value == 1) {
            return language.stateFormatters.productionOrder.created;
        } else if (value == 2) {
            return language.stateFormatters.productionOrder.assignedToMachine;
        } else if (value == 3) {
            return language.stateFormatters.productionOrder.readyForProduction;
        } else if (value == 4) {
            return language.stateFormatters.productionOrder.inProduction;
        } else if (value == 5) {
            return language.stateFormatters.productionOrder.produced;
        } else if (value == 6) {
            return language.stateFormatters.productionOrder.shipped;
        } else if (value == 7) {
            return language.stateFormatters.productionOrder.productionPaused;
        } else if (value == 8) {
            return language.stateFormatters.productionOrder.cancelled;
        } else if (value == 9) {
            return language.stateFormatters.productionOrder.allPressed;
        }
    };
    var productionOrderStateFormatter = function(row, cell, value, columnDef, dataContext) {
        return productionOrderStateFormatterFromValue(value);
    };
    var orderStateFormatterFromValue = function (value) {
        var state = "";
        if (value == 1) {
            state = language.stateFormatters.order.newOrder;
        } else if (value == 2) {
            state = language.stateFormatters.order.hasNumberInterval;
        } else if (value == 3) {
            state = language.stateFormatters.order.partiallyPocessed;
        } else if (value == 4) {
            state = language.stateFormatters.order.processed;
        } else if (value == 5) {
            state = language.stateFormatters.order.missingNumberSpace;
        } else if (value == 6) {
            state = language.stateFormatters.order.cancelled;
        } else if (value == 7) {
            state = language.stateFormatters.order.shipped;
        } else if (value == 8) {
            state = language.stateFormatters.order.partiallyShipped;
        }
        return state;
    };
    var orderStateFormatterCssClassFromValue = function (value) {
        var cssClass = "";
        if (value == 1) {
            cssClass = language.stateFormatters.orderCssClass.newOrder;
        } else if (value == 2) {
            cssClass = language.stateFormatters.orderCssClass.hasNumberInterval;
        } else if (value == 3) {
            cssClass = language.stateFormatters.orderCssClass.partiallyPocessed;
        } else if (value == 4) {
            cssClass = language.stateFormatters.orderCssClass.processed;
        } else if (value == 5) {
            cssClass = language.stateFormatters.orderCssClass.missingNumberSpace;
        } else if (value == 6) {
            cssClass = language.stateFormatters.orderCssClass.cancelled;
        } else if (value == 7) {
            cssClass = language.stateFormatters.orderCssClass.shipped;
        } else if (value == 8) {
            cssClass = language.stateFormatters.orderCssClass.partiallyShipped;
        }
        return cssClass;
    };

    var orderDetailStateFormatterCssClassFromValue = function (value) {
        var cssClass = "";
        if (value == 1) {
            cssClass = language.stateFormatters.orderDetailCssClass.newOrder;
        } else if (value == 2) {
            cssClass = language.stateFormatters.orderDetailCssClass.hasNumberInterval;
        } else if (value == 3) {
            cssClass = language.stateFormatters.orderDetailCssClass.partiallyPocessed;
        } else if (value == 4) {
            cssClass = language.stateFormatters.orderDetailCssClass.processed;
        } else if (value == 5) {
            cssClass = language.stateFormatters.orderDetailCssClass.missingNumberSpace;
        } else if (value == 6) {
            cssClass = language.stateFormatters.orderDetailCssClass.cancelled;
        } else if (value == 7) {
            cssClass = language.stateFormatters.orderDetailCssClass.shipped;
        } else if (value == 8) {
            cssClass = language.stateFormatters.orderDetailCssClass.partiallyShipped;
        }
        return cssClass;
    };

    var orderStateFormatter = function (row, cell, value, columnDef, dataContext) {
        return orderStateFormatterFromValue(value);
    };
    var selfAdhesivePrintStateFormatter = function (row, cell, value, columnDef, dataContext) {
        switch (value) {
            case 1:
                return language.stateFormatters.selfAdhesivePrint.newSelfAdhesivePrint;
            case 2:
                return language.stateFormatters.selfAdhesivePrint.pdfCreated;
            case 3:
                return language.stateFormatters.selfAdhesivePrint.printed;
            case 9:
                return language.stateFormatters.selfAdhesivePrint.cancelled;
            default:
                break;
        }
    };
    var plateStateFormatterFromValue = function (value) {
        switch (value) {
            case 1:
                return language.stateFormatters.plate.newPlate;
            case 2:
                return language.stateFormatters.plate.knownEanCode;
            case 3:
                return language.stateFormatters.plate.pressed;
            case 4:
                return language.stateFormatters.plate.stickerIsGlued;
            case 5:
                return language.stateFormatters.plate.packed;
            case 6:
                return language.stateFormatters.plate.cancelled;
            case 7:
                return language.stateFormatters.plate.rejected;
            default:
                return "";
        }
    };
    var plateStateFormatter = function (row, cell, value, columnDef, dataContext) {
        return plateStateFormatterFromValue(value);
    };
    var bagStateFormatterFromValue = function (value) {
        switch (value) {
            case 1:
                return language.stateFormatters.bag.newBag;
            case 2:
                return language.stateFormatters.bag.packed;
            case 3:
                return language.stateFormatters.bag.labelIsPrinted;
            case 4:
                return language.stateFormatters.bag.shipped;
            case 5:
                return language.stateFormatters.bag.cancelled;
            case 6:
                return language.stateFormatters.bag.rejected;
            case 7:
                return language.stateFormatters.bag.rejectionreplaced;
            default:
                return "";
        }
    };

    var DestroyedPackageStateFormatterFromValue = function (value) {
        switch (value) {
            case 0:
                return language.stateFormatters.package.unknown;
            case 1:
                return language.stateFormatters.package.new;
            case 2:
                return language.stateFormatters.package.inProcess;
            case 3:
                return language.stateFormatters.package.destroyed;
            case 9:
                return language.stateFormatters.package.cancelled;
            default:
                return "";
        }
    };
    var bagStateFormatter = function (row, cell, value, columnDef, dataContext) {
        return bagStateFormatterFromValue(value);
    };
    var orderDetailLogStateFormatter = function (row, cell, value, columnDef, dataContext) {
        switch (dataContext.entityName) {
            case "Order":
                return orderStateFormatterFromValue(value);
            case "ProductionOrder":
                return productionOrderStateFormatterFromValue(value);
            case "Plate":
                return plateStateFormatterFromValue(value);
            case "Bag":
                return bagStateFormatterFromValue(value);
            default:
                return "";
        }
    };
    var orderTypeFormatterFromValue = function (value) {
        return (value == 2) ? language.orderTypeFormatter.Service : language.orderTypeFormatter.Serie;
    };
    var orderTypeFormatter = function (row, cell, value, columnDef, dataContext) {
        //return (value == 2) ? language.orderTypeFormatter.Service : language.orderTypeFormatter.Serie;
        return orderTypeFormatterFromValue(value);
    };
    var formatState = function (value) {
                return "<span style='color: #C8D200;'>" + orderStateFormatterFromValue(value).toUpperCase() + "</span>";
            };
    var formatOrderType = function (value) {
        var val = "";
        if (value == 1) {
            val = "<span style='color: #2382C8;'>" + orderTypeFormatterFromValue(value).toUpperCase() + "</span>";
        } else if (value == 2) {
            val = "<span style='color: #C8D200;'>" + orderTypeFormatterFromValue(value).toUpperCase() + "</span>";
        }
        return val;

    };
    var productionMachineTypeFormatter = function (row, cell, value, columnDef, dataContext) {
        var type = "";
        if (value == 1)
            type = language.productionMachineFormatter.press;
        else if (value == 2)
            type = language.productionMachineFormatter.stikers;
        else if (value == 3)
            type = language.productionMachineFormatter.packing;
        else if (value == 4)
            type = language.productionMachineFormatter.labels;
        else if (value === 5)
            type = language.productionMachineFormatter.dispatching;
        else if (value === 6)
            type = language.productionMachineFormatter.robot;
        else if (value === 7)
            type = language.productionMachineFormatter.plateDestroyer;
        return type;
    };

    var dispatchStateFormatterFromValue = function(value) {
        var state = "";
        if (value == 1)
            state = language.stateFormatters.dispatchFormmater.New;
        else if (value == 2)
            state = language.stateFormatters.dispatchFormmater.deliveryNoteAssigned;
        else if (value == 3)
            state = language.stateFormatters.dispatchFormmater.dispatched;
        else if (value == 4)
            state = language.stateFormatters.dispatchFormmater.cancelled;
        
        return state;
    };
    var dispatchStateFormatter = function (row, cell, value, columnDef, dataContext) {
        return dispatchStateFormatterFromValue(value);
    };

    var boxStateFormatterFromValue = function (value) {
        var state = "";
            if (value == 1)
                state = language.stateFormatters.box.New;
                
            else if (value == 2)
                state = language.stateFormatters.box.Created;
                
            else if (value == 3)
                state = language.stateFormatters.box.Closed;
                
            else if (value == 4)
                state = language.stateFormatters.box.PostalLabelPrinted;
                
            else if (value == 5)
                state = language.stateFormatters.box.AssignedToDispatch;
                
            else if (value == 9)
                state = language.stateFormatters.box.Cancelled;
                
        return state;
    };
    var boxStateFormatter = function(row, cell, value, columnDef, dataContext) {
        return boxStateFormatterFromValue(value);
    };    

    return {
        timeFormatter: timeFormatter,
        dateTimeFormatter: dateTimeFormatter,
        dateFormatter: dateFormatter,
        currentDate: currentDate,
        currentDateTime: currentDateTime,
        formatToTimeString: formatToTimeString,
        booleanFormatter: booleanFormatter,
        postBooleanFormatter: postBooleanFormatter,
        convertFloatToDateTime: convertFloatToDateTime,
        convertFloatToDate: convertFloatToDate,
        convertFloatToTime: convertFloatToTime,
        stickerFormatter: stickerFormatter,
        stickerFormatterFromValue: stickerFormatterFromValue,
        orderNumberFormatter: orderNumberFormatter,
        algorythmFormatter: algorythmFormatter,
        algorythmFormatterFromValue: algorythmFormatterFromValue,
        productionOrderStateFormatter: productionOrderStateFormatter,
        productionOrderStateFormatterFromValue: productionOrderStateFormatterFromValue,
        orderTypeFormatter: orderTypeFormatter,
        orderTypeFormatterFromValue: orderTypeFormatterFromValue,
        orderStateFormatter: orderStateFormatter,
        orderStateFormatterFromValue: orderStateFormatterFromValue,
        orderStateFormatterCssClassFromValue: orderStateFormatterCssClassFromValue,
        orderDetailStateFormatterCssClassFromValue: orderDetailStateFormatterCssClassFromValue,
        productionMachineTypeFormatter: productionMachineTypeFormatter,
        selfAdhesivePrintStateFormatter: selfAdhesivePrintStateFormatter,
        plateStateFormatter: plateStateFormatter,
        plateStateFormatterFromValue: plateStateFormatterFromValue,
        bagStateFormatterFromValue: bagStateFormatterFromValue,
        bagStateFormatter: bagStateFormatter,
        orderDetailLogStateFormatter: orderDetailLogStateFormatter,
        dispatchStateFormatter: dispatchStateFormatter,
        dispatchStateFormatterFromValue: dispatchStateFormatterFromValue,
        boxStateFormatterFromValue: boxStateFormatterFromValue,
        boxStateFormatter: boxStateFormatter,
        formatOrderType: formatOrderType,
        formatState: formatState,
        DestroyedPackageStateFormatterFromValue: DestroyedPackageStateFormatterFromValue
    };
};
var formatters = new formatters();
//});