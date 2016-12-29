/**
 * namespace appService
 *
 * @requires app, appService
 * @object entityConfig
 */
var appService = appService || {};

appService.entityConfig = (function (app)
{
    var serviceId = 'entityConfig';
    var container = {};
    var selectTableName = {};
    var selectConfigId = {};
    var inputEntityName = {};
    var inputConfigurationName = {};
    var inputColumnsName = {};
    var btnUpdateConfig = {};
    var btnTestConfig = {};
    var btnCreateConfig = {};
    var groupUpdateConfig = {};
    var groupCreateConfig = {};
    var configErrorAlert = {};
    var entityConfiguration = [];
    var entitiesConfigTpl = {};
    var configInfoTpl = {};
    var createConfigIsValid = false;
    var updateConfigIsValid = false;
    var config = {
        'css' : {
            'config' : 'config-blocked',
            'entry' : 'entry-blocked'
        },
        'tables' : {},
        'tablesCache' : {},
        'testConfCache' : {},
        'columns' : '',
        'columnsCache' : {},
        'configIdsCache' : {},
        'hasDependenciesCache' : {},
        'isDependenceCache' : {},
        'selectedConfigName' : ''
    };
    var messages = app.objCopy(app.controllerMsg);

    function renderConfigItem ()
    {
        var resTmplt = '', resCache = '';
        var tmplt = '<div class="listbox-wrapper noevent"><ul>';
        tmplt += '<li><strong><span class="glyphicon glyphicon-file"></span>[configName]</strong></li>';
        tmplt += '<li><strong>Entity :</strong> [name]</li>';
        tmplt += '<li><strong>Table :</strong> [tablename]</li>';
        tmplt += '<li><strong>Dependencies :</strong> [dependence_entities]</li>';
        tmplt += '</ul></div>';

        if (app.objLen(entityConfiguration))
        {
            $.each(entityConfiguration, function(index, args)
            {
                resCache = tmplt;

                $.each(args, function(key, val) {
                    resCache = resCache.replace('['+key+']',val);
                });
                resTmplt += resCache;
            });
            entitiesConfigTpl.html(resTmplt);
        }
    }
    function renderConfigInfoTpl (infoTxt)
    {
        infoTxt = infoTxt.replace('[countTable]',app.objLen(entityConfiguration));
        infoTxt = infoTxt.replace('[configName]','<strong>'+entityConfiguration[0]['configName']+'</strong>');

        var tableNames = $.map(entityConfiguration,function(obj){return obj['tablename']});
        var txt = '<li><span class="glyphicon glyphicon-info-sign"></span>' + infoTxt+ '<hr></li>';
        txt += '<li><span class="glyphicon glyphicon-hand-right"></span><strong>Tables :</strong> '+ tableNames.join(', ') +'</li>';
        txt = '<ul>' + txt + '</ul>';
        configInfoTpl.html(txt);
    }
    function renderConfigErrorAlert (txt) {
        if (txt != '') {
            configErrorAlert.html(txt);
            configErrorAlert.show();
        } else {
            configErrorAlert.hide();
        }
    }
    function resetSelectedIndex (obj) {
        obj.prop('selectedIndex',0);
    }
    function resetNameProperties () {
        inputEntityName.val('');
        inputConfigurationName.val('');
        inputColumnsName.val('');
    }
    function getEntityObj (conf)
    {
        var entityName = (conf.level < 1) ? createEntityName(conf.name) : app.replaceStr(conf.name,[{rule:/_/g, replacer:''}]);
        var row = {
            configName:inputConfigurationName.val(),
            name:entityName,
            tablename:conf.name,
            dependence_entities:app.replaceStr(conf.dep,[{rule:/_/g, replacer:''}]),
            dependence_level:conf.level
        };
        return row;
    }
    function createEntityName(objVal) {
        return app.replaceStr(objVal+inputEntityName.attr('placeholder'),[{rule:/_/g, replacer:''}]);
    }
    function checkTableIsDependence(objVal)
    {
        var res = true;

        if (! configIdIsSelect() && tableIsDependence(objVal))
        {
            app.appMessage.dialog(messages.warningIsDep + config.isDependenceCache[objVal].join(', '),'alert-danger');
            res = false;
        }
        return res;
    }
    function displayConfigInterface()
    {
        if (! configIdIsSelect() && ! tableNameIsSelect()) {
            $('.wrapper-step2, .wrapper-step3', container).removeClass('active');
        }else{
            if(tableNameIsSelect()) {
                $('.wrapper-step2', container).addClass('active');
            }
            if(configIdIsSelect()) {
                $('.wrapper-step2', container).removeClass('active');
            }
        }
    }
    function displayEntityConfigTpl(show)
    {
        if (show) {
            $('.wrapper-step3', container).addClass('active');
        } else {
            $('.wrapper-step3', container).removeClass('active');
        }
    }
    function displayButtonGroup()
    {
        groupUpdateConfig.hide();
        groupCreateConfig.hide();

        if (configIdIsSelect()) {
            groupUpdateConfig.show();
        } else if (tableNameIsSelect() && ! tableIsDependence(selectTableName.val()) ) {
            groupCreateConfig.show();
        }
    }
    function displayBtCreateConfig ()
    {
        if( ! createConfigIsValid)
        {
            btnCreateConfig
                .attr('disabled','disabled');

            if (btnCreateConfig.hasClass('btn-success')) {
                btnCreateConfig
                    .removeClass('btn-success')
                    .addClass('btn-default');
            }
        } else {
            btnCreateConfig
                .removeAttr('disabled')
                .removeClass('btn-default')
                .addClass('btn-success')
        }
    }
    function configIdIsSelect () {
        return selectConfigId.prop('selectedIndex');
    }
    function tableNameIsSelect () {
        return selectTableName.prop('selectedIndex');
    }
    function tableIsDependence (table) {
        return (config.isDependenceCache.hasOwnProperty(table) && config.isDependenceCache[table].length);
    }
    function setNameProperties (objVal)
    {
        var entityName = createEntityName(objVal);
        inputConfigurationName.val('config_' + entityName);
        inputEntityName.val(entityName);
        inputColumnsName.val(config.columns);
    }
    function setConfigTest ()
    {
        if (config.testConfCache.hasOwnProperty(selectTableName.val())) {
            setConfigTestResponse();
        } else {
            var setting = {
                tablename : selectTableName.val(),
                entityName : inputEntityName.val(),
                configurationName : inputConfigurationName.val()
            };
            setting.hasDependencies = config.hasDependenciesCache[setting.tablename];
            sendTestRequest(setting);
        }
    }
    function setTableConfiguration ()
    {
        var objVal = selectTableName.val();

        setNameProperties(objVal);
        checkTableIsDependence(objVal);
        displayEntityConfigTpl(false);
        displayButtonGroup();
        displayConfigInterface();
    }
    function setTestConfCache (res)
    {
        if (res.hasOwnProperty('masterconf') && res.masterconf.length) {
            config.testConfCache[selectTableName.val()] = app.objCopy(res.masterconf);
        } else {
            if(res.hasOwnProperty('error')) {
                renderConfigErrorAlert(res.error);
            }
        }
    }
    function setTablesCache ()
    {
        if(config.selectedConfigName != '') {
            config.tablesCache[config.selectedConfigName] = app.objCopy(config.tables);
        }
        config.tables = {};
    }
    function setConfigCache (res,obj)
    {
        config.hasDependenciesCache[obj.attr('data-val')] = $.map(res.hasDependencies,function(obj){return obj['tablename']});
        config.isDependenceCache[obj.attr('data-val')] = $.map(res.isDependence,function(obj){return obj['tablename']});
        config.configIdsCache[obj.attr('data-val')] = $.map(res.configIds,function(obj){return obj['id']});
        config.columnsCache[obj.attr('data-val')] = {'table':obj.attr('data-val'),'columns':res.data[0]['columns_names']};
        config.columns = res.data[0]['columns_names'];
    }
    function setConfigTables (res)
    {
        if (app.objLen(res.data)) {
            $.each(res.data, function(index, args) {
                config.tables[args['tablename']] = args;
            });
        }
    }
    function setIntegrationsConfigFromCache ()
    {
        var res = false;

        if(config.tablesCache.hasOwnProperty(config.selectedConfigName))
        {
            setUpdateConfig({data:config.tablesCache[config.selectedConfigName]});
            res = true;
        }
        return res;
    }
    function setEntityConfig (rows) {
        $.each(rows, function(i,value){$.each(value,function(e,arr){ entityConfiguration.push(app.objCopy(arr))})});
    }
    function setEntityConfigFromIntegration () {
        $.each(config.tables, function(i,obj){if(obj.hasOwnProperty('isNewElement')){entityConfiguration.push(app.objCopy(obj))}});
    }
    function setUpdateConfig (res)
    {
        entityConfiguration = [];
        setConfigTables(res);

        if (app.objLen(config.tables))
        {
            setEntityConfigFromIntegration();
            renderConfigInfoTpl(messages.createConfigIntegration);
            renderConfigItem();
            displayEntityConfigTpl(true);
        }
        displayButtonGroup();
        displayConfigInterface();
    }
    function setSelectedConfigName ()
    {
        if(selectConfigId.val() != '') {
            config.selectedConfigName = $('option:selected',selectConfigId).text();
        } else {
            config.selectedConfigName = '';
        }
    }
    function sendConfigEntries (obj)
    {
        if (entityConfiguration.length) {
            app.updateIgnoreKeys('entityconf');
            obj.val(JSON.stringify(entityConfiguration));
            sendRequest(obj);
        }
    }
    function sendUpdateRequest ()
    {
        resetSelectedIndex(selectTableName);
        setTablesCache();
        setSelectedConfigName();
        displayEntityConfigTpl(false);
        displayConfigInterface();

        if (selectConfigId.val() != '')
        {
            var fomatedval = selectConfigId.val().split('/');

            if ( ! setIntegrationsConfigFromCache()) {
                sendRequest(selectConfigId,fomatedval[0]);
            }
        } else {
            entitiesConfigTpl.empty();
            displayButtonGroup();
        }
    }
    function sendTestRequest (setting)
    {
        btnTestConfig.val(setting.tablename);
        sendRequest(btnTestConfig);
    }
    function sendTableNameRequest ()
    {
        createConfigIsValid = false;
        displayBtCreateConfig();
        resetSelectedIndex(selectConfigId);

        if (tableNameIsSelect())
        {
            if (config.columnsCache.hasOwnProperty(selectTableName.val()))
            {
                config.columns = config.columnsCache[selectTableName.val()].columns;
                setTableConfiguration();

            } else {
                sendRequest(selectTableName);
            }
        } else {
            resetNameProperties();
            displayButtonGroup();
            displayConfigInterface();
        }
    }
    function sendRequest (selectObj)
    {
        app.appRequestCallback.setCallback('setResponse',{serviceId:serviceId, obj:selectObj});
        if (arguments.length > 1) {
            selectObj.attr('data-val',arguments[1]);
        } else {
            selectObj.attr('data-val',selectObj.val());
        }
        app.ajaxRequest.setRequestObj(selectObj);
    }
    function setUpdateResponse (res)
    {
        if(res.hasOwnProperty('error')) {
            updateConfigIsValid = false;
            renderConfigErrorAlert(res.error);
        } else {
            app.appMessage.dialog(res.message,'alert-success');
            setTimeout(function(){document.location.href = document.location.href},2500);
        }
    }
    function setConfigResponse (res)
    {
        if(res.hasOwnProperty('error')) {
            createConfigIsValid = false;
            renderConfigErrorAlert(res.error);
            displayBtCreateConfig();
        } else {
            app.appMessage.dialog(res.message,'alert-success');
            setTimeout(function(){document.location.href = document.location.href},2500);
        }
    }
    function setConfigTestResponse()
    {
        var rows = [], error='';
        entityConfiguration = [];
        createConfigIsValid = false;

        if (config.testConfCache.hasOwnProperty(selectTableName.val()) )
        {
            var masterconf = app.objCopy(config.testConfCache[selectTableName.val()]);

            $.each(masterconf, function (i,conf)
            {
                if(typeof rows[conf.level] == 'undefined') {
                    rows[conf.level] = [getEntityObj(conf)];
                } else {
                    rows[conf.level].push(getEntityObj(conf));
                }
                if(conf.level*1 < 1){
                    createConfigIsValid = true;
                }
            });
            if ( ! createConfigIsValid) {
                error = messages.noMastertableMatch;
            } else {
                if(rows.length){
                    setEntityConfig(rows);
                    renderConfigInfoTpl(messages.createNewConf);
                    renderConfigItem ();
                    displayEntityConfigTpl(true);
                    displayBtCreateConfig();
                }
            }
        }

        renderConfigErrorAlert(error);
    }
    function setResponse (res, obj)
    {
        switch (obj.attr('name'))
        {
            case('tablename'):
                setConfigCache(res,obj);
                setTableConfiguration();
                break;
            case('entities_config_id'):
                setUpdateConfig(res,obj);
                break;
            case('settestconf'):
                setTestConfCache(res);
                setConfigTestResponse();
                break;
            case('createconf'):
                setConfigResponse(res);
                break;
            case('updateconf'):
                setUpdateResponse(res);
                break;
        }
    }
    function initSelectEvent (obj)
    {
        switch (obj.attr('name'))
        {
            case('tablename'):
                sendTableNameRequest();
                break;
            case('entities_config_id'):
                sendUpdateRequest();
                break;
        }
    }
    function initEvents ()
    {
        $('select',container).on('change', function(e) {
            e.preventDefault();
            renderConfigErrorAlert('');
            initSelectEvent($(this));
        });
        btnTestConfig.on('click', function(e) {
            e.preventDefault();
            setConfigTest();
        });
        btnCreateConfig.on('click', function(e) {
            e.preventDefault();
            sendConfigEntries($(this));
        });
        btnUpdateConfig.on('click', function(e) {
            e.preventDefault();
            sendConfigEntries($(this));
        });
    }
    function init ()
    {
        container = $('[data-service-controller='+ serviceId +']');
        selectTableName = $('select[name="tablename"]',container);
        selectConfigId = $('select[name="entities_config_id"]',container);
        inputConfigurationName = $('input[name="configuration_name"]',container);
        inputEntityName = $('input[name="name"]',container);
        btnUpdateConfig = $('#btnUpdateConfig',container);
        btnTestConfig = $('#btnTestConfig',container);
        btnCreateConfig = $('#btnCreateConfig',container);
        inputColumnsName = $('input[name="query_cols"]',container);
        groupUpdateConfig = $('.form-group.updateConfig',container);
        groupCreateConfig = $('.form-group.createConfig',container);
        configErrorAlert = $('#configErrorAlert',container);
        entitiesConfigTpl = $('#entitiesConfigTpl',container);
        configInfoTpl = $('#configInfoTpl',container);
        configErrorAlert.hide();
        initEvents();
    }

    return {
        init        : init,
        setResponse : setResponse
    };
})(app);