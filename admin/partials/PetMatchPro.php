<?php
class PetMatchProAPI {
    public $plugin_file=__FILE__;
    public $responseObj;
    public $licenseMessage;
    public $showMessage=false;
    public $slug="pet-match-pro";

    function __construct() {
        add_action( 'admin_print_styles', [ $this, 'SetAdminStyle' ] );
        $licenseKey=get_option("PMP_lic_Key","");
        $liceEmail=get_option( "PMP_lic_email","");
        PetMatchProBase::addOnDelete(function(){
           delete_option("PMP_lic_Key");
        });

        if(PetMatchProBase::CheckWPPlugin($licenseKey,$liceEmail,$this->licenseMessage,$this->responseObj,__FILE__)){
        	echo '<pre>LICENSE MESSAGE '; print_r($this->licenseMessage); echo '</pre>';
        	echo '<pre>LICENSE RESPONSE '; print_r($this->responseObj); echo '</pre>';
            add_action( 'admin_menu', [$this,'ActiveAdminMenu'],99999);
            add_action( 'admin_post_PetMatchPro_el_deactivate_license', [ $this, 'action_deactivate_license' ] );
            //$this->licenselMessage=$this->mess;
            //***Write you plugin's code here***
        } else {
            if(!empty($licenseKey) && !empty($this->licenseMessage)){
               $this->showMessage=true;
            }
            update_option("PMP_lic_Key","") || add_option("PMP_lic_Key","");
            add_action( 'admin_post_PMP_activate_license', [ $this, 'action_activate_license' ] );
            add_action( 'admin_menu', [$this,'InactiveMenu']);
        }
    }

    function SetAdminStyle() {
        wp_register_style( "PetMatchProLic", plugins_url("_lic_style.css",$this->plugin_file),10);
        wp_enqueue_style( "PetMatchProLic" );
    }

    function ActiveAdminMenu() {
		add_menu_page (  "PetMatchPro", "PetMatch Pro", "activate_plugins", $this->slug, [$this,"Activated"], " dashicons-star ");
		//add_submenu_page(  $this->slug, "PetMatchPro License", "License Info", "activate_plugins",  $this->slug."_license", [$this,"Activated"] );
    }

    function InactiveMenu() {
        add_menu_page( "PetMatchPro", "PetMatch Pro", 'activate_plugins', $this->slug,  [$this,"LicenseForm"], " dashicons-star " );
    }

    function action_activate_license() {
        check_admin_referer( 'el-license' );
        $licenseKey=!empty($_POST['el_license_key'])?sanitize_text_field($_POST['el_license_key']):"";
        $licenseEmail=!empty($_POST['el_license_email'])?sanitize_email($_POST['el_license_email']):"";
        update_option("PMP_lic_Key",$licenseKey) || add_option("PMP_lic_Key",$licenseKey);
        update_option("PMP_lic_email",$licenseEmail) || add_option("PMP_lic_email",$licenseEmail);
        update_option('_site_transient_update_plugins','');
        wp_safe_redirect(admin_url( 'admin.php?page='.$this->slug));
    }

    function action_deactivate_license() {
        check_admin_referer( 'el-license' );
        $message="";
        if(PetMatchProBase::RemoveLicenseKey(__FILE__,$message)){
            update_option("PMP_lic_Key","") || add_option("PMP_lic_Key","");
            update_option('_site_transient_update_plugins','');
        }
        wp_safe_redirect(admin_url( 'admin.php?page='.$this->slug));
    }

    function Activated() {
?>
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
                        <span class="el-license-info-title"><?php _e("License Type23",$this->slug);?></span>
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
                        <span class="el-license-info-title"><?php _e("Your License Key",$this->slug);?></span>
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
    <?php
    }

    function LicenseForm() {
?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="PMP_activate_license"/>
        <div class="el-license-container">
            <h3 class="el-license-title"><i class="dashicons-before dashicons-star-filled"></i> <?php _e("PetMatch Pro Licensing",$this->slug);?></h3>
            <hr>
            <?php
            if(!empty($this->showMessage) && !empty($this->licenseMessage)){
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo _e($this->licenseMessage,$this->slug); ?></p>
                </div>
                <?php
            }
            ?>
            /home/customer/www/petmatchpro.com/public_html/wp-content/uploads/elf_2020/wp/form-header.app
            <div class="el-license-field">
                <label for="el_license_key"><?php _e("License code24",$this->slug);?></label>
                <input type="text" class="regular-text code" name="el_license_key" size="50" placeholder="xxxxxxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx" required="required">
            </div>
            <div class="el-license-field">
                <label for="el_license_key"><?php _e("Email Address",$this->slug);?></label>
                <?php
                    $purchaseEmail   = get_option( "PMP_lic_email", get_bloginfo( 'admin_email' ));
                ?>
                <input type="text" class="regular-text code" name="el_license_email" size="50" value="<?php echo $purchaseEmail; ?>" placeholder="" required="required">
                <div><small><?php _e("We will send update news of this product by this email address, don't worry, we hate spam",$this->slug);?></small></div>
            </div>
            <div class="el-license-active-btn">
                <?php wp_nonce_field( 'el-license' ); ?>
                <?php submit_button('Activate'); ?>
            </div>
        </div>
    </form>

<?php
    }
}

new PetMatchProAPI();