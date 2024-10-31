<form method="post" action="options.php">
    <?php
    settings_fields('el-license');
    do_settings_sections('PMP_activate_license');
    ?>
    <input type="hidden" name="action" value="PMP_activate_license"/>
    <div class="el-license-container">
        <h3 class="el-license-title"><i class="dashicons-before dashicons-star-filled"></i> <?php _e("PetMatchPro Licensing", $this->slug); ?></h3>
        <hr>
        <?php
        if (!empty($this->showMessage) && !empty($this->licenseMessage)) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo _e($this->licenseMessage, $this->slug); ?></p>
            </div>
            <?php
        }
        ?>
        <div class="el-license-field">
            <label for="el_license_key"><?php _e("License code", $this->slug); ?></label>
            <input type="text" class="regular-text code" name="el_license_key" size="50" placeholder="xxxxxxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx" required="required">
        </div>
        <div class="el-license-field">
            <label for="el_license_key"><?php _e("Email Address", $this->slug); ?></label>
            <?php
            $purchaseEmail = get_option("PMP_lic_email", get_bloginfo('admin_email'));
            ?>
            <input type="text" class="regular-text code" name="el_license_email" size="50" value="<?php echo $purchaseEmail; ?>" placeholder="" required="required">
            <div><small><?php _e("We will send update news of this product by this email address, don't worry, we hate spam", $this->slug); ?></small></div>
        </div>
        <div class="el-license-active-btn">
<?php //wp_nonce_field( 'el-license' );  ?>
<?php submit_button('Activate'); ?>
        </div>
    </div>
</form>