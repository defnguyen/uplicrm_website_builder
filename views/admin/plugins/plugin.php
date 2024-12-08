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
                        <?php echo form_open_multipart($this->uri->uri_string()); ?>
                        <?php $attrs = (isset($plugin) ? array('required' => true) : array('autofocus' => true, 'required' => true)); ?>
                        <?php $value = (isset($plugin) ? $plugin->name : ''); ?>
                        <?php echo render_input('name', 'plugin_name', $value, 'text', $attrs); ?>
                        <?php $value = (isset($plugin) ? $plugin->description : ''); ?>
                        <?php echo render_textarea('description', 'plugin_description', $value); ?>
                        <?php $value = (isset($plugin) ? $plugin->price : ''); ?>
                        <?php echo render_input('price', 'plugin_price', $value, 'number', array('required' => true)); ?>
                        <?php $value = (isset($plugin) ? $plugin->source_code : ''); ?>
                        <?php echo render_input('source_code', 'plugin_source_code', $value, 'file'); ?>
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