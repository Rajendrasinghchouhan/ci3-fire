<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends Admin_Controller {

    /**
     * Constructor
     */


    function __construct()
    { 
        parent::__construct();

    
        // load the language files
        $this->lang->load('users');

        // load the users model
        $this->load->model('post_model');
		//load the form validation
		$this->load->library('form_validation');
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/post'));
        define('DEFAULT_LIMIT', $this->settings->per_page_limit);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "last_name");
        define('DEFAULT_DIR', "asc");

        // use the url in session (if available) to return to the previous filter/sorted/paginated list
        if ($this->session->userdata(REFERRER))
        {
            $this->_redirect_url = $this->session->userdata(REFERRER);
        }
        else
        {
            $this->_redirect_url = THIS_URL;
        }
    }
    function index()
    {
    	$this
			->add_css_theme('summernote.css')
			->add_css_theme('jquery.dataTables.min.css')
			->add_js_theme('summernote.min.js')
			->add_js_theme('settings_i18n.js', TRUE)
			->add_js_theme('jquery.dataTables.min.js')
			->add_js_theme('post_custom_script.js')
			->set_title(lang('post title post_list'));

        $data = $this->includes;
      
        $content_data = array(
            //'settings'   => $settings,
            'cancel_url' => "/admin",
        );

        // load views
        $data['content'] = $this->load->view('admin/post/list', $content_data, TRUE);
        $this->load->view($this->template, $data);	
    }
    
    function form($id=NULL)
    { 
    	$getData="";
		
    	$this->form_validation->set_rules('posttitle', 'Title', 'trim|required');
    	$this->form_validation->set_rules('status', 'Status', 'required');
    	if($id)
    	{
    		$getData = $this->post_model->getfetch($id);
    	}
		//print_r($getData);die;
		if($this->form_validation->run() != false)
		{
				//print_r($content);die;
			$content = array();
			$content['post_title'] = $this->input->post('posttitle');
			$content['post_description'] = $this->input->post('postdescription');
			$imageChk = $_FILES['image']['name'];
			//print_r($imageChk);exit;
			if(!empty($imageChk))
			{
				$fetchImage = $this->post_model->checkImage($id);
				if(!empty($fetchImage))
				{
					$path = FCPATH . "assets/images/post_image/$fetchImage";
					//print_r($path);die;
					unlink($path);	
				}
				$config['upload_path'] = FCPATH . "assets\images\post_image";
				$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
				
				$this->load->library('upload', $config);
			    if (!$this->upload->do_upload('image')) {
		    			
				    $error = array('error' => $this->upload->display_errors());
				    
				    $this->session->set_flashdata('error',$error['error']);
				    //redirect(base_url('admin/products/form'),'refresh');
				}
				$file = $this->upload->data();
	    		//echo '<pre>';print_r($file);die();
				$content['post_image'] = $file['file_name'];
			}
			//$config['max_size']  = '2048000';
		    //$config['max_width']  = '1024';
		    //$config['max_height']  = '768';
		    //$config['overwrite'] = true;
		    
			$content['post_status'] = $this->input->post('status');
			if($this->input->post('save'))
			{
				$this->post_model->insert($content);			
				$this->session->set_flashdata('success', 'Post Inserted Successfully !');
				redirect(base_url('admin/post'));
			}

			if($this->input->post('update'))
			{
				$this->post_model->update($id,$content);
				$this->session->set_flashdata('success', 'Post Updated Successfully !');
				redirect(base_url('admin/post'));				
			}
		}
		else
		{
			$fielderror = $this->form_validation->error_array();
			//print_r($fielderror);die;
		}
    	
    	$this
			->add_css_theme('summernote.css')
			->add_js_theme('summernote.min.js')
			->add_js_theme('settings_i18n.js', TRUE);
			if($id)
			{
			 $this->set_title(lang('post title post_edit'));
			}
			else
			{
				$this->set_title(lang('post title post_add'));	
			}
			

        $data = $this->includes;
        $content_data = array(
            //'settings'   => $settings,
            'cancel_url' => "/ci3-fire/admin/post",
            'fielderror' => $fielderror,
            'editData' => $getData,
        );

        // load views
        $data['content'] = $this->load->view('admin/post/form', $content_data, TRUE);
        $this->load->view($this->template, $data);	
    }
     function post_list()
     {
     	$list = $this->post_model->get_post();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $posts) 
        {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ucfirst($posts->post_title);
            $row[] = ucfirst($posts->post_description);
            if($posts->post_image)
            {
            	$row[] = "<img src='".base_url('assets/images/post_image/'.$posts->post_image)."' style='width:60px;height:60px;border-radius:100%;'>";
            }
            else
            {
             $row[] = "<img src='".base_url('assets/images/post_image/default_post.jpg')."' style='width:60px;height:60px;border-radius:100%;'>";
            }
            if($posts->post_status=='1')
            {
            	$statusName = 'posted';
            } 
            elseif ($posts->post_status=='2') {
            	$statusName = 'drafted';
            }
            elseif ($posts->post_status=='3')
            {
            	$statusName = 'deleted';	
            }
            $row[] = ucfirst($statusName);
            $row[] = date("Y-M-d",strtotime($posts->post_added));
            $row[] = '<a  href="'.base_url("admin/post/form/".$posts->id).'" data-toggle="modal" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil"></span></a> 
            		<a onClick="return confirm(\'Are you sure want to Delete ! \')" href="'.base_url("admin/post/delete/".$posts->id).'" data-toggle="modal" class="btn btn-danger btn-xs" ><span class="glyphicon glyphicon-trash"></span></a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->post_model->count_all(),
                        "recordsFiltered" => $this->post_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
     }

     function delete($id=NULL)
     {
     	$findImage = $this->post_model->getimage($id);
     	//print_r($findImage);exit;
     	if(!empty($findImage))
     	{
     		$path = FCPATH . "assets/images/post_image/$findImage";
			//print_r($path);die;
			unlink($path);
     	}
     	$this->post_model->delete($id);
     	$this->session->set_flashdata('success', 'Post Deleted Successfully !');
		redirect(base_url('admin/post'));
     }
}