<?php

/**
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\Mapping as ORM;

//use Gedmo\Mapping\Annotation as Gedmo; // Add a behavous

/**
 * Tags entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="tag_tag")
 */
class Tag_Entity_Tag extends Zikula_EntityAccess
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

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function getId()
    {
        return $this->id;
    }

}
