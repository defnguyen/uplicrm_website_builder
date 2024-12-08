<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-margin">
    <?php echo $title; ?>
</h4>
<hr class="hr-panel-heading" />
<div class="row">
    <?php foreach ($templates as $template) : ?>
        <div class="col-md-4">
            <div class="panel_s">
                <div class="panel-body">
                    <?php if ($template->image) : ?>
                        <img src="<?php echo upli_templates_url($template->image); ?>" class="img-responsive" alt="<?php echo $template->name; ?>">
                    <?php endif; ?>
                    <h4><?php echo $template->name; ?></h4>
                    <p><?php echo $template->description; ?></p>
                    <p><b><?php echo _l('price'); ?>:</b> <?php echo app_format_money($template->price, get_base_currency()->name); ?></p>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buildModal<?php echo $template->id; ?>">
                        <?php echo _l('start_build'); ?>
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="buildModal<?php echo $template->id; ?>" tabindex="-1" role="dialog" aria-labelledby="buildModalLabel<?php echo $template->id; ?>">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="buildModalLabel<?php echo $template->id; ?>"><?php echo _l('start_build_website'); ?> - <?php echo $template->name; ?></h4>
                    </div>
                    <?php echo form_open(site_url('uplicrm_website_builder_client/websites')); ?>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="start_build">
                        <input type="hidden" name="template_id" value="<?php echo $template->id; ?>">
                        <?php echo render_input('subdomain', 'website_subdomain'); ?>
                        <?php if(get_option('allow_plan_selection_on_website_creation') == 1) : ?>
                            <?php echo render_select('plan_id', $plans, ['id', 'name'], 'website_plan'); ?>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo _l('start_build'); ?></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>