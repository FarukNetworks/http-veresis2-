define([], 
    function () {
        var jsonCamelCaseConverter = function () {
            
            function toCamel(o) {
                var build, key, destKey, value;
            
                if (o instanceof Array) {
                    build = [];
                    for (key in o) {
                        value = o[key];
            
                        if (typeof value === "object") {
                            value = toCamel(value);
                        }
                        build.push(value);
                    }
                } else {
                    build = {};
                    for (key in o) {
                        if (o.hasOwnProperty(key)) {
                            destKey = (key.charAt(0).toLowerCase() + key.slice(1) || key).toString();
                            value = o[key];
                            if (value !== null && typeof value === "object") {
                                value = toCamel(value);
                            }
            
                            build[destKey] = value;
                        }
                    }
                }
                return build;
            }      
            
            return {
                toCamel: toCamel
            }
        };
        return new jsonCamelCaseConverter(); 
    }
);