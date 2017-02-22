<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Create content type</h1>
            <?php echo form_open();?>
            <div class="form-group">
                <?php
                echo form_label('Name','name');
                echo form_error('name');
                echo form_input('name',set_value('name'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Plural','plural');
                echo form_error('plural');
                echo form_input('plural',set_value('plural'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Table name','table_name');
                echo form_error('table_name');
                echo form_input('table_name',set_value('table_name'),'class="form-control"');
                ?>
            </div>
            <?php
            echo form_submit('submit', 'Add content type', 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/content-types/', 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>