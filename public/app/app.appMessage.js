/**
 * @namespace app
 *
 * @object appMessage
 */

var app = app || {};

app.appMessage = (function()
{
    function alert(hasError, message, formMessageElmn)
    {
        var cssRemove = (hasError) ? 'text-success' : 'text-danger';
        var cssAdd = ( ! hasError) ? 'text-success' : 'text-danger';

        if (formMessageElmn) {
            formMessageElmn
                .removeClass(cssRemove)
                .addClass(cssAdd)
                .html(message);
        }
    }
    function dialog(txt, alertcss)
    {
        var dialog = $('#myAppDialogModal');

        if (dialog) {
            $('.modal-body',dialog).html($('<div>',{"class":"alert "+alertcss}).html(txt));
            dialog.modal('show');
        }
    }
    return {
        alert  : alert,
        dialog : dialog
    };
})();
