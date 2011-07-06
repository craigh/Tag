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

        // initialize sort array - used to display sort classes and urls
        $sort = array();
        $fields = array('id', 'tag', 'cnt'); // possible sort fields
        foreach ($fields as $field) {
            $sort['class'][$field] = 'z-order-unsorted'; // default values
        }

        // Get parameters from whatever input we need.
//        $startnum = (int)$this->request->getGet()->get('startnum', $this->request->getPost()->get('startnum', isset($args['startnum']) ? $args['startnum'] : null));
        $orderby = $this->request->getGet()->get('orderby', $this->request->getPost()->get('orderby', isset($args['orderby']) ? $args['orderby'] : 'title'));
        $original_sdir = $this->request->getGet()->get('sdir', $this->request->getPost()->get('sdir', isset($args['sdir']) ? $args['sdir'] : 0));

//        $this->view->assign('startnum', $startnum);
        $this->view->assign('orderby', $orderby);
        $this->view->assign('sdir', $original_sdir);

        $sdir = $original_sdir ? 0 : 1; //if true change to false, if false change to true
        // change class for selected 'orderby' field to asc/desc
        if ($sdir == 0) {
            $sort['class'][$orderby] = 'z-order-desc';
            $orderdir = 'DESC';
        }
        if ($sdir == 1) {
            $sort['class'][$orderby] = 'z-order-asc';
            $orderdir = 'ASC';
        }
        // complete initialization of sort array, adding urls
        foreach ($fields as $field) {
            $sort['url'][$field] = ModUtil::url('Tag', 'admin', 'view', array(
                        'orderby' => $field,
                        'sdir' => $sdir));
        }
        $this->view->assign('sort', $sort);
        
        $fieldmap = array('tag' => 't.tag', 'id' => 't.id', 'cnt' => 'cnt');
        $tags = $this->entityManager
                ->getRepository('Tag_Entity_Tag')
                ->getTagsWithCount(0, 0, $fieldmap[$orderby], $orderdir);

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