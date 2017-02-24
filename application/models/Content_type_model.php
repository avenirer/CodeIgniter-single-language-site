<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Content_type_model extends MY_Model
{
    public $before_create = array('created_by');
    public $before_update = array('updated_by');

    public $default_fields = array(
        'id' => array(
            'type'=>'INT',
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'published' => array(
            'type' => 'TINYINT',
            'unsigned' => 1,
            'default' => 0
        ),
        'created_by' => array(
            'type' => 'INT',
            'unsigned' => TRUE,
            'null' => TRUE
        ),
        'created_at' => array(
            'type' => 'DATETIME',
            'null' => TRUE
        ),
        'updated_by' => array(
            'type' => 'INT',
            'unsigned' => TRUE,
            'null' => TRUE
        ),
        'updated_at' => array(
            'type' => 'DATETIME',
            'null' => TRUE
        ),
        'deleted_by' => array(
            'type' => 'INT',
            'unsigned' => TRUE,
            'null' => TRUE
        ),
        'deleted_at' => array(
            'type' => 'DATETIME',
            'null' => TRUE
        )
    );

    public function __construct()
    {
        $this->table = 'content_types';
        $this->primary_key = 'id';

        parent::__construct();

        $this->has_many['contents'] = array('foreign_model'=>'Content_model','foreign_table'=>'contents','local_key'=>'id','foreign_key'=>'content_type_id');
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

    public function create_table($table_name,$content_type_id)
    {
        $this->load->dbforge();
        $this->dbforge->add_field($this->default_fields);
        $this->dbforge->add_key('id', TRUE);
        if($this->dbforge->create_table($table_name, TRUE))
        {
            $insert = array();
            foreach($this->default_fields as $field_name => $field_data)
            {
                $data = array(
                    'content_type_id' => $content_type_id,
                    'table_field'=>$field_name,
                    'tf_constraint'=>'',
                    'tf_unsigned'=>'0',
                    'tf_default'=>'',
                    'tf_attributes' => 'NONE',
                    'tf_null'=>'0',
                    'tf_auto_increment'=>'0',
                    'tf_index'=>'NONE',

                );
                foreach($field_data as $def => $value)
                {
                    $data['tf_'.$def] = (string) $value;
                }
                $insert[] = $data;
            }
            if(!empty($insert) && $this->db->insert_batch('input_definitions',$insert))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function get_fields_data($content_type)
    {
        $this->db->where('content_type_id',$content_type->id);
        $input_definitions = $this->db->get('input_definitions')->result();


        if($input_definitions==false)
        {
            return false;
        }

        foreach($input_definitions as &$definition)
        {
            if(array_key_exists($definition->table_field,$this->default_fields) || $definition->table_field=='id')
            {
                $definition->deletable = 0;

            }
            else
            {
                $definition->deletable = 1;
            }
        }
        return $input_definitions;
    }
}