		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        	<input type="hidden" name="action" value="PetMatchPro_el_deactivate_license"/>
            <div class="el-license-container">
                <h3 class="el-license-title"><i class="dashicons-before dashicons-star-filled"></i> <?php _e("PetMatchPro License Info",$this->slug);?> </h3>
                <hr>
                <ul class="el-license-info">
	                <li>
	                    <div>
	                        <span class="el-license-info-title"><?php _e("Status",$this->slug);?></span>
	                        <?php if ( $this->responseObj->is_valid ) : ?>
	                            <span class="el-license-valid"><?php _e("Valid",$this->slug);?></span>
	                        <?php else : ?>
	                            <span class="el-license-valid"><?php _e("Invalid",$this->slug);?></span>
	                        <?php endif; ?>
	                    </div>
	                </li>
	                <li>
	                    <div>
	                        <span class="el-license-info-title"><?php _e("License Type",$this->slug);?></span>
	                        <?php echo $this->responseObj->license_title; ?>
	                    </div>
	                </li>
	               <li>
	                   <div>
	                       <span class="el-license-info-title"><?php _e("License Expired on",$this->slug);?></span>
	                       <?php echo $this->responseObj->expire_date;
	                       if(!empty($this->responseObj->expire_renew_link)){
	                           ?>
	                           <a target="_blank" class="el-blue-btn" href="<?php echo $this->responseObj->expire_renew_link; ?>">Renew</a>
	                           <?php
	                       }
	                       ?>
	                   </div>
	               </li>
	               <li>
	                   <div>
	                       <span class="el-license-info-title"><?php _e("Support Expired on",$this->slug);?></span>
	                       <?php
	                           echo $this->responseObj->support_end;
	                        if(!empty($this->responseObj->support_renew_link)){
	                            ?>
	                               <a target="_blank" class="el-blue-btn" href="<?php echo $this->responseObj->support_renew_link; ?>">Renew</a>
	                            <?php
	                        }
	                       ?>
	                   </div>
	               </li>
	               <li>
	                   <div>
	                        <span class="el-license-info-title"><?php _e("Your License Key",$this->slug); ?></span>
	                        <span class="el-license-key"><?php echo esc_attr( substr($this->responseObj->license_key,0,9)."XXXXXXXX-XXXXXXXX".substr($this->responseObj->license_key,-9) ); ?></span>
	                   </div>
	               </li>
           	</ul>
           	<div class="el-license-active-btn">
				<?php wp_nonce_field( 'el-license' ); ?>
				<?php submit_button('Deactivate'); ?>
			</div>
        </div>
    </form>