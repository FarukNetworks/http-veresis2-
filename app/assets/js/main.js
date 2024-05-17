(function () {
    requirejs.config(
        {
            baseUrl: siteUrl + 'app/assets/js',
            paths: {
                i: 'infrastructure',
                m: 'models',
                vm: 'viewmodels'                
            }            
        });
    var root = this;
    var define3rdPartyModules = function () {
        define('jquery', [], function () { return root.jQuery; });
        define('ko', [], function () { return root.ko; });
        define('knockout', [], function () { return root.ko; });
        define('bootbox', [], function () { return root.bootbox; });
        //define('underscore', [], function () { return root._; });
        //define('underscore.string', [], function () { return root._.str; });
        define('amplify', [], function () { return root.amplify; });
        //define('Slick', [], function () { return root.Slick; });
        //define('infuser', [], function () { return root.infuser; });
        define('toastr', [], function () { return root.toastr; });
        //define('moment', [], function() { return root.moment; });
    };
    define3rdPartyModules();
})();