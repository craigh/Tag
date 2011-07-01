<?php
/**
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * This is the Admin controller class providing navigation and interaction functionality.
 */
class Tag_Controller_Admin extends Zikula_AbstractController
{
    /**
     * This method is the default function.
     *
     * Called whenever the module's Admin area is called without defining arguments.
     *
     * @param array $args Array.
     *
     * @return redirect
     */
    public function main($args)
    {
        $this->redirect(ModUtil::url('Tag', 'admin', 'view', $args));
    }

    /**
     * This method provides a generic item list overview.
     *
     * @param array $args Array.
     *
     * @return string|boolean Output.
     */
    public function view($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Tag::', '::', ACCESS_EDIT), LogUtil::getErrorMsgPermission());

        $tags = $this->entityManager
                ->getRepository('Tag_Entity_Tag')
                ->findAll();

        return $this->view->assign('tags', $tags)
                          ->fetch('admin/view.tpl');
    }

    /**
     * Create or edit record.
     *
     * @return string|boolean Output.
     */
    public function edit()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADD), LogUtil::getErrorMsgPermission());

        $form = FormUtil::newForm('Tag', $this);
        return $form->execute('admin/edit.tpl', new Tag_FormHandler_Admin_Edit());
    }
    
    /**
     * modify the module settings
     */
    public function modifyconfig($args)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * @desc sets module variables as requested by admin
     * @return      status/error ->back to modify config page
     */
    public function updateconfig()
    {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $modvars = array(
            'poptagsoneditform' => $this->request->getPost()->get('poptagsoneditform', 10),
        );

        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the Tag configuration.'));
        return $this->redirect(ModUtil::url('Tag', 'admin', 'view', array()));
    }

}