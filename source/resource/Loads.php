<?php
namespace Catapult;
/**
 * Loads resource will figure
 * out what's needed for the object
 * on load. Also how to load the object
 * 
 * init => 'id_of_init'
 * id => 'id capable'
 * primary => 'primary method on create'
 *
 *
 */
final class LoadsResource extends BaseResource {
    public function __construct($args) {
      $this->init = $args['init'];
      $this->id = $args['id'];
      $this->primary = $args['primary'];
      $this->silent = $args['silent'];
    }
}
