<?php

/**
 * @model Report
 * https://api.test.inetwork.com/v1.0/accounts/reports
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Reports extends RestEntry {

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function getList($filters = Array()) {

        $reports = [];

        $data = parent::_get('reports', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));
        print_r($data); exit;
        /* TODO:  correct struct */
        if($data['ListOrderIdUserIdDate'] && $data['ListOrderIdUserIdDate']['TotalCount']) {
            foreach($data['ListOrderIdUserIdDate']['OrderIdUserIdDate'] as $report) {
                $reports[] = new Report($this, $report);
            }
        }

        return $reports;
    }

    public function get_by_id($id) {
        $order = new Report($this, array("Id" => $id));
        $order->get();
        return $order;
    }

    public function get_appendix() {
        return '/reports';
    }

}

final class Report extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "orderId" => array(
            "type" => "string"
        ),
        /* TODO:  fill fields */
    );


    public function __construct($reports, $data)
    {
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($reports)) {
            $this->parent = $reports;
            parent::_init($reports->get_rest_client(), $reports->get_relative_namespace());
        }
    }

    public function get() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');

        $data = parent::_get($this->id);
        $this->set_data($data['Order']);
    }

    public function areaCodes()
    {
        $url = sprintf('%s/%s', $this->id, 'areaCodes');
        $data = parent::_get($url);
        return $data;
    }

    public function instances()
    {
        $rep_instances = [];

        $data = parent::_get('reports/{$this->id}/instances', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));

        if($data['ListOrderIdUserIdDate'] && $data['ListOrderIdUserIdDate']['TotalCount']) {
            foreach($data['ListOrderIdUserIdDate']['OrderIdUserIdDate'] as $instance) {
                $rep_instances[] = new ReportInctace($this, $instance);
            }
        }

        return $rep_instances;
    }
}


final class ReportInstance extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "orderId" => array(
            "type" => "string"
        ),
        /* TODO:  fill fields */
    );


    public function __construct($report, $data)
    {
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($report)) {
            $this->parent = $report;
            parent::_init($report->get_rest_client(), $report->get_relative_namespace());
        }
    }

    public function get() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');

        $data = parent::_get($this->id);
        $this->set_data($data['Order']);
    }
}
