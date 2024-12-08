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
                        <?php echo form_open_multipart(admin_url('uplicrm_website_builder/categories')); ?>
                        <?php $attrs = (isset($category) ? array('required' => true) : array('autofocus' => true, 'required' => true)); ?>
                        <?php $value = (isset($category) ? $category->name : ''); ?>
                        <?php echo render_input('name', 'category_name', $value, 'text', $attrs); ?>
                        <?php $value = (isset($category) ? $category->description : ''); ?>
                        <?php echo render_textarea('description', 'category_description', $value); ?>
                        <?php $value = (isset($category) ? $category->image : ''); ?>
                        <?php echo render_input('image', 'category_image', $value, 'file'); ?>
                        <?php if (isset($category) && $category->image != '') : ?>
                            <div class="form-group">
                                <img src="<?php echo upli_templates_url('categories/' . $category->image); ?>" alt="<?php echo $category->name; ?>" width="200">
                            </div>
                        <?php endif; ?>
                        <?php $value = (isset($category) ? $category->status : ''); ?>
                        <?php $statuses = [['id' => 'active', 'name' => _l('active')], ['id' => 'inactive', 'name' => _l('inactive')]]; ?>
                        <?php echo render_select('status', $statuses, array('id', 'name'), 'category_status', $value); ?>
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