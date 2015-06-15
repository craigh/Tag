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

use ModUtil;
use SecurityUtil;
use LogUtil;
use Zikula\TagModule\AbstractTaggedObjectMeta;
use Zikula\TagModule\TaggedObjectMeta\GenericTaggedObjectMeta;

/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class UserController extends \Zikula_AbstractController
{
    /**
     * This method is the default function.
     *
     * @param array $args Array.
     *
     * @return redirect
     */
    public function main($args)
    {
        $this->redirect(ModUtil::url('Tag', 'user', 'view', $args));
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
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Tag::', '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());
        $selectedTag = $this->request->getGet()->get('tag', isset($args['tag']) ? $args['tag'] : null);
        if (isset($selectedTag)) {
            $result = $this->entityManager->getRepository('Zikula\TagModule\Entity\ObjectEntity')->getTagged($selectedTag);
            foreach ($result as $key => $item) {
                $possibleClassNames = array(
                    "{$item['module']}_TaggedObjectMeta_{$item['module']}",
                    'Zikula\TagModule\TaggedObjectMeta\\' . $item['module'],
                    'Zikula\TagModule\TaggedObjectMeta\GenericTaggedObjectMeta');
                // core 1.3.7 compatibility
                $moduleBundle = ModUtil::getModule($item['module']);
                if (!empty($moduleBundle)) {
                    array_unshift($possibleClassNames, $moduleBundle->getNamespace() . '\\TaggedObjectMeta\\' . $moduleBundle->getName());
                }
                foreach ($possibleClassNames as $classname) {
                    if (class_exists($classname)) {
                        break;
                    }
                }
                $objectMeta = new $classname($item['objectId'], $item['areaId'], $item['module'], $item['url'], $item['urlObject']);
                if (!$objectMeta instanceof AbstractTaggedObjectMeta) {
                    $objectMeta = new GenericTaggedObjectMeta($item['objectId'], $item['areaId'], $item['module'], $item['url'], $item['urlObject']);
                }
                $result[$key]['link'] = $objectMeta->getPresentationLink();
            }
            $this->view->assign('selectedtag', $this->entityManager
                ->getRepository('Zikula\TagModule\Entity\TagEntity')
                ->findBy(array('slug' => $selectedTag)))->assign('result', $result);
        }
        $tagsByPopularity = $this->entityManager->getRepository('Zikula\TagModule\Entity\TagEntity')->getTagsByFrequency();
        return $this->view->assign('tags', $tagsByPopularity)->fetch('user/view.tpl');
    }
}