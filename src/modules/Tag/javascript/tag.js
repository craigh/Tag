function livetagsearch()
{
    $('livetagsearch').removeClassName('z-hide');

    var options = Zikula.Ajax.Request.defaultOptions({
        paramName: 'fragment',
        tokens: ',',
        minChars: 2//,
//        afterUpdateElement: function(data){
//            $('modifyuser').observe('click', function() {
//                window.location.href = Zikula.Config.entrypoint + "?module=users&type=admin&func=modify&userid=" + $($(data).value).value;
//            });
//            $('deleteuser').observe('click', function() {
//                window.location.href=Zikula.Config.entrypoint + "?module=users&type=admin&func=deleteusers&userid=" + $($(data).value).value;
//            });
//        }
    });
    new Ajax.Autocompleter('tag_tags', 'tag_choices', Zikula.Config.baseURL + 'ajax.php?module=tag&func=gettags', options);
}