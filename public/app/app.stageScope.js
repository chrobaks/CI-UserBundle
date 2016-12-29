/**
 * @namespace app
 * @object stageScope
 */

var app = app || {};

app.stageScope = (function ()
{
    var scopeStorage = {};
    var scopeListStorage = {};
    var scopeInputObjStorage = {};
    var scopeItem = $('.scope', 'body');
    var scopeObj = $('.data-scope');

    function scopeStorageLength ()
    {
        return $.map(scopeStorage, function(n, i) { return i; }).length;
    }
    function scopeStorageListLength ()
    {
        return $.map(scopeListStorage, function(n, i) { return i; }).length;
    }
    function elementScopeStorage (element)
    {
        scopeStorage = {};

        if ($(scopeObj, element).length) {
            $(scopeObj, element).each(function () {
                scopeStorage[$(this).attr('data-scope')] = $(this).val();
            });
        }
        if (element.attr('data-scope')) {
            if (element.attr('data-scope-type')) {
                scopeListStorage[element.attr('data-scope')] = {};
                $(scopeObj, element).each(function () {
                    scopeListStorage[element.attr('data-scope')][$(this).attr('name')] = $(this).val();
                });
            }
        }
    }
    function renderScopeStorage ()
    {
        if (scopeItem.length) {
            scopeItem.each(function () {
                if ($(this).attr('data-scope') && typeof scopeStorage[$(this).attr('data-scope')] !== 'undefined') {
                    if ($(this).attr('data-scope-attr')) {
                        $(this).attr('data-original-' + $(this).attr('data-scope-attr'), scopeStorage[$(this).attr('data-scope')]);
                    }
                }
            });
        }
        scopeStorage = {};
    }
    function renderScopeListStorage ()
    {
        scopeListStorage = {};
    }

    return {
        scopeStorageLength     : scopeStorageLength,
        elementScopeStorage    : elementScopeStorage,
        renderScopeStorage     : renderScopeStorage,
        renderScopeListStorage : renderScopeListStorage
    };
})();
