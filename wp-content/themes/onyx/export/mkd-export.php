<?php
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

class Mikado_Export {

	public $export_values = "";

	public function __construct() {
		add_action('admin_menu', array($this, 'mkd_admin_export'));
	}

	/**
	 * Method that checks 'export_option' variable in $_REQUEST
	 * and calls adequate method
	 */
	public function init_mkd_export() {

		if(isset($_REQUEST['export_option'])) {
			switch($_REQUEST['export_option']) {
				case "widgets":
					$this->export_widgets_sidebars();
					break;
				case "custom_sidebars":
					$this->export_custom_sidebars();
					break;
				case "mkd_options":
					$this->export_options();
					break;
				case "mkd_menus":
					$this->export_mkd_menus();
					break;
				case "setting_pages":
					$this->export_settings_pages();
					break;
				case "all":
					$this->export_all();
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Method that exports custom made sidebars
	 *
	 * @param bool $save_file whether to save to file or not
	 * @return bool | array
	 */
	public function export_custom_sidebars($save_file = false){
		$custom_sidebars = get_option("mkd_sidebars");

		$output = base64_encode(serialize($custom_sidebars));
		return $this->save_as_txt_file("custom_sidebars.txt", $output, $save_file);
	}

	/**
	 * Method that exports theme options
	 *
	 * @param bool $save_file whether to save to file or not
	 * @return bool | array
	 */
	public function export_options($save_file = false) {
		$mkd_options = get_option("mkd_options_onyx");

		//Code to reset google maps api key.
		$clean_output = array(
			'google_maps_api_key' => ''
		);
		$output       = base64_encode( serialize( array_merge( $mkd_options, $clean_output ) ) );

		return $this->save_as_txt_file("options.txt", $output, $save_file);
	}

	/**
	 * Method that exports widgets
	 *
	 * @param $save_file bool whether to save exported output to file
	 * @return bool | array
	 *
	 * @see Mikado_Export::export_sidebars
	 * @see Mikado_Export::export_widgets
	 */
	public function export_widgets_sidebars($save_file = false) {
		$this->data = array();
		$this->data['sidebars'] = $this->export_sidebars(); 
		$this->data['widgets'] 	= $this->export_widgets();
		$output = base64_encode(serialize($this->data));

		return $this->save_as_txt_file("widgets.txt", $output, $save_file);
	}

	public function export_widgets(){
		global $wp_registered_widgets;

		$all_mkd_widgets = array();
		
		foreach ($wp_registered_widgets as $mkd_widget_id => $widget_params) {
			$all_mkd_widgets[] = $widget_params['callback'][0]->id_base;
		}

		foreach ($all_mkd_widgets as $mkd_widget_id) {
			$mkd_widget_data = get_option( 'widget_' . $mkd_widget_id );

			if ( !empty($mkd_widget_data) ) {
				$widget_datas[ $mkd_widget_id ] = $mkd_widget_data;
			}

		}

		unset($all_mkd_widgets);

		return $widget_datas;
	
	}

	public function export_sidebars(){
		$mkd_sidebars = get_option("sidebars_widgets");
		$mkd_sidebars = $this->exclude_sidebar_keys($mkd_sidebars);

		return $mkd_sidebars;
	}

	private function exclude_sidebar_keys( $keys = array() ){
		if (!is_array($keys)) {
			return $keys;
		}

		unset($keys['wp_inactive_widgets']);
		unset($keys['array_version']);

		return $keys;
	}

	/**
	 * Method that exports navigation menus
	 *
	 * @param bool $save_file whether to save file or not
	 * @return bool | array
	 * @see get_nav_menu_locations()
	 */
	public function export_mkd_menus($save_file = false){
		global $wpdb;
		
		$this->data = array();
		$locations = get_nav_menu_locations();

		$terms_table = $wpdb->prefix . "terms";
		foreach ((array)$locations as $location => $menu_id) {
			$menu_slug = $wpdb->get_results($wpdb->prepare("SELECT * FROM $terms_table where term_id=%d", $menu_id), ARRAY_A);
			if (count($menu_slug) > 0)
				$this->data[ $location ] = $menu_slug[0]['slug'];
		}

		$output = base64_encode(serialize( $this->data ));
		return $this->save_as_txt_file("menus.txt", $output, $save_file);
	}

	/**
	 * Method that exports necessary options from Settings pages
	 *
	 * @param bool $save_file whether to save file or not
	 * @return bool | array
	 */
	public function export_settings_pages($save_file = false) {
		$mkd_static_page = get_option("page_on_front");
		$mkd_post_page = get_option("page_for_posts");
		$mkd_show_on_front = get_option("show_on_front");

		$mkd_settings_pages = array(
			'show_on_front' => $mkd_show_on_front,
			'page_on_front' => $mkd_static_page,
			'page_for_posts' => $mkd_post_page
		);

		$output = base64_encode(serialize($mkd_settings_pages));

		return $this->save_as_txt_file("settingpages.txt", $output, $save_file);
		
	}

	/**
	 * Method that exports all options and sends a zip file as an output to browser
	 *
	 * @see Mikado_Export::export_settings_pages
	 * @see Mikado_Export::export_widgets_sidebars
	 * @see Mikado_Export::export_mkd_menus
	 * @see Mikado_Export::export_custom_sidebars
	 * @see Mikado_Export::export_options
	 */
	public function export_all() {
		global $wpdb;
		
		$files_array = array();

		//get all files created and all options exported
		$settings_file = $this->export_settings_pages(true);
		$widgets_file = $this->export_widgets_sidebars(true);
		$menus_file = $this->export_mkd_menus(true);
		$custom_sidebar_file = $this->export_custom_sidebars(true);
		$options_file = $this->export_options(true);

		//add all generated files to files array
		if($settings_file) {
			$files_array[] = $settings_file;
		}

		if($widgets_file) {
			$files_array[] = $widgets_file;
		}

		if($menus_file) {
			$files_array[] = $menus_file;
		}

		if($custom_sidebar_file) {
			$files_array[] = $custom_sidebar_file;
		}

		if($options_file) {
			$files_array[] = $options_file;
		}

		//if we have added files to files array
		if(is_array($files_array) && count($files_array)) {
			/**
			 * Generate zip file name. It will contain name of the folder where WP is installed
			 * and current time stamp (for caching purposes)
			 */
			$wp_dir_array = parse_url(home_url());
			$wp_dir_name = ltrim(end($wp_dir_array), '/');
			//$zip_name = $wp_dir_name.'_'.date('y-m-d').'_'.date('H').'_'.date('s').'.zip';
			$zip_name = $wpdb->dbname.'.zip';

			/**
			 * Instantiate ZipArchive class and add all files to zip
			 */
			$zip = new ZipArchive();
			$zip->open($zip_name, ZipArchive::CREATE);
			foreach($files_array as $file) {
				$zip->addFile($file['full_path'], $file['filename']);
			}

			$zip->close();

			//delete all created export files from server
			foreach ($files_array as $file) {
				if(file_exists(dirname(__FILE__).'/'.$file['filename'])) {
					unlink(dirname(__FILE__).'/'.$file['filename']);
				}
			}

			//send output to browser so user can download generated zip
			header('Content-Type: application/zip');
			header('Content-disposition: attachment; filename='.$zip_name);
			header('Content-Length: ' . filesize($zip_name));
			readfile($zip_name);
		}
	}

	/**
	 * Method that saves output as a txt file. It has an option for sending file to browser
	 * or saving it on file system
	 *
	 * @param $file_name string name of the file to save
	 * @param $output string content of the file
	 * @param bool $save_to_file whether to save file to file system or not
	 * @return array|bool if file is saved on file system it returns an array that contains
	 * 'full_path' of the file and 'filename' of the file. If it fails to save file it returns false
	 */
	public function save_as_txt_file($file_name, $output, $save_to_file = false){
		if($save_to_file) {
			if(file_put_contents(get_template_directory().'/export/'.$file_name, $output)) {
				return array(
					'full_path' => get_template_directory().'/export/'.$file_name,
					'filename' => $file_name
				);
			}

			return false;
		} else {
			header("Content-type: application/text",true,200);
			header("Content-Disposition: attachment; filename=$file_name");
			header("Pragma: no-cache");
			header("Expires: 0");
			print $output;
			exit;
		}
	}

	/**
	 * Method that adds export admin page
	 */
	public function mkd_admin_export() {
		if(isset($_REQUEST['export'])) {
			$this->init_mkd_export();
		}

		$this->pagehook = add_menu_page(
			'Mikado Theme', esc_html__('Mikado Export', 'mkd'),
			'manage_options',
			'mkd_options_export_page',
			array($this, 'mkd_generate_export_page')
		);
	}

	/**
	 * Method that
	 */
	public function mkd_generate_export_page() {

		?>
		<div id="mkd-metaboxes-general" class="wrap">
		    <form method="post" action="">
				<div id="poststuff" class="metabox-holder">
					<div id="post-body" class="has-sidebar">
						<div id="post-body-content" class="has-sidebar-content">
							<table class="form-table">
								<tbody>
								<tr><td scope="row" width="150"><h2><?php esc_html_e('Export', 'mkd'); ?></h2></td></tr>
								<tr valign="middle">

									<td>
										<form method="post" action="">
											<input type="hidden" name="export_option" value="widgets" />
											<input type="submit" value="Export Widgets" name="export" />
										</form>
										<br />
										<br />
										<form method="post" action="">
											<input type="hidden" name="export_option" value="custom_sidebars" />
											<input type="submit" value="Export Custom Sidebars" name="export" />
										</form>
										<br />
										<form method="post" action="">
											<input type="hidden" name="export_option" value="mkd_options" />
											<input type="submit" value="Export Options" name="export" />
										</form>
										<br />
										<form method="post" action="">
											<input type="hidden" name="export_option" value="mkd_menus" />
											<input type="submit" value="Export Menus" name="export" />
										</form>
										<br />
										<form method="post" action="">
											<input type="hidden" name="export_option" value="setting_pages" />
											<input type="submit" value="Export Setting Pages" name="export" />
										</form>
										<br />
										<form method="post" action="">
											<input type="hidden" name="export_option" value="all" />
											<input type="submit" value="Export all" name="export" />
										</form>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
					<br class="clear"/>
				</div>
		    </form>

		</div>

<?php	}
}

$my_Mikado_Export = new Mikado_Export();