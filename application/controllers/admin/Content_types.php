<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Content_types extends Admin_Controller
{
    public $mysql_types = array(
        'INT' => 'INT',
        'TINYINT' => 'TINYINT',
        'SMALLINT' => 'SMALLINT',
        'MEDIUMINT' => 'MEDIUMINT',
        'BIGINT' => 'BIGINT',
        'FLOAT' => 'FLOAT',
        'DOUBLE' => 'DOUBLE',
        'DECIMAL' => 'DECIMAL',
        'DATE' => 'DATE',
        'DATETIME' => 'DATETIME',
        'TIMESTAMP' => 'TIMESTAMP',
        'TIME' => 'TIME',
        'YEAR' => 'YEAR',
        'CHAR' => 'CHAR',
        'VARCHAR' => 'VARCHAR',
        'BLOB' => 'BLOB',
        'TEXT' => 'TEXT',
        'TINYBLOB' => 'TINYBLOB',
        'TINYTEXT' => 'TINYTEXT',
        'MEDIUMBLOB' => 'MEDIUMBLOB',
        'MEDIUMTEXT' => 'MEDIUMTEXT',
        'LONGBLOB' => 'LONGBLOB',
        'LONGTEXT' => 'LONGTEXT',
        'ENUM' => 'ENUM'
    );

    public $mysql_index = array(
        'NONE' => 'NONE',
        'PRIMARY' => 'PRIMARY',
        'UNIQUE' => 'UNIQUE',
        'INDEX' => 'INDEX',
        'FULLTEXT' => 'FULLTEXT',
        'SPATIAL' => 'SPATIAL'
    );

    public $input_types = array(
        'text'=>'text',
        'textarea'=>'textarea',
        'wysiwyg'=>'textarea with WYSIWYG',
        'select'=>'select',
        'checkbox'=>'checkbox',
        'radio'=>'radio'
    );


	function __construct()
	{
		parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
        {
            $this->postal->add('You are not allowed to visit the Content types page','error');
            redirect('admin','refresh');
        }
        $this->load->model('content_type_model');
	}

	public function index()
	{
        $content_types = $this->content_type_model->order_by('name')->get_all();
        if($content_types)
        {
            $parents = array(0=>'No parent');
            foreach($content_types as $type)
            {
                $parents[$type->id] = $type->name;
            }
            $this->data['parents'] = $parents;
        }
        $this->data['content_types'] = $content_types;
        $this->render('admin/content_types/index_view');
	}

    public function create()
    {
        $this->form_validation->set_rules('name','Name','trim|required|is_unique[content_types.name]');
        $this->form_validation->set_rules('plural','Plural','trim|required|is_unique[content_types.plural]');
        $this->form_validation->set_rules('table_name','Table name','trim|required|is_unique[content_types.table_name]');
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/content_types/create_view');
        }
        else
        {
            $name = $this->input->post('name');
            $plural = $this->input->post('plural');
            $table_name = $this->input->post('table_name');
            $insert = compact('name','plural','table_name');

            $content_type_id = $this->content_type_model->insert($insert);

            if($content_type_id)
            {
                $this->postal->add('Content type added','success');
                if($this->content_type_model->create_table($table_name,$content_type_id))
                {
                    $this->postal->add('Table "'.$table_name.'" was created.','success');
                }
                else
                {
                    $this->postal->add('Couldn\'t create table "'.$table_name.'".','error');
                }
            }
            else
            {
                $this->postal->add('Couldn\'t add content type','error');
            }
            redirect('admin/content-types');
        }
    }

    public function edit($type_id)
    {
        $content_type = $this->content_type_model->get($type_id);
        if($content_type === FALSE)
        {
            $this->postal->add('There is no content type to edit.','error');
            redirect('admin/content-types/index', 'refresh');
        }
        $this->data['content_type'] = $content_type;
        $types = $this->content_type_model->order_by('name')->as_dropdown('name')->get_all();
        $types[0] = 'No parent';
        $this->data['types'] = $types;
        $this->form_validation->set_rules('name','Name','trim|required');
        $this->form_validation->set_rules('plural','Plural','trim|required');
        $this->form_validation->set_rules('table_name','Table name','trim|required');
        $this->form_validation->set_rules('parent_id','Parent ID','trim|is_numeric');
        $this->form_validation->set_rules('id','ID','trim|is_natural_no_zero');
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/content_types/edit_view');
        }
        else
        {
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $parent_id = $this->input->post('parent_id');
            if($parent_id=='-1') $parent_id = $id;
            $plural = $this->input->post('plural');
            $table_name = $this->input->post('table_name');
            $update = compact('name','parent_id','plural','table_name');

            if($this->content_type_model->update($update,$id))
            {
                $this->postal->add('Content type edited','success');
            }
            else
            {
                $this->postal->add('Couldn\'t edit content type','error');
            }
            redirect('admin/content-types');

        }
    }
    public function table_definition($content_type_id)
    {
        $content_type = $this->content_type_model->get($content_type_id);
        if($content_type === false)
        {
            $this->postal->add('The content type doesn\'t exist','error');
            redirect('admin/content-types');
        }
        $table_fields = $this->content_type_model->get_fields_data($content_type);

        $this->data['content_type'] = $content_type;
        $this->data['table_fields'] = $table_fields;
        $this->render('admin/content_types/table_definition_view');
    }

    public function add_field($content_type_id)
    {
        $content_type = $this->content_type_model->get($content_type_id);
        if($content_type==false)
        {
            $this->postal->add('Couldn\' find the requested content type.','error');
            redirect('admin/content-types');
            die();
        }

        $field_positions = array('last'=>'Last','first'=>'First','before'=>'Before','after'=>'After');
        $this->data['field_positions'] = $field_positions;
        $in_list_positions = strtolower(implode(',',$field_positions));

        $table_fields = $this->content_type_model->get_fields_data($content_type);
        $table_fields_arr = array('-'=>'-');
        foreach($table_fields as $field)
        {
            $table_fields_arr[$field->table_field] = $field->table_field;
        }
        $this->data['table_fields'] = $table_fields_arr;
        $in_list_table_fields = implode(',',$table_fields_arr);

        $this->data['mysql_types'] = $this->mysql_types;
        $in_list_types = implode(',',$this->mysql_types);
        $attributes = array('NONE' => 'NONE','BINARY' => 'BINARY','UNSIGNED' => 'UNSIGNED','UNSIGNED ZEROFILL' => 'UNSIGNED ZEROFILL','on update CURRENT_TIMESTAMP' => 'on update CURRENT_TIMESTAMP');
        $this->data['attributes'] = $attributes;
        $in_list_attributes = implode(',',$attributes);
        $this->data['mysql_index'] = $this->mysql_index;
        $in_list_index = implode(',',$this->mysql_index);
        $this->data['input_types'] = $this->input_types;
        $in_list_inputs = implode(',',$this->input_types);


        $this->form_validation->set_rules('table_field','Table field name','trim|required|alpha_dash|not_in_list['.$in_list_table_fields.']');
        $this->form_validation->set_rules('field_position','trim|required|in_list['.$in_list_positions.']');
        $this->form_validation->set_rules('table_column','trim|required|in_list['.$in_list_table_fields.']');
        $this->form_validation->set_rules('type','Type','trim|in_list['.$in_list_types.']');
        $this->form_validation->set_rules('length','Length','trim');
        $this->form_validation->set_rules('default','Default value','trim');
        $this->form_validation->set_rules('attributes','trim|in_list['.$in_list_attributes.']');
        $this->form_validation->set_rules('null','Accept NULL value','trim|in_list[0,1]');
        $this->form_validation->set_rules('index','Index','trim|in_list['.$in_list_index.']');

        $this->form_validation->set_rules('label_name','Label name','trim|required');
        $this->form_validation->set_rules('input_type','Input type','trim|required|in_list['.$in_list_inputs.']');
        $this->form_validation->set_rules('insert_validation_rules','Insert validation rules','trim|required');
        $this->form_validation->set_rules('update_validation_rules','Update validation rules','trim|required');
        $this->form_validation->set_rules('input_position','Input position','trim|required|is_natural');

        if($this->form_validation->run()===false)
        {
            $this->render('admin/content_types/table_field_create_view');
        }
        else
        {
            $input_definition = array('content_type_id'=>$content_type->id,'deletable'=>'1');

            $table_field = trim($this->input->post('table_field'));
            $type = trim($this->input->post('type'));
            $field = array();
            $field[$table_field] = array('type'=>$type);

            $input_definition['table_field'] = $table_field;
            $input_definition['tf_type'] = $type;

            $length = $this->input->post('length');
            if(strlen($length)!=0) {
                $field[$table_field]['constraint'] = $length;
                $input_definition['tf_constraint'] = $length;
            }

            $field_position = strtolower($this->input->post('field_position'));
            $table_column = ($this->input->post('table_column')=='-') ? TRUE : $this->input->post('table_column');
            $field[$table_field][$field_position] = $table_column;

            $default_value = $this->input->post('default');
            if(strlen($default_value)>0) {
                $field[$table_field]['default'] = $default_value;
                $input_definition['tf_default'] = $default_value;
            }

            $attributes = trim($this->input->post('attributes'));
            if($attributes!='NONE') $field[$table_field][$attributes] = TRUE;
            $input_definition['tf_attributes'] = $attributes;

            $null = $this->input->post('null');
            if($null=='1')
            {
                $field[$table_field]['null'] = TRUE;
                $input_definition['tf_null'] = TRUE;
            }
            else
            {
                $input_definition['tf_null'] = '0';
            }

            $index = trim($this->input->post('index'));
            if($index!='NONE') $field[$table_field][$index] = TRUE;
            $input_definition['tf_index'] = $index;

            $this->load->dbforge();
            if($this->dbforge->add_column($content_type->table_name,$field))
            {
                $input_definition['input_label'] = $this->input->post('label_name');
                $input_definition['input_type'] = $this->input->post('input_type');
                $input_definition['insert_rules'] = $this->input->post('insert_validation_rules');
                $input_definition['update_rules'] = $this->input->post('update_validation_rules');
                $input_definition['input_order'] = $this->input->post('input_position');



                $this->load->model('input_definition_model');
                $this->input_definition_model->insert($input_definition);
                $this->postal->add('Field added successfully','success');
            }
            else
            {
                $this->postal->add('There was a problem when adding field','error');
            }

            redirect('admin/content-types/table-definition/'.$content_type->id);

            /*
            echo '<pre>';
            print_r($field);
            echo '<pre>';

            echo '<pre>';
            print_r($this->input->post());
            echo '</pre>';
            */
        }

        print_r($content_type);
    }

    public function publish($content_id, $published)
    {

    }

    public function delete($content_id)
    {

    }
}