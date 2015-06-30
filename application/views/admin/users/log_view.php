<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="container" style="margin-top:60px;">
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php echo site_url('admin/users');?>" class="btn btn-primary">Back to users</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="margin-top: 10px;">
            <?php
            if(!empty($log))
            {
                echo '<table class="table table-hover table-bordered table-condensed">';
                echo '<tr><td>Date and time</td><td>Message</td></tr>';
                foreach($log as $message)
                {
                    echo '<tr>';
                    echo '<td>'.$message->date_time.'</td>';
                    echo '<td>'.$message->message.'</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            ?>
        </div>
    </div>
</div>