<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <div class="_buttons">
                        <a href="<?php echo admin_url('uplicrm_website_builder/template'); ?>" class="btn btn-primary pull-left display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_template'); ?>
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="panel_s tw-mt-2 sm:tw-mt-4">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable([
                            _l('template_image'),
                            _l('template_name'),
                            _l('template_associated_category'),
                            _l('template_description'),
                            _l('template_price'),
                            _l('template_source_type'),
                            _l('template_created_at'),
                            _l('options')
                        ], 'uplicrm-website-builder-templates'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-uplicrm-website-builder-templates', admin_url + 'uplicrm_website_builder/templates_table', [7], [7]);
    });

    function startBuildWebsite(template_id) {
        // Add loading animation or disable button here
        $('body').append('<div class="dt-loader"></div>');

        $.ajax({
            url: admin_url + 'uplicrm_website_builder/start_build_website?id=' + template_id,
            type: 'POST', // Or GET, depending on your controller method
            data: {
                // Add any additional data needed by the controller, e.g., a CSRF token
                <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',

                // If you still need to send subdomain and plan_id (decide in the modal later):
                //subdomain: subdomain, // Get the value from somewhere (e.g., an input field)
                //plan_id: plan_id    // Get the value from somewhere
            },
            dataType: 'json', // Expect JSON response from the server
            success: function(response) {
                $('.dt-loader').remove(); // Remove the loading animation
                // Handle success/failure based on the response
                if (response.success) {
                    alert_float('success', response.message);
                    $('.table-uplicrm-website-builder-templates').DataTable().ajax.reload(); // Refresh DataTable

                } else {
                    alert_float('danger', response.message);
                }
            },
            error: function(xhr, status, error) {
                $('.dt-loader').remove(); // Remove loading animation even on error
                alert_float('danger', 'Error: ' + error); // Show an error message
            }
        });
    }
</script>
</body>
</html>