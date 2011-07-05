function initTagUI()
{
    if ($('tagsAvailableToAdd')) {
        $$('.tag_available').invoke('observe', 'click', tag_add_available);
    }
    if ($('selectedTags')) {
        $$('.tagRemover').invoke('observe', 'click', tag_remove)
    }
    if ($('addNewTag')) {
        $('addNewTag').observe('click', tag_add_new);
    }
    
    var options = Zikula.Ajax.Request.defaultOptions({
        paramName: 'fragment',
        tokens: ',',
        minChars: 2,
        afterUpdateElement: function(data){
                var thing = $($(data).value).value;
                alert(thing);
            }
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

function tag_add_available(event)
{
    var id = event.element().identify();
    var tagnameparts = id.split("_");
    var tagname = tagnameparts.pop();
    _tag_add(tagname);
}

function _tag_add(tagname)
{
    if ($('li_' + tagname) == undefined) {
        // add visible tag
        $('selectedTags').insert("<li class='activeTag' id='li_" + tagname + "'><span class='taghole'>&bull;</span>" + tagname + " <a href='javascript:void(0);' title='" + Zikula.__('remove tag') + "' id='tagRemove_" + tagname + "' class='tagRemover tooltips'>x</a></li>\n");
        // engage tooltip observer
        var defaultTooltip = new Zikula.UI.Tooltip($('tagRemove_' + tagname));
        // engage removal observer
        $('tagRemove_' + tagname).observe('click', tag_remove);
        
        // add hidden form element
        $('activeTagContainer').insert("<input type='hidden' name='tag[tags][]' id='tagActive_" + tagname + "' value='" + tagname + "' />\n");
        // form.insert(new Element('input', {name: 'q', value: 'a', type: 'hidden'}));

    } else {
        new Effect.Highlight('li_' + tagname, { startcolor: '#99ff66', endcolor: '#C5E8F1' });
    }
}

function tag_remove(event)
{
    var id = event.element().identify();
    var tagnameparts = id.split("_");
    var tagname = tagnameparts.pop();
    _tag_remove(tagname);
}

function _tag_remove(tagname)
{    
    // remove visible tooltip
    // ??
    // remove visible tag
    $('li_' + tagname).remove();
    // remove hidden form element
    $('tagActive_' + tagname).remove();
}

function tag_add_new(event)
{
    var taglist = $('tag_adder').value;
    var tagArray = tagListToCleanArray(taglist);
    tagArray.each(function(word) {
        _tag_add(word);
    })
    $('tag_adder').value = '';
//    alert(taglist);
}

function tagListToCleanArray(list)
{
    var tagArray = list.split(",");
    var resultArray = [];
    tagArray.each(function(s) {
        var word = s.stripTags();
        word = word.stripScripts();
        // trim?
        if (word.empty() != null) {
            resultArray.push(word);
        }
    });
    return resultArray;
}