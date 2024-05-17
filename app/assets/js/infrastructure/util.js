//define(['jquery','ko', 'i/requesthandler', 'amplify'], function($, ko, requesthandler, amplify) {
var CrmUtils = function () {
    /*************************************************************************************************************************
     * Boostrap DatetimePicker binding handler
     * used with <input type="text" data-bind="customDateTimePicker: { format: 'DD.MM.YYYY' }, value: observableValue" />
     * https://eonasdan.github.io/bootstrap-datetimepicker/
     *************************************************************************************************************************/
    var initDateTimePickerBinding = function () {
        
        ko.bindingHandlers.customDateTimePicker = {
            init: function (element, valueAccessor, allBindings, viewModel, bindingContext) {

                var options = ko.unwrap(valueAccessor());
                var valueObservable = allBindings.get('value');

                options.locale = moment.locale('sl');
                
                var defaults = {
                    stepping: 1,
                    showTodayButton: true
                };

                var config = ko.utils.extend(defaults, options); //override defaults with passed in options
                var $pickerElement = $(element).datetimepicker(config);
                
                if (valueObservable() != undefined) {
                    //in case return from server datetime i am get in this form for example /Date(93989393)/ then fomat this
                    var koDate = valueObservable();
                    koDate = (typeof (koDate) !== 'object') ? new Date(parseFloat(koDate.replace(/[^0-9]/g, ''))) : koDate;
                    $pickerElement.data("DateTimePicker").date(koDate);
                    valueObservable(moment(koDate).format(options.format));
                    //valueObservable(moment(koDate).format("DD.MM.YYYY"));
                }
                
                $pickerElement.bind('dp.change', function (eventObj) {
                    var picker = $(element).data("DateTimePicker");
                    if (picker) {
                        var date = picker.date();
                        var formattedDate = date ? date.format(picker.format()) : "";
                        if (formattedDate != valueObservable()) {
                            valueObservable(formattedDate);
                        }
                    }
                });

                ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
                    var picker = $(element).data("DateTimePicker");
                    if (picker) {
                        picker.destroy();
                    }
                });

            },
            update: function (element, valueAccessor, allBindings, viewModel, bindingContext) {

                var picker = $(element).data("DateTimePicker");
                if (picker) {
                    var valueObservable = allBindings.get('value');
                    var date = picker.date();
                    var formattedDate = date ? date.format(picker.format()) : undefined;
                    if (formattedDate !== valueObservable()) {
                        picker.date(valueObservable() == undefined ? null : valueObservable());
                    }
                }
            }
        };
    };
    
    var initTinyMceEditor = function () {
        var instances_by_id = {}; // needed for referencing instances during updates.
        var init_queue = $.Deferred(); // jQuery deferred object used for creating TinyMCE instances synchronously
        init_queue.resolve();

        ko.bindingHandlers.tinymce = {
            init: function (element, valueAccessor, allBindingsAccessor, context) {

                var options = allBindingsAccessor().tinymceOptions || {};
                var modelValue = valueAccessor();
                var value = ko.utils.unwrapObservable(valueAccessor());
                var $element = $(element);

                options.setup = function (ed) {
                    ed.on('change', function (e) {
                        if (ko.isWriteableObservable(modelValue)) {
                            var current = modelValue();
                            if (current !== this.getContent()) {
                                modelValue(this.getContent());
                            }
                        }
                    });
                    ed.on('keyup', function (e) {
                        if (ko.isWriteableObservable(modelValue)) {
                            var current = modelValue();
                            var editorValue = this.getContent({ format: 'raw' });
                            if (current !== editorValue) {
                                modelValue(editorValue);
                            }
                        }
                    });
                    ed.on('beforeSetContent', function (e, l) {
                        if (ko.isWriteableObservable(modelValue)) {
                            if (typeof (e.content) !== 'undefined') {
                                var current = modelValue();
                                if (current !== e.content) {
                                    modelValue(e.content);
                                }
                            }
                        }
                    });
                };

                //handle destroying an editor 
                ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
                    $(element).parent().find("span.mceEditor,div.mceEditor").each(function (i, node) {
                        var tid = node.id.replace(/_parent$/, ''),
                            ed = tinymce.get(tid);
                        if (ed) {
                            ed.remove();
                            // remove referenced instance if possible.
                            if (instances_by_id[tid]) {
                                delete instances_by_id[tid];
                            }
                        }
                    });
                });

                setTimeout(function () {
                    if (!element.id) {
                        element.id = tinymce.DOM.uniqueId();
                    }
                    tinyMCE.init(options);
                    tinymce.execCommand("mceAddEditor", true, element.id);
                }, 0);
                $element.html(value);

            },
            update: function (element, valueAccessor, allBindingsAccessor, context) {
                var $element = $(element),
                    value = ko.utils.unwrapObservable(valueAccessor()),
                    id = $element.attr('id');

                //handle programmatic updates to the observable
                // also makes sure it doesn't update it if it's the same. 
                // otherwise, it will reload the instance, causing the cursor to jump.
                if (id !== undefined) {
                    var tinymceInstance = tinyMCE.get(id);
                    if (!tinymceInstance)
                        return;
                    var content = tinymceInstance.getContent({ format: 'raw' });
                    if (content !== value) {
                        $element.html(value);
                        //this should be more proper but ctr+c, ctr+v is broken, above need fixing
                        tinymceInstance.setContent(value, { format: 'raw' });
                    }
                }
            }
        };
    };

    var initUserAutoComplete = function (bindingElementId) {
        requesthandler.defineRequest("getUserList", "/Tickets/GetUsersByFullName");

        var userObject = undefined;

        $(bindingElementId).autocomplete({
            source: function (request, response) {
                amplify.request({
                    resourceId: "getUserList",
                    data: request,
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                selectedObject: item,
                                label: item.FullName,
                                value: item.Id
                            };
                        }));
                    },
                    error: function () {
                        response([]);
                    }
                });
            },
            focus: function (event, ui) {
                // prevent autocomplete from updating the textbox
                event.preventDefault();
                // manually update the textbox
                $(this).val(ui.item.label);
            },
            select: function (event, ui) {
                event.preventDefault();
                $(bindingElementId).val(ui.item.label);
                userObject = ui.item.selectedObject;
                amplify.publish("userFromAutoCompleteSelected", userObject);
                return false;
            },
            minLength: 1
        }).keyup(function (e) {
            //preventing enable conformation button (backspace or delete)
            if (e.keyCode === 8 || e.keyCode === 46) {
                userObject = undefined;
            }
        });
    };

    var initCustomerAutoComplete = function (bindingElementId) {
        requesthandler.defineRequest("getCustomerList", "/Customers/GetCustomerList");

        var customerObject = undefined;

        $(bindingElementId).autocomplete({
            source: function (request, response) {
                amplify.request({
                    resourceId: "getCustomerList",
                    data: request,
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                selectedObject: item,
                                label: item.Name,
                                value: item.Id
                            };
                        }));
                    },
                    error: function () {
                        response([]);
                    }
                });
            },
            focus: function (event, ui) {
                // prevent autocomplete from updating the textbox
                event.preventDefault();
                // manually update the textbox
                $(this).val(ui.item.label);
            },
            select: function (event, ui) {
                event.preventDefault();
                $(bindingElementId).val(ui.item.label);
                customerObject = ui.item.selectedObject;
                amplify.publish("customerFromAutoCompleteSelected", customerObject);
                return false;
            },
            minLength: 1
        }).keyup(function (e) {
            //preventing enable conformation button (backspace or delete)
            if (e.keyCode === 8 || e.keyCode === 46) {
                customerObject = undefined;
            }
        });

        //return customerObject;
    };

    var initProductAutoComplete = function (bindingElementId) {
        requesthandler.defineRequest("getProductList", "/Products/GetProductList");

        var selectedProduct = undefined;

        $(bindingElementId).autocomplete({
            source: function (request, response) {
                amplify.request({
                    resourceId: "getProductList",
                    data: request,
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                selectedObject: item,
                                label: item.ErpCode,
                                value: item.Id
                            };
                        }));
                    },
                    error: function () {
                        response([]);
                    }
                });
            },
            focus: function (event, ui) {
                // prevent autocomplete from updating the textbox
                event.preventDefault();
                // manually update the textbox
                $(this).val(ui.item.label);
            },
            select: function (event, ui) {
                event.preventDefault();
                $(bindingElementId).val(ui.item.label);
                selectedProduct = ui.item.selectedObject;
                amplify.publish("productFromAutoCompleteSelected", selectedProduct);
                return false;
            },
            minLength: 1
        }).keyup(function (e) {
            //preventing enable conformation button (backspace or delete)
            if (e.keyCode === 8 || e.keyCode === 46) {
                selectedProduct = undefined;
            }
        });
    };

    var initCategorizationTypeAutoCompl = function (bindingElementId) {
        requesthandler.defineRequest("getCatTypes", "/CategorizationType/GetCategorizatinTypeList");

        var selectedCatType = undefined;

        $(bindingElementId).autocomplete({
            source: function (request, response) {
                amplify.request({
                    resourceId: "getCatTypes",
                    data: { searchString: request.term },
                    success: function (data) {
                        response($.map(data.CategorizationTypeList, function (item) {
                            return {
                                selectedObject: item,
                                label: item.Name,
                                value: item.Id
                            };
                        }));
                    },
                    error: function () {
                        response([]);
                    }
                });
            },
            focus: function (event, ui) {
                // prevent autocomplete from updating the textbox
                event.preventDefault();
                // manually update the textbox
                $(this).val(ui.item.label);
            },
            select: function (event, ui) {
                event.preventDefault();
                $(bindingElementId).val(ui.item.label);
                selectedCatType = ui.item.selectedObject;
                amplify.publish("catTypeFromAutoCompleteSelected", selectedCatType);
                return false;
            },
            minLength: 1
        }).keyup(function (e) {
            //preventing enable conformation button (backspace or delete)
            if (e.keyCode === 8 || e.keyCode === 46) {
                selectedCatType = undefined;
            }
        });
    };

    var getMonths = function() {
        var months = [];
        for (var j = 1; j <= 12; j++) {
            months.push(j);
        }
        return months;
    };

    var getDays = function() {
        var days = [];
        for (var j = 1; j <=31 ; j++) {
            days.push(j);
        }
        return days;
    };

    var getMonthsWithNames = function() {
        var months = [];
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var monthNamesLong = ["Januar", "Februar", "Marec", "April", "Maj", "Junij", "Julij", "August", "September", "October", "November", "December" ];
        for (var j = 0; j < 12; j++) {
            months.push({
                code: j + 1,
                shortName: monthNames[j],
                longName: monthNamesLong[j]
            });
        }
        return months;
    };

    var getDaysWithNames = function () {
        var days = [];
        days.push({ code: 1, shortName: 'pon', longName: 'Ponedeljek' });
        days.push({ code: 2, shortName: 'tor', longName: 'Torek' });
        days.push({ code: 3, shortName: 'sre', longName: 'Sreda' });
        days.push({ code: 4, shortName: 'čet', longName: 'Četrtek' });
        days.push({ code: 5, shortName: 'pet', longName: 'Petek' });
        days.push({ code: 6, shortName: 'sob', longName: 'Sobota' });
        days.push({ code: 0, shortName: 'ned', longName: 'Nedelja' });
        return days;
    };

    var stringContains = function (source, find) {
        return source.indexOf(find) !== -1;
    };

    var stringToBoolean = function(string) {
        switch (string.toLowerCase().trim()) {
            case "true":
            case "yes":
            case "1":
                return true;
            case "false":
            case "no":
            case "0":
            case null:
                return false;
            default:
                return Boolean(string);
        }
    };

    //check if null or empty
    var isEmpty = function(str) {
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

    //Replace all character in string plus special characters
    var replaceAll = function(str, find, replace) {
        return str.replace(new RegExp(find.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g'), replace);
    };

    var queryStringToJsObject = function (qs) {
        qs = qs || location.search.slice(1);

        var pairs = qs.split('&');
        var result = {};
        pairs.forEach(function (item) {
            var pair = item.split('=');
            var key = pair[0].replace("%5B%5D", ""); //replace code for []
            var value = decodeURIComponent(pair[1] || '');

            //this is need for replace all unwanted characters
            value = replaceAll(value, "+", " ");

            if (result[key]) {
                if (Object.prototype.toString.call(result[key]) === '[object Array]') {
                    result[key].push(value);
                } else {
                    result[key] = [result[key], value];
                }
            } else {
                result[key] = value;
            }
        });
        return result;
        //return JSON.parse(JSON.stringify(result));
    };


    var isObjectArray = function(trackObj, property) {
        var ret = false;
        if (Object.prototype.toString.call(trackObj[property]) === "[object Array]") {
            ret = true;
        } else {
            ret = false;
        }
        return ret;
    };

    function arraysIdentical(a, b) {
        var i = a.length;
        if (i !== b.length) return false;
        while (i--) {
            if (a[i] !== b[i]) return false;
        }
        return true;
    };

    /**
     * Is model changed
     * @param {} sourceObj - pure Js object from server  
     * @param {} trackObj - must be plan Js object
     * @param {} trackProperties - which properties for track 
     * @returns true or false 
     */
    var isModelChanged = function (sourceObj, trackObj, trackProperties) {
        if (!$.isPlainObject(sourceObj))
            throw "SourceObj must be a plain javascript object !";
        if (!$.isPlainObject(trackObj))
            throw "TrackObj must be a plain javascript object !";

        var modelChanged = false;

        for (var i = 0; i < trackProperties.length; i++) {
            var property = trackProperties[i];
            if (trackObj[property] !== sourceObj[property]) {
                if (property === "__ko_mapping__") continue;
                if (isObjectArray(trackObj, property) && isObjectArray(sourceObj, property)) {
                    modelChanged = arraysIdentical(sourceObj[property], trackObj[property]);
                } else {
                    if ((sourceObj[property] == null || sourceObj[property] == undefined) && isEmpty(trackObj[property])) {
                        modelChanged = false;
                    } else {
                        //console.log("SRC: " + property + " dest: " + property);
                        modelChanged = true;
                    }
                }
            }
        }
        return modelChanged;
    };

    /**
     *  Get object properties
     * @param {} object 
     * @returns array of propertes 
     */
    var getObjectProperties = function (object) {
        if (!Object.keys) {
            if (o !== Object(object))
                throw new TypeError('Object.keys called on a non-object');
            var k = [], p;
            for (p in object) if (Object.prototype.hasOwnProperty.call(object, p)) k.push(p);
            return k;
        }
        else {
            return Object.keys(object);
        }


    };

    var base64encoder = {
        _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

        encode: function(input) {
            var output = "";
            var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
            var i = 0;

            input = base64encoder._utf8_encode(input);

            while (i < input.length) {

                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }

                output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

            }

            return output;
        },

        decode: function(input) {
            var output = "";
            var chr1, chr2, chr3;
            var enc1, enc2, enc3, enc4;
            var i = 0;

            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

            while (i < input.length) {

                enc1 = this._keyStr.indexOf(input.charAt(i++));
                enc2 = this._keyStr.indexOf(input.charAt(i++));
                enc3 = this._keyStr.indexOf(input.charAt(i++));
                enc4 = this._keyStr.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }

            }

            output = base64encoder._utf8_decode(output);

            return output;

        },

        _utf8_encode: function(string) {
            string = string.replace(/\r\n/g, "\n");
            var utftext = "";

            for (var n = 0; n < string.length; n++) {

                var c = string.charCodeAt(n);

                if (c < 128) {
                    utftext += String.fromCharCode(c);
                } else if ((c > 127) && (c < 2048)) {
                    utftext += String.fromCharCode((c >> 6) | 192);
                    utftext += String.fromCharCode((c & 63) | 128);
                } else {
                    utftext += String.fromCharCode((c >> 12) | 224);
                    utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                    utftext += String.fromCharCode((c & 63) | 128);
                }

            }

            return utftext;
        },

        _utf8_decode: function(utftext) {
            var string = "";
            var i = 0;
            var c = c1 = c2 = 0;

            while (i < utftext.length) {

                c = utftext.charCodeAt(i);

                if (c < 128) {
                    string += String.fromCharCode(c);
                    i++;
                } else if ((c > 191) && (c < 224)) {
                    c2 = utftext.charCodeAt(i + 1);
                    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;
                } else {
                    c2 = utftext.charCodeAt(i + 1);
                    c3 = utftext.charCodeAt(i + 2);
                    string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    i += 3;
                }

            }

            return string;
        }
    };

    /*String.prototype.b64encode = function () {
        return btoa(unescape(encodeURIComponent(this)));
    };
    String.prototype.b64decode = function () {
        return decodeURIComponent(escape(atob(this)));
    };*/


    return {
        initDateTimePickerBinding: initDateTimePickerBinding,
        initTinyMceEditor: initTinyMceEditor,
        initUserAutoComplete: initUserAutoComplete,
        initCustomerAutoComplete: initCustomerAutoComplete,
        initProductAutoComplete: initProductAutoComplete,
        initCategorizationTypeAutoCompl:initCategorizationTypeAutoCompl,
        getMonths: getMonths,
        getDays: getDays,
        getDaysWithNames: getDaysWithNames,
        isEmpty: isEmpty,
        queryStringToJsObject: queryStringToJsObject,
        isModelChanged: isModelChanged,
        stringContains: stringContains,
        stringToBoolean: stringToBoolean,
        base64encoder: base64encoder,
        getMonthsWithNames: getMonthsWithNames,
        getObjectProperties: getObjectProperties
    };
};
var util = new CrmUtils();
