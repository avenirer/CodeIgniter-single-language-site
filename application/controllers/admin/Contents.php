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
        $this->load->model('content_model');
        $this->load->model('slug_model');
        $this->load->library('form_validation');
        $this->load->helper('text');
	}

	public function index($content_type = 'page')
	{
        $list_content = $this->content_model->where('content_type',$content_type)->get_all();
        $this->data['content_type'] = $content_type;
        $this->data['contents'] = $list_content;
        $this->render('admin/contents/index_view');
	}

    public function create($content_type = 'page')
    {
        $this->data['parents'] = $this->content_model->get_parents_list($content_type);
        $this->data['content_type'] = $content_type;
        $rules = $this->content_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/contents/create_view');
        }
        else
        {
            $content_type = $this->input->post('content_type');
            $parent_id = $this->input->post('parent_id');
            $title = $this->input->post('title');
            $short_title = (strlen($this->input->post('short_title')) > 0) ? $this->input->post('short_title') : $title;
            $slug = (strlen($this->input->post('slug')) > 0) ? url_title($this->input->post('slug'),'-',TRUE) : url_title(convert_accented_characters($title),'-',TRUE);
            $order = (int) $this->input->post('order');
            $content = $this->input->post('content');
            $teaser = (strlen($this->input->post('teaser')) > 0) ? $this->input->post('teaser') : substr($content, 0, strpos($content, '<!--more-->'));
            if($teaser == 0) $teaser = '';
            $page_title = (strlen($this->input->post('page_title')) > 0) ? $this->input->post('page_title') : $title;
            $page_description = (strlen($this->input->post('page_description')) > 0) ? $this->input->post('page_description') : ellipsize($teaser, 160);
            $page_keywords = $this->input->post('page_keywords');
            $published_at = $this->input->post('published_at');

            $insert_data = array(
                'content_type'=>$content_type,
                'title' => $title,
                'short_title' => $short_title,
                'teaser' => $teaser,
                'content' => $content,
                'page_title' => $page_title,
                'page_description' => $page_description,
                'page_keywords' => $page_keywords,
                'order' => $order,
                'published_at'=>$published_at,
                'parent_id' => $parent_id
            );

            if($content_id = $this->content_model->insert($insert_data))
            {
                $this->postal->add('A new '.$content_type.' was created', 'success');
                $url = $this->_verify_slug($slug);
                $this->slug_model->insert(
                    array(
                    'content_type'=> $content_type,
                    'content_id'=>$content_id,
                    'url'=>$url)
                );
            }

            redirect('admin/contents/index/'.$content_type,'refresh');
        }
    }

    public function edit($content_id)
    {
        $content = $this->content_model->get($content_id);
        if($content === FALSE)
        {
            $this->postal->add('There is no content to edit.','error');
            redirect('admin/contents/index', 'refresh');
        }
        $this->data['content'] = $content;
        $this->data['parents'] = $this->content_model->get_parents_list($content->content_type,$content->id);
        $this->data['slugs'] = $this->slug_model->where(array('content_type'=>$content->content_type,'content_id'=>$content->id))->get_all();
        $rules = $this->content_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/contents/edit_view');
        }
        else
        {
            $content_id = $this->input->post('content_id');
            $content = $this->content_model->get($content_id);
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

                if ($this->content_model->update($update_data, $content_id))
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
                    $this->postal->add('The content was updated successfully.','success');
                }
            }
            else
            {
                $this->postal->add('There is no content to update.','error');
            }
            redirect('admin/contents/index/'.$content->content_type,'refresh');
        }
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

            $this->postal->add($deleted_content.' content was deleted. There were also '.$deleted_keywords.' keywords, '.$deleted_keyphrases.' key phrases, '.$deleted_slugs.' slugs and '.$deleted_images.' images deleted.','success');
        }
        else
        {
            $this->postal->add('There is no content to delete.','error');
        }
        redirect('admin/contents/index/'.$content->content_type,'refresh');

    }
}