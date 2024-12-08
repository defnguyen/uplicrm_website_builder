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
                        <?php $attrs = (isset($plan) ? array('required' => true) : array('autofocus' => true, 'required' => true)); ?>
                        <?php $value = (isset($plan) ? $plan->name : ''); ?>
                        <?php echo render_input('name', 'service_plan_name', $value, 'text', $attrs); ?>
                        <?php $value = (isset($plan) ? $plan->description : ''); ?>
                        <?php echo render_textarea('description', 'service_plan_description', $value); ?>
                        <?php $value = (isset($plan) ? $plan->price : ''); ?>
                        <?php echo render_input('price', 'service_plan_price', $value, 'number', array('required' => true)); ?>
                        <?php $value = (isset($plan) ? $plan->type : ''); ?>
                        <?php $types = [['id' => 'trial', 'name' => _l('trial')], ['id' => 'non_trial', 'name' => _l('non_trial')]]; ?>
                        <?php echo render_select('type', $types, array('id', 'name'), 'service_plan_type', $value); ?>
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