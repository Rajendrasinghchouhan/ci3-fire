<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php echo form_open_multipart('', array('role'=>'form')); ?>
	<div class="row">
        
        <div class="form-group col-sm-12">
            <?php echo form_label(lang('post input postname'), 'postname', array('class'=>'control-label')); ?>
            <span class="required">*</span>
            <?php $populateData = (!empty($editData['post_title']) && isset($editData['post_title']) ? $editData['post_title'] : (!empty($this->input->post('posttitle')) ? $this->input->post('posttitle') : '' )) ?>
            <?php echo form_input(array('name'=>'posttitle','id'=>'postname','class'=>'form-control','value'=> $populateData)); ?>
            <?php $errorMsg = (!empty($fielderror) && isset($fielderror['posttitle']) ? $fielderror['posttitle'] : '')?>
            <span class="text-danger"><?php echo $errorMsg;?></span>
        </div>
    
    	<div class="form-group col-sm-12">
            <?php echo form_label(lang('post input postdesc'), 'postdesc', array('class'=>'control-label')); ?><br/>
            <?php $populateData = (!empty($editData['post_description']) && isset($editData['post_description']) ? $editData['post_description'] : (!empty($this->input->post('postdescription')) ? $this->input->post('postdescription') : '' )) ?>
            <?php $field_data['name']  ='postdescription' ;
            	  $field_data['id']    = 'postdesc';
            	  $field_data['class']    = 'form-control';
            	  $field_data['value'] = $populateData;
           	?>
            <?php echo form_textarea($field_data); ?>
        </div>
        <div class="form-group col-sm-12">
        	<?php echo form_label(lang('post input postimg'), 'postimg', array('class'=>'control-label')); ?><br/>
        	<?php

        	 if(!empty($editData['post_image']) && isset($editData['post_image'])) {?>
        	<img src="<?php echo base_url('assets/images/post_image/'.$editData['post_image'])?>" style="width:80px;height:80px;">
        <?php } else { }?>
        	<?php echo form_upload(array('name'=>'image')); ?>
        </div>
        <div class="form-group col-sm-12">
        	<?php echo form_label(lang('post input status'), 'status', array('class'=>'control-label')); ?><span class="required">*</span><br/>
        	<?php 
        	
        	if(!empty($editData['post_status']) && isset($editData['post_status']))
        	{
        		$posted = $editData['post_status']=='1';
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

        	<?php echo form_radio(array('name'=>'status','value'=>'1','checked'=>$posted));?>Activate<br/>

        	<?php if(!empty($editData['post_status']) && isset($editData['post_status']))
        	{
        		$draft = $editData['post_status']=='2';
        	}
        	elseif(!empty($this->input->post('status'))) 
        	{
        		$draft	= $this->input->post('status')=='2';
        	}
        	else 
        	{
        		$draft="";	
        	} ?>

        	<?php echo form_radio(array('name'=>'status','value'=>'2','checked'=>$draft));?>Inactive<br/>

        	<?php if(!empty($editData['post_status']) && isset($editData['post_status']))
        	{
        		$deleted = $editData['post_status']=='3';
        	}
        	elseif(!empty($this->input->post('status'))) 
        	{
        		$deleted = $this->input->post('status')=='3';
        	}
        	else 
        	{
        		$deleted="";	
        	} ?>
        	<?php echo form_radio(array('name'=>'status','value'=>'3','checked'=>$deleted));?>Deleted<br/>
        	<?php $errorMsg = (!empty($fielderror) && isset($fielderror['status']) ? $fielderror['status'] : '')?>
        	<span class="text-danger"><?php echo $errorMsg;?></span>
        </div>
        <div class="row pull-right">
	        <a class="btn btn-link" href="<?php echo $cancel_url; ?>"><?php echo lang('core button cancel'); ?></a>
	        <?php $saveUpdate = (!empty($editData['id']) && isset($editData['id']) ? 'update' : 'save');?>
	        <input type="submit" name="<?php echo $saveUpdate;?>" class="btn btn-success" value="<?php echo ucfirst($saveUpdate);?>">
	    </div>
    </div>
    </div>	
<?php echo form_close(); ?>
