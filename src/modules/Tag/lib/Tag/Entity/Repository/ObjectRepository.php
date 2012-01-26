<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\EntityRepository;

/**
 * Repository class for DQL calls
 *
 */
class Tag_Entity_Repository_ObjectRepository extends EntityRepository
{

    /**
     * get all associated tags for an object
     * 
     * @param string $module
     * @param integer $areaId
     * @param integer $objectId
     * @return Object Zikula_EntityAccess 
     */
    public function getTags($module, $areaId, $objectId)
    {
        $dql = "SELECT t.tag FROM Tag_Entity_Object o JOIN o.tags t" .
               " WHERE o.module = ?1 AND o.areaId = ?2 AND o.objectId = ?3";

        $em = ServiceUtil::getService('doctrine.entitymanager');
        $query = $em->createQuery($dql);
        return $query->setParameter(1, $module)
                ->setParameter(2, $areaId)
                ->setParameter(3, $objectId)
                ->getArrayResult(); // hydrate result to array
    }
    
    /**
     * get all objects tagged as specified from provided slug
     * 
     * @param string $tag
     * @return Object Zikula_EntityAccess
     */
    public function getTagged($tag)
    {
        $dql = "SELECT o FROM Tag_Entity_Object o JOIN o.tags t" .
               " WHERE t.slug = ?1 ORDER BY o.module ASC, o.objectId DESC";
        
        $em = ServiceUtil::getService('doctrine.entitymanager');
        $query = $em->createQuery($dql);
        return $query->setParameter(1, $tag)
                ->getArrayResult(); // hydrate result to array
    }
    
}