<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-margin">
    <?php echo $title; ?>
</h4>
<hr class="hr-panel-heading" />
<div class="row">
    <?php foreach ($plugins as $plugin) : ?>
        <div class="col-md-4">
            <div class="panel_s">
                <div class="panel-body">
                    <h4><?php echo $plugin->name; ?></h4>
                    <p><?php echo $plugin->description; ?></p>
                    <p><b><?php echo _l('price'); ?>:</b> <?php echo app_format_money($plugin->price, get_base_currency()->name); ?></p>
                    <!-- Install Plugin Button (You might need a form here for each plugin) -->
                    <?php foreach ($websites as $website) : ?>
                        <?php if ($website->user_id == get_client_user_id()) : ?>
                            <a href="<?php echo site_url('uplicrm_website_builder_client/install_plugin/' . $website->id . '/' . $plugin->id); ?>" class="btn btn-warning"><?php echo _l('install_plugin') . ' on ' . $website->subdomain; ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>