<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo anchor('admin/content-types/create','Add content type', 'class="btn btn-primary"');
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
            echo '<th>Name</th>';
            echo '<th>Parent</th>';
            echo '<th>Plural</th>';
            echo '<th>Table name</th>';
            echo '<th>Operations</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if(!empty($content_types))
            {

                foreach($content_types as $type)
                {
                    echo '<tr>';
                    echo '<td>'.$type->id.'</td>';
                    echo '<td>'.$type->name.'</td>';
                    echo '<td>'.$parents[$type->parent_id].'</td>';
                    echo '<td>'.$type->plural.'</td>';
                    echo '<td>'.$type->table_name.'</td>';
                    echo '<td>';
                    echo anchor('admin/content-types/edit/'.$type->id,'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>');
                    echo ' '.anchor('admin/content-types/table-definition/'.$type->id, '<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>');
                    echo ' '.anchor('admin/content-types/delete/'.$type->id,'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>','onclick="return confirm(\'Are you sure you want to delete?\')"');
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