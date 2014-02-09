<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zikula\Core\ModUrl;

//use Gedmo\Mapping\Annotation as Gedmo; // Add behaviors

/**
 * Tagged object entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="Tag_Entity_Repository_ObjectRepository")
 * @ORM\Table(name="tag_object")
 */
class Tag_Entity_Object extends Zikula_EntityAccess
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
     * @ORM\ManyToMany(targetEntity="Tag_Entity_Tag")
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
     * url field (object url)
     * @var string
     * @deprecated since Tag version 1.0.2
     *     use $urlObject
     * 
     * @ORM\Column(nullable=true)
     */
    private $url = null;

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
        $this->setUrl($urlObject->getUrl(null, null, false, false)); // deprecated
        // the fourth arg is forceLang and if left to default (true) then the url is malformed - core bug as of 1.3.0
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
     * @param Tag_Entity_Tag $tag 
     */
    public function assignToTags(Tag_Entity_Tag $tag)
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
     * @deprecated since Tag version 1.0.2
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @deprecated since Tag version 1.0.2
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
