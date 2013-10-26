<?php

use Doctrine\ORM\EntityManager;

class ActiveEntityRegistry
{
    /**
     * @var array
     */
    private static $managers = array();
    
    private static $defaultManager = array();
    
    static public function setClassManager($class, EntityManager $manager)
    {
        self::$managers[$class] = $manager;
    }
    
    static public function setDefaultManager(EntityManager $manager)
    {
        self::$defaultManager = $manager;
    }
    
    static public function getClassManager($class)
    {
        if (isset(self::$managers[$class])) {
            return self::$managers[$class];
        } else if (self::$defaultManager) {
            return self::$defaultManager;
        } else {
            throw new \BadMethodCallException("ActiveEntity is not yet connected to an EntityManager.");
        }
    }
}