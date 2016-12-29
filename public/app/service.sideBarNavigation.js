/**
 * namespace appService
 *
 * @requires appService
 * @object sideBarNavigation
 */
var appService = appService || {};

appService.sideBarNavigation = (function ()
{
    var serviceId = 'sideBarNavigation';
    var container = {};
    var topOffset = 50;
    var psc = null;
    var psn = null;
    var pw = null;

    function setHeight ()
    {
        var height = ((window.innerHeight > 0) ? window.innerHeight : screen.height) - 1;

        if(psn.css('display') == 'block') {
            height = height - topOffset;
            if (height < 1) height = 1;
            if (height > topOffset) {
                psn.css("min-height", (height) + "px");
            }
        }
    }
    function setWidth ()
    {
        var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;

        if (width < 768) {
            width = (width*1-20)+ "px";
            psc.hide();
            psn.hide();
            pw.css("width", width );
            if( ! $('.sidebar','#navbar').length){
                cloneSidebar();
            } else {
                $('.sidebar','#navbar').show();
            }
        } else {
            if( $('.sidebar','#navbar').length){
                $('.sidebar','#navbar').hide();
            }
            width = (width*1-220)+ "px";
            psc.show();
            psn.show();
            pw.css("width", width );
        }
    }
    function cloneSidebar ()
    {
        $('#navbar').append($('<ul>',{"class":"nav navbar-nav sidebar"}));
        $('.list-group-item',psn).each(function(){
            var activeCss = ($(this).hasClass('disabled')) ? true : false;
            var clone = $(this).clone(true).removeClass('list-group-item');
            var li = $('<li>').append(clone);
            if(activeCss){
                li.addClass('active');
            }
            li.appendTo($('.sidebar','#navbar'));
        });
    }
    function events ()
    {
        // sidebar submenu dropdown
        $('.list-group-dropdown-item',psn).on('click', function (e)
        {
            e.preventDefault();

            var index = $('.list-group-dropdown-item',psn).index($(this));

            $('.list-group-dropdown-item.active > span.glyphicon',psn)
                .removeClass('glyphicon-folder-open')
                .addClass('glyphicon-folder-close');

            $('.sidebar-sub-menu.active, .list-group-dropdown-item.active',psn)
                .removeClass('active');

            $('.list-group-dropdown-item:eq('+index+') > span.glyphicon',psn)
                .removeClass('glyphicon-folder-close')
                .addClass('glyphicon-folder-open');

            $('.sidebar-sub-menu:eq('+index+'), .list-group-dropdown-item:eq('+index+')',psn)
                .addClass('active');
        });
    }

    function resize ()
    {
        if (psn) {
            setWidth();
            setHeight(psn);
        }
    }
    function init ()
    {
        container = $('[data-service-controller='+ serviceId +']');
        psc = $('#page-sidebar-container');
        psn = $('#page-sidebar-navi');
        pw = $('#page-wrapper');

        events();
        resize();
    }

    return {
        init : init,
        resize : resize
    };

})();