<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contents extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
        {
            $this->postal->add('You are not allowed to visit the Contents page','error');
            redirect('admin','refresh');
        }
        $this->load->model('content_type_model');
        $this->load->model('content_model');
        $this->load->model('slug_model');
        $this->load->library('form_validation');
        $this->load->helper('text');
	}

	public function index($content_type_id = 0)
	{
        $content_type = $this->content_type_model->get($content_type_id);
        if($content_type == false)
        {
            $this->postal->add('The content type doesn\'t exist','error');
            redirect('admin');
        }
        else {
            $this->data['content_type'] = $content_type;

            $list_content = $this->content_model->get_list_from_table($content_type);
            $this->data['contents'] = $list_content;

            $this->load->model('input_definition_model');
            $input_definitions = $this->input_definition_model->where(array('content_type_id'=>$content_type->id,'input_type != ' => ''))->order_by('input_order', 'ASC')->get_all();
            $this->data['definitions'] = $input_definitions;
            $this->render('admin/contents/index_view');
        }
	}

    public function create($content_type_id = 0)
    {
        $content_type = $this->content_type_model->get($content_type_id);
        if($content_type == false)
        {
            $this->postal->add('The content type doesn\'t exist','error');
            redirect('admin');
        }

        $this->data['parents'] = $this->content_model->get_parents_list($content_type->id);

        $this->data['content_type'] = $content_type;

        $this->load->model('input_definition_model');
        $input_definitions = $this->input_definition_model->where(array('content_type_id'=>$content_type->id,'input_type != ' => ''))->order_by('input_order', 'ASC')->get_all();

        if($input_definitions == false)
        {
            $this->postal->add('You must first have table fields defined','error');
            redirect('admin/content-types/table-definition/'.$content_type->id);
        }

        $rules = array();
        foreach($input_definitions as $definition)
        {
            $rules[] = array(
                'field' => $definition->table_field,
                'label' => $definition->input_label,
                'rules' => $definition->insert_rules
            );
        }

        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()===FALSE)
        {
            $this->data['input_definitions'] = $input_definitions;
            $this->render('admin/contents/create_view');
        }
        else
        {
            echo '<pre>';
            print_r($input_definitions);
            echo '</pre>';
            $insert_data = array();

            foreach($input_definitions as $definition)
            {
                $insert_data[$definition->table_field] = $this->input->post($definition->table_field);
            }

            if($this->content_model->insert_into_table($content_type->table_name,$insert_data))
            {
                $this->postal->add('New '.$content_type->name.' was added successfully','success');
            }
            else
            {
                $this->postal->add('There was a problem when inserting '.$content_type->name,'error');
            }
            redirect('admin/contents/index/'.$content_type->id);
        }
    }

    public function edit($content_type_id,$content_id)
    {
        $content_type = $this->content_type_model->get($content_type_id);
        $content = $this->content_model->get_content_from_table($content_type,$content_id);
        if($content_type == false || $content == false)
        {
            $this->postal->add('There is no content to edit.','error');
            redirect('admin');
        }



        echo '<pre>';
        print_r($content_type);
        echo '</pre>';

        echo '<pre>';
        print_r($content);
        echo '</pre>';
        exit;

    }
    private function _verify_slug($str)
    {
        if($this->slug_model->where(array('url'=>$str))->get() !== FALSE)
        {
            $parts = explode('-',$str);
            if(is_numeric($parts[sizeof($parts)-1]))
            {
                $parts[sizeof($parts)-1] = $parts[sizeof($parts)-1]++;
            }
            else
            {
                $parts[] = '1';
            }
            $str = implode('-',$parts);
            $this->_verify_slug($str);
        }
        return $str;
    }

    public function publish($content_id, $published)
    {
        $content = $this->content_model->get($content_id);
        if( ($content != FALSE) && ($published==1 || $published==0))
        {
            if($this->content_model->update(array('published'=>$published),$content_id))
            {
                $this->rat->log('The user set publish status to '.$published.' for the content type "'.$content->content_type.'" with the ID '.$content->id.' and the title "'.$content->title.'"');
                $this->postal->add('The published status was set.','success');
            }
            else
            {
                $this->postal->add('Couldn\'t set the published status.','error');
            }
        }
        else
        {
            $this->postal->add('Can\'t find the content or the published status isn\'t correctly set.','error');
        }
        redirect('admin/contents/index/'.$content->content_type,'refresh');
    }

    public function delete($content_id)
    {
        if($content = $this->content_model->get($content_id))
        {
            if($content->content_type=='event')
            {
                $this->load->model('calendar_model');
                if($this->calendar_model->where(array('content_type'=>'event','content_id'=>$content->id))->get() !== FALSE)
                {
                    $this->postal->add('You must first delete the dates','error');
                    redirect('admin/contents/index/event');
                }
            }
            $deleted_slugs = $this->slug_model->where(array('content_type'=>$content->content_type,'content_id'=>$content->id))->delete();

            if(strlen($content->featured_image)>0)
            {
                $deleted_feature = 1;
                @unlink(FCPATH . 'media/' . $this->featured_image . '/' . $content->featured_image);
            }
            else
            {
                $deleted_feature = 0;
            }

            $deleted_images = 0;
            $this->load->model('image_model');
            $images = $this->image_model->where(array('content_type'=>$content->content_type,'content_id'=>$content->id))->get_all();
            if(!empty($images))
            {
                foreach($images as $image)
                {
                    @unlink(FCPATH.'media/'.$image->file);
                }
                $deleted_images = $this->image_model->where(array('content_type'=>$content->content_type,'content_id'=>$content->id))->delete();
            }

            $this->load->model('keyword_model');
            $deleted_keywords = $this->keyword_model->where(array('content_type'=>$content->content_type,'content_id'=>$content->id))->delete();

            $this->load->model('keyphrase_model');
            $deleted_keyphrases = $this->keyphrase_model->where(array('content_type'=>$content->content_type,'content_id'=>$content->id))->delete();

            $deleted_content = $this->content_model->delete($content->id);

            $this->rat->log('The user deleted the content type "'.$content->content_type.'" with the ID: '.$content->id.' and title: "'.$content->title.'"');
            $this->postal->add($deleted_content.' content was deleted. There were also '.$deleted_keywords.' keywords, '.$deleted_keyphrases.' key phrases, '.$deleted_slugs.' slugs and '.$deleted_images.' images deleted.','success');
        }
        else
        {
            $this->postal->add('There is no content to delete.','error');
        }
        redirect('admin/contents/index/'.$content->content_type,'refresh');

    }
}