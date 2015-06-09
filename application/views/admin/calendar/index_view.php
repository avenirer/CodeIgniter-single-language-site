<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo '<h1>'.$content->title.'</h1>';
            echo anchor('admin/calendar/create/'.$content->content_type.'/'.$content->id,'Add date','class="btn btn-primary"');

            if($content->content_type == 'event')
            {
                echo ' '.anchor('admin/contents/index/event','Back to events', 'class="btn btn-success"');
                echo ' '.anchor('admin/contents/edit/'.$content->id,'Edit event','class="btn btn-primary"');
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            echo '<table class="table table-hover table-bordered table-condensed">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Start</th>';
            echo '<th>End</th>';
            echo '<th>Title</th>';
            echo '<th>Operations</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if(!empty($dates))
            {
                foreach($dates as $date)
                {
                    echo '<tr>';
                    echo '<td>';
                    echo $date->start_dt;
                    echo '</td>';
                    echo '<td>';
                    echo $date->end_dt;
                    echo '</td>';
                    echo '<td>';
                    echo $date->title;
                    echo '</td>';
                    echo '<td>';
                    echo anchor('admin/calendar/edit/'.$date->id,'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>');
                    echo ' '.anchor('admin/calendar/delete/'.$date->id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                    $publish = ($date->published=='1') ? 0 : 1;
                    $style = ($date->published=='1') ? '' : ' style="color: red;"';
                    $icon = ($date->published == '1') ? 'up' : 'down';
                    echo ' '.anchor('admin/calendar/publish/'.$date->id.'/'.$publish,'<span class="glyphicon glyphicon-thumbs-'.$icon.'" aria-hidden="true"'.$style.'></span>');
                    echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</tbody>';
            echo '</table>';
            ?>
        </div>
    </div>
</div>