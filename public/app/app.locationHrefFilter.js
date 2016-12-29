/**
 * @namespace app
 *
 * @object locationHrefFilter
 */

var app = app || {};

app.locationHrefFilter = (function()
{
    var container =  null;
    var dependenceChild = null;
    var dependenceMaster = null;
    var dependenceChildKeys = [];
    var attrObj = {};
    var conf = null;
    var obj = null;

    function setProperties ()
    {
        if (obj.closest('.group-filter').length)
        {
            container = obj.closest('.group-filter');
            dependenceChild = (container.attr('data-dependence-child')) ? container : null;
            dependenceMaster = (dependenceChild == null) ? container : $('[data-dependence-master="'+container.attr('data-dependence-child')+'"]');
            dependenceChildKeys = [];
            attrObj = (dependenceMaster != null) ? dependenceMaster : obj;
        } else {
            attrObj = obj;
        }
    }
    function setLocationUrl ()
    {
        if (attrObj.attr('data-act'))
        {
            conf = [ attrObj.attr('data-act') ];

            if (attrObj.attr('data-val') && attrObj.attr('data-val') != '') {
                conf.push(attrObj.attr('data-val'))
            }
        }
    }
    function setLocationFilter ()
    {
        conf.push('filter');

        if (dependenceChild != null)
        {
            $('[data-filter-module="group-filter"]',container).each(function (){
                if ($(this).val() != '')
                {
                    conf = conf.concat([$(this).attr('data-filter'),$(this).val()]);
                    dependenceChildKeys.push($(this).attr('data-filter'));
                }
            });
        }
        if (dependenceMaster != null)
        {
            $('[data-filter-module="group-filter"]',dependenceMaster).each(function ()
            {
                if ($(this).val() != '' && $.inArray($(this).attr('data-filter'), dependenceChildKeys) == -1)
                {
                    conf = conf.concat([$(this).attr('data-filter'),$(this).val()]);
                }
            });
        } else {
            if (obj.val() != '')
            {
                conf = conf.concat([obj.attr('data-filter'),obj.val()]);
            }
        }
    }
    function loadUrl (element)
    {
        obj = element;

        setProperties();
        setLocationUrl();

        if (conf != null)
        {
            setLocationFilter();
            document.location.href = app.siteUrl + app.controller + '/' + conf.join('/');
        }
    }

    return {loadUrl : loadUrl};
})();