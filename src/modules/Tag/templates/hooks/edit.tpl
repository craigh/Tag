{ajaxheader modname='Tag' filename='tag.js'}
{pageaddvar name='stylesheet' value='modules/Tag/style/taghooksstyle.css'}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        livetagsearch();
    });
</script>
{/pageaddvarblock}
<fieldset>
    <legend>{gt text="Tags"}</legend>
    <div id="livetagsearch" class="z-hide z-formrow">
        <label for="tag_tags">{gt text='Add tags'}</label>
        <span><input type="text" name="tag[tags]" id="tag_tags" value="{$tag.taglist}" />
        {img id="ajax_indicator" style="display: none;" modname=core set="ajax" src="indicator_circle.gif" alt=""}</span>
        <em class="z-sub z-formnote">{gt text='comma separated (e.g. zikula, computer, code)'}</em>
        <div id="tag_choices" class="autocomplete_tag"></div>
    </div>
    <div id='tagcloud' class="z-formnote">
        {gt text='Popular tags'}:
        <ul>
        {foreach from=$tagsByPopularity item='tag'}
            <li class="tag_pop_{$tag.weight}"><a href='javascript:void()' title='{gt text="Add tag %s" tag1=$tag.tag|safetext}' class='tooltips'>{$tag.tag|safetext}</a></li>
        {foreachelse}
            <li>{gt text='No tags.'}</li>
        {/foreach}
        </ul>
    </div>
</fieldset>