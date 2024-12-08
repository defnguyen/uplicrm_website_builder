<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-margin">
    <?php echo $title; ?>
</h4>
<hr class="hr-panel-heading" />
<ul class="list-group">
    <?php foreach ($logs as $log) : ?>
        <li class="list-group-item">
            <?php echo $log->description; ?>
            <span class="pull-right text-muted"><?php echo time_ago($log->date); ?></span>
        </li>
    <?php endforeach; ?>
</ul>