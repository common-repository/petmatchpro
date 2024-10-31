<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
    <input type="hidden" name="action" value="PMP_activate_license"/>
    <div class="el-license-container">
        <h3 class="el-license-title"><i class="dashicons-before dashicons-star-filled"></i> <?php _e("PetMatch Pro Licensing",$this->plugin_slug);?></h3>
        <hr>
        <?php
        if(!empty($this->showMessage) && !empty($this->licenseMessage)){
        ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo _e($this->licenseMessage,$this->plugin_slug); ?></p>
            </div> <!-- .notice -->
        <?php
        }
        ?>
        <p><?php _e("Enter your license code and the email address used when registering PetMatchPro to enable the plugin, get feature updates and support.",$this->plugin_slug);?></p>
		<ol>
		    <li><?php _e('If you do not have a license code, visit <a href="https://petmatchpro.com/" target="_blank" title="Register Now">PetMatchPro.com</a> to register for your free license.',$this->plugin_slug);?></li>
		    <li><?php _e('If you have registered, check your email for a message from no-reply@petmatchpro.com with your license code.',$this->plugin_slug);?></li>
		    <li><?php _e('If you would like to use our premium features, <a href="https://petmatchpro.com/my-account/" target="_blank" title="Upgrade Now">login to your account</a> to upgrade your free license.',$this->plugin_slug);?></li>
		    <li><?php _e('If you need help installing, configuring or customizing PetMatchPro, get help by logging into your <a href="https://petmatchpro.com/my-account/" target="_blank" title="Upgrade Now">PetMatchPro account</a> and selecting the Support tab.',$this->plugin_slug);?></li>
		</ol>
   		<div>
           	<span class="pmp-license-info-title"><?php _e("Version: ",$this->plugin_slug);?></span>
            <?php echo constant('PET_MATCH_PRO_VERSION'); ?>
        </div>		
        <div class="el-license-field">
            <label for="el_license_key"><?php _e("License Code: ",$this->plugin_slug);?></label>
            <input type="text" class="regular-text code" name="el_license_key" size="50" placeholder="xxxxxxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx" required="required">
        </div> <!-- .el-license-field -->
        <div class="el-license-field">
            <label for="el_license_key"><?php _e("Email Address: ",$this->plugin_slug);?></label>
            <?php
                $purchaseEmail   = get_option( "PMP_lic_email", get_bloginfo( 'admin_email' ));
            ?>
            <input type="text" class="regular-text code" name="el_license_email" size="50" value="<?php echo $purchaseEmail; ?>" placeholder="" required="required">
            <div><small><?php _e("We will send PetMatchPro related news to this email address, don't worry, we hate SPAM.",$this->plugin_slug);?></small></div>
       </div> <!-- .el-license-field -->
       <div class="el-license-active-btn">
            <?php wp_nonce_field( 'el-license' ); ?>
            <?php submit_button('Activate'); ?>
       </div> <!-- .el-license-active-btn -->
    </div>
</form>