<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Version.
 */
class Tag_Version extends Zikula_AbstractVersion
{

    /**
     * Module meta data.
     *
     * @return array Module metadata.
     */
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname'] = $this->__('Tag');
        $meta['description'] = $this->__("Tagging module");
        $meta['url'] = $this->__('tag');
        $meta['version'] = '1.0.1';
        $meta['core_min'] = '1.3.2'; // requires minimum 1.3.2 or later
        $meta['core_max'] = '1.3.99'; // doesn't work with later branches
        $meta['securityschema'] = array('Tag::' => '::');
        $meta['capabilities'] = array();
        $meta['capabilities'][HookUtil::PROVIDER_CAPABLE] = array('enabled' => true);
        return $meta;
    }

    protected function setupHookBundles()
    {
        $bundle = new Zikula_HookManager_ProviderBundle($this->name, 'provider.tag.ui_hooks.service', 'ui_hooks', $this->__('Content tagging service'));
        $bundle->addServiceHandler('display_view', 'Tag_HookHandlers', 'uiView', 'tag.service');
        $bundle->addServiceHandler('form_edit', 'Tag_HookHandlers', 'uiEdit', 'tag.service');
        $bundle->addServiceHandler('validate_edit', 'Tag_HookHandlers', 'validateEdit', 'tag.service');
        $bundle->addServiceHandler('process_edit', 'Tag_HookHandlers', 'processEdit', 'tag.service');
        $bundle->addServiceHandler('process_delete', 'Tag_HookHandlers', 'processDelete', 'tag.service');
        $this->registerHookProviderBundle($bundle);
    }

}
