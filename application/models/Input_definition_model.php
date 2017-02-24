<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Input_definition_model extends MY_Model
{
    public $before_create = array('created_by');
    public $before_update = array('updated_by');

    public function __construct()
    {
        $this->table = 'input_definitions';
        $this->primary_key = 'id';

        parent::__construct();
    }

    public function created_by($data)
    {
        $data['created_by'] = $this->user_id;
        return $data;
    }

    public function updated_by($data)
    {
        $data['updated_by'] = $this->user_id;
        return $data;
    }
}