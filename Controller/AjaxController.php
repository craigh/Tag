<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
namespace Zikula\TagModule\Controller;

use Zikula_View;
use SecurityUtil;
use Zikula_Response_Ajax_Plain;
/**
 * Access to actions initiated through AJAX for the Tag module.
 */
class AjaxController extends \Zikula_Controller_AbstractAjax
{
    /**
     * Performs a search based on the fragment entered so far.
     *
     * Parameters passed via POST:
     * ---------------------------
     * string fragment A partial tag name entered by the user.
     *
     * @return \Zikula_Response_Ajax_Plain with list of tags matching the criteria.
     */
    public function gettagsAction()
    {
        $this->checkAjaxToken();
        $view = \Zikula_View::getInstance($this->name);
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADD)) {
            $fragment = $this->request->query->get('fragment', $this->request->request->get('fragment'));
            $tags = $this->entityManager->getRepository('Zikula\\TagModule\\Entity\\TagEntity')->getTagsByFragments(array($fragment));
            $view->assign('tags', $tags);
        }
        $output = $view->fetch('hooks/tagautocomplete.tpl');
        return new \Zikula_Response_Ajax_Plain($output);
    }
}