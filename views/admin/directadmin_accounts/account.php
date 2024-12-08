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
                        <?php $attrs = (isset($account) ? array('required' => true) : array('autofocus' => true, 'required' => true)); ?>
                        <?php $value = (isset($account) ? $account->username : ''); ?>
                        <?php echo render_input('username', 'directadmin_account_username', $value, 'text', $attrs); ?>
                        <?php $value = (isset($account) ? $account->password : ''); ?>
                        <?php echo render_input('password', 'directadmin_account_password', $value, 'text', $attrs); ?>
                        <?php $value = (isset($account) ? $account->user_id : ''); ?>
                        <?php echo render_select('user_id', $users, array((is_client_logged_in() ? 'userid' : 'staffid'), (is_client_logged_in() ? 'company' : 'firstname')), 'directadmin_account_user', $value); ?>
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