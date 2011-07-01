function livetagsearch()
{
    if ($('tagsAvailableToAdd')) {
        // this is wrong :-)
        var tagsAvailableToAssign = $$('.tag_available');
        $tagsAvailableToAssign.each(function(tag_available) {
            $(tag_available).observe('click', tag_add_available);
        });
        //observe('click', tag_add_available)
    }
    
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
    new Ajax.Autocompleter('tag_adder', 'tag_choices', Zikula.Config.baseURL + 'ajax.php?module=tag&func=gettags', options);
}

function tag_add_available()
{
    alert('clicked!');
}