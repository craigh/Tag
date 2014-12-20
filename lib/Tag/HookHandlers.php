<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
class Tag_HookHandlers extends Zikula_Hook_AbstractHandler
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
        $this->view = Zikula_View::getInstance("Tag");
        $this->entityManager = ServiceUtil::getService('doctrine.entitymanager');
        $this->request = ServiceUtil::getService('request');
        // hooks do not autoload the bootstrap for the module
        $helper = ServiceUtil::getService('doctrine_extensions');
        $helper->getListener('sluggable');
    }

    /**
     * Display hook for edit views.
     *
     * @param Zikula_DisplayHook $hook
     *
     * @return void
     */
    public function uiEdit(Zikula_DisplayHook $hook)
    {
        // Security check
        if (!SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADD)) {
            return;
        }
        $module = $hook->getCaller();
        $hookObjectId = $hook->getId();
        $objectId = isset($hookObjectId) ? $hookObjectId : 0;
        $areaId = $hook->getAreaId();

        if (!empty($objectId)) {
            $selectedTags = $this->entityManager->getRepository('Tag_Entity_Object')->getTags($module, $areaId, $objectId);
        } else {
            $selectedTags = array();
        }
        $this->view->assign('selectedTags', $selectedTags);

        $tagsByPopularity = $this->entityManager->getRepository('Tag_Entity_Tag')->getTagsByFrequency(ModUtil::getVar('Tag', 'poptagsoneditform', null));
        $this->view->assign('tagsByPopularity', $tagsByPopularity);

        // add this response to the event stack
        $area = 'provider.tag.ui_hooks.service';
        $hook->setResponse(new Zikula_Response_DisplayHook($area, $this->view, 'hooks/edit.tpl'));
    }

    /**
     * validation handler for validate_edit hook type.
     *
     * @param Zikula_ValidationHook $hook
     *
     * @return void
     */
    public function validateEdit(Zikula_ValidationHook $hook)
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
     * @param Zikula_ProcessHook $hook
     *
     * @return void
     */
    public function processEdit(Zikula_ProcessHook $hook)
    {
        // check for validation here
        if (!$this->validation) {
            return;
        }

        $module = $hook->getCaller();
        $objectId = $hook->getId();
        $areaId = $hook->getAreaId();
        $objUrl = $hook->getUrl();

        $hookdata = $this->validation->getObject();
        $hookdata = DataUtil::cleanVar($hookdata);
        $tagArray = $this->cleanTagArray($hookdata['tags']);

        if (count($tagArray) > 0) {
            // search for existing object
            $hookObject = $this->entityManager
                    ->getRepository('Tag_Entity_Object')
                    ->findOneBy(array(
                'module' => $module,
                'objectId' => $objectId,
                'areaId' => $areaId));
            if (isset($hookObject)) {
                $hookObject->clearTags();
            } else {
                $hookObject = new Tag_Entity_Object($module, $objectId, $areaId, $objUrl);
            }

            foreach ($tagArray as $word) {
                $tagObject = $this->entityManager->getRepository('Tag_Entity_Tag')->findOneBy(array('tag' => $word));
                if (!isset($tagObject)) {
                    $tagObject = new Tag_Entity_Tag();
                    $tagObject->setTag($word);
                    $this->entityManager->persist($tagObject);
                }
                $hookObject->assignToTags($tagObject);
            }
            $this->entityManager->persist($hookObject);
            $this->entityManager->flush();
        }
    }

    /**
     * Display hook for view.
     *
     * @param Zikula_DisplayHook $hook
     *
     * @return void
     */
    public function uiView(Zikula_DisplayHook $hook)
    {
        // Security check
        if (!SecurityUtil::checkPermission('Tag::', '::', ACCESS_READ)) {
            return;
        }
        // get data from $event
        $module = $hook->getCaller();
        $objectId = $hook->getId();
        $areaId = $hook->getAreaId();

        if (!$objectId) {
            return;
        }

        $tags = $this->entityManager->getRepository('Tag_Entity_Object')->getTags($module, $areaId, $objectId);

        $this->view->setCacheId('uiview|' . $module . '|' . $areaId . '|' . $objectId);

        $this->view->assign('tags', $tags);

        // add this response to the event stack
        $area = 'provider.tag.ui_hooks.service';
        $hook->setResponse(new Zikula_Response_DisplayHook($area, $this->view, 'hooks/view.tpl'));
    }

    /**
     * delete process hook handler.
     *
     * @param Zikula_ProcessHook $event
     *
     * @return void
     */
    public function processDelete(Zikula_ProcessHook $hook)
    {
        $module = $hook->getCaller();
        $objectId = $hook->getId();
        $areaId = $hook->getAreaId();

        $hookObject = $this->entityManager
                ->getRepository('Tag_Entity_Object')
                ->findOneBy(array(
            'module' => $module,
            'objectId' => $objectId,
            'areaId' => $areaId));
        if (!empty($hookObject)) {
            $this->entityManager->remove($hookObject);
            $this->entityManager->flush();
        }
    }

    /**
     * Handle module uninstall event "installer.module.uninstalled".
     * Receives $modinfo as $args
     *
     * @param Zikula_Event $event
     *
     * @return void
     */
    public static function moduleDelete(Zikula_Event $event)
    {
        $module = $event['name'];
        $em = ServiceUtil::getService('doctrine.entitymanager');
        $hookObjects = $em->getRepository('Tag_Entity_Object')
                ->findBy(array('module' => $module));
        // better to do it this way than DQL because removes related objects also
        if (count($hookObjects) > 0) {
            foreach ($hookObjects as $hookObject) {
                $em->remove($hookObject);
            }
            $em->flush();
            LogUtil::registerStatus(__('Hooked content in Tags removed.', ZLanguage::getModuleDomain('Tag')));
        }
    }

    /**
     * clean up words in array values
     * 
     * @param array $array
     * 
     * @return array
     */
    private function cleanTagArray($array)
    {
        $final = array();
        if (isset($array) && is_array($array)) {
            foreach ($array as $word) {
                $word = trim(strip_tags($word));
                if (!empty($word)) {
                    $final[] = $word;
                }
            }
        }
        return $final;
    }

}
