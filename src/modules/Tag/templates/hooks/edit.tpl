{ajaxheader modname='Tag' filename='tag.js' ui=true}
{pageaddvar name='stylesheet' value='modules/Tag/style/style.css'}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        initTagUI();
        Zikula.UI.Tooltips($$('.tooltips'));
    });
</script>
{/pageaddvarblock}
<fieldset>
    <legend>{gt text="Tags"}</legend>
    <div id="livetagsearch" class="z-formrow">
        <label for="tag_tags">{gt text='Add tags'}</label>
        <span><input type="text" name="tag_adder" id="tag_adder" value="" />
        <span class="z-buttons">{button type='button' src="button_ok.png" set="icons/extrasmall" id='addNewTag' class='z-bt-small' __alt="Add" __title="Add" __text="Add"}</span>
        {img id="ajax_indicator" style="display: none;" modname=core set="ajax" src="indicator_circle.gif" alt=""}</span>
        <em class="z-sub z-formnote">{gt text='comma separated (e.g. zikula, computer, code)'}</em>
        <div id="tag_choices" class="autocomplete_tag"></div>
    </div>
    <div class='z-formnote'>
        <ul id="selectedTags">
        {if count($selectedTags) > 0}
        {foreach from=$selectedTags item='sTag'}
            <li class='activeTag' id='li_{$sTag.tag|safetext}'><span class='taghole'>&bull;</span>{$sTag.tag|safetext} <a href='javascript:void(0);' title='{gt text='remove tag'}' id='tagRemove_{$sTag.tag|safetext}' class='tagRemover'>x</a></li>
        {/foreach}
        {else}
            <li id='tag_null_li' style='display: none;'>This prevents an html validation error when $selectedtags is empty.</li>
        {/if}
        </ul>
    </div>
    <div class="tagcloud z-formnote">
        {gt text='Popular tags'}:
        <ul id='tagsAvailableToAdd'>
        {foreach from=$tagsByPopularity item='tag'}
            <li class="tag_pop_{$tag.weight}"><a href='javascript:void(0);' title='{gt text="Add tag \"%s\"" tag1=$tag.tag|safetext}' id='TagAvail_{$tag.tag|safetext}' class='tag_available tooltips'>{$tag.tag|safetext}</a></li>
        {foreachelse}
            <li>{gt text='No tags.'}</li>
        {/foreach}
        </ul>
    </div>
    <div id='activeTagContainer'>
        {foreach from=$selectedTags item='sTag'}
        <input type="hidden" name="tag[tags][]" id="tagActive_{$sTag.tag|safetext}" value="{$sTag.tag|safetext}" />
        {/foreach}
    </div>
</fieldset>