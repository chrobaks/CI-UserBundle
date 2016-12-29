/**
 * @namespace app
 *
 * @object appEvents
 */

var app = app || {};

app.appEvents = (function ()
{
    function radioGroupUnique (obj)
    {
        if (obj.hasClass('btn-default')) {
            // set default btn and remove radio attr checked
            $('.btn-primary', obj.closest('.btn-group')).removeClass('btn-primary').addClass('btn-default');
            $('.btn-default', obj.closest('.btn-group')).find('input[type=radio]').removeAttr('checked');
            // set active btn and radio attr checked
            obj.removeClass('btn-default').addClass('btn-primary');
            obj.find('input[type=radio]').attr('checked','checked');
            // set btn group input val
            $('input[type=hidden]', obj.closest('.btn-group')).val(obj.find('input[type=radio]').val());
        }
    }
    function togglePanelGroupIcon (e, container, iconfirst, iconlast)
    {
        e.preventDefault();
        $(e.target,container)
            .prev('.panel-heading',container)
            .find('.glyphicon')
            .toggleClass(iconfirst + ' ' + iconlast);
    }
    function scrollToPageIndex (obj,space)
    {
        var indexObj = $('[data-index="'+obj.attr('data-index-target')+'"]');
        var topPos = indexObj.offset().top - space;
        $('html, body').animate({ scrollTop:indexObj.offset().top = topPos },'slow');
    }

    return {
        togglePanelGroupIcon : togglePanelGroupIcon,
        scrollToPageIndex    : scrollToPageIndex,
        radioGroupUnique     : radioGroupUnique
    };
})();