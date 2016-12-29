/**
 * app.js
 * this is the base object
 */
var app = app || {};
// regex rules
app.regex = {
    'active'            : /^(0|1)$/ig,
    'content'           : /^[\w\d\.\!\?\(\)\[\]\{\}\$\|#&;,:="'@\+\*\/\-_\s]+$/ig,
    'data-mod'          : /^(lang)$/ig,
    'email'             : /^[a-z]{3,}[@]{1}[a-z0-9\.]+[a-z]{1,}$/ig,
    'id'                : /^((?!(0))[0-9]{1,11})$/ig,
    'lang'              : /^(de|en)$/ig,
    'name'              : /^[a-z\s]{2,50}$/ig,
    'password'          : /^[a-zöäü0-9\-\_]{8,20}$/ig,
    'role'              : /^(user|admin|root)$/ig,
    'tablename'         : /^[a-z_,]{2,250}$/ig,
    'syncname'          : /^config_[a-z]{2,250}$/ig,
    'title_de'          : /^[a-zöüäß\s\-_]{2,250}$/ig,
    'title_en'          : /^[a-z\s\-_]{2,250}$/ig,
    'url_link'          : /^[a-z]{2,250}$/ig,
    'username'          : /^[a-z]{8,20}$/ig,
    'zIndex'            : /^((?!(0))[0-9]+)$/ig
};
// regex rules from stored rules
app.regex['confirmation'] = app.regex['active'];
app.regex['is_pre_tag'] = app.regex['active'];
app.regex['message'] = app.regex['content'];
app.regex['passconf'] = app.regex['password'];
app.regex['tutorial_category_id'] = app.regex['id'];
app.regex['tutorial_chapter_id'] = app.regex['id'];
app.regex['documentation_category_id'] = app.regex['id'];
app.regex['documentation_chapter_id'] = app.regex['id'];
app.regex['entities_config_id'] = app.regex['id'];
// key = field name , value = field name
// named fields values must be equal
app.equalFields = {
    'passconf' : 'password'
};
// app services
app.definedServices = [
    {service : 'dataModule', identifier : '[data-module]'}
];
// helper function
app.replaceStr = function (str, regex){
    if (regex.length){
        $.each(regex,function(i,expr) { while (str.match(expr.rule)) { str = str.replace(expr.rule,expr.replacer); } });
    }
    return str;
};
app.objLen = function (obj) {
    var res = 0;
    for(var e in obj){ res++; }
    return res;
};
app.objCopy = function (obj) {
    var res = {};
    for(var e in obj){
        if (typeof obj[e] == 'string' || typeof obj[e] == 'number' ||obj[e] == null) {
            res[e] = obj[e];
        } else {
            res[e] = app.objCopy(obj[e]);
        }
    }
    return res;
};
app.updateIgnoreKeys = function (key) {
    key = $.trim(key);
    if(key != '' && $.inArray(key,app.ignoreKeys) == -1) {app.ignoreKeys.push(key);}
};
