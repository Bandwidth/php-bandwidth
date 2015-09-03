<?php

/**
 * @model PhoneNumbers
 * http://ap.bandwidth.com/docs/rest-api/phonenumbers/
 *
 * Interface to manage account numbers.
 *
 * provides:
 * getNumberInfo
 * batchAllocateLocal 
 * batchAllocateTollFree
 * validateSearchQuery
 *
 */
namespace Catapult;

final class PhoneNumbers extends GenericResource {
    private $availablePath = "availableNumbers";

    /**
     * CTor for phone numbers 
     * use PhoneNumbers path primary,
     * availableNumbers secondary 
     *
     * Init Forms
     * GET 
     * PhoneNumbers('number-id')
     * PhoneNumbers
     *
     * POST
     * PhoneNumbers(array)
     */
    public function __construct() {
        $data = Ensure::Input(func_get_args());
        parent::_init($data, new DependsResource,
          new LoadsResource(array("primary" => "GET", "id" => "number", "init" => array(), "silent" => true)),
          new SchemaResource(array("fields" => array(
            'id', 'application', 'number', 'nationalNumber',
            'name', 'createdTime', 'city', 'state', 'price',
            'numberState', 'fallbackNumber', 'patternMatch', 'lata', 'rateCenter'
             ), "needs" => array("id", "number", "name"),
         )));
    }

    /**
     * Get the information
     * for a given number
     * @param valid number
     */
    public function getNumberInfo($number)
    {
        return $this->get($number);
    }
    /**
     * Make the needed changes to 
     * the PhoneNumber. Where
     * set of params can be:
     * applicationId, 
     * fallback_number,
     *  
     * @param data: set of valid patching options
     */
    public function patch($data)
    {
        $app = $data['applicationId'];
        if ($app instanceof Application)
            $data['applicationId'] = $app->id; 
        $data = Ensure::Input($data);
        $url = URIResource::Make($this->path, array($this->id));
    
        $this->client->post($url, $data->get());    
        return Constructor::Make($this, $data->get());
    }
    /* Deletes an allocated
     * number. this cannot be undone
     * 
     * update: make arguments compatable with
     * genericResource
     * @param id in place of initialized
     */
    public function delete($data=null)
    {
        $url = URIResource::Make($this->path, array($this->id)); 
        return $this->client->delete($url);
    }
    /**
     * stub for allocate 
     * get new numbers
     * @param args: see allocate
     */
    public function create()
    {
        $input = Ensure::Input(func_get_args());
        return $this->allocate($input->get());
    }
    /**
     * allocate a new number
     * number must be available
     * or warning will be thrown
     * @param args
     *   number, 
     *   application (one you want to associate this number with)
     *   fallback a fallback option if this isnt available
     */
    public function allocate($args)
    {
        $data = Ensure::Input($args);
        $url = URIResource::Make($this->path);
        $id = Locator::Find($this->client->post($url, $data->get()));
    
        $data->add("id", $id);
        return Constructor::Make($this, $data->get());
    }
    /** validate params for availabe local number
     * search. Rules:
     * 1) state, zip and areaCode are mutually exclusive use only one of them per
     *    request
     * 2) localNumber and inLocalCallingArea only applies for searching and  order
     *    numbers in specific areaCode
     *
     * @param args: set of arguments with above constraints
     */
    public function validateSearchQuery($args)
    {
        if (array_key_exists("zip", $args) && !(array_key_exists("state", $args) || array_key_exists("areaCode", $args)))
            return;
        if (array_key_exists("state", $args) && !(array_key_exists("zip", $args) || array_key_exists("areaCode", $args)))
            return;
        if (array_key_exists("areaCode", $args) && !(array_key_exists("zip", $args) || array_key_exists("state", $args)))
            return;
        if (!(array_key_exists("areaCode", $args) && array_key_exists("zip", $args) && array_key_exists("state", $args)))
            throw new \CatapultApiException("state, zip and areaCode are mutually exclusive, you may use only one of them per request");
        if (!(array_key_exists("areaCode")))
            throw new \CatapultApiException("localNumber and inLocalCallingArea only applies '
                             'for searching numbers in specific areaCode'");
    }
    /**
     * List the local numbers
     * according to the provided numbers
     * 
     * @param params
     */
    public function listLocal($params)
    {
        $data = Ensure::Input($params);
        if (!($data->has("size")))
            $data->add("size", DEFAULTS::SIZE);
        if (!($data->has("page")))
            $data->add("page", DEFAULTS::PAGE);
        $url = URIResource::Make($this->availablePath, array("local"));
        $data = $this->client->get($url, $data->get(), true, false);
        return new PhoneNumbersCollection(new DataPacketCollection($data));
    }
    /**
     * List toll free numbers
     * according to the provided parameters
     *
     * @param set of toll free parameters
     */
    public function listTollFree($params)
    {
        $data = Ensure::Input($params);
        if (!($data->has("size")))
            $data->add("size", DEFAULTS::SIZE);
        if (!($data->has("page")))
            $data->add("page", DEFAULTS::PAGE);
        $url = URIResource::Make($this->availablePath, array("tollFree"));
        $data = $this->client->get($url, $data->get(), true, false);
        return new PhoneNumbersCollection(new DataPacketCollection($data));
    }
    /**
     * Allocate numbers in batch
     * where numbers must be local
     *
     * notes:
     * 1. state, zip and area_code are mutually exclusive,
     *   you may use only one of them per calling list_local.
     * 2. local_number and in_local_calling_area only applies
     *    for searching numbers in specific area_code.
     * @param params
     */
    public function batchAllocateLocal($params)
    {
        $this->validate_search_query($params);
        $args = Ensure::Input($params);
        $url = URIResource::Make($this->availablePath, array("local"));
        $data = $this->client->post($url, $args->get(), true, false, true /* mixed uses GET parameters */);
        return new PhoneNumbersCollection(new DataPacketCollection($data));
    }
    /**
     * TollFree version batch allocation
     *
     * @param params
     */
    public function batchAllocateTollFree($params)
    {
        $url = URIResource::Make($this->availablePath, array("tollFree"));  
        
        $args = Ensure::Input($params);
        $data = $this->client->post($url, $args->get(), true, false, true /* mixed use GET parameters */);
        return new PhoneNumbersCollection(new DataPacketCollection($data));
    }
}
