<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Edit item "<?php echo $item->title;?>"</h1>
            <?php echo form_open();?>
            <div class="form-group">
                <?php
                echo form_label('Parent item','parent_id');
                echo form_dropdown('parent_id',$parent_items,set_value('parent_id',(isset($item->parent_id) ? $item->parent_id : '0')),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Title','title');
                echo form_error('title');
                echo form_input('title',set_value('title',$item->title),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('URL','url');
                echo form_error('url');
                echo form_input('url',set_value('url',$item->url),'class="form-control"');
                ?>
                <div class="checkbox">
                    <label>
                        <?php
                        echo form_error('absolute_path');
                        echo form_checkbox('absolute_path','1',$item->absolute_path).' Absolute path';
                        ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Order','order');
                echo form_error('order');
                echo form_input('order',set_value('order', $item->order),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Additional styling','styling');
                echo form_error('styling');
                echo form_input('styling',set_value('styling', $item->styling),'class="form-control" placeholder="class=\'item\' style=\'border: 1px solid silver\'"');
                ?>
            </div>
            <?php echo form_error('item_id');?>
            <?php echo form_hidden('item_id',$item->id);?>
            <?php
            $submit_button = 'Edit item';
            echo form_submit('submit', $submit_button, 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/menus/items/'.$item->menu_id, 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>