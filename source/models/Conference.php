<?php

/**
 * @model Conference
 * https://catapult.inetwork.com/docs/api-docs/conferences/
 *  
 * Creates conferences through
 * Catapult.
 *
 */
namespace Catapult;

final class Conference extends AudioMixin {

    /**
     *
     * Init forms:
     * 
     * GET 
     * Conference('conference-id')
     * Conference()
     * 
     * POST
     * Conference(array)
     */
	public function __construct()
	{
      $data = Ensure::Input(func_get_args());

      parent::_init($data, new DependsResource,
        new LoadsResource(
          array("primary" => "GET", "id" => "id", "silent" => false)
        ),
        new SchemaResource(
          array("fields" => array("id", "state", "from", "created_time", "completed_time", "fallback_url"), 
              "needs" => array(
              "id", "state", "from"
              )
          )),
        new SubFunctionResource(array(
                array("type" => "get", "term" => "members"),
                array("type" => "add", "term" => "members"),
                array("type" => "update", "term" => "member")
           )
        )
       );        
	}

	/**
	 * Return a partial for
	 *
	 * the member selected
	 */
	public function member()
	{
		return new ConferenceMember($this->id);
	}
}


?>
