<?php
$form = new PhpFormBuilder();
$form->set_att('action', 'options.php');
$form->set_att('add_nonce', 'pet-match-pro-license');
$form->set_att('form_element', true);
$form->set_att('add_submit', true);
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="el-license-title"><i class="dashicons-before dashicons-star-filled"></i> <?php _e("Filter Adoptable Pets", $this->slug); ?></h3>
        <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label for="pet-match-pro-license[api_license_key]">License API Key</label>
        </div>
        <div class="col-8">
            <?php
            $form->add_input('License API Key', 
                    array('request_populate' => false),
                    'pet-match-pro-license[api_license_key]'
                    );
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label for="pet-match-pro-license[api_email]">License API Key</label>
        </div>
        <div class="col-8">
            <?php
            $form->add_input('License API Key', 
                    array(
                        'type' => 'email',
                        'request_populate' => false),
                    'pet-match-pro-license[api_email]'
                    );
            ?>
        </div>
    </div>
</div>  
<?php $form->build_form(); ?>