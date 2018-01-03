<?php
namespace Catapult;
/**
 * Create the keys and values
 * for Catapult objects all these
 * should be accessible within
 * the model calling SchemaResource
 * SchemaResource should be directly
 * used with VerifyResource these are client
 * side checks on whether input is valid
 *
 * this defines two properties
 * @param: needs a list of what the object needs
 * @param: fields a list of what the object uses
 */
class SchemaResource extends BaseResource {
    public function __construct($opts) {
      $this->needs = $opts['needs'];
      $this->fields = $opts['fields'];
    }
}
