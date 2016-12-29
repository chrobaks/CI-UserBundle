/**
 * namespace appService
 *
 * @requires app, appService
 * @object devTool
 */
var appService = appService || {};

appService.devTool = (function (app)
{
    var serviceId = 'devTool';
    var container = {};
    var containerBtn = {};
    var containerContent = {};

    function slideMaxTop () {
        return $(window).height()/2;
    }
    function displayStatus () {
        return (container.css('bottom').replace('px','')*1 > 0);
    }
    function display ()
    {
        if (displayStatus()) {
            container.css('bottom','0');
        } else {
            container.css('bottom',slideMaxTop()+'px');
            containerContent.css('height',slideMaxTop()+'px');
            $('.content-wrapper',containerContent).css('height',(slideMaxTop())+'px');
            $('.content-data',containerContent).css('height',(slideMaxTop()-20)+'px');
        }
    }
    function events ()
    {
        containerBtn.on('click', function(e) {
            e.preventDefault();
            display();
        });
        $('.panel-group',container).on('hidden.bs.collapse', function (e){
            app.appEvents.togglePanelGroupIcon(e,container,'glyphicon-folder-close','glyphicon-folder-open');
        });
        $('.panel-group',container).on('shown.bs.collapse', function (e){
            app.appEvents.togglePanelGroupIcon(e,container,'glyphicon-folder-close','glyphicon-folder-open');
        });
    }
    function init ()
    {
        container = $('[data-service-controller='+ serviceId +']');
        containerBtn = $('#devToolBtn');
        containerContent = $('#devToolContent');
        events();
    }
    return {
        init : init
    };
})(app);