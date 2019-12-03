<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php echo form_open_multipart('', array('role'=>'form')); ?>

<div class="row">    
    <div class="form-group col-sm-12">
    	<?php echo form_label(lang('page input pagename'), 'pagename', array('class'=>'control-label')); ?>
    	<span class="required">*</span>
    	<?php $populateData = (!empty($fetchData['page_title']) && isset($fetchData['page_title']) ? $fetchData['page_title'] : (!empty($this->input->post('pagetitle')) ? $this->input->post('pagetitle') : '' ));
    	?>
    	<?php echo form_input(array('name'=>'pagetitle','id'=>'pagename','class'=>'form-control','value'=> $populateData)); ?>
    	<?php $errorMsg = (!empty($fielderror['pagetitle']) && isset($fielderror['pagetitle']) ? $fielderror['pagetitle'] : '')?>
            <span class="text-danger"><?php echo $errorMsg;?></span>
	</div>
	<div class="form-group col-sm-12">
    	<?php echo form_label(lang('page input pagedesc'), 'pagedesc', array('class'=>'control-label')); ?><br/>
    	<?php $populateData = (!empty($fetchData['page_description']) && isset($fetchData['page_description']) ? $fetchData['page_description'] : (!empty($this->input->post('pagedescription')) ? $this->input->post('pagedescription') : '' ));
    	?>
    	<?php $field_data['name']  ='pagedescription' ;
        	$field_data['id']    = 'pagedesc';
			$field_data['class']    = 'form-control editor';
			$field_data['value'] = $populateData;
        ?>
        <?php echo form_textarea($field_data); ?>
	</div>    
	<div class="form-group col-sm-12">
		<?php echo form_label(lang('page input pageimage'), 'pageimage', array('class'=>'control-label clear')); ?><br/>
        <div class="dropzone clsbox" id="imageupload">

        </div>
	</div>
		<?php
			if(isset($fetchData['id']))
			{
				$break_image = explode(',', $fetchData['page_image']);
	       		if(!empty($break_image)) { 
	       		 	foreach($break_image as $key => $imgvalue ) { ?>
		       		<div class="col-sm-1">
		       			<div class="editImage"> 		
		        			<img src="<?php echo base_url('assets/images/page_image/'.$imgvalue)?>"><br/>
		        			<button type="button" data-uploadimg="<?php echo $imgvalue; ?>" data-id="<?php echo $fetchData['id'];?>" class="btn btn-dark remove_img_btn">Delete</button>
		        		</div>	
		        	</div>
        <?php } } }?>
	<?php $populateData = (!empty($fetchData['page_image']) && isset($fetchData['page_image']) ? $fetchData['page_image'] : '');?>
	<input type="hidden" name="page_images" id="page_images" value="<?=$populateData?>">
	<div class="form-group col-sm-12">
		<?php echo form_label(lang('page input pageselect'), 'pageselect', array('class'=>'control-label')); ?><br/>
		<?php $populateData = (!empty($fetchData['parent_page']) && isset($fetchData['parent_page']) ? $fetchData['parent_page'] : (!empty($this->input->post('parentpage')) ? $this->input->post('parentpage') : '')); ?>
		<select name="parentpage" class="form-control">
			<option value="">Select Parent Page</option>
		<?php foreach($parentTitle as $key=>$value) { 
			$selected = ($value['page_title'] == $populateData) ? "selected" : '' ;  
		?>
			<option <?php echo $selected; ?> value="<?php echo $value['id'];?>"><?php echo $value['page_title'];?></option>
		<?php } ?>
		</select>
	</div>
	<div class="form-group col-sm-12">
		<?php echo form_label(lang('page input pagestatus'), 'pagestatus', array('class'=>'control-label')); ?><span class="required">*</span><br/>
		<?php 
			if(!empty($fetchData['status']) && isset($fetchData['status']))
        	{
        		$posted = $fetchData['status']=='1';
        	}
        	elseif(!empty($this->input->post('status'))) 
        	{
        		$posted	= $this->input->post('status')=='1';
        	}
        	else 
        	{
        		$posted="";	
        	}
		?>
		<?php echo form_radio(array('name'=>'status','value'=>'1','checked'=>$posted));?>Active<br/>
		<?php 
			if(!empty($fetchData['status']) && isset($fetchData['status']))
        	{
        		$posted = $fetchData['status']=='2';
        	}
        	elseif(!empty($this->input->post('status'))) 
        	{
        		$posted	= $this->input->post('status')=='2';
        	}
        	else 
        	{
        		$posted = "";	
        	}
		?>
		<?php echo form_radio(array('name'=>'status','value'=>'2','checked'=>$posted));?>Inactive<br/>
		<?php 
			if(!empty($fetchData['status']) && isset($fetchData['status']))
        	{
        		$posted = $fetchData['status']=='3';
        	}
        	elseif(!empty($this->input->post('status'))) 
        	{
        		$posted	= $this->input->post('status')=='3';
        	}
        	else 
        	{
        		$posted="";	
        	}
		?>
		<?php echo form_radio(array('name'=>'status','value'=>'3','checked'=>$posted));?>Drafted<br/>
		<?php $errorMsg = (!empty($fielderror['status']) && isset($fielderror['status']) ? $fielderror['status'] : '')?>
            <span class="text-danger"><?php echo $errorMsg;?></span>
	</div>
	<div class="row pull-right">
		<a class="btn btn-link" href="<?php echo $cancel_url; ?>"><?php echo lang('core button cancel'); ?></a>
		<?php $saveUpdate = (!empty($fetchData['id']) && isset($fetchData['id']) ? 'update' : 'save');?>
	    <input type="submit" name="<?php echo $saveUpdate;?>" class="btn btn-success" value="<?php echo ucfirst($saveUpdate);?>">
	</div>
<?php echo form_close(); ?>