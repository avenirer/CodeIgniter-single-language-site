<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <h1>Add date for <?php echo str_replace('_',' ',$content->content_type);?></h1>
            <?php echo form_open();?>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <?php
                        echo form_label('Start', 'start_datetime');
                        echo form_error('start_datetime');
                        ?>
                        <div class="input-group date datetimepicker">
                            <?php
                            echo form_input('start_datetime', set_value('start_datetime', $content->start_dt), 'class="form-control"');
                            ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <?php
                        echo form_label('End', 'end_datetime');
                        echo form_error('end_datetime');
                        ?>
                        <div class="input-group date datetimepicker">
                            <?php
                            echo form_input('end_datetime', set_value('end_datetime', $content->end_dt), 'class="form-control"');
                            ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php
                echo form_label('Title','title');
                echo form_error('title');
                echo form_input('title',set_value('title',$content->title),'class="form-control"');
                ?>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <?php
                        echo form_label('Short title','short_title');
                        echo form_error('short_title');
                        echo form_input('short_title',set_value('short_title',$content->short_title),'class="form-control"');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_label('Teaser','teaser');
                        echo form_error('teaser');
                        echo form_textarea('teaser',set_value('teaser',$content->teaser),'class="form-control"');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_label('Slug','slug');
                        echo form_error('slug');
                        echo form_input('slug',set_value('slug'),'class="form-control"');
                        ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <?php
                        echo form_label('Page title','page_title');
                        echo form_error('page_title');
                        echo form_input('page_title',set_value('page_title',$content->page_title),'class="form-control" placeholder="SEO..."');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_label('Page keywords','page_keywords');
                        echo form_error('page_keywords');
                        echo form_input('page_keywords',set_value('page_keywords',$content->page_keywords),'class="form-control" placeholder="SEO..."');
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_label('Page description','page_description');
                        echo form_error('page_description');
                        echo form_textarea('page_description',set_value('page_description',$content->page_description),'class="form-control" placeholder="SEO..."');
                        ?>
                    </div>
                </div>
            </div>
            <?php
            echo '<div class="panel panel-primary">';
            echo '<div class="panel-heading">Currently active slugs</div>';
            echo '<div class="panel-body">';
            foreach($slugs as $slug)
            {
                echo $slug->url.'<br />';
            }
            echo '</div>';
            echo '</div>';
            ?>
            <div class="form-group">
                <?php
                echo form_label('Content', 'content');
                echo form_error('content');
                echo form_textarea('content', set_value('content', $content->content, false), 'class="form-control editor"');
                ?>
            </div>
            <?php
            echo form_error('content_type');
            echo form_hidden('content_type',$content->content_type);
            echo form_error('id');
            echo form_hidden('id',$content->id);
            $submit_button = 'Edit date';
            echo form_submit('submit', $submit_button, 'class="btn btn-primary btn-lg btn-block"');?>
            <?php echo anchor('/admin/calendar/index/'.$content->content_type.'/'.$content->id, 'Cancel','class="btn btn-default btn-lg btn-block"');?>
            <?php echo form_close();?>
        </div>
    </div>
</div>