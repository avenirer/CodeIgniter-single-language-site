<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Add <?php echo str_replace('_',' ',$content_type->name);?></h1>
            <?php echo form_open();?>
            <?php
            if($parents !== false) {
                ?>
                <div class="form-group">
                    <?php
                    echo form_label('Parent', 'parent_id');
                    echo form_dropdown('parent_id', $parents, set_value('parent_id', (isset($content->parent_id) ? $content->parent_id : '0')), 'class="form-control"');
                    ?>
                </div>
                <?php
            }
            ?>

            <?php
            echo '<pre>';
            print_r($input_definitions);
            echo '</pre>';

            echo validation_errors();

            foreach($input_definitions as $definition)
            {
                echo '<div class="form-group">';
                echo form_label($definition->input_label,$definition->table_field);
                echo form_error($definition->table_field);
                switch ($definition->input_type)
                {
                    case 'text' :
                        echo form_input($definition->table_field, set_value($definition->table_field),'class="form-control"');
                        break;
                }
                echo '</div>';
            }

            $submit_button = 'Add '.str_replace('_',' ',$content_type->name);
            echo form_submit('submit', $submit_button, 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/contents/index/'.$content_type->id, 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>