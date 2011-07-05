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
 * Class to control User Api
 */
class Tag_Api_User extends Zikula_AbstractApi
{

    /**
     * decode the shorturl
     */
    public function decodeurl($args)
    {
        if (!isset($args['vars'])) {
            return LogUtil::registerArgsError();
        }

        System::queryStringSetVar('type', 'user');
        System::queryStringSetVar('func', 'view');
        
        if (isset($args['vars'][2])) {
            System::queryStringSetVar('tag', $args['vars'][2]);
        }

        return true;
    }

    /**
     * encode the shorturl
     */
    public function encodeurl($args)
    {
        if (!isset($args['modname'])) {
            return LogUtil::registerArgsError();
        }

        $url = $args['modname'];
        $url .= isset($args['args']['tag']) ? '/' . $args['args']['tag'] . '/' : '';

        return $url;
    }
}