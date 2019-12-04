<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Admin_Controller {

	function __construct()
    { 
        parent::__construct();
    	$this->add_css_theme('summernote.css')
			->add_css_theme('jquery.dataTables.min.css')
			->add_css_theme('dropzone.css')
			->add_js_theme('dropzone.js')
			->add_js_theme('jquery.dataTables.min.js')
			->add_js_theme('page_custome_script.js',TRUE)
			->add_js_theme('summernote.min.js')
			->add_js_theme('settings_i18n.js', TRUE);

			
        // load the language files
        $this->lang->load('users');

        // load the users model
        $this->load->model('page_model');
		//load the form validation
		$this->load->library('form_validation');
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/page'));
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
    	$this->set_title(lang('page title page_list'));

        $data = $this->includes;
      
        $content_data = array(
            //'cancel_url' => "/admin",
        );

        // load views
        $data['content'] = $this->load->view('admin/page/list', $content_data, TRUE);
        $this->load->view($this->template, $data);	
    }

    function form($id=NULL)
    {	
    	$fetchData = "";$fielderror="";
    	if($id)
    	{
    		$fetchData = $this->page_model->getfetch($id);
    	}
    	$title = $this->page_model->parentpage();
		//p($title);
    	$this->form_validation->set_rules('pagetitle', 'Title', 'trim|required');
    	$this->form_validation->set_rules('status', 'Status', 'required');

    	if($this->form_validation->run() != false)
		{	
			
			$content = array();
			$content['page_title'] = $this->input->post('pagetitle');
			$content['page_slug'] = $this->slugify($content['page_title']);
			$content['page_description'] = $this->input->post('pagedescription');
			$content['page_image'] = $this->input->post('page_images');
			//image work here
			$content['parent_page'] = $this->input->post('parentpage');
			$content['status'] = $this->input->post('status');
			if($this->input->post('save'))
			{	
				$this->page_model->insert($content);
				$this->session->set_flashdata('success', 'Page Inserted Successfully !');
				redirect(base_url('admin/page'));
			}
			if($this->input->post('update'))
			{
				$this->page_model->update($content,$id);
				$this->session->set_flashdata('success', 'Page Updated Successfully !');
				redirect(base_url('admin/page'));
			}
		}
		else
		{
			$fielderror = $this->form_validation->error_array();
		}
		if($id)
		{
    		$this->set_title(lang('page title page_edit'));
		}
		else
		{
			$this->set_title(lang('page title page_add'));
		}
		$data = $this->includes;
        $content_data = array(
            'parentTitle'   => $title, 
            'cancel_url' => "/ci3-fire/admin/page",
            'fetchData' => $fetchData,
            'fielderror' => $fielderror,
        );
        
        // load views
        $data['content'] = $this->load->view('admin/page/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }
    //$slug = $this->slugify($name);

	public function slugify($text)
	{
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, '-');

	  // remove duplicate -
	  $text = preg_replace('~-+~', '-', $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) {
	    return 'n-a';
	  }

	  return $text;
	}

	function uploadfile()
	{	//print_r($_FILES);exit;
		$image = array();
		//$new_name = time().$_FILES;
		$name = $_FILES['file']['name'];
		//print_r($name);exit;
		
		$config['upload_path'] = FCPATH . "assets\images\page_image";
		$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
		//$confi['']
		//$config['max_size']             = 100;
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
        //$config['overwrite']            = true;
        $this->load->library('upload', $config);

	    if (!$this->upload->do_upload('file')) {

		    $image['msg']= 'error';

		    echo json_encode($image); 
		}
		else
		{
			// print_r($config['upload_path']);
			// exit();
			$file = $this->upload->data();
			$success = array(
            'status'=>true,
            'messages'=>'image upload Success',
            'name'          => $name,
            'original_name' => $name,
        	); 
        	echo json_encode($success);

  		}
	}
	function deletefile()
	{
		
		$fileName = $_POST['filename'];
		$path = FCPATH . "assets/images/page_image/$fileName";
		unlink($path);
		$success = array(
            'status'=>true,
            'messages'=>'image Deleted Success',
            'name' => $fileName,
        	); 
        echo json_encode($success);
	}

	function page_list()
     { 
     	$list = $this->page_model->get_page();
     	$data = array();
        $no = $_POST['start'];
        foreach ($list as $page) 
        {	
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ucfirst($page->page_title);
            $row[] = ucfirst($page->page_description);
            $split = explode(",",$page->page_image);
            //print_r($split);exit;
            if(!empty($split[0]))
            {	
            	$row[] = "<img src='".base_url('assets/images/page_image/'.$split[0])."' style='width:60px;height:60px;border-radius:100%;'>";
            }
            else
            {
             $row[] = "<img src='".base_url('assets/images/page_image/defaultpage.png')."' style='width:60px;height:60px;border-radius:100%;'>";
            }
            if($page->status=='1')
            {
            	$statusName = 'active';
            } 
            elseif ($page->status=='2') {
            	$statusName = 'inactive';
            }
            elseif ($page->status=='3')
            {
            	$statusName = 'drafted';	
            }
            $row[] = ucfirst($statusName);
            $row[] = date("Y-M-d",strtotime($page->page_added));
            $row[] = '<a  href="'.base_url("admin/page/form/".$page->id).'" data-toggle="modal" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil"></span></a> 
            		<a onclick="return confirm(\'Are you sure want to Delete ! \')" href="'.base_url("admin/page/delete/".$page->id).'" data-toggle="modal" class="btn btn-danger btn-xs" ><span class="glyphicon glyphicon-trash"></span></a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->page_model->count_all(),
                        "recordsFiltered" => $this->page_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
     }

      function delete($id=NULL)
     {	
     	$findImage = $this->page_model->getimage($id);
     	//print_r($findImage);exit;
     	if(!empty($findImage))
     	{
     		$path = FCPATH . "assets/images/page_image/$findImage";
			//print_r($path);die;
			unlink($path);
     	}
     	$this->page_model->delete($id);
     	$this->session->set_flashdata('success', 'Page Deleted Successfully !');
		redirect(base_url('admin/page'));
     }

    function updatedDelete()
    {
     	$fileName = $_POST['filename'];
     	$fileid = $_POST['id'];
     	$path = FCPATH . "assets\images\page_image\\".$fileName;
     	unlink($path);
     	$imgName = $this->page_model->checkimg($fileid);

     	if(strpos($imgName['page_image'] , $fileName.','))
     	{
     		$result = str_replace($fileName.',', '', $imgName['page_image']);
     	}
     	else
     	{
			$result = str_replace(','.$fileName, "", $imgName['page_image']);	    		
     	}
     	$this->page_model->fileupdate($fileid,$result);
		$success = array(
            'status'=>true,
            'messages'=>'image Deleted Success',
            'name' => $fileName,
        	); 
        echo json_encode($success);
    }
}