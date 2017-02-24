<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Content_model extends MY_Model
{
    private $featured_image;
    public $before_create = array( 'created_by' );
    public $before_update = array('updated_by');

    public $rules = array(
        'insert' => array(
            'parent_id' => array('field'=>'parent_id','label'=>'Parent ID','rules'=>'trim|is_natural|required'),
            'title' => array('field'=>'title','label'=>'Title','rules'=>'trim|required'),
            'short_title' => array('field'=>'short_title','label'=>'Short title','rules'=>'trim'),
            'slug' => array('field'=>'slug', 'label'=>'Slug', 'rules'=>'trim'),
            'order' => array('field'=>'order','label'=>'Order','rules'=>'trim|is_natural'),
            'teaser' => array('field'=>'teaser','label'=>'Teaser','rules'=>'trim'),
            'content' => array('field'=>'content','label'=>'Content','rules'=>'trim'),
            'page_title' => array('field'=>'page_title','label'=>'Page title','rules'=>'trim'),
            'page_description' => array('field'=>'page_description','label'=>'Page description','rules'=>'trim'),
            'page_keywords' => array('field'=>'page_keywords','label'=>'Page keywords','rules'=>'trim'),
            'content_type_id' => array('field'=>'content_type_id','label'=>'Content type ID','rules'=>'trim|is_natural_no_zero|required'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime')
        ),
        'update' => array(
            'parent_id' => array('field'=>'parent_id','label'=>'Parent ID','rules'=>'trim|is_natural|required'),
            'title' => array('field'=>'title','label'=>'Title','rules'=>'trim|required'),
            'short_title' => array('field'=>'short_title','label'=>'Short title','rules'=>'trim'),
            'slug' => array('field'=>'slug', 'label'=>'Slug', 'rules'=>'trim'),
            'order' => array('field'=>'order','label'=>'Order','rules'=>'trim|is_natural'),
            'teaser' => array('field'=>'teaser','label'=>'Teaser','rules'=>'trim'),
            'content' => array('field'=>'content','label'=>'Content','rules'=>'trim'),
            'page_title' => array('field'=>'page_title','label'=>'Page title','rules'=>'trim|required'),
            'page_description' => array('field'=>'page_description','label'=>'Page description','rules'=>'trim'),
            'page_keywords' => array('field'=>'page_keywords','label'=>'Page keywords','rules'=>'trim'),
            'content_id' => array('field'=>'content_id', 'label'=>'Content ID', 'rules'=>'trim|is_natural_no_zero|required'),
            'published_at' => array('field'=>'published_at','label'=>'Published at','rules'=>'trim|datetime')
        ),
        'insert_featured' => array(
            'file_name' => array('field'=>'file_name','label'=>'File name','rules'=>'trim'),
            'content_id' => array('field'=>'content_id','label'=>'Contend ID','rules'=>'tirm|is_natural_no_zero|required')
        )
    );

    public function __construct()
    {
        $this->featured_image = $this->config->item('cms_featured_image');
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

    public function get_parents_list($content_type_id,$content_id = 0)
    {
        $this->load->model('content_type_model');
        $content_type = $this->content_type_model->get($content_type_id);
        $parent_id = $content_type->parent_id;
        if($parent_id != 0)
        {
            $this->db->select('id,short_title');
            $this->db->order_by('short_title', 'asc');
            $this->db->where('contents.id != ', $content_id);
            $this->db->where('contents.content_type_id', $parent_id);
            $query = $this->db->get('contents');
            $parents = array('0' => 'No parent');
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $parents[$row->id] = $row->short_title;
                }
            }
            return $parents;
        }
        return false;
    }

    public function insert_into_table($table_name, $insert)
    {
        $insert['created_by'] = $_SESSION['user_id'];
        $insert['created_at'] = date('Y-m-d H:i:s');
        if($this->db->insert($table_name,$insert))
        {
            return TRUE;
        }
        return FALSE;
    }

    public function get_content_from_table($content_type,$content_id)
    {
        $table_name = $content_type->table_name;
        $this->db->where('id',$content_id);
        $this->db->limit(1);
        return $this->db->get($table_name)->row();
    }

    public function get_list_from_table($content_type)
    {
        $table_name = $content_type->table_name;
        return $this->db->get($table_name)->result();
    }


}