/**
 * @namespace app
 *
 * @requires app
 * @object appRequestCallback
 */

var app = app || {};

app.appRequestCallback = (function(app)
{
    var eventCallBackArguments = {};
    var eventCallBackTasks = [];
    var eventCallBack = {
        setResponse : function(res)
        {
            if (res.hasOwnProperty('redirect')) {
                document.location.href = document.location.href;
            } else {

                setcsrf(res);

                var args = eventCallBackArguments['setResponse'];

                if (args.hasOwnProperty('serviceId')) {
                    if (typeof appService[args.serviceId].init != 'undefined') {
                        appService[args.serviceId].setResponse(res, args.obj);
                    }
                }
                if (args.hasOwnProperty('appCallBack') && app.hasOwnProperty(args.appCallBack)) {
                    app[args.appCallBack].setResponse(res, args.obj);
                }
            }
        }
    };
    function setcsrf (res)
    {
        if (res.hasOwnProperty('csrfn') && res.hasOwnProperty('csrfv')) {
            app.csrfToken.setToken(res);
        }
    }
    function getCallback ()
    {
        var res = null;

        if (eventCallBackTasks.length) {
            res = eventCallBack[eventCallBackTasks[0]];
            eventCallBackTasks = [];
        }
        return res;
    }
    function setCallback (callback,args)
    {
        if (eventCallBack.hasOwnProperty(callback) && ! eventCallBackTasks.length) {

            eventCallBackTasks.push(callback);

            if (args) {
                eventCallBackArguments[callback] = args;
            }
        }
    }
    return {
        getCallback      : getCallback,
        setCallback      : setCallback
    };
})(app);