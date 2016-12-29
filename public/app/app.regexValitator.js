/**
 * @namespace app
 * @object regexValitator
 */

var app = app || {};

app.regexValitator = (function (app)
{
    function regexForm (form)
    {
        var res = null;
        var error = [];
        var equalFieldsCheck = {};
        var formdata = form.serializeArray();
        var decodeKeys = (form.attr('data-decode')) ? form.attr('data-decode').split(',') : [];

        if (formdata.length) {
            res = {};
            $.each(formdata, function (index, obj) {

                obj.value = $.trim(obj.value);

                if($.inArray(obj.name, decodeKeys) != -1)
                {
                    obj.value = app.replaceStr(obj.value,[
                        {rule:/>/g, replacer:'&gt;'},
                        {rule:/</g, replacer:'&lt;'}
                    ]);
                }
                if ($.inArray(obj.name,app.ignoreKeys) != -1) {
                    res[obj.name] = obj.value;
                } else {
                    if (app.regex.hasOwnProperty(obj.name))
                    {
                        if (obj.value.match(app.regex[obj.name]))
                        {
                            res[obj.name] = obj.value;

                            if (app.equalFields.hasOwnProperty(obj.name)) {
                                equalFieldsCheck[app.equalFields[obj.name]]= obj.value;
                            }
                        } else {
                            error.push('Field value ' + obj.value + ' is not valid in field :' + obj.name);
                        }
                    } else {
                        error.push('Field ' + obj.name + ' not valid.');
                    }
                }
            });

            $.each(equalFieldsCheck, function (key, val) {
                if ( typeof res[key] !== 'undefined' && res[key] !== val) {
                    error.push('Confirmfield Field value for ' + key + ' not the same.');
                }
            });
        }
        return {res:res,error:error};
    }
    function regexObj (data)
    {
        var res ={};
        var error = [];

        $.each(data, function (key, val)
        {
            if ($.inArray(key,app.ignoreKeys) != -1)
            {
                res[key] = val;
            } else {
                if (app.regex.hasOwnProperty(key))
                {
                    val = $.trim(val);

                    if (val.match(app.regex[key])) {
                        res[key] = val;
                    } else {
                        error.push('Field value ' + val + ' of ' + key + ' not valid.');
                    }
                } else {
                    error.push('Field ' + key + ' not valid.');
                }
            }

        });
        return {res:res,error:error};
    }
    return {
        regexForm : regexForm,
        regexObj  : regexObj
    };
})(app);