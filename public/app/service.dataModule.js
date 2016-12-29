/**
 * namespace appService
 *
 * @requires app, appService
 * @object dataModule
 */
var appService = appService || {};

appService.dataModule = (function (app)
{
    var serviceId = 'dataModule';

    function moduleColUpdate (obj,act)
    {
        var actOk = false;

        switch (act) {
            case('change'):
                obj.attr('data-val',obj.val());
                actOk = true;
                break;
            case('click'):
                obj.attr('data-val',$('input[name='+obj.attr('data-key')+']',obj).val());
                actOk = true;
                break;
        }
        if (actOk) {
            app.ajaxRequest.setRequestObj(obj);
        }
    }
    function moduleCloneToModal (obj)
    {
        var sourceId = obj.attr('data-clone-source-id');
        var modalId = obj.attr('data-clone-modal-id');
        var targetId = obj.attr('data-clone-target-id');
        var dataKey = obj.attr('data-key');
        var dataVal = obj.attr('data-val');
        var dataCloneType = (obj.attr('data-clone-type')) ? obj.attr('data-clone-type'):'' ;

        if (sourceId && targetId) {
            if (dataCloneType == '')
            {
                var cloneData = $('[data-clone-source="'+sourceId+'"]').html();

                $(targetId,modalId).val(cloneData);
                $('[name="'+dataKey+'"]').val(dataVal);
                $(modalId).modal('show');
            }
            else if (dataCloneType == 'wrapper')
            {
                var cloneData = $('[data-clone-source="'+sourceId+'"]').clone();
                $(targetId,modalId).html(cloneData);
                $(targetId,modalId).find('.hidden').removeClass('hidden');
                $(modalId).modal('show');
            }
        }
    }
    function loadFileAsText (obj) {

        var file = $.trim($(obj.attr('data-source')).val());

        if (file) {
            jQuery.ajax({
                dataType: "text",
                cache: false,
                url: app.siteUrl + "public/app/"+file+".js",
                success : function (res) {
                    $(obj.attr('data-target')).val(res);
                }
            });
        }
    }
    function responseDependenceData (res, obj)
    {
        $('input[name=zIndex]',obj.closest('form'))
            .attr('max',res.maxZIndex)
            .val(res.maxZIndex)
    }
    function resetDependenceData (obj)
    {
        $('input[name=zIndex]',obj.closest('form'))
            .attr('max',1)
            .val(1);
    }
    function sendDependenceData (obj)
    {
        var val = obj.val();

        if (val!=='') {
            obj.attr('data-id',val);
            app.updateIgnoreKeys(obj.attr('data-key'));
            setRequest(obj);
        } else {
            resetDependenceData(obj);
        }
    }
    function events ()
    {
        // select change
        $('select[data-module="catMaxZIndex"],select[data-module="colupdate"]').on('change', function (e) {

            e.preventDefault();
            var moduleName = $(this).attr('data-module');

            switch (moduleName) {
                case('catMaxZIndex'):
                    sendDependenceData($(this));
                    break;
                case('colupdate'):
                    moduleColUpdate($(this),'change');
                    break;
            }
        });
        $('[data-module="listedit"], ' +
            'div[data-module="colupdate"], ' +
            '[data-module="loadFileAsText"], ' +
            '[data-module="clone"]').on('click', function (e) {

            e.preventDefault();
            var moduleName = $(this).attr('data-module');

            switch (moduleName) {
                case('colupdate'):
                    if ($(this).hasClass('btn-group')) {
                        moduleColUpdate($(this),'click');
                    }
                    break;
                case('clone'):
                    moduleCloneToModal($(this));
                    break;
                case('loadFileAsText'):
                    loadFileAsText($(this));
                    break;
            }
        });
    }

    function setRequest (obj)
    {
        app.appRequestCallback.setCallback('setResponse',{serviceId:serviceId, obj:obj});
        app.ajaxRequest.setRequestObj(obj);
    }
    function setResponse (res, obj)
    {
        switch (obj.attr('data-module'))
        {
            case('catMaxZIndex'):
                responseDependenceData (res, obj);
                break;
        }
    }
    function init ()
    {
        events();
    }

    return {
        init        : init,
        setResponse : setResponse
    };

})(app);