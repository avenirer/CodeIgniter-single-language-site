<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Content_types extends Admin_Controller
{

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