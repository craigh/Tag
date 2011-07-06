{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="edit" size="small"}
    <h3>{if isset($id)}{gt text="Edit Tag"}{else}{gt text="New Tag"}{/if}</h3>
</div>
{form cssClass="z-form"}
    <fieldset>
        <legend>{gt text="Tag"}</legend>

        {formvalidationsummary}

        <div class="z-formrow">
            {formlabel for="tag" __text="Tag"}
            {formtextinput id="tag" mandatory=true maxLength=36}
        </div>
        <div class="z-buttons z-formbuttons">
            {formbutton class='z-bt-ok' commandName='create' __text='Save'}
            {formbutton class='z-bt-cancel' commandName='cancel' __text='Cancel'}
            {if isset($id)}{formbutton class="z-bt-delete z-btred" commandName="delete" __text="Delete" __confirmMessage='Are you sure you want to delete this item'}{/if}
        </div>

    </fieldset>
{/form}
{adminfooter}