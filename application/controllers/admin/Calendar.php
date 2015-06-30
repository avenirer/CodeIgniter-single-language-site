<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
        {
            $this->postal->add('You are not allowed to visit the Calendar page','error');
            redirect('admin','refresh');
        }
        $this->load->model('calendar_model');
        $this->load->model('content_model');
        $this->load->model('slug_model');
        $this->load->library('form_validation');
        $this->load->helper('text');
	}

	public function index($content_type,$content_id)
	{
        $content = $this->content_model->get($content_id);
        $list_dates = $this->calendar_model->where(array('content_type'=>$content_type,'content_id'=>$content_id))->order_by('start_dt','asc')->get_all();
        $this->data['content'] = $content;
        $this->data['dates'] = $list_dates;
        $this->render('admin/calendar/index_view');
	}

    public function create($content_type, $content_id)
    {
        $this->data['content_type'] = $content_type;
        $this->data['content_id'] = $content_id;
        $rules = $this->calendar_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/calendar/create_view');
        }
        else
        {
            $start_dt = $this->input->post('start_datetime');
            $end_dt = $this->input->post('end_datetime');
            $content_type = $this->input->post('content_type');
            $content_id = $this->input->post('content_id');
            $title = $this->input->post('title');
            $short_title = (strlen($this->input->post('short_title')) > 0) ? $this->input->post('short_title') : $title;
            $slug = (strlen($this->input->post('slug')) > 0) ? url_title($this->input->post('slug'),'-',TRUE) : url_title(convert_accented_characters($title),'-',TRUE);
            $content = $this->input->post('content');
            $teaser = (strlen($this->input->post('teaser')) > 0) ? $this->input->post('teaser') : substr($content, 0, strpos($content, '<!--more-->'));
            if($teaser == 0) $teaser = '';
            $page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
            $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($teaser, 160);
            $page_keywords = $this->input->post('page_keywords');

            $insert_data = array(
                'content_type'=>$content_type,
                'content_id'=>$content_id,
                'start_dt'=>$start_dt,
                'end_dt'=>$end_dt,
                'title' => $title,
                'short_title' => $short_title,
                'teaser' => $teaser,
                'content' => $content,
                'page_title' => $page_title,
                'page_description' => $page_description,
                'page_keywords' => $page_keywords
            );

            if($date_id = $this->calendar_model->insert($insert_data))
            {
                $this->rat->log('The user created a new date for an event named: '.$insert_data['title']);
                $this->postal->add('A new date was created', 'success');
                $this->slug_model->verify_insert(array('content_type'=> 'calendar','content_id'=>$date_id,'url'=>$slug));
            }
            redirect('admin/calendar/index/'.$content_type.'/'.$content_id);
        }
    }

    public function edit($content_id)
    {
        $content = $this->calendar_model->get($content_id);
        if($content === FALSE)
        {
            $this->postal->add('There is no content to edit.','error');
            redirect('admin');
        }
        $this->data['content'] = $content;
        $this->data['slugs'] = $this->slug_model->where(array('content_type'=>'calendar','content_id'=>$content->id))->get_all();
        $rules = $this->calendar_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/calendar/edit_view');
        }
        else
        {
            $content_id = $this->input->post('content_id');
            $content = $this->calendar_model->get($content_id);
            if($content!== FALSE)
            {
                $parent_id = $this->input->post('parent_id');
                $title = $this->input->post('title');
                $short_title = $this->input->post('short_title');
                $slug = url_title(convert_accented_characters($this->input->post('slug')),'-',TRUE);
                $order = $this->input->post('order');
                $text = $this->input->post('content');
                $teaser = (strlen($this->input->post('teaser')) > 0) ? $this->input->post('teaser') : substr($text, 0, strpos($text, '<!--more-->'));
                $page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
                $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($teaser, 160);
                $page_keywords = $this->input->post('page_keywords');
                $published_at = $this->input->post('published_at');

                $update_data = array(
                    'title' => $title,
                    'short_title' => $short_title,
                    'teaser' => $teaser,
                    'content' => $text,
                    'page_title' => $page_title,
                    'page_description' => $page_description,
                    'page_keywords' => $page_keywords,
                    'parent_id' => $parent_id,
                    'published_at' => $published_at,
                    'order' => $order);

                if ($this->calendar_model->update($update_data, $content_id))
                {
                    if(strlen($slug)>0)
                    {
                        $url = $this->_verify_slug($slug);
                        $new_slug = array(
                            'content_type' => $content->content_type,
                            'content_id' => $content->id,
                            'url' => $url);
                        if($slug_id =  $this->slug_model->insert($new_slug))
                        {
                            $this->slug_model->where(array('content_type'=>$content->content_type, 'id !='=>$slug_id))->update(array('redirect'=>$slug_id,'updated_by'=>$this->user_id));
                        }
                    }
                    $this->rat->log('The user edited the event date having "'.$content->title.'" as title. The ID of the date is: '.$content->id);
                    $this->postal->add('The content was updated successfully.','success');
                }
            }
            else
            {
                $this->postal->add('There is no content to update.','error');
            }
            redirect('admin/calendar/index/'.$content->content_type.'/'.$content->content_id);
        }
    }

    public function publish($date_id, $published)
    {
        $date = $this->calendar_model->get($date_id);
        if( ($date != FALSE) && ($published==1 || $published==0))
        {
            if($this->calendar_model->update(array('published'=>$published),$date->id))
            {
                $this->rat->log('The user has set the publish status of the date having "'.$date->title.'" as title to '.$published.'. The ID of the date is: '.$date->id);
                $this->postal->add('The published status was set.','success');
            }
            else
            {
                $this->postal->add('Couldn\'t set the published status.','error');
            }
        }
        else
        {
            $this->postal->add('Can\'t find the date or the published status isn\'t correctly set.','error');
        }
        redirect('admin/calendar/index/'.$date->content_type.'/'.$date->content_id);
    }

    public function delete($date_id)
    {
        if($content = $this->calendar_model->get($date_id))
        {
            $deleted_slugs = $this->slug_model->where(array('content_type'=>'calendar','content_id'=>$content->id))->delete();
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

            $deleted_content = $this->calendar_model->delete($content->id);
            $this->rat->log('The user deleted a date with the title: '.$content->title.'. The ID was: '.$date_id);
            $this->postal->add($deleted_content.' content was deleted. There were also '.$deleted_slugs.' slugs and '.$deleted_images.' images deleted.','success');
        }
        else
        {
            $this->postal->add('There is no content to delete','error');
        }
        redirect('admin/calendar/index/'.$content->content_type.'/'.$content->content_id);

    }
}