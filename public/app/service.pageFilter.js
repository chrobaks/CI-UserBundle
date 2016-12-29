/**
 * namespace appService
 *
 * @requires app, appService
 * @object pageFilter
 */
var appService = appService || {};

appService.pageFilter = (function (app)
{
    var serviceId = 'pageFilter';
    var obj = {};
    var modulePagination = {};
    var moduleFilter = {};

    function setPagination ()
    {
        var inpt = obj.closest('[data-filter-module="pagination"]').find('input[data-filter="page"]');

        if (inpt) {
            inpt.val(obj.attr('data-page'));
            app.locationHrefFilter.loadUrl(inpt);
        }
    }
    function setGroupFilter ()
    {
        app.locationHrefFilter.loadUrl(obj);
    }
    function event ()
    {
        // filter request / pagination
        modulePagination.on('click', function (e) {
            e.preventDefault();
            obj = $(this);
            setPagination();
        });
        // filter request / entity filters
        moduleFilter.on('change', function (e) {
            e.preventDefault();
            obj = $(this);

            if(obj.attr('data-target-to-default')) {
                $('[data-filter=' + obj.attr('data-target-to-default') + ']').prop('selectedIndex',0);
            }
            setGroupFilter();
        });
    }
    function init ()
    {
        modulePagination = $('[data-filter-module="pagination"] a');
        moduleFilter = $('[data-filter-module="filter"],[data-filter-module="group-filter"]');
        event();
    }

    return {
        init : init
    };

})(app);