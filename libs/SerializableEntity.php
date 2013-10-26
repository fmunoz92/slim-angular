<?php

use Doctrine\Common\Util\Inflector;

trait SerializableEntity
{
    static private function serializeEntity($entity)
    {
        $className = get_class($entity);
        $em = ActiveEntityRegistry::getClassManager($className);
        $class = $em->getClassMetadata($className);
        
        $data = array();
        foreach ($class->fieldMappings as $field => $mapping) {
            $value = $class->reflFields[$field]->getValue($entity);
            $field = Inflector::tableize($field);
            if ($value instanceof \DateTime) {
                $data[$field] = $value->format(\DateTime::ATOM);
            } else if (is_object($value)) {
                $data[$field] = (string)$value;
            } else {
                $data[$field] = $value;
            }
        }
        foreach ($class->associationMappings as $field => $mapping) {
            $key = Inflector::tableize($field);
            if ($mapping['isCascadeDetach']) {
                $data[$key] = self::serializeEntity( $class->reflFields[$field]->getValue($entity) );
            } else if ($mapping['isOwningSide'] && $mapping['type'] & ClassMetadata::TO_ONE) {
                // if its not detached to but there is an owning side to one entity at least reflect the identifier.
                $data[$key] = $em->getUnitOfWork()->getEntityIdentifier( $class->reflFields[$field]->getValue($entity) );
            }
        }
        return $data;
    }
    
    public function toArray()
    {
        return self::serializeEntity($this);
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    public function toDOMDocument()
    {
        $arrToXml = function($node, $data) use (&$arrToXml) {
            foreach ($data AS $k => $v) {
                $child = $node->ownerDocument->createElement($k);
                $node->appendChild($child);
                if (is_array($v)) {
                    $arrToXml($child, $v);
                } else {
                    $child->appendChild($node->ownerDocument->createTextNode($v));
                }
            }
        };
        
        $className = get_class($this);
        $em = ActiveEntityRegistry::getClassManager($className);
        $class = $em->getClassMetadata($className);
        
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $root = $dom->createElement(Inflector::tableize($class->reflClass->getShortName()));
        $dom->appendChild($root);
        
        $arrToXml($root, $this->toArray());
        
        return $dom;
    }
    
    public function toXml($formatOutput = false)
    {
        $dom = $this->toDOMDocument();
        $dom->formatOutput = $formatOutput;
        return $dom->saveXML();
    }
}
