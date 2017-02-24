<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Create content type</h1>
            <?php echo form_open();?>
            <div class="form-group">
                <?php
                echo form_label('Table field name','table_field');
                echo form_error('table_field');
                echo form_input('table_field',set_value('table_field'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Field position','field_position');
                echo form_error('field_position');
                echo form_dropdown('field_position', $field_positions, set_value('field_position'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Column name','table_column');
                echo form_error('table_column');
                echo form_dropdown('table_column',$table_fields,set_value('table_column'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Field type','type');
                echo form_error('type');
                echo form_dropdown('type',$mysql_types, set_value('type'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Length / Values','length');
                echo form_error('length');
                echo form_input('length',set_value('length'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Default value','default');
                echo form_error('default');
                echo form_input('default',set_value('default'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Attributes','attributes');
                echo form_error('attributes');
                echo form_dropdown('attributes',$attributes,set_value('attributes'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Accept NULL','null');
                echo form_error('null');
                echo form_input('null',set_value('null',0),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Index','index');
                echo form_error('index');
                echo form_dropdown('index', $mysql_index, set_value('index'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Label name','label_name');
                echo form_error('label_name');
                echo form_input('label_name',set_value('label_name'),'class="form-control"');
                ?>
            </div><div class="form-group">
                <?php
                echo form_label('Input type','input_type');
                echo form_error('input_type');
                echo form_dropdown('input_type',$input_types,set_value('input_type'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Insert validation rules','insert_validation_rules');
                echo form_error('insert_validation_rules');
                echo form_input('insert_validation_rules',set_value('insert_validation_rules'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Update validation rules','update_validation_rules');
                echo form_error('update_validation_rules');
                echo form_input('update_validation_rules',set_value('update_validation_rules'),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Input position','input_position');
                echo form_error('input_position');
                echo form_input('input_position',set_value('input_position'),'class="form-control"');
                ?>
            </div>
            <?php
            echo form_submit('submit', 'Add content type', 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/content-types/', 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>