<?php

/**
 * Smarty function to display object tags.
 *
 * This function takes the modulename, objectid and areaid and returns an unordered list of associated tags
 *
 * Available parameters:
 *   - modname
 *   - objectid
 *   - areaname: hook bundle area name
 *   - assign: If set, the results are assigned to the corresponding variable instead of printed out
 *
 * Example
 * {displaytags modname='News' objectid=10012 areaname='subscriber.news.ui_hooks.articles'}
 *
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the unordered list
 */
function smarty_function_displaytags($params, &$smarty)
{
    $modname = isset($params['modname']) ? $params['modname'] : null;
    $objectid = isset($params['objectid']) ? (int)$params['objectid'] : null;
    $areaname = isset($params['areaname']) ? $params['areaname'] : null;
    if (empty($modname) || empty($objectid) || empty($areaname)) {
        return;
    }
    $assign = isset($params['assign']) ? $params['assign'] : null;

    $area = Doctrine_Core::getTable('Zikula_Doctrine_Model_HookArea')->findOneBy('areaname', $areaname)->toArray();
    $areaId = $area['id'];
    if (ModUtil::available('Tag')) {
        $em = ServiceUtil::getService('doctrine.entitymanager');
        $tags = $em->getRepository('Tag_Entity_Object')->getTags($modname, $areaId, $objectid);
        $smarty->assign('tags', $tags);
        $display = $smarty->fetch('file:modules/Tag/templates/hooks/view.tpl');
        if ($assign) {
            $smarty->assign($assign, $banner['displaystring']);
        } else {
            return $display;
        }
    } else {
        return;
    }
}
