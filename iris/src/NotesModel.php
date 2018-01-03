<?php

namespace Iris;

/**
 *
 */
class Notes extends RestEntry {
    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    /**
    * Get Notes
    * @return array Array of Notes
    */
    public function getList() {
        $out = [];

        $data = parent::_get('notes');

        if(isset($data) && isset($data['Note'])) {
            $notes = $data['Note'];
            if($this->is_assoc($notes))
                $notes = [$notes];
            foreach($notes as $note) {
                $out[] = new Note($this, $note);
            }
        }

        return $out;
    }

    public function create($data, $save = true) {
        $note = new Note($this, $data);
        if($save)
            $note->save();
        return $note;
    }


    public function get_appendix() {
        return '/notes';
    }

}

/**
 * Note Model Class
 * @property string $id ID
 * @property string $UserId
 * @property string $Description
 * @property string $LastDateModifier
 */
class Note extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "Id" => array("type" => "string"),
        "UserId" => array("type" => "string"),
        "Description" => array("type" => "string"),
        "LastDateModifier" => array("type" => "string")
    );


    public function __construct($parent, $data) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
        $this->set_data($data);
    }

    public function save() {
        $header = parent::post(null, "Note", $this->to_array());
        $splitted = explode("/", $header['Location']);
        $this->Id = end($splitted);
    }
}
?>
