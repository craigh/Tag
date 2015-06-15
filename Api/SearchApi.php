<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\TagModule\Api;

use SecurityUtil;
use Zikula_View;
use ModUtil;
use DataUtil;
use Search_Api_User;
use DBUtil;
use LogUtil;

class SearchApi extends \Zikula_AbstractApi
{
    /**
     * Search plugin info
     */
    public function info()
    {
        return array('title' => $this->name, 'functions' => array('tag' => 'search'));
    }
    /**
     * Search form component
     */
    public function options($args)
    {
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $render = Zikula_View::getInstance($this->name);
            $render->assign('active', !isset($args['active']) || isset($args['active'][$this->name]));
            return $render->fetch('search/options.tpl');
        }
        return '';
    }
    /**
     * Search plugin main function
     */
    public function search($args)
    {
        ModUtil::dbInfoLoad('Search');
        $sessionId = session_id();
        $searchFragments = Search_Api_User::split_query(DataUtil::formatForStore($args['q']), false);
        // this is an 'eager' search - it doesn't compensate for search type indicated in search UI
        $results = $this->entityManager->getRepository('Zikula\TagModule\Entity\TagEntity')->getTagsByFragments($searchFragments);
        foreach ($results as $result) {
            $record = array(
                'title' => $result->getTag(),
                'text' => '',
                'extra' => serialize(array(
                    'tag' => $result->getTag(),
                    'slug' => $result->getSlug())),
                'module' => $this->name,
                'session' => $sessionId);
            if (!DBUtil::insertObject($record, 'search_result')) {
                return LogUtil::registerError($this->__('Error! Could not save the search results.'));
            }
        }
        return true;
    }
    /**
     * Do last minute access checking and assign URL to items
     *
     * Access checking is ignored since access check has
     * already been done. But we do add a URL to the found item
     */
    public function search_check($args)
    {
        $datarow =& $args['datarow'];
        $extra = unserialize($datarow['extra']);
        $datarow['url'] = ModUtil::url($this->name, 'user', 'view', array('tag' => $extra['slug']));
        return true;
    }
}