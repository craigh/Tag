<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\TagModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ObjectRepository extends EntityRepository
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
        $dql = 'SELECT t.tag, t.slug FROM Zikula\TagModule\Entity\ObjectEntity o JOIN o.tags t' . ' WHERE o.module = ?1 AND o.areaId = ?2 AND o.objectId = ?3';
        $query = $this->_em->createQuery($dql);
        return $query->setParameter(1, $module)->setParameter(2, $areaId)->setParameter(3, $objectId)->getArrayResult();
    }
    /**
     * get all objects tagged as specified from provided slug
     * 
     * @param string $tag
     * @return Object Zikula_EntityAccess
     */
    public function getTagged($tag)
    {
        $dql = 'SELECT o FROM Zikula\TagModule\Entity\ObjectEntity o JOIN o.tags t' . ' WHERE t.slug = ?1 ORDER BY o.module ASC, o.objectId DESC';
        $query = $this->_em->createQuery($dql);
        return $query->setParameter(1, $tag)->getArrayResult();
    }
}