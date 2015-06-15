{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Tag settings'}</h3>
</div>

<form class="z-form" action="{modurl modname=$module type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        <fieldset>
            <legend>{gt text='General settings'}</legend>

            <div class="z-formrow">
                <label for="poptagsoneditform">{gt text='Number of popular tags to display on edit form'}</label>
                <input id="poptagsoneditform" type="text" name="poptagsoneditform" value="{$modvars.$module.poptagsoneditform|safetext}" size="10" maxlength="10" />
            </div>
        </fieldset>

        <div class="z-buttons z-formbuttons">
            {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
            <a href="{modurl modname="Downloads" type="admin" func='modifyconfig'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </div>
</form>
{adminfooter}