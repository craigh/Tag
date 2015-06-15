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
use ModUtil;

/**
 * Class to control Admin Api
 */
class AdminApi extends \Zikula_AbstractApi
{
    /**
     * Get available admin panel links
     *
     * @return array array of admin links
     */
    public function getlinks()
    {
        // Define an empty array to hold the list of admin links
        $links = array();
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url($this->name, 'admin', 'modifyconfig'),
                'text' => $this->__('Settings'),
                'class' => 'z-icon-es-config');
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url($this->name, 'admin', 'view'),
                'text' => $this->__('Tag List'),
                'class' => 'z-icon-es-view');
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url($this->name, 'admin', 'edit'),
                'text' => $this->__('New tag'),
                'class' => 'z-icon-es-new');
        }

        return $links;
    }
}