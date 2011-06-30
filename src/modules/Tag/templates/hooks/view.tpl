<ul>
{foreach from=$tags item='tag'}
<li>{$tag.tag|safetext}</li>
{foreachelse}
<li>notags</li>
{/foreach}
</ul>