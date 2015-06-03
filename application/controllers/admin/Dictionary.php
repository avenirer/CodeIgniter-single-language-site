<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dictionary extends Admin_Controller
{
    function __construct()
	{
		parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('dictionary_model');
	}

	public function index()
    {
        $this->load->model('dictionary_model');
        $total_words = $this->dictionary_model->count();
        $words = $this->dictionary_model->order_by('verified,word','ASC')->paginate(200,$total_words);
        $this->data['words'] = $words;
        $this->data['next_previous_pages'] = $this->dictionary_model->all_pages;
        $this->render('admin/dictionary/index_view');
    }
    public function add_word_from_content($content_id,$word)
    {
        $this->load->model('dictionary_model');
        $word = urldecode($word);
        if($word_id = $this->dictionary_model->insert(array('word'=>$word,'verified'=>'0')))
        {
            $this->postal->add('The word was inserted.','success');
        }
        else
        {
            $this->postal->add('Couldn\'t insert word.','error');
        }
        redirect('admin/rake/analyze/'.$content_id.'/#add_words');
    }

    public function add_word()
    {
        $this->load->model('dictionary_model');
        $rules = $this->dictionary_model->rules;
        $this->form_validation->set_rules($rules['insert']);
        if($this->form_validation->run()===FALSE)
        {
            $this->data['before_body'] = '
<script>
window.onunload = refreshParent;
function refreshParent()
{
window.opener.location.reload();
}
$(\'#root_word_options\').textext({
    plugins : \'autocomplete ajax\',
    ajax : {
        url : \''.site_url('admin/dictionary/autosuggest/?').'\',
        dataType : \'json\',
        cacheResults : true
    }
})</script>';
            $this->render('admin/dictionary/create_view');
        }
        else {
            $word = strtolower($this->input->post('word'));
            $root_word = trim($this->input->post('root_word'), '"');
            $noise_word = (null !== $this->input->post('noise_word')) ? '1' : '0';

            $insert_data = array();
            $insert_data['word'] = $word;
            if (strlen($root_word) > 0)
            {
                $root = $this->dictionary_model->where(array('word' => $root_word))->get();
                if ($root === FALSE)
                {
                    $root_id = $this->dictionary_model->insert(array('word' => $root_word));
                }
                else {
                    $root_id = $root->id;
                }
                $insert_data['parent_id'] = $root_id;
            }
            else
            {
                $insert_data['parent_id'] = '0';
            }
            $insert_data['noise'] = $noise_word;
            $insert_data['verified'] = '1';
            if ($word_id = $this->dictionary_model->insert($insert_data))
            {
                $this->postal->add('The word was inserted','success');
            }
            else
            {
                $this->postal->add('Couldn\'t insert word','error');
            }

            echo '<script>window.close();</script>';
        }
    }

    public function autosuggest($q = NULL)
    {
        if(isset($_GET['q']))
        {
            $query = $_GET['q'];
            $data = array();
            if($words = $this->dictionary_model->where(array('parent_id'=>'0'))->where('word','like',$query)->get_all())
            {
                foreach ($words as $word) {
                    $data[] = $word->word;
                }
            }
            $data = json_encode($data);
            echo $data;
        }
    }

    public function edit($word_id)
    {
        $this->load->model('dictionary_model');
        $word = $this->dictionary_model->get($word_id);
        $this->data['root_word'] = '';
        if($word->parent_id!=0 && ($root_word = $this->dictionary_model->get($word->parent_id)))
        {
            $this->data['root_word'] = $root_word->word;
        }
        $this->data['word'] = $word;
        $rules = $this->dictionary_model->rules;
        $this->form_validation->set_rules($rules['update']);
        if($this->form_validation->run()===FALSE)
        {
            $this->data['before_body'] = '
<script>
window.onunload = refreshParent;
function refreshParent()
{
window.opener.location.reload();
}
$(\'#root_word_options\').textext({
    plugins : \'autocomplete ajax\',
    ajax : {
        url : \''.site_url('admin/dictionary/autosuggest/?').'\',
        dataType : \'json\',
        cacheResults : true
    }
})</script>';
            $this->render('admin/dictionary/edit_view');
        }
        else
        {
            $the_word = $this->input->post('word');
            $word_id = $this->input->post('word_id');
            $root_word = trim($this->input->post('root_word'),'"');
            $noise_word = $this->input->post('noise_word');
            $this->load->model('dictionary_model');
            $update_data = array();
            $update_data['word'] = $the_word;
            $update_data['verified'] = '1';
            $update_data['noise'] = $noise_word;
            if(strlen($root_word)>0)
            {
                $root = $this->dictionary_model->where(array('word'=>$root_word))->get();
                if($root===FALSE)
                {
                    $root_id = $this->dictionary_model->insert(array('word'=>$root_word));
                }
                else
                {
                    $root_id = $root->id;
                }
                if($this->dictionary_model->where('parent_id',$word_id)->get_all())
                {
                    $this->dictionary_model->where('parent_id',$word_id)->update(array('parent_id'=>$root_id));
                }
                $update_data['parent_id'] = $root_id;
            }
            else
            {
                $update_data['parent_id'] = '0';
            }
            if($this->dictionary_model->update($update_data,$word_id))
            {
                $this->postal->add('The word was updated successfuly','success');
            }
            else
            {
                $this->postal->add('Couldn\'t update word','error');
            }
            //$letter = substr($the_word, 0, 1);
            echo '<script>window.close();</script>';
            //redirect('admin/dictionary/index/'.$language_slug,'refresh');
        }

        //redirect('admin/dictionary','refresh');
    }

    public function delete($word_id)
    {
        $this->load->model('dictionary_model');
        $word = $this->dictionary_model->get($word_id);
        $the_word = $word->word;
        $child_words = $this->dictionary_model->where('parent_id', $word->id)->update(array('parent_id' => '0', 'verified' => '0'));
        $deleted = $this->dictionary_model->delete($word->id);
        $this->postal->add('The word ' . $the_word . ' was deleted. Also there were ' . $child_words . ' that had the status changed','success');
        redirect('admin/dictionary');
    }
}