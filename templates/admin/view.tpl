{ajaxheader modname='Tag' ui=true}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        Zikula.UI.Tooltips($$('.tooltips'));
    });
</script>
{/pageaddvarblock}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Tag List"}</h3>
</div>

{insert name="getstatusmsg"}

<table class="z-datatable">
    <thead>
        <tr>
            <td><a class='{$sort.class.id}' href='{$sort.url.id|safetext}'>{gt text='ID'}</a></td>
            <td><a class='{$sort.class.tag}' href='{$sort.url.tag|safetext}'>{gt text='Text Tag'}</a></td>
            <td>{gt text='Link Tag'}</td>
            <td><a class='{$sort.class.cnt}' href='{$sort.url.cnt|safetext}'>{gt text='Items tagged'}</a></td>
            <td>{gt text='Options'}</td>
        </tr>
    </thead>
    <tbody>
        {foreach from=$tags item='tag'}
            <tr class="{cycle values="z-odd,z-even"}">
                <td>{$tag.0->getId()|safetext}</td>
                <td>{$tag.0->getTag()|safetext}</td>
                <td>{$tag.0->getSlug()|safetext}</td>
                <td>{$tag.cnt|safetext}</td>
                <td>
                    <a href="{modurl modname="Tag" type="user" func="view" tag=$tag.0->getSlug()}">{img modname='core' set='icons/extrasmall' src='14_layer_visible.png' __title='View' __alt='View' class='tooltips'}</a>
                    <a href="{modurl modname="Tag" type="admin" func="edit" id=$tag.0->getId()}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}</a>
                </td>
            </tr>
        {foreachelse}
        <tr class='z-datatableempty'><td colspan='4' class='z-center'>{gt text='No tags.'}</td></tr>
        {/foreach}
    </tbody>
</table>
{adminfooter}