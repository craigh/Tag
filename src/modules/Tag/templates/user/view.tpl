{ajaxheader modname='Tag' ui=true}
{if isset($selectedtag)}
{pageaddvar name='title' value=$selectedtag[0]->getTag()|safetext}
{/if}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        Zikula.UI.Tooltips($$('.tooltips'));
    });
</script>
{/pageaddvarblock}
<h2>{gt text='Tags on this site'}</h2>
<div class='tagcloud'>
    <ul>
    {foreach from=$tags item='tag'}
        <li class="tag_pop_{$tag.weight}"><a href='{modurl modname="Tag" type="user" func="view" tag=$tag.slug|safetext}' title='{gt text="tagged %s time" plural="tagged %s times" count=$tag.freq tag1=$tag.freq}' class='tooltips'>{$tag.tag|safetext}</a></li>
    {foreachelse}
        <li>{gt text='No tags.'}</li>
    {/foreach}
    </ul>
</div>
{if isset($selectedtag)}
<h3>{$selectedtag[0]->getTag()|safetext}</h3>
<ul>
{foreach from=$result item='r'}
    {if isset($r.link)}
    <li>{$r.link|safehtml}</li>
    {/if}
{foreachelse}
    <li>{gt text='No items tagged'}</li>
{/foreach}
</ul>
{/if}
