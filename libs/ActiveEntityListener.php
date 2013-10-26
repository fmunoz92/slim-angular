<?php

class ActiveEntityListener
{
    public function postLoad($args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        $metadata = $em->getClassMetadata(get_class($entity));
        if (in_array("ActiveEntity", $metadata->reflClass->getTraitNames())) {
            $entity->setDoctrine($em, $metadata);
        }
    }
}
