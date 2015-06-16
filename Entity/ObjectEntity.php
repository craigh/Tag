<?php
/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\TagModule\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zikula\Core\ModUrl;
use Zikula\TagModule\Entity\TagEntity;

/**
 * Tagged object entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="Zikula\TagModule\Entity\Repository\ObjectRepository")
 * @ORM\Table(name="tag_object")
 */
class ObjectEntity extends \Zikula_EntityAccess
{
    /**
     * id field (record id)
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * tags
     *
     * @ORM\ManyToMany(targetEntity="TagEntity")
     * @ORM\JoinTable(name="tag_entity_object_tag_entity_tag",
     *      joinColumns={@ORM\JoinColumn(name="tag_entity_tag_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_entity_object_id", referencedColumnName="id")}
     *      )
     * table/column names maintains BC with v1
     */
    private $tags = null;
    /**
     * module field (hooked module name)
     *
     * @ORM\Column(length=50)
     */
    private $module;
    /**
     * areaId field (hooked area id)
     *
     * @ORM\Column(type="integer")
     */
    private $areaId;
    /**
     * objectId field (object id)
     *
     * @ORM\Column(type="integer")
     */
    private $objectId;

    /**
     * url object
     * @var ModUrl
     * 
     * @ORM\Column(type="object", nullable=true)
     */
    private $urlObject = null;
    public function __construct($module, $objectId, $areaId, ModUrl $urlObject)
    {
        $this->tags = new ArrayCollection();
        $this->setModule($module);
        $this->setObjectId($objectId);
        $this->setAreaId($areaId);
        $this->setUrlObject($urlObject);
    }
    /**
     * get the record ID
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * get the Tags
     * @return ArrayCollection 
     */
    public function getTags()
    {
        return $this->tags;
    }
    /**
     * Assign Tag to ArrayCollection
     * @param TagEntity $tag
     */
    public function assignToTags(TagEntity $tag)
    {
        $this->tags[] = $tag;
    }
    /**
     * get the module name
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }
    /**
     * set the module name
     * @param string $module 
     */
    public function setModule($module)
    {
        $this->module = $module;
    }
    /**
     * get the Hook Area ID
     * @return integer
     */
    public function getAreaId()
    {
        return $this->areaId;
    }
    /**
     * Set the Hook Area ID
     * @param integer $areaId 
     */
    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;
    }
    /**
     * Get the hooked object ID
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }
    /**
     * Set the hooked object ID
     * @param integer $objectId 
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    /**
     * get the hooked object UrlObject
     * @return ModUrl
     */
    public function getUrlObject()
    {
        return $this->urlObject;
    }
    /**
     * set the hooked object UrlObject
     * @param ModUrl $urlObject
     */
    public function setUrlObject(ModUrl $urlObject)
    {
        $this->urlObject = $urlObject;
    }
    /**
     * remove the tags in this object 
     */
    public function clearTags()
    {
        $this->tags = null;
    }
}