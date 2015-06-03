<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo anchor('admin/contents/create/'.$content_type,'Add '.$content_type,'class="btn btn-primary"');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            echo '<table class="table table-hover table-bordered table-condensed">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>'.ucfirst($content_type).' title</th>';
            echo '<th>Featured</th>';
            echo '<th>Operations</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if(!empty($contents))
            {

                foreach($contents as $content)
                {
                    echo '<tr>';
                    echo '<td>'.$content->id.'</td>';
                    echo '<td>'.$content->title.'</td>';
                    echo '<td>';
                    if(strlen($content->featured_image)>0)
                    {
                        echo anchor($content->featured_image,'<span class="glyphicon glyphicon-picture"></span>','target="_blank"');
                        echo ' '.anchor('admin/images/delete_featured/'.$content->id,'<span class="glyphicon
                            glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                    }
                    else
                    {
                        echo anchor('admin/images/featured/'.$content->id,'<span class="glyphicon glyphicon-plus"></span>');
                    }
                    echo '</td>';
                    echo '<td>';
                    echo anchor('admin/contents/edit/'.$content->id,'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>');
                    echo ' '.anchor('admin/contents/delete/'.$content->id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
                    $rakestyle = '';
                    if($content->rake!='1') $rakestyle = ' style="color:red;"';
                    echo ' '.anchor('admin/rake/analyze/'.$content->id,'<span class="glyphicon glyphicon-cog" aria-hidden="true"'.$rakestyle.'></span>');
                    echo ' '.anchor('admin/images/index/'.$content->id,'<span class="glyphicon glyphicon-picture"></span>');
                    $publish = ($content->published=='1') ? 0 : 1;
                    $style = ($content->published=='1') ? '' : ' style="color: red;"';
                    $icon = ($content->published == '1') ? 'up' : 'down';
                    echo ' '.anchor('admin/contents/publish/'.$content->id.'/'.$publish,'<span class="glyphicon glyphicon-thumbs-'.$icon.'" aria-hidden="true"'.$style.'></span>');
                    echo '<br />'.$content->published_at;
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