<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
interface Tag_TaggedObjectMetaInterface
{

    public function setObjectTitle($title);

    public function setObjectDate($date);

    public function setObjectAuthor($author);

    public function getPresentationLink();
}
