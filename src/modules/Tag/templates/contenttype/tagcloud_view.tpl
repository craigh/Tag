{ajaxheader modname='Tag' ui=true}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        Zikula.UI.Tooltips($$('.tooltips'));
    });
</script>
{/pageaddvarblock}
<div class='tagcloud'>
    <ul>
    {foreach from=$tags item='tag'}
        <li class="tag_pop_{$tag.weight}"><a href='{modurl modname="Tag" type="user" func="view" tag=$tag.slug|safetext}' title='{gt text="tagged %s time" plural="tagged %s times" count=$tag.freq tag1=$tag.freq}' class='tooltips'>{$tag.tag|safetext}</a></li>
    {foreachelse}
        <li>{gt text='No tags.'}</li>
    {/foreach}
    </ul>
</div>