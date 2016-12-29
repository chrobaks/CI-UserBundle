/**
 * @namespace app
 *
 * @requires app
 * @object csrfToken
 */

var app = app || {};

app.csrfToken = (function(app)
{
    var token = {key:'',value:''};

    function setToken (res)
    {
        $('input[name="'+res['csrfn']+'"]').val(res['csrfv']);
        $('[data-key="'+res['csrfn']+'"]').val(res['csrfv']);
        $('[data-csrf-key="'+res['csrfn']+'"]').attr('data-csrf-val',res['csrfv']);
        token = {key:res['csrfn'],hash:res['csrfv']};
    }
    function getFormToken (form)
    {
        var token = {key:'', hash:''};

        form.find('input').each(function () {
            if($(this).attr('data-csrf-key')) {
                token.key = $(this).attr('data-csrf-key');
                token.hash = $(this).val();
                return true;
            }
        });
        return token;
    }
    function getToken () { return token; }
    function init () { token = {key:app.token.key,hash:app.token.hash}; }

    return {
        init         : init,
        setToken     : setToken,
        getToken     : getToken,
        getFormToken : getFormToken
    };
})(app);