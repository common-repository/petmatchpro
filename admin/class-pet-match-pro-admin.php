<?php

class Pet_Match_Pro_Admin {

	/**

	 * The ID of this plugin.

	 *

	 * @since    1.0.0

	 * @access   private

	 * @var      string    $plugin_name    The ID of this plugin.

	 */

	private $plugin_name;



	/**

	 * The version of this plugin.

	 *

	 * @since    1.0.0

	 * @access   private

	 * @var      string    $version    The current version of this plugin.

	 */



	private $version;

    private $plugin_slug;


	/**

	 * Initialize the class and set its properties.

	 *

	 * @since    1.0.0

	 * @param      string    $plugin_name       The name of this plugin.

	 * @param      string    $version    The version of this plugin.

	 */

	 

	 private $requireFile;



	public function __construct( $plugin_name, $version, $slug ) {

		$this->plugin_name = $plugin_name;

		$this->version = $version;

        $this->plugin_slug = $slug;

        //Load needed files for Admin Options

        $this->load_dependencies();

	}



	/**

	 * Load the required dependencies for the Admin facing functionality.

	 *

	 * Include the following files that make up the plugin:

	 *

	 * - pet-match-pro_Admin_Settings. Registers the admin settings and page.

	 *

	 *

	 * @since    1.0.0

	 * @access   private

	 */



	private function load_dependencies() {

		/*

         * Lets check if licensed

         * load license activator

         */

         $this->requireFile = constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/license/class-pet-match-pro-license.php';

         require_once $this->requireFile;

//         require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/license/class-pet-match-pro-license.php';



		/**

		 * The class responsible for orchestrating the actions and filters of the

		 * core plugin.

		 */

		$this->requireFile = constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/class-pet-match-pro-admin-settings.php';

		require_once $this->requireFile;  

//		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-pet-match-pro-admin-settings.php';



		/**

		 * The class containing the general functions for preparing animal searches. 

		 * core plugin.

		 */

		$this->requireFile = constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/class-pet-match-pro-functions.php';

		require_once $this->requireFile;  

	}

	

	/**

	 * Register the stylesheets for the admin area.

	 *

	 * @since    1.0.0

	 */



	public function enqueue_styles() {

		/**

		 * This function is provided for demonstration purposes only.

		 *

		 * An instance of this class should be passed to the run() function

		 * defined in Pet_Match_Pro_Loader as all of the hooks are defined

		 * in that particular class.

		 *

		 * The Pet_Match_Pro_Loader will then create the relationship

		 * between the defined hooks and the functions defined in this

		 * class.

		 */



		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . constant('CSS_DIR') . '/pet-match-pro-admin.css', array(), $this->version, 'all' );

	}



	/**

	 * Register the JavaScript for the admin area.

	 *

	 * @since    1.0.0

	 */

	public function enqueue_scripts() {



		/**

		 * This function is provided for demonstration purposes only.

		 *

		 * An instance of this class should be passed to the run() function

		 * defined in Pet_Match_Pro_Loader as all of the hooks are defined

		 * in that particular class.

		 *

		 * The Pet_Match_Pro_Loader will then create the relationship

		 * between the defined hooks and the functions defined in this

		 * class.

		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pet-match-pro-admin.js', array( 'jquery' ), $this->version, false );

	}

}