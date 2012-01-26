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
 * This is the User controller class providing navigation and interaction functionality.
 */
class Tag_Controller_User extends Zikula_AbstractController
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
            $result = $this->entityManager->getRepository('Tag_Entity_Object')->getTagged($selectedTag);
            foreach ($result as $key => $item) {
                $possibleClassNames = array(
                    "$item[module]_TaggedObjectMeta_$item[module]",
                    "Tag_TaggedObjectMeta_$item[module]",
                    "Tag_TaggedObjectMeta_Generic",
                );
                foreach ($possibleClassNames as $classname) {
                    if (class_exists($classname)) {
                        break;
                    }
                }
                $objectMeta = new $classname($item['objectId'], $item['areaId'], $item['module'], $item['url']);
                if (!($objectMeta instanceof Tag_AbstractTaggedObjectMeta)) {
                    $objectMeta = new Tag_TaggedObjectMeta_Generic($item['objectId'], $item['areaId'], $item['module'], $item['url']);
                }
                $result[$key]['link'] = $objectMeta->getPresentationLink();
            }
            $temp = $this->entityManager->getRepository('Tag_Entity_Tag')->findBy(array('slug' => $selectedTag));
            $this->view->assign('selectedtag', $this->entityManager->getRepository('Tag_Entity_Tag')->findBy(array('slug' => $selectedTag)))
                       ->assign('result', $result);
        }
        
        $tagsByPopularity = $this->entityManager->getRepository('Tag_Entity_Tag')->getTagsByFrequency();

        return $this->view->assign('tags', $tagsByPopularity)
                          ->fetch('user/view.tpl');
    }
}
