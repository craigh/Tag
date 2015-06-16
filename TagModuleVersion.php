<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\TagModule;

use HookUtil;
use Zikula_HookManager_ProviderBundle;

/**
 * Version.
 */
class TagModuleVersion extends \Zikula_AbstractVersion
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
        $meta['oldnames'] = array("Tag");
        $meta['description'] = $this->__('Tagging module');
        $meta['url'] = $this->__('tag');
        $meta['version'] = '2.0.0';
        $meta['core_min'] = '1.4.0';
        $meta['core_max'] = '2.9.99';
        $meta['securityschema'] = array($this->name . '::' => '::');
        $meta['capabilities'] = array();
        $meta['capabilities'][HookUtil::PROVIDER_CAPABLE] = array('enabled' => true);
        return $meta;
    }
    protected function setupHookBundles()
    {
        $bundle = new \Zikula_HookManager_ProviderBundle($this->name, 'provider.tag.ui_hooks.service', 'ui_hooks', $this->__('Content tagging service'));
        $bundle->addServiceHandler('display_view', '\Zikula\TagModule\HookHandlers', 'uiView', 'tag.service');
        $bundle->addServiceHandler('form_edit', '\Zikula\TagModule\HookHandlers', 'uiEdit', 'tag.service');
        $bundle->addServiceHandler('validate_edit', '\Zikula\TagModule\HookHandlers', 'validateEdit', 'tag.service');
        $bundle->addServiceHandler('process_edit', '\Zikula\TagModule\HookHandlers', 'processEdit', 'tag.service');
        $bundle->addServiceHandler('process_delete', '\Zikula\TagModule\HookHandlers', 'processDelete', 'tag.service');
        $this->registerHookProviderBundle($bundle);
    }
}