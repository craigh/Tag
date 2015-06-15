<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\TagModule\FormHandler\Admin;

use LogUtil;
use ModUtil;
use System;
use Zikula\TagModule\Entity\TagEntity;

/**
 * Form handler for create and edit.
 */
class Edit extends \Zikula_Form_AbstractHandler
{
    /**
     * Tag id.
     *
     * When set this handler is in edit mode.
     *
     * @var integer
     */
    private $id;
    /**
     * Setup form.
     *
     * @param \Zikula_Form_View $view Current Zikula_Form_View instance.
     *
     * @return boolean
     */
    public function initialize(\Zikula_Form_View $view)
    {
        $id = $this->request->getGet()->get('id', null);
        // FILTER_SANITIZE_NUMBER_INT ??
        if ($id) {
            // load user with id
            $tag = $this->entityManager->find('Zikula\TagModule\Entity\TagEntity', $id);
            if ($tag) {
                // switch to edit mode
                $this->id = $id;
                // assign current values to form fields
                $view->assign($tag->toArray());
            } else {
                return LogUtil::registerError($this->__f('Tag with id %s not found', $id));
            }
        }
        if (!$view->getStateData('returnurl')) {
            $editurl = ModUtil::url($this->name, 'admin', 'edit');
            $returnurl = System::serverGetVar('HTTP_REFERER');
            if (strpos($returnurl, $editurl) === 0) {
                $returnurl = ModUtil::url($this->name, 'admin', 'main');
            }
            $view->setStateData('returnurl', $returnurl);
        }
        return true;
    }
    /**
     * Handle form submission.
     *
     * @param \Zikula_Form_View $view  Current Zikula_Form_View instance.
     * @param array            &$args Args.
     *
     * @return boolean
     */
    public function handleCommand(\Zikula_Form_View $view, &$args)
    {
        $returnurl = $view->getStateData('returnurl');
        // process the cancel action
        if ($args['commandName'] == 'cancel') {
            return $view->redirect($returnurl);
        }
        if ($args['commandName'] == 'delete') {
            $tag = $this->entityManager->find('Zikula\TagModule\Entity\TagEntity', $this->id);
            $this->entityManager->remove($tag);
            $this->entityManager->flush();
            LogUtil::registerStatus($this->__f('Item [id# %s] deleted!', $this->id));
            return $view->redirect($returnurl);
        }
        // check for valid form
        if (!$view->isValid()) {
            return false;
        }
        // load form values
        $data = $view->getValues();
        // switch between edit and create mode
        if ($this->id) {
            $tag = $this->entityManager->find('Zikula\TagModule\Entity\TagEntity', $this->id);
        } else {
            $tag = new TagEntity();
        }
        $tag->merge($data);
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
        return $view->redirect(ModUtil::url($this->name, 'admin', 'view'));
    }
}