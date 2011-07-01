<ul>
{if isset($tags) and is_array($tags) and count($tags) gt 0}
{foreach from=$tags item='tag'}
<li>{$tag->getTag()|safetext}</li>
{/foreach}
{/if}
</ul>