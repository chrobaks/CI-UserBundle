
var app = app || {};

$(document).ready( function ()
{
    // load csrf token
    app.csrfToken.init();
    // load services
    appService.init();
    //show readyMessage if exists
    if (app.hasOwnProperty('readyMessage') && app.readyMessage != '') {
        app.appMessage.dialog(app.readyMessage, "alert-success");
    }
    //resize sidebar by  window resize event
    $(window).on('resize', function (e) {
        e.preventDefault();
        if (appService.hasOwnProperty('sideBarNavigation')) {
            appService.sideBarNavigation.resize();
        }
    });
    //set event for tooltip
    $('[data-toggle="tooltip"]').tooltip();
    //set event for form action confirmation
    $('[data-toggle="confirmation"]','.group-row').confirmation({
        rootSelector:'[data-toggle="confirmation"]',
        singleton:true,
        popout: true,
        onConfirm : function(){ app.ajaxRequest.setRequestConfirm('.group-row', $(this))}
    });
    $('[data-toggle="confirmation"]','.toolbar-form').confirmation({
        rootSelector:'[data-toggle="confirmation"]',
        singleton:true,
        popout: true,
        onConfirm : function(){ app.ajaxRequest.setRequestConfirm('.toolbar-form', $(this))}
    });
    // set ajax form submit
    $('form.formAjaxRequest').on('submit', function (e) {
        console.log('formAjaxRequest');
        e.preventDefault();
        app.ajaxRequest.setRequestForm($(this),$(this).attr('action'),$('div.forminfo:eq(0) > p:eq(0)',$(this)));
        return false;
    });
    // set non-form ajax event
    $('.btn-ajax').on('click', function (e) {
        e.preventDefault();
        app.ajaxRequest.setRequestObj($(this));
    });
    // set ajax container event
    $('.ajax-container-request').on('click', function (e) {
        e.preventDefault();
        app.ajaxRequest.setRequestContainerObj($(this));
    });
    // radio groups
    $('.btn-group.radio-group.unique').on('click', '.btn', function(e) {
        e.preventDefault();
        app.appEvents.radioGroupUnique($(this));
    });
    // help dialog
    $('.dialog-request').on('click',function(e) {
        e.preventDefault();
        app.ajaxRequest.setRequestDialog($(this));
    });
    // scroll to content index
    $('.content-index').on('click', function (e) {
        e.preventDefault();
        app.appEvents.scrollToPageIndex($(this), 90);
    });
    // escape html in pre
    /* */
    $('pre').each(function () {
        var content = $(this).html();
        content = content.replace(new RegExp('<', 'gi'), '#');
        content = content.replace(new RegExp('>', 'gi'), '#');
        $(this).html(content);
    });

});

