<?php

namespace Iris;

class FileMetaData {
    use BaseModel;

    protected $fields = array(
        "DocumentName" => array("type" => "string"),
        "DocumentType" => array(
            "type" => "string",
            "validate" => array(
                "type" => "in_array",
                "value" => array(
                    "LOA",
                    "INVOICE",
                    "CSR",
                    "OTHER"
                )
        ))
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
