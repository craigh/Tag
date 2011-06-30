<fieldset>
    <legend>{gt text="Tags"}</legend>
    <div class="z-formrow">
        <label for="tag_tags">{gt text='Add tags'}</label>
        <input type="text" name="tag[tags]" id="tag_tags" value="{$tag.taglist}" />
        <em class="z-sub z-formnote">{gt text='comma separated (e.g. zikula, computer, code)'}</em>
        <div class="z-informationmsg z-formnote">{gt text='Existing tags'}: {$globaltaglist}</div>
    </div>
</fieldset>