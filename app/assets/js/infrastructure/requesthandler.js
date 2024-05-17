    var requestHandler = function() {

        var getCorrectSiteUrl = function(requestUrl) {
            var baseUrl = window.location.origin;
            var url = "";
            url = siteUrl + requestUrl;
            return url;
        };

        var init = function() {
            amplify.request.decoders.requestDecoder = function(data, status, xhr, success, error) {
                if (status === "error") {
                    error(xhr.responseText, "error");
                } else if (data && data.status === "error") {
                    delete data.status;
                    error(data, "error");
                } else {
                    if (xhr.responseText.indexOf("<!DOCTYPE html>") > -1 && xhr.responseText.indexOf("<input type=\"hidden\" name=\"loginpage\" value=\"Login\">") > -1)
                        window.location.href = getCorrectSiteUrl("/") + "Account/Login";
                    else {
                        success(data);
                    }
                }
            };
        };

        var authenticationExternalApi = function () {
            
        };

        //traditional parameter si used for for correct sending array 
        //thought ajax request and does not require post request
        var defineRequest = function (requestName, requestUrl) {
            amplify.request.define(requestName, "ajax", { url: getCorrectSiteUrl(requestUrl), traditional: true, cache: false, dataType: "json", type: "GET", contentType: "application/json; charset=utf-8", decoder: amplify.request.decoders.requestDecoder });
        };

        var definePostRequest = function (requestName, requestUrl) {
            amplify.request.define(requestName, "ajax", { url: getCorrectSiteUrl(requestUrl), cache: false, dataType: "json", type: "POST", contentType: "application/json; charset=utf-8", decoder: amplify.request.decoders.requestDecoder });
        };

        var defineRequestExternal = function (requestName, requestUrl) {
            amplify.request.define(requestName, "ajax", { url: requestUrl, traditional: true, cache: false, dataType: "json", type: "GET", contentType: "application/json; charset=utf-8", decoder: amplify.request.decoders.requestDecoder });
        };

        var definePostRequestExternal = function (requestName, requestUrl) {
            amplify.request.define(requestName, "ajax", { url: requestUrl, cache: false, dataType: "json", type: "POST", contentType: "application/json; charset=utf-8", decoder: amplify.request.decoders.requestDecoder });
        };

        init();

        return {
            defineRequest: defineRequest,
            definePostRequest: definePostRequest,
            getCorrectSiteUrl: getCorrectSiteUrl
        };         
    };
 var requesthandler = new requestHandler();