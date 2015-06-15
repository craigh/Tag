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

use Zikula_View;
use ServiceUtil;
use SecurityUtil;
use ModUtil;
use Zikula_Response_DisplayHook;
use Zikula_Hook_ValidationResponse;
use ZLanguage;
use LogUtil;

class HookHandlers extends \Zikula_Hook_AbstractHandler
{
    /**
     * Zikula_View instance
     * @var object
     */
    private $view;
    /**
     * Zikula Service Doctrine EntityManager instance
     * @var object
     */
    private $entityManager;
    /**
     * Zikula Request instance
     * @var object
     */
    private $request;
    /**
     * Post constructor hook.
     *
     * @return void
     */
    public function setup()
    {
        $this->view = \Zikula_View::getInstance('ZikulaTagModule');
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
        $this->request = ServiceUtil::getService('request');
        // hooks do not autoload the bootstrap for the module
        $helper = ServiceUtil::getService('doctrine_extensions');
        $helper->getListener('sluggable');
    }
    /**
     * Display hook for edit views.
     *
     * @param \Zikula_DisplayHook $hook
     *
     * @return void
     */
    public function uiEdit(\Zikula_DisplayHook $hook)
    {
        // Security check
        if (!SecurityUtil::checkPermission('ZikulaTagModule::', '::', ACCESS_ADD)) {
            return;
        }
        $module = $hook->getCaller();
        $hookObjectId = $hook->getId();
        $objectId = isset($hookObjectId) ? $hookObjectId : 0;
        $areaId = $hook->getAreaId();
        // Load module, otherwise translation is not working in template
        ModUtil::load('ZikulaTagModule');
        if (!empty($objectId)) {
            $selectedTags = $this->entityManager->getRepository('Zikula\TagModule\Entity\ObjectEntity')->getTags($module, $areaId, $objectId);
        } else {
            $selectedTags = array();
        }
        $this->view->assign('selectedTags', $selectedTags);
        $tagsByPopularity = $this->entityManager->getRepository('Zikula\TagModule\Entity\TagEntity')->getTagsByFrequency(ModUtil::getVar('ZikulaTagModule', 'poptagsoneditform', null));
        $this->view->assign('tagsByPopularity', $tagsByPopularity);
        // add this response to the event stack
        $area = 'provider.tag.ui_hooks.service';
        $hook->setResponse(new Zikula_Response_DisplayHook($area, $this->view, 'hooks/edit.tpl'));
    }
    /**
     * validation handler for validate_edit hook type.
     *
     * @param \Zikula_ValidationHook $hook
     *
     * @return void
     */
    public function validateEdit(\Zikula_ValidationHook $hook)
    {
        // get data from post
        $data = $this->request->getPost()->get('tag', null);
        // create a new hook validation object and assign it to $this->validation
        $this->validation = new Zikula_Hook_ValidationResponse('data', $data);
        $hook->setValidator('provider.tag.ui_hooks.service', $this->validation);
    }
    /**
     * process edit hook handler.
     *
     * @param \Zikula_ProcessHook $hook
     *
     * @return void
     */
    public function processEdit(\Zikula_ProcessHook $hook)
    {
        // check for validation here
        if (!$this->validation) {
            return;
        }
        $args = array('module' => $hook->getCaller(), 'objectId' => $hook->getId(), 'areaId' => $hook->getAreaId(), 'objUrl' => $hook->getUrl(), 'hookdata' => $this->validation->getObject());
        ModUtil::apiFunc('ZikulaTagModule', 'user', 'tagObject', $args);
    }
    /**
     * Display hook for view.
     *
     * @param \Zikula_DisplayHook $hook
     *
     * @return void
     */
    public function uiView(\Zikula_DisplayHook $hook)
    {
        // Security check
        if (!SecurityUtil::checkPermission('ZikulaTagModule::', '::', ACCESS_READ)) {
            return;
        }
        // get data from $event
        $module = $hook->getCaller();
        $objectId = $hook->getId();
        $areaId = $hook->getAreaId();
        if (!$objectId) {
            return;
        }
        // Load module, otherwise translation is not working in template
        ModUtil::load('ZikulaTagModule');
        $tags = $this->entityManager->getRepository('Zikula\TagModule\Entity\ObjectEntity')->getTags($module, $areaId, $objectId);
        $this->view->setCacheId('uiview|' . $module . '|' . $areaId . '|' . $objectId);
        $this->view->assign('tags', $tags);
        // add this response to the event stack
        $area = 'provider.tag.ui_hooks.service';
        $hook->setResponse(new \Zikula_Response_DisplayHook($area, $this->view, 'hooks/view.tpl'));
    }
    /**
     * delete process hook handler.
     *
     * @param \Zikula_ProcessHook $event
     *
     * @return void
     */
    public function processDelete(\Zikula_ProcessHook $hook)
    {
        $module = $hook->getCaller();
        $objectId = $hook->getId();
        $areaId = $hook->getAreaId();
        $hookObject = $this->entityManager->getRepository('Zikula\TagModule\Entity\ObjectEntity')->findOneBy(array('module' => $module, 'objectId' => $objectId, 'areaId' => $areaId));
        if (!empty($hookObject)) {
            $this->entityManager->remove($hookObject);
            $this->entityManager->flush();
        }
    }
    /**
     * Handle module uninstall event "installer.module.uninstalled".
     * Receives $modinfo as $args
     *
     * @param \Zikula_Event $event
     *
     * @return void
     */
    public static function moduleDelete(\Zikula_Event $event)
    {
        $module = $event['name'];
        $em = ServiceUtil::getService('doctrine.entitymanager');
        $hookObjects = $em->getRepository('Zikula\TagModule\Entity\ObjectEntity')->findBy(array('module' => $module));
        // better to do it this way than DQL because removes related objects also
        if (count($hookObjects) > 0) {
            foreach ($hookObjects as $hookObject) {
                $em->remove($hookObject);
            }
            $em->flush();
            LogUtil::registerStatus(__('Hooked content in Tags removed.', ZLanguage::getModuleDomain('ZikulaTagModule')));
        }
    }
    /**
     * Handle hook uninstall event "installer.subscriberarea.uninstalled".
     * Receives $areaId in $args
     *
     * @param \Zikula_Event $event
     *
     * @return void
     */
    public static function moduleDeleteByArea(\Zikula_Event $event)
    {
        $areaId = $event['areaid'];
        $em = ServiceUtil::getService('doctrine.entitymanager');
        $hookObjects = $em->getRepository('Zikula\TagModule\Entity\ObjectEntity')->findBy(array('areaId' => $areaId));
        // better to do it this way than DQL because removes related objects also
        if (count($hookObjects) > 0) {
            foreach ($hookObjects as $hookObject) {
                $em->remove($hookObject);
            }
            $em->flush();
            LogUtil::registerStatus(__f('Hooked content in Tags removed for area %s.', $areaId, ZLanguage::getModuleDomain('ZikulaTagModule')));
        }
    }
}