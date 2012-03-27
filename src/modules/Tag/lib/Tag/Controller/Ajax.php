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
 * Access to actions initiated through AJAX for the Tag module.
 */
class Tag_Controller_Ajax extends Zikula_Controller_AbstractAjax
{

    /**
     * Performs a search based on the fragment entered so far.
     *
     * Parameters passed via POST:
     * ---------------------------
     * string fragment A partial tag name entered by the user.
     *
     * @return string Zikula_Response_Ajax_Plain with list of tags matching the criteria.
     */
    public function gettags()
    {
        $this->checkAjaxToken();
        $view = Zikula_View::getInstance($this->name);
        if (SecurityUtil::checkPermission('Tag::', '::', ACCESS_ADD)) {
            $fragment = $this->request->getGet()->get('fragment', $this->request->getPost()->get('fragment'));
            $tags = $this->entityManager->getRepository('Tag_Entity_Tag')->getTagsByFragments(array($fragment));
            $view->assign('tags', $tags);
        }
        $output = $view->fetch('hooks/tagautocomplete.tpl');
        return new Zikula_Response_Ajax_Plain($output);
    }

}
