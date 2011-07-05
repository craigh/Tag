<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Tag_Block_TagCloud extends Zikula_Controller_AbstractBlock
{

    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('Tag:tagcloud:', 'Block title::');
    }

    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type' => 'tagcloud',
            'module' => 'Tag',
            'text_type_long' => $this->__('Tag cloud display'),
            'allow_multiple' => true,
            'form_content' => false,
            'form_refresh' => false,
            'show_preview' => true,
            'admin_tableless' => true);
    }

    /**
     * display block
     */
    public function display($blockinfo)
    {
        if (!SecurityUtil::checkPermission('Tag:tagcloud:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('Tag')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        // Defaults
        $vars['limit'] = !empty($vars['limit']) ? (int)$vars['limit'] : 10;
        $tagsByPopularity = $this->entityManager->getRepository('Tag_Entity_Tag')->getTagsByFrequency($vars['limit']);
        $this->view->assign('tags', $tagsByPopularity);
        $blockinfo['content'] = $this->view->fetch('blocks/tagcloud.tpl');
        return BlockUtil::themeBlock($blockinfo);
    }

    /**
     * modify block settings
     */
    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        $vars['limit'] = !empty($vars['limit']) ? (int)$vars['limit'] : 10;
        $this->view->assign('vars', $vars);
        return $this->view->fetch('blocks/tagcloud_modify.tpl');
    }

    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        $vars['limit'] = $this->request->getPost()->get('limit', isset($args['limit']) ? $args['limit'] : 10);
        $blockinfo['content'] = BlockUtil::varsToContent($vars);
        $this->view->clear_cache('blocks/tagcloud.tpl');
        return $blockinfo;
    }

}