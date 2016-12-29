/**
 * @namespace app
 *
 * @requires app
 * @object ajaxRequest
 */

var app = app || {};

app.ajaxRequest = (function (app)
{
    var textMessageElmn = '';
    var isLoading = false;
    var hasRequestBlock = false;

    function displayRequestStatus (obj)
    {
        var t = obj.offset().top, l = 0;

        if ( ! $('#requestStatusIcon','body').length) {
            renderRequestStatusIcon();
        }
        if (obj.attr('data-request-status') == 'right') {
            l = obj.offset().left + obj.outerWidth( true );
        }
        if (obj.attr('data-request-status') == 'left') {
            l = obj.offset().left - $('#requestStatusIcon').outerWidth( true ) - 1;
        }
        if (t>0 && l>0) {
            $('#requestStatusIcon').css('top',t+'px').css('left',l+'px').show();
            hasRequestBlock = true;
        }
    }
    function renderRequestStatusIcon ()
    {
        $('<div></div>',{"id":"requestStatusIcon"})
            .append($('<span></span>',{"class":"glyphicon glyphicon-transfer"}))
            .appendTo('body');
    }
    function requestUrl(obj)
    {
        var controller = (obj.attr('data-space')) ? obj.attr('data-space') : app.controller ;
        return app.siteUrl + controller + '/' + obj.attr('data-act');
    }
    function responseConfirmObj (res, obj)
    {
        if(obj.row) {obj.row.remove()}
        responseDialogMessage(res);
    }
    function responseDialogMessage (res)
    {
        var msg = '';
        var alertcss = 'alert-success';

        if (res.hasOwnProperty('error')) {
            msg = res.error;
            alertcss = 'alert-danger';
        } else if (res.hasOwnProperty('message')) {
            msg = res.message;
        }
        if (msg != '') { app.appMessage.dialog(msg, alertcss); }
    }
    function responseTextMessage (res, obj) {

        var hasError = (res.hasOwnProperty('error'));
        var message =  (hasError) ? res.error : res.message;
        app.appMessage.alert(hasError,message,obj.messageElmn);
    }
    function responseDataDialog (res, obj)
    {
        if( res.hasOwnProperty('data'))
        {
            app.appMessage.dialog(res.data, 'alert-success');
        }
    }
    function setRequestCsrf (dataVal)
    {
        var token = app.csrfToken.getToken();
        dataVal[token.key] = token.hash;
    }
    function setAttrData(obj,dataVal)
    {
        setRequestCsrf (dataVal);

        if (obj.length == 1)
        {
            if(obj.attr('data-id')) {
                dataVal['id'] = obj.attr('data-id');
            }
            if(obj.attr('data-key') && obj.attr('data-val')) {
                dataVal[obj.attr('data-key')] = obj.attr('data-val');
                if(obj.attr('data-mod')) {
                    dataVal['data-mod'] = obj.attr('data-mod');
                }
            }
            if(obj.attr('data-request-status')) {
                displayRequestStatus(obj);
            }
        } else if (obj.length > 1) {
            obj.each(function () {
                if ($(this).attr('data-key')) {
                    dataVal[$(this).attr('data-key')] = $(this).val();
                }
            });
        }
    }
    function sendRequestObj(dataVal,url)
    {
        // check data
        var check = app.regexValitator.regexObj(dataVal);
        // data is ok than fire request
        if($.map(check.res, function(n, i) { return i; }).length && check.error.length == 0) {
            // callback function
            var ajaxCallBack = app.appRequestCallback.getCallback();

            if (ajaxCallBack === null) {
                app.appRequestCallback.setCallback('setResponse',{appCallBack:'ajaxRequest', obj:{id:'requestObj'}});
                ajaxCallBack = app.appRequestCallback.getCallback();
            }
            // fire request
            ajax(url,check.res,ajaxCallBack);
        } else {
            // data not ok message
            app.appMessage.dialog(check.error.join('<br>'), "alert-danger");
        }
    }
    function send (url, data, cllback)
    {
        if ( ! isLoading) {

            if (hasRequestBlock) { isLoading = true; }

            jQuery.ajax({
                type : 'post',
                url : url,
                dataType : 'json',
                data : data,
                success : function (res)
                {
                    cllback(res);
                },
                error : function() {
                    if (textMessageElmn != '') {
                        app.appMessage.alert(true,'Applikationsfehler, bitte wende dich an den Support!',textMessageElmn);
                    } else {
                        app.appMessage.dialog('Requestfehler, bitte wende dich an den Support!','alert-danger');
                    }
                }
            }).done(function () {
                if (hasRequestBlock) {
                    $('#requestStatusIcon').hide();
                    hasRequestBlock = false;
                }
                isLoading = false;
            });
        }
    }
    function ajax (url, data, cllback)
    {
        textMessageElmn = '';
        send(url, data, cllback);
    }
    function setRequestForm (form, url, messageElmn)
    {
        var formdata = app.regexValitator.regexForm(form);
        textMessageElmn = messageElmn;

        if (formdata.res === null || formdata.res !== null && formdata.error.length ) {
            if (formdata.error.length) {
                app.appMessage.alert(true,formdata.error.join('<br>'),messageElmn);
            } else {
                app.appMessage.alert(true, 'Keine Daten gefunden!',messageElmn);
            }
        } else {
            // callback function
            var ajaxCallBack = app.appRequestCallback.getCallback();

            if (ajaxCallBack === null) {
                $('input, textarea', form).val('');
                $('select', form).prop('selectedIndex',0);
                app.appRequestCallback.setCallback('setResponse',{appCallBack:'ajaxRequest', obj:{id:'formObj',messageElmn:messageElmn}});
                ajaxCallBack = app.appRequestCallback.getCallback();
            }
            // fire request
            send(url, formdata.res, ajaxCallBack);
        }
    }
    function setRequestObj (obj)
    {
        // request data value object
        var dataVal = {};
        // optinal second argument obj list
        if (arguments.length > 1) {
            setAttrData(arguments[1], dataVal);
        } else {
            setAttrData(obj,dataVal);
        }
        sendRequestObj(dataVal,requestUrl(obj));
    }
    function setRequestContainerObj (obj)
    {
        // request data value object
        var dataVal = {};
        var container = '';
        var list = null;

        if (obj.closest('[data-container=ajax]')) {
            container = obj.closest('[data-container=ajax]');
        }
        if (obj.closest('[data-container=ajax-list]')) {
            container = obj.closest('[data-container=ajax-list]');
            list = $('input,select',container);
        }
        if (list) {
            setAttrData(list, dataVal);
        } else {
            setAttrData(obj,dataVal);
        }
        sendRequestObj(dataVal,requestUrl(obj));
    }
    function setRequestConfirm (containerCss, obj)
    {
        var token = app.csrfToken.getToken();
        var row = (containerCss == '.group-row') ? obj.closest(containerCss) : '';
        var dataVal = {"id" : obj.attr('data-val')};
        dataVal[token.key] = token.hash;
        app.appRequestCallback.setCallback('setResponse',{appCallBack:'ajaxRequest', obj:{id:'confirmObj',row:row}});
        ajax(requestUrl(obj), dataVal, app.appRequestCallback.getCallback());
    }
    function setRequestDialog (obj)
    {
        var token = app.csrfToken.getToken();
        var url = app.siteUrl + obj.attr('data-space') + '/' + obj.attr('data-act');
        var dataVal = {val:obj.attr('data-key')};
        dataVal[token.key] = token.hash;
        app.appRequestCallback.setCallback('setResponse',{appCallBack:'ajaxRequest', obj:{id:'dialogObj',obj:obj}});
        ajax(url, dataVal, app.appRequestCallback.getCallback());
    }
    function setResponse (res, obj)
    {
        switch(obj.id) {
            case('confirmObj'):
                responseConfirmObj(res, obj);
                break;
            case('requestObj'):
                responseDialogMessage(res);
                break;
            case('formObj'):
                responseTextMessage(res, obj);
                break;
            case('dialogObj'):
                responseDataDialog(res, obj);
                break;
        }
    }
    return {
        ajax                   : ajax,
        setRequestForm         : setRequestForm,
        setRequestObj          : setRequestObj,
        setRequestContainerObj : setRequestContainerObj,
        setRequestConfirm      : setRequestConfirm,
        setResponse            : setResponse,
        setRequestDialog       : setRequestDialog
    };
})(app);