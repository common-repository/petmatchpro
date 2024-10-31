<form class="gm_license" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="PetMatchPro_el_deactivate_license"/>
    <div class="el-license-container">
    	<h3 class="el-license-title"><i class="dashicons-before dashicons-star-filled"></i> <?php _e("PetMatchPro License Info",$this->plugin_slug);?> </h3>
		<hr>
        <ul class="el-license-info">       
        	<li>
            	<div>
                	<span class="pmp-license-info-title"><?php _e("Status:",$this->plugin_slug);?></span>
                    <?php if ( $isValid ) : ?>
                    <span class="el-license-valid"><?php _e("Valid",$this->plugin_slug);?></span>
                    <?php else : ?>
                    <span class="el-license-valid"><?php _e("Invalid",$this->plugin_slug);?></span>
                    <?php endif; ?>
                </div>
            </li>
           	<li>
           		<div>
                	<span class="pmp-license-info-title"><?php _e("Version: ",$this->plugin_slug);?></span>
                    <?php echo constant('PET_MATCH_PRO_VERSION'); ?>
                </div>
            </li>
           	<li>
           		<div>
                	<span class="pmp-license-info-title"><?php _e("License Type (ID): ",$this->plugin_slug);?></span>
                    <?php echo $license_title . ' ('. $license_param . ')'  ?>
                </div>
            </li>
            <li>
            	<div>
                	<span class="pmp-license-info-title"><?php _e("License Code: ",$this->plugin_slug); /*var_dump($this->responseObj->license_key);*/?></span>
                    <span class="el-license-key"><?php echo esc_attr( substr($license_key,0,9)."XXXXXXXX-XXXXXXXX".substr($license_key,-9) ); ?></span>
                </div>
            </li>
            <li>
            	<div class="pmp-license-help">
    				<span class="pmp-license-info-title"><?php _e('NEED HELP installing, configuring or customizing PetMatchPro?',$this->plugin_slug);?></span>
    				<br><?php _e('Log into your <a href="https://petmatchpro.com/my-account/" target="_blank" title="Upgrade Now">PetMatchPro account</a> and select the Support tab.',$this->plugin_slug);?>
                </div> <!-- .pmp-license-help -->
            </li>
		</ul> <!-- .el-license-info -->
		<div class="el-license-active-btn">
        	<?php wp_nonce_field( 'el-license' ); ?>
            <?php submit_button('Deactivate'); ?>
        </div> <!-- .el-license-active-btn -->
	</div> <!-- .el-license-container -->
</form> <!-- .gm_license -->