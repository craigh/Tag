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

    /**
     * Set the tags for an object
     *
     * @param mixed[] $args {
     *      @type string        $module the module name
     *      @type integer       $objectId the id of the object being tagged
     *      @type integer       $areaId the areaId
     *      @type Zikula_ModUrl $objUrl
     *      @type array         $hookdata
     * }
     */
    public function tagObject(array $args)
    {
        $module = $args['module'];
        $objectId = $args['objectId'];
        $areaId = $args['areaId'];
        $objUrl = $args['objUrl'];
        $hookdata = $args['hookdata'];

        $hookdata = DataUtil::cleanVar($hookdata);
        $tagArray = $this->cleanTagArray($hookdata['tags']);

        if (count($tagArray) > 0) {
            // search for existing object
            $hookObject = $this->entityManager
                ->getRepository('Tag_Entity_Object')
                ->findOneBy(array(
                    'module' => $module,
                    'objectId' => $objectId,
                    'areaId' => $areaId));
            if (isset($hookObject)) {
                $hookObject->clearTags();
            } else {
                $hookObject = new Tag_Entity_Object($module, $objectId, $areaId, $objUrl);
            }

            foreach ($tagArray as $word) {
                $tagObject = $this->entityManager->getRepository('Tag_Entity_Tag')->findOneBy(array('tag' => $word));
                if (!isset($tagObject)) {
                    $tagObject = new Tag_Entity_Tag();
                    $tagObject->setTag($word);
                    $this->entityManager->persist($tagObject);
                }
                $hookObject->assignToTags($tagObject);
            }
            $this->entityManager->persist($hookObject);
            $this->entityManager->flush();
        }
    }

    /**
     * clean up words in array values
     *
     * @param array $array
     *
     * @return array
     */
    private function cleanTagArray($array)
    {
        $final = array();
        if (isset($array) && is_array($array)) {
            foreach ($array as $word) {
                $word = trim(strip_tags($word));
                if (!empty($word)) {
                    $final[] = $word;
                }
            }
        }
        return $final;
    }

}