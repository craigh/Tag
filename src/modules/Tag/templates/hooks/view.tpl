{pageaddvar name='stylesheet' value='modules/Tag/style/style.css'}
<div class='tagcloud'>
    <ul>
    {foreach from=$tags item='tag'}
        <li class='activeTag'><a href='{modurl modname='Tag' type='user' func='view' tag=$tag.tag|safetext}'><span class='taghole'>&bull;</span>{$tag.tag|safetext}</a></li>
    {foreachelse}
        <li>notags</li>
    {/foreach}
    </ul>
</div>