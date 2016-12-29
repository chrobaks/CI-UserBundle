/**
 * @function global appService.js
 * @require object app
 *
 * include defined services
 * services init call in appReady.js
 */
var appService = (function (app)
{
    function loadService (serviceName)
    {
        var options = $.extend( {}, {
            dataType: "script",
            cache: false,
            url: app.siteUrl + app.servicePath + serviceName + ".js",
            success : function () {initService(serviceName)}
        });
        jQuery.ajax(options);
    }
    function initService (serviceName)
    {
        if (typeof appService[serviceName].init != 'undefined'){appService[serviceName].init()}
    }
    function init ()
    {
        if (app.definedServices.length)
        {
            $.each(app.definedServices, function(i, obj) {
                if ($('body').find($(obj.identifier)).length) {
                    loadService(obj.service);
                }
            });
            if ($('body').find($('[data-service-controller]')).length) {
                $('[data-service-controller]','body').each( function () {
                    loadService($(this).attr('data-service-controller'));
                });
            }
        }
    }
    return { init : init};
})(app);