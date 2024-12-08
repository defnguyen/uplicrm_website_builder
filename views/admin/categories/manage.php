<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <div class="_buttons">
                        <a href="<?php echo admin_url('uplicrm_website_builder/category'); ?>" class="btn btn-primary pull-left display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_category'); ?>
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="panel_s tw-mt-2 sm:tw-mt-4">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <div class="panel-table-full">
                            <?php render_datatable([
                                _l('category_image'),
                                _l('category_name'),
                                _l('category_slug'),
                                _l('category_description'),
                                _l('category_status'),
                                _l('options')
                            ], 'uplicrm-website-builder-categories'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-uplicrm-website-builder-categories', admin_url + 'uplicrm_website_builder/categories_table', [5], [5]);
    });
</script>
</body>

</html>