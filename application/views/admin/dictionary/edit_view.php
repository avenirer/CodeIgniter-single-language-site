<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Edit word</h1>
            <?php echo form_open();?>
            <div class="form-group">
                <?php
                echo form_label('Word','word');
                echo form_error('word');
                echo form_input('word',set_value('word',$word->word),'class="form-control"');
                ?>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Root word','root_word');
                echo form_error('root_word');
                echo form_input('root_word',set_value('root_word',$root_word),'id="root_word_options" autofocus autocomplete="off"');
                ?>
                <div class="checkbox">
                    <label>
                        <?php
                        echo form_error('noise_word');
                        echo form_checkbox('noise_word','1',$word->noise).' Noise word';
                        ?>
                    </label>
                </div>
            </div>
            <?php echo form_error('word_id');?>
            <?php echo form_hidden('word_id',set_value('word_id',$word->id));?>
            <?php
            $submit_button = 'Edit word';
            echo form_submit('submit', $submit_button, 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/dictionary', 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>