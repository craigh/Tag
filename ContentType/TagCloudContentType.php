<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\TagModule\ContentType;

use ServiceUtil;

class TagCloudContentType extends \Content_AbstractContentType
{
    protected $limit;
    public function getTitle()
    {
        return $this->__('Tag Cloud');
    }
    public function getDescription()
    {
        return $this->__('Display  available tags');
    }
    public function loadData(&$data)
    {
        $this->limit = $data['limit'];
    }
    public function display()
    {
        if (!isset($this->limit)) {
            $this->limit = 10;
        }
        $em = ServiceUtil::getService('doctrine.entitymanager');
        $tagsByPopularity = $em->getRepository('Zikula\TagModule\Entity\TagEntity')->getTagsByFrequency($this->limit);
        $this->view->assign('tags', $tagsByPopularity);
        return $this->view->fetch($this->getTemplate());
    }
    public function displayEditing()
    {
        return $this->__('Tag cloud');
    }
    public function getDefaultData()
    {
        return array('limit' => 10);
    }
    public function getSearchableText()
    {
        return;
    }
}