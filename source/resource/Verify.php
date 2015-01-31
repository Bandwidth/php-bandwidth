<?php
namespace Catapult;
/**
 * Provides a class for
 * objects to verify their
 * schema.  this needs SchemaResource,
 * An object and ensured input, a
 * 
 *
 * Usage:
 *
 * VerifyResource(object, data)
 *
 * This should also check if it is a need
 * or optional. TODO
 */
final class VerifyResource  {
    public function verify(&$object, &$data) {
        $schema = $object->schema->fields;
        foreach ($data as $k => $v) {
           $f = 0;
           foreach ($schema as $k1 => $s) {
              if ($v == $k1)
                $f = 1;
           }

           /** throw a warning, no need for exception here **/
           if (!$f)
              printf((string) new \CatapultApiWarning("$k is not a valid term in model " . get_class($object) . " please use one of the following: "));
        }
    }
}
