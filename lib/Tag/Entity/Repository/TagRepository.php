<?php

/**
 * Tag - a content-tagging module for the Zikukla Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM;

/**
 * Repository class for DQL calls
 *
 */
class Tag_Entity_Repository_TagRepository extends ORM\EntityRepository
{

    /**
     * get an array of tags by frequency of usage sorted DESC
     * 
     * @param integer $limit
     * @return array
     */
    public function getTagsByFrequency($limit = 10, $fillLimit = true)
    {
        $rsm = new ORM\Query\ResultSetMapping;
        $rsm->addEntityResult('Tag_Entity_Tag', 't');
        $rsm->addFieldResult('t', 'tag', 'tag');
        $rsm->addFieldResult('t', 'slug', 'slug');
        $rsm->addFieldResult('t', 'id', 'id');
        $rsm->addScalarResult('freq', 'freq');

        $sql = "SELECT t.id, t.tag, t.slug, count(j.tag_entity_tag_id) freq" .
                " FROM tag_entity_object_tag_entity_tag j" .
                " LEFT JOIN tag_tag t ON j.tag_entity_tag_id = t.id" .
                " GROUP BY j.tag_entity_tag_id ORDER BY freq DESC";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        $tags = $this->_em->createNativeQuery($sql, $rsm)
                ->getResult();
        $result = array();
        if (count($tags) > 0) {
            $weightUnit = $tags[0]['freq'] / 5;
            foreach ($tags as $tag) {
                $result[$tag[0]->getTag()] = array('freq' => $tag['freq'],
                    'tag' => $tag[0]->getTag(),
                    'slug' => $tag[0]->getSlug(),
                    'weight' => $this->getWeight($weightUnit, $tag['freq']));
            }
        }
        // add unused tags to fill out limit
        if ($fillLimit) {
            $tagsNeeded = $limit - count($result);
            $unusedTags = $this->getTagsByRandom();
            foreach ($unusedTags as $tag) {
                if ($tagsNeeded <= 0) {
                    break;
                }
                if (!isset($result[$tag->getTag()])) {
                    $result[$tag->getTag()] = array('freq' => 0,
                        'tag' => $tag->getTag(),
                        'slug' => $tag->getSlug(),
                        'weight' => '20');
                    $tagsNeeded--;
                }
            }
        }
        return $result;
    }

    /**
     * determine the tag's weight based on frequency
     * 
     * @param float $unit
     * @param integer $freq
     * @return string
     */
    private function getWeight($unit, $freq)
    {
        switch ($freq) {
            case (0 <= $freq) && ($freq < $unit):
                return '20';
                break;
            case ($unit < $freq) && ($freq < $unit * 2):
                return '40';
                break;
            case ($unit * 2 < $freq) && ($freq < $unit * 3):
                return '60';
                break;
            case ($unit * 3 < $freq) && ($freq < $unit * 4):
                return '80';
                break;
            case ($unit * 4 < $freq) && ($freq <= $unit * 5):
                return '100';
                break;
        }
    }

    /**
     * get an array of tags matching text fragment(s)
     * 
     * @param array $fragments
     * @param integer $limit
     * @return array
     */
    public function getTagsByFragments(array $fragments, $limit = -1)
    {
        if (empty($fragments)) {
            return array();
        }
        $rsm = new ORM\Query\ResultSetMapping;
        $rsm->addEntityResult('Tag_Entity_Tag', 't');
        $rsm->addFieldResult('t', 'tag', 'tag');
        $rsm->addFieldResult('t', 'slug', 'slug');
        $rsm->addFieldResult('t', 'id', 'id');

        $sql = "SELECT t.id, t.tag, t.slug FROM tag_tag t WHERE ";
        $subSql = array();
        foreach ($fragments as $fragment) {
            $subSql[] = "t.tag REGEXP '(" . DataUtil::formatForStore($fragment) . ")'";
        }
        $sql .= implode(" OR ", $subSql);
        $sql .= " ORDER BY t.tag ASC";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        $tags = $this->_em->createNativeQuery($sql, $rsm)
                ->getResult();
        return $tags;
    }

    /**
     * get an array of tags in random order
     * 
     * @param integer $limit
     * @return  Object Zikula_EntityAccess
     */
    public function getTagsByRandom($limit = 0)
    {
        $dql = "SELECT t from Tag_Entity_Tag t";
        $result = $this->_em->createQuery($dql)
                ->getResult();
        shuffle($result);
        if ($limit > 0) {
            $result = array_slice($result, 0, $limit);
        }
        return $result;
    }

    /**
     * get tags with a count of tagged objects
     * 
     * @param integer $limit
     * @param integer $offset
     * @param string $orderBy
     * @param string $sortDir
     * @return Object Zikula_EntityAccess 
     */
    public function getTagsWithCount($limit = 0, $offset = 0, $orderBy = 't.tag', $sortDir = 'ASC')
    {
        $rsm = new ORM\Query\ResultSetMapping;
        $rsm->addEntityResult('Tag_Entity_Tag', 't');
        $rsm->addFieldResult('t', 'tag', 'tag');
        $rsm->addFieldResult('t', 'id', 'id');
        $rsm->addFieldResult('t', 'slug', 'slug');
        $rsm->addScalarResult('cnt', 'cnt');

        $sql = "SELECT t.id, t.tag, t.slug, count(j.tag_entity_tag_id) cnt FROM tag_tag t" .
                " LEFT JOIN tag_entity_object_tag_entity_tag j" .
                " ON t.id = j.tag_entity_tag_id GROUP BY t.id" .
                " ORDER BY $orderBy $sortDir";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        $tags = $this->_em->createNativeQuery($sql, $rsm)
                ->getResult();
        return $tags;
    }

}