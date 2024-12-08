<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('uplicrm_website_builder/directadmin_account'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_directadmin_account'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable([
                            _l('directadmin_account_username'),
                            _l('directadmin_account_user'),
                            _l('options')
                        ], 'uplicrm-website-builder-directadmin-accounts'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-uplicrm-website-builder-directadmin-accounts', admin_url + 'uplicrm_website_builder/directadmin_accounts_table', [2], [2]);
    });
</script>
</body>

</html>