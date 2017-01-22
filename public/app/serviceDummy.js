/**
 * namespace appService
 *
 * @requires app, appService
 * @object YourServiceName
 */
var appService = appService || {};

appService.YourServiceName = (function (app)
{
    var serviceId = 'YourServiceName';
    var container = {};

    function init ()
    {
        container = $('[data-service-controller='+ serviceId +']');
    }
    return {
        init : init
    };
})(app);