<h2>{gt text='Tags on this site'}</h2>
<ul>
{foreach from=$tags item='tag'}
    <li class="tag_pop_{$tag.weight}"><a href='{modurl modname="Tag" type="user" func="view" tag=$tag.tag|escape:'url'}'>{$tag.tag|safetext}</a> ({gt text="tagged %s time" plural="tagged %s times" count=$tag.freq tag1=$tag.freq})</li>
{foreachelse}
    <li>{gt text='No tags.'}</li>
{/foreach}
</ul>
{if isset($result)}
<h3>{$selectedtag}</h3>
<ul>
{foreach from=$result item='r'}
<li><a href='{$r.url}'>{gt text="%s item (id# %s)" tag1=$r.module tag2=$r.objectId}</a></li>
{/foreach}
</ul>
{/if}