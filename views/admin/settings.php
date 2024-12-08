<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('uplicrm_website_builder_settings'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open(admin_url('uplicrm_website_builder/save_settings')); ?>
                        <!-- General Settings -->
                        <div class="form-group">
                            <label for="default_domain"><?php echo _l('default_domain'); ?></label>
                            <input type="text" name="default_domain" id="default_domain" class="form-control" value="<?php echo get_option('uplicrm_wb_default_domain'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="default_plan"><?php echo _l('default_plan'); ?></label>
                            <select name="default_plan" id="default_plan" class="form-control">
                                <?php foreach ($plans as $plan) { ?>
                                    <option value="<?php echo $plan->id; ?>" <?php echo (get_option('uplicrm_wb_default_plan') == $plan->id) ? 'selected' : ''; ?>><?php echo $plan->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" name="enable_client_website_creation" id="enable_client_website_creation" <?php echo (get_option('uplicrm_wb_enable_client_website_creation') == '1') ? 'checked' : ''; ?>>
                                <label for="enable_client_website_creation"><?php echo _l('enable_client_website_creation'); ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" name="enable_plugin_purchases" id="enable_plugin_purchases" <?php echo (get_option('uplicrm_wb_enable_plugin_purchases') == '1') ? 'checked' : ''; ?>>
                                <label for="enable_plugin_purchases"><?php echo _l('enable_plugin_purchases'); ?></label>
                            </div>
                        </div>

                        <!-- DirectAdmin Settings -->
                        <h4><?php echo _l('directadmin_settings'); ?></h4>
                        <div class="form-group">
                            <label for="directadmin_url"><?php echo _l('directadmin_url'); ?></label>
                            <input type="text" name="directadmin_url" id="directadmin_url" class="form-control" value="<?php echo get_option('uplicrm_wb_directadmin_url'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="directadmin_username"><?php echo _l('directadmin_username'); ?></label>
                            <input type="text" name="directadmin_username" id="directadmin_username" class="form-control" value="<?php echo get_option('uplicrm_wb_directadmin_username'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="directadmin_password"><?php echo _l('directadmin_password'); ?></label>
                            <input type="password" name="directadmin_password" id="directadmin_password" class="form-control" value="<?php echo get_option('uplicrm_wb_directadmin_password'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="directadmin_ip"><?php echo _l('directadmin_ip'); ?></label>
                            <input type="text" name="directadmin_ip" id="directadmin_ip" class="form-control" value="<?php echo get_option('uplicrm_wb_directadmin_ip'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="directadmin_port"><?php echo _l('directadmin_port'); ?></label>
                            <input type="number" name="directadmin_port" id="directadmin_port" class="form-control" value="<?php echo get_option('uplicrm_wb_directadmin_port'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="directadmin_package"><?php echo _l('directadmin_package'); ?></label>
                            <input type="text" name="directadmin_package" id="directadmin_package" class="form-control" value="<?php echo get_option('uplicrm_wb_directadmin_package'); ?>">
                        </div>

                        <!-- Email Settings (Optional) -->
                        <h4><?php echo _l('email_settings'); ?></h4>
                        <div class="form-group">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" name="enable_website_creation_notifications" id="enable_website_creation_notifications" <?php echo (get_option('uplicrm_wb_enable_website_creation_notifications') == '1') ? 'checked' : ''; ?>>
                                <label for="enable_website_creation_notifications"><?php echo _l('enable_website_creation_notifications'); ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notification_email_address"><?php echo _l('notification_email_address'); ?></label>
                            <input type="email" name="notification_email_address" id="notification_email_address" class="form-control" value="<?php echo get_option('uplicrm_wb_notification_email_address'); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary"><?php echo _l('save_settings'); ?></button>
                        <button type="button" class="btn btn-success" id="test_connection"><?php echo _l('test_connection'); ?></button>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(document).ready(function() {
        $('#test_connection').click(function() {
            var host = $('#directadmin_ip').val();
            var port = $('#directadmin_port').val();
            var username = $('#directadmin_username').val();
            var password = $('#directadmin_password').val();

            $.ajax({
                url: admin_url + 'uplicrm_website_builder/test_connection', // Create this route in your controller
                type: 'POST',
                data: {
                    host: host,
                    port: port,
                    username: username,
                    password: password
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert_float('success', '<?php echo _l('connection_successful'); ?>');
                    } else {
                        alert_float('danger', response.message); // Show the error message from the server
                    }
                },
                error: function(xhr, status, error) {
                    alert_float('danger', '<?php echo _l('connection_failed'); ?>' + ': ' + error);
                }
            });
        });
    });
</script>