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

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tags entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="Zikula\TagModule\Entity\Repository\TagRepository")
 * @ORM\Table(name="tag_tag")
 */
class TagEntity extends \Zikula_EntityAccess
{
    /**
     * id field
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * tag field (the 'word')
     *
     * @ORM\Column(length=36)
     */
    private $tag;
    /**
     * slug
     * 
     * @ORM\Column(name="slug", type="string", length=128)
     * @Gedmo\Slug(fields={"tag"})
     */
    private $slug;
    /**
     * Set the Tag
     * @param string $tag 
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }
    /**
     * retieve the tag
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }
    /**
     * retrieve the record ID
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * retrieve the slug
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}