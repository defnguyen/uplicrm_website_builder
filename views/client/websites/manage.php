<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-margin">
    <?php echo $title; ?>
</h4>
<hr class="hr-panel-heading" />
<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table dt-table">
                        <thead>
                            <tr>
                                <th><?php echo _l('website_name'); ?></th>
                                <th><?php echo _l('website_subdomain'); ?></th>
                                <th><?php echo _l('website_plan'); ?></th>
                                <th><?php echo _l('website_status'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($websites as $website) : ?>
                                <tr>
                                    <td><?php echo $website->name; ?></td>
                                    <td><?php echo $website->subdomain; ?></td>
                                    <td><?php echo $website->plan_name; ?></td>
                                    <td><?php echo _l($website->status); ?></td>
                                    <td>
                                        <a href="<?php echo site_url('uplicrm_website_builder_client/delete_website/' . $website->id); ?>" class="btn btn-danger btn-xs _delete"><?php echo _l('delete'); ?></a>
                                        <!-- Park Domain -->
                                        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#parkDomainModal<?php echo $website->id; ?>">
                                            <?php echo _l('park_domain'); ?>
                                        </button>
                                        <!-- Upgrade Plan -->
                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#upgradePlanModal<?php echo $website->id; ?>">
                                            <?php echo _l('upgrade_plan'); ?>
                                        </button>
                                        <!-- Install Plugin -->
                                        <a href="<?php echo site_url('uplicrm_website_builder_client/plugins'); ?>" class="btn btn-warning btn-xs"><?php echo _l('install_plugin'); ?></a>
                                        <!-- Park Domain Modal -->
                                        <div class="modal fade" id="parkDomainModal<?php echo $website->id; ?>" tabindex="-1" role="dialog" aria-labelledby="parkDomainModalLabel<?php echo $website->id; ?>">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                        <h4 class="modal-title" id="parkDomainModalLabel<?php echo $website->id; ?>"><?php echo _l('park_domain'); ?> - <?php echo $website->subdomain; ?></h4>
                                                    </div>
                                                    <?php echo form_open(site_url('uplicrm_website_builder_client/websites')); ?>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="park_domain">
                                                        <input type="hidden" name="website_id" value="<?php echo $website->id; ?>">
                                                        <p><?php echo _l('park_domain_confirmation'); ?></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                                                        <button type="submit" class="btn btn-info"><?php echo _l('park_domain'); ?></button>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Upgrade Plan Modal -->
                                        <div class="modal fade" id="upgradePlanModal<?php echo $website->id; ?>" tabindex="-1" role="dialog" aria-labelledby="upgradePlanModalLabel<?php echo $website->id; ?>">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                        <h4 class="modal-title" id="upgradePlanModalLabel<?php echo $website->id; ?>"><?php echo _l('upgrade_plan'); ?> - <?php echo $website->name; ?></h4>
                                                    </div>
                                                    <?php echo form_open(site_url('uplicrm_website_builder_client/websites')); ?>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="upgrade_plan">
                                                        <input type="hidden" name="website_id" value="<?php echo $website->id; ?>">
                                                        <?php echo render_select('plan_id', $plans, array('id', 'name'), 'website_plan'); ?>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                                                        <button type="submit" class="btn btn-success"><?php echo _l('upgrade_plan'); ?></button>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>