<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Findcontent extends Public_Controller
{
    public $content;

    function __construct()
    {
        parent::__construct();
        $this->load->model('slug_model');
        $this->load->model('content_model');
        $this->load->library('ion_auth');
    }

    public function index()
    {
        $url = $this->uri->segment(1);
        if($slug = $this->slug_model->where(array('url'=>$url))->get())
        {
            if($slug->redirect != '0')
            {
                $slug = $this->slug_model->get($slug->redirect);
                redirect($slug->url,'location','302');
            }
            else
            {

                $content_id = $slug->content_id;

                $where = array();

                if(!$this->ion_auth->is_admin())
                {
                    $where['published'] = '1';
                    $where['published_at'] = date('Y-m-d H:i:s');
                }

                $this->content = $this->content_model->where(array('published'=>'1','published_at <= '=>date('Y-m-d H:i:s')))->get($content_id);

                if($this->content!==false)
                {
                    $this->{'_get_'.$this->content->content_type}();
                }
/*
                $this->data['page_title'] = $content->page_title;
                $this->data['page_description'] = $content->page_description;
                $this->data['page_keywords'] = $content->page_keywords;
                $this->data['title'] = $content->title;
                $this->data['content'] = $content->content;

                $this->render('public/' . $content->content_type . '_view');*/
            }
        }
        else
        {
            echo 'oups...';
            show_404();
            exit;
        }
    }
    private function _get_category()
    {
        echo '<pre>';
        print_r($this->content);
        echo '</pre>';
    }
    private function _get_post()
    {
        echo '<pre>';
        print_r($this->content);
        echo '</pre>';
    }
    private function _get_page()
    {
        echo '<pre>';
        print_r($this->content);
        echo '</pre>';
    }
}