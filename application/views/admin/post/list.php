<?php defined('BASEPATH') OR exit('No direct script access allowed');
	if($this->session->flashdata('success')) {
		echo '<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" aria-label="close" data-dismiss="alert">&times;</button>
		  		<strong>Success!</strong> '.$this->session->flashdata("success").'
			</div>';
	}
 ?>
<a href="<?php echo base_url('admin/post/form');?>" class="btn btn-primary" >Add Post</a>
<div class="panel-heading"> 
    <div class="row">
    	<table id="table" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

