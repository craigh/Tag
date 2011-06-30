{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Tag List"}</h3>
</div>

{insert name="getstatusmsg"}

<table class="z-datatable">
    <thead>
        <tr>
            <td>{gt text='ID'}</td>
            <td>{gt text='Tag'}</td>
            <td>{gt text='Options'}</td>
        </tr>
    </thead>
    <tbody>
        {foreach from=$tags item='tag'}
            <tr class="{cycle values="z-odd,z-even"}">
                <td>{$tag->getId()|safetext}</td>
                <td>{$tag->getTag()|safetext}</td>
                <td><a href="{modurl modname="Tag" type="admin" func="edit" id=$tag->getId()}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}</a></td>
            </tr>
        {foreachelse}
        <tr class='z-datatableempty'><td colspan='3' class='z-center'>{gt text='No tags.'}</td></tr>
        {/foreach}
    </tbody>
</table>
{adminfooter}