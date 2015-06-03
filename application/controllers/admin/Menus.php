<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
        {
            $this->postal->add('You are not allowed to visit the Menus page','error');
            redirect('admin');
        }
        $this->load->model('menu_model');
        $this->load->model('menu_item_model');
        $this->load->model('slug_model');
        $this->load->library('form_validation');
        $this->load->helper('text');
	}

	public function index()
	{
        $this->data['menus'] = $this->menu_model->order_by('created_at, updated_at','desc')->get_all();
        $items = $this->menu_item_model->where('menu_id','0')->get_all();
        $this->data['without_menu'] = $items;
		$this->render('admin/menus/index_view');
	}

    public function create()
    {
        $rules = $this->menu_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/menus/create_menu_view');
        }
        else
        {
            $title = url_title(convert_accented_characters($this->input->post('title')),'-',TRUE);
            if ($this->menu_model->insert(array('title'=>$title, 'created_by'=>$this->user_id)))
            {
                $this->postal->add('The new menu was created.','success');
            }
            redirect('admin/menus');
        }
    }

    public function edit($menu_id)
    {
        $menu = $this->menu_model->get($menu_id);
        if($menu == FALSE)
        {
            $this->postal->add('There is no menu to edit.','error');
            redirect('admin/menus');
        }
        $this->data['menu'] = $menu;

        $rules = $this->menu_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/menus/edit_menu_view');
        }
        else
        {
            $title = $this->input->post('title');
            $menu_id = $this->input->post('menu_id');
            $update_data = array('title' => $title,'updated_by' => $this->user_id);
            $this->postal->add('Couldn\'t edit menu.','error');
            if ($this->menu_model->update($update_data, $menu_id))
            {
                $this->postal->add('The menu was updated successfully.','success');
            }
            redirect('admin/menus');
        }
    }

    public function delete($menu_id)
    {
        if(!$this->menu_model->delete($menu_id))
        {
            $this->postal->add('The menu doesn\'t exist.','error');
            redirect('admin/menus');
        }
        if($menu_items = $this->menu_item_model->update(array('menu_id'=>'0','updated_by'=>$this->user_id),array('menu_id'=>$menu_id)))
        {
            $this->postal->add('The menu was deleted. Now you have '.$menu_items.' menu item without a menu location.','success');
        }
        redirect('admin/menus');
    }

    public function items($menu_id = NULL)
    {
        if(!isset($menu_id) || $menu_id == 0)
        {
            redirect('admin/menus');
        }
        $this->data['menu'] = $this->menu_model->get($menu_id);
        $this->data['items'] = $this->menu_item_model->order_by('order','asc')->where('menu_id',$menu_id)->get_all();
        $this->render('admin/menus/index_items_view');
    }

    public function create_item($menu_id)
    {
        $this->data['menu'] = $this->menu_model->get($menu_id);
        if($this->data['menu'] === FALSE)
        {
            $this->postal->add('The menu doesn\'t exist.','error');
            redirect('admin/menus');
        }
        $items = $this->menu_item_model->where('menu_id',$menu_id)->order_by('title')->fields('id,title')->get_all();
        $this->data['parent_items'] = array('0'=>'Top level');
        if(!empty($items))
        {
            foreach($items as $item)
            {
                $this->data['parent_items'][$item->id] = $item->title;
            }
        }

        $rules = $this->menu_item_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/menus/create_item_view');
        }
        else
        {
            $parent_id = $this->input->post('parent_id');
            $title = $this->input->post('title');
            $url = $this->input->post('url');
            $absolute_path = $this->input->post('absolute_path');
            $order = $this->input->post('order');
            $styling = $this->input->post('styling');
            $menu_id = $this->input->post('menu_id');
            $insert_data = array(
                'menu_id' => $menu_id,
                'parent_id' => $parent_id,
                'order' => $order,
                'title' => $title,
                'url' => $url,
                'absolute_path' => $absolute_path,
                'styling'=>$styling,
                'created_by'=>$this->user_id);
            if ($this->menu_item_model->insert($insert_data))
            {
                $this->postal->add('Item successfuly added','success');
            }
            else
            {
                $this->postal->add('Couldn\'t add item','success');
            }

            redirect('admin/menus/items/'.$menu_id);

        }
    }

    public function edit_item($item_id)
    {
        $item = $this->menu_item_model->get($item_id);
        if($item===FALSE)
        {
            $this->postal->add('There is no item to edit','error');
            redirect('admin/menus');
        }
        $this->data['item'] = $item;
        $items = $this->menu_item_model->where('menu_id',$item->menu_id)->where('id','!=',$item->id)->order_by('title')->fields('id,title')->get_all();
        $this->data['parent_items'] = array('0'=>'Top level');
        if(!empty($items))
        {
            foreach($items as $item)
            {
                $this->data['parent_items'][$item->id] = $item->title;
            }
        }
        $rules = $this->menu_item_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->render('admin/menus/edit_item_view');
        }
        else
        {
            $parent_id = $this->input->post('parent_id');
            $title = $this->input->post('title');
            $url = $this->input->post('url');
            $absolute_path = $this->input->post('absolute_path');
            $order = $this->input->post('order');
            $styling = $this->input->post('styling');
            $item_id = $this->input->post('item_id');
            $update_data = array(
                'title' => $title,
                'url' => $url,
                'absolute_path' => $absolute_path,
                'parent_id'=>$parent_id,
                'order'=>$order,
                'styling'=>$styling,
                'updated_by'=>$this->user_id);

            if($this->menu_item_model->update($update_data,$item_id))
            {
                $this->postal->add('Item successfuly edited','success');
            }
            else
            {
                $this->postal->add('Couldn\'t edit item','error');
            }
            redirect('admin/menus/items/'.$item->id);

        }
    }

    public function delete_item($item_id)
    {
        $item = $this->menu_item_model->get($item_id);
        $menu_id = $item->menu_id;
        if($this->menu_item_model->delete($item_id))
        {
            $this->postal->add('The item was deleted','success');
        }
        else
        {
            $this->postal->add('Couldn\'t delete the item','error');
        }
        redirect('admin/menus/items/'.$menu_id);
    }
}