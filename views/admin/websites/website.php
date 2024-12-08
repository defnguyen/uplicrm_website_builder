<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo $title; ?>
                        </h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open($this->uri->uri_string()); ?>
                        <?php $attrs = (isset($website) ? array('required' => true) : array('autofocus' => true, 'required' => true)); ?>
                        <?php $value = (isset($website) ? $website->name : ''); ?>
                        <?php echo render_input('name', 'website_name', $value, 'text', $attrs); ?>
                        <?php $value = (isset($website) ? $website->template_id : ''); ?>
                        <?php echo render_select('template_id', $templates, array('id', 'name'), 'template', $value); ?>
                        <?php $value = (isset($website) ? $website->subdomain : ''); ?>
                        <?php echo render_input('subdomain', 'website_subdomain', $value); ?>
                        <?php $value = (isset($website) ? $website->plan_id : ''); ?>
                        <?php echo render_select('plan_id', $plans, array('id', 'name'), 'website_plan', $value); ?>
                        <?php $value = (isset($website) ? $website->status : ''); ?>
                        <?php $statuses = [['id' => 'active', 'name' => _l('active')], ['id' => 'inactive', 'name' => _l('inactive')]]; ?>
                        <?php echo render_select('status', $statuses, array('id', 'name'), 'website_status', $value); ?>
                        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>