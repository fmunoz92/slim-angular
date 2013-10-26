<?php

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Active Entity trait
 * 
 * Limitations: a class can only ever be assocaited with ONE active entity manager. Multiple entity managers
 * per entity class are not supported.
 */
trait ActiveEntity
{
    private $doctrineEntityManager;
    private $doctrineClassMetadata;

    public function setDoctrine($em, $classMetadata)
    {
        $this->doctrineEntityManager = $em;
        $this->doctrineClassMetadata = $classMetadata;
    }

    private function set($field, $args)
    {
        if (isset($this->doctrineClassMetadata->fieldMappings[$field])) {

            if ($this->$field instanceof \DateTime &&  is_string($args[0]))
                $this->$field = new \DateTime($args[0]);
            else
                $this->$field = $args[0];
        } else if (isset($this->doctrineClassMetadata->associationMappings[$field]) &&
                 $this->doctrineClassMetadata->associationMappings[$field]['type'] & ClassMetadata::TO_ONE) {

            $assoc = $this->doctrineClassMetadata->associationMappings[$field];
            if (!($args[0] instanceof $assoc['targetEntity'])) {
                throw new \InvalidArgumentException(
                    "Expected entity of type '".$assoc['targetEntity']."'"
                );
            }

            if ($assoc['type'] & ClassMetadata::ONE_TO_ONE && !$assoc['isOwning']) {
                $setter = "set".$assoc['mappedBy'];
                $args[0]->$setter($this);
            }

            $this->$field = $args[0];
        } else {
            throw new \BadMethodCallException("no field with name '".$field."' exists on '".$this->doctrineClassMetadata->name."'");
        }
    }
    
    private function get($field)
    {
        if ( (isset($this->doctrineClassMetadata->fieldMappings[$field]) && $this->doctrineClassMetadata->fieldMappings[$field]['type'] != "boolean") ||
            isset($this->doctrineClassMetadata->associationMappings[$field])) {
            return $this->$field;
        } else {
            throw new \BadMethodCallException("no field with name '".$field."' exists on '".$this->doctrineClassMetadata->name."'");
        }
    }
    
    private function add($field, $args)
    {
        if (isset($this->doctrineClassMetadata->associationMappings[$field]) &&
            $this->doctrineClassMetadata->associationMappings[$field]['type'] & ClassMetadata::TO_MANY) {

            $assoc = $this->doctrineClassMetadata->associationMappings[$field];
            if (!($args[0] instanceof $assoc['targetEntity'])) {
                throw new \InvalidArgumentException(
                    "Expected entity of type '".$assoc['targetEntity']."'"
                );
            }

            // add this object on the owning side aswell, for obvious infinite recursion
            // reasons this is only done when called on the inverse side.
            if (!$assoc['isOwning']) {
                $setter = (($assoc['type'] & ClassMetadata::MANY_TO_MANY) ? "add" : "set").$assoc['mappedBy'];
                $args[0]->$setter($this);
            }

            $this->$field->add($args[0]);
        } else {
            throw new \BadMethodCallException("There is no method ".$method." on ".$this->doctrineClassMetadata->name);
        }
    }
    
    private function is($field)
    {
        if ( isset($this->doctrineClassMetadata->fieldMappings[$field]) && $this->doctrineClassMetadata->fieldMappings[$field]['type'] == "boolean") {
            return $this->$field;
        } else {
            throw new \BadMethodCallException("There is no method ".$method." on ".$this->doctrineClassMetadata->name);
        }
    }
    
    private function initializeDoctrine()
    {
        $this->doctrineEntityManager = ActiveEntityRegistry::getClassManager($className = get_class($this));
        $this->doctrineClassMetadata = $this->doctrineEntityManager->getClassMetadata($className);
    }
    
    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        // this happens if you call new on the entity.
        if ($this->doctrineClassMetadata === null) {
            $this->initializeDoctrine();
        }
        
        $command = substr($method, 0, 3);
        $field = lcfirst(substr($method, 3));
        if ($command == "set") {
            $this->set($field, $args);
        } else if ($command == "get") {
            return $this->get($field);
        } else if ($command == "add") {
            $this->add($field, $args);
        } else if (substr($command, 0, 2) == "is") {
            $this->is($field, $args);
        } else {
            throw new \BadMethodCallException("There is no method ".$method." on ".$this->doctrineClassMetadata->name);
        }
    }
    
    public function persist()
    {
        $this->doctrineEntityManager->persist($this);
    }
    
    public function remove()
    {
        $this->doctrineEntityManager->remove($this);
    }
    
    public function update(array $data = array())
    {        
        foreach ($data AS $k => $v) {
            $this->set($k, array($v));
        }
        return $this;
    }
        
    public function updateFromJson($data) {
        return $this->update(json_decode($data, true));
    }

    static public function createFromJson($data) {
        return self::create(json_decode($data, true));
    }

    static public function create(array $data = array())
    {
        $instance = new static();
        $instance->initializeDoctrine();
        
        foreach ($data AS $k => $v) {
            $instance->set($k, array($v));
        }
        return $instance;
    }
    
    static public function createQueryBuilder($rootAlias = 'r')
    {
        $class = get_called_class();
        return ActiveEntityRegistry::getClassManager($class)->createQueryBuilder($rootAlias);
    }

    static public function createQuery($alias = 'r')
    {
        $class = get_called_class();
        return ActiveEntityRegistry::getClassManager($class)->createQueryBuilder($alias)->select($alias)->from($class, $alias)->getQuery();
    }
    
    static public function find($id)
    {
        $class = get_called_class();
        return ActiveEntityRegistry::getClassManager($class)->find($class, $id);
    }
    
    static public function findOneBy(array $criteria = array())
    {
        $class = get_called_class();
        return ActiveEntityRegistry::getClassManager($class)->getRepository($class)->findOneBy($criteria);
    }
    
    static public function findBy(array $criteria = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $class = get_called_class();
        return ActiveEntityRegistry::getClassManager($class)->getRepository($class)->findBy($criteria, $orderBy, $limit, $offset);
    }
    
    static public function findAll()
    {
        $class = get_called_class();
        return ActiveEntityRegistry::getClassManager($class)->getRepository($class)->findAll($criteria);
    }
    
    static public function expr()
    {
        $class = get_called_class();
        return ActiveEntityRegistry::getClassManager($class)->getExpressionBuilder();
    }
}
