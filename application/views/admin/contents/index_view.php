<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo anchor('admin/contents/create/'.$content_type->id,'Add '.str_replace('_',' ',$content_type->name),'class="btn btn-primary"');
            if($content_type->name == 'post')
            {
                echo ' '.anchor('admin/contents/index/category','Categories', 'class="btn btn-success"');
                echo ' '.anchor('admin/contents/create/category','Add category','class="btn btn-primary"');
            }

            if($content_type->name == 'event')
            {
                echo ' '.anchor('admin/contents/index/event_type','Event types', 'class="btn btn-success"');
                echo ' '.anchor('admin/contents/create/event_type','Add event type','class="btn btn-primary"');
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            echo '<pre>';
            print_r($definitions);
            echo '</pre>';
            echo '<table class="table table-hover table-bordered table-condensed">';
            echo '<thead>';
            echo '<tr>';
            foreach($definitions as $definition) {
                echo '<th>' . $definition->input_label . '</th>';
            }
            echo '<th>Operations</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if(!empty($contents))
            {
                foreach($contents as $content)
                {
                    echo '<tr>';
                    foreach($definitions as $definition) {
                        echo '<td>' . $content->{$definition->table_field} . '</td>';
                    }
                    echo '<td>';
                    echo anchor('admin/contents/edit/'.$content_type->id.'/'.$content->id,'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>');
                    echo ' '.anchor('admin/contents/delete/'.$content_type->id.'/'.$content->id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                    /*
                    $rakestyle = '';
                    if($content->rake!='1') $rakestyle = ' style="color:red;"';
                    echo ' '.anchor('admin/rake/analyze/'.$content->id,'<span class="glyphicon glyphicon-cog" aria-hidden="true"'.$rakestyle.'></span>');
                    echo ' '.anchor('admin/images/index/'.$content->id,'<span class="glyphicon glyphicon-picture"></span>');
                    $publish = ($content->published=='1') ? 0 : 1;
                    $style = ($content->published=='1') ? '' : ' style="color: red;"';
                    $icon = ($content->published == '1') ? 'up' : 'down';
                    echo ' '.anchor('admin/contents/publish/'.$content->id.'/'.$publish,'<span class="glyphicon glyphicon-thumbs-'.$icon.'" aria-hidden="true"'.$style.'></span>');
                    echo '<br />'.$content->published_at;
                    echo '</td>';*/
                    echo '</tr>';
                }
            }
            echo '</tbody>';
            echo '</table>';
            ?>
        </div>
    </div>
</div>