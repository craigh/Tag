<?php

/**
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
    public function getTagsByFrequency($limit = 10)
    {
        $em = ServiceUtil::getService('doctrine.entitymanager');
        
        $rsm = new ORM\Query\ResultSetMapping;
        $rsm->addEntityResult('Tag_Entity_Tag', 't');
        $rsm->addFieldResult('t', 'tag', 'tag');
        $rsm->addFieldResult('t', 'id', 'id');
        $rsm->addScalarResult('freq', 'freq');

        $sql = "SELECT t.id, t.tag, count(j.tag_entity_tag_id) freq" .
               " FROM tag_entity_object_tag_entity_tag j" .
               " LEFT JOIN tag_tag t ON j.tag_entity_tag_id = t.id" .
               " GROUP BY j.tag_entity_tag_id ORDER BY freq DESC";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        $tags = $em->createNativeQuery($sql, $rsm)
                   ->getResult();
        $result = array();
        $weightUnit = $tags[0]['freq'] / 5;
        foreach ($tags as $tag) {
            $result[] = array('freq' => $tag['freq'],
                'tag' => $tag[0]->getTag(),
                'weight' => $this->getWeight($weightUnit, $tag['freq']));
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
        switch ($freq)
        {
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
}