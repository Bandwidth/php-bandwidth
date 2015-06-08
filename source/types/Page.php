<?php
/**
 * @type Page
 * 
 * Use page when directly
 * accessing models lists
 *
 * TODO: this is intended to be an iterative
 * check agaisnt the page
 */
 
namespace Catapult;
final class Page extends Types {
    public function __construct($page=DEFAULTS::PAGE_SIZE)
    {
      $this->page = $page;
    }

    public function __toString()
    {
      return (string) $this->page;
    }
}
