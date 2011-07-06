<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Tag_Handlers
{
    public static function getTypes(Zikula_Event $event) {
        $types = $event->getSubject();
        $types->add('Tag_ContentType_TagCloud');
    }
}