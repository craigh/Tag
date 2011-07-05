<?php
/**
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Tag_Api_Search extends Zikula_AbstractApi
{

    /**
     * Search plugin info
     */
    public function info()
    {
        return array('title' => 'Tag',
            'functions' => array('tag' => 'search'));
    }

    /**
     * Search form component
     */
    public function options($args)
    {
        if (SecurityUtil::checkPermission('Tag::', '::', ACCESS_READ)) {
            $render = Zikula_View::getInstance('Tag');
            $render->assign('active', !isset($args['active']) || isset($args['active']['Tag']));
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
        $results = $this->entityManager->getRepository('Tag_Entity_Tag')->getTagsByFragments($searchFragments);

        foreach ($results as $result) {
            $record = array(
                'title' => $result->getTag(),
                'text' => '',
                'extra' => serialize(array('tag' => $result->getTag())),
                'module' => 'Tag',
                'session' => $sessionId
            );

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
        $datarow = &$args['datarow'];
        $datarow['url'] = ModUtil::url('Tag', 'user', 'view', array('tag' => $datarow['title']));
        return true;
    }

}