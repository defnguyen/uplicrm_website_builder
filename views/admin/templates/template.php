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
                        <?php echo form_open_multipart(admin_url('uplicrm_website_builder/templates')); ?>
                        <?php $attrs = (isset($template) ? array('required' => true) : array('autofocus' => true, 'required' => true)); ?>
                        <?php $value = (isset($template) ? $template->name : ''); ?>
                        <?php echo render_input('name', 'template_name', $value, 'text', $attrs); ?>
                        <?php $value = (isset($template) ? $template->subdomain : ''); ?>
                        <?php echo render_input('subdomain', 'website_subdomain', $value); ?>
                        <?php $value = (isset($template) ? $template->description : ''); ?>
                        <?php echo render_textarea('description', 'template_description', $value); ?>
                        <?php $value = (isset($template) ? $template->image : ''); ?>
                        <?php echo render_input('image', 'template_image', $value, 'file'); ?>
                        <?php if (isset($template) && $template->image != '') : ?>
                            <div class="form-group">
                                <img src="<?php echo upli_templates_url($template->image); ?>" alt="<?php echo $template->name; ?>" width="200">
                            </div>
                        <?php endif; ?>
                        <?php $value = (isset($template) ? $template->price : ''); ?>
                        <?php echo render_input('price', 'template_price', $value, 'number', array('required' => true)); ?>
                        <?php $value = (isset($template) ? $template->source_code : ''); ?>
                        <?php echo render_input('source_code', 'template_source_code', $value, 'file'); ?>
                        <?php $value = (isset($template) ? $template->source_type : ''); ?>
                        <?php $source_types = [['id' => 'wordpress', 'name' => 'WordPress'], ['id' => 'codeigniter', 'name' => 'CodeIgniter'], ['id' => 'laravel', 'name' => 'Laravel']]; ?>
                        <?php echo render_select('source_type', $source_types, array('id', 'name'), 'template_source_type', $value); ?>
                        <?php $value = (isset($template) ? $template->file_config_path : ''); ?>
                        <?php echo render_input('file_config_path', 'template_file_config_path', $value); ?>
                        <?php $value = (isset($template) ? $template->config_content : ''); ?>
                        <?php echo render_textarea('config_content', 'template_config_content', $value); ?>
                        <?php $value = (isset($template) ? $template->category_id : ''); ?>
                        <?php echo render_select('category_id', $categories, array('id', 'name'), 'template_associated_category', $value); ?>
                        <?php if (isset($template)) : ?>
                            <input type="hidden" name="is_created" value="<?php echo $template->is_created; ?>">
                        <?php endif; ?>
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