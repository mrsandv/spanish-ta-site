<?php
/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname(__FILE__) . '/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'mkd_register_js_composer_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function mkd_register_js_composer_plugins() {
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // This is an example of how to include a plugin pre-packaged with a theme
        array(
                'name' => esc_html__('Mikado Core', 'onyx'),
                'slug' => 'mikado-core',
                'source' => get_template_directory() . '/plugins/mikado-core.zip',
                'version' => '1.0.3',
                'required' => true,
                'force_activation' => false,
                'force_deactivation' => false
            ),
        array(
            'name'			=> 'WPBakery Visual Composer', // The plugin name
            'slug'			=> 'js_composer', // The plugin slug (typically the folder name)
            'source'			=> get_template_directory() . '/plugins/js_composer.zip', // The plugin source
            'required'			=> true, // If false, the plugin is only 'recommended' instead of required
            'version'			=> '5.5.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'		=> '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'          => 'LayerSlider WP', // The plugin name
            'slug'          => 'LayerSlider', // The plugin slug (typically the folder name)
            'source'            => get_template_directory() . '/plugins/LayerSlider.zip', // The plugin source
            'required'          => true, // If false, the plugin is only 'recommended' instead of required
            'version'           => '6.7.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'      => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
                'name' => esc_html__('Envato Market', 'onyx'),
                'slug' => 'envato-market',
                'source' => get_template_directory() . '/plugins/envato-market.zip',
                'version' => '2.0.1',
                'required' => true,
                'force_activation' => false,
                'force_deactivation' => false
            ),
        array(
            'name'         => esc_html__( 'WooCommerce plugin', 'onyx' ),
            'slug'         => 'woocommerce',
            'external_url' => 'https://wordpress.org/plugins/woocommerce/',
            'required'     => false
        ),
        array(
            'name'         => esc_html__( 'Contact Form 7', 'onyx' ),
            'slug'         => 'contact-form-7',
            'external_url' => 'https://wordpress.org/plugins/contact-form-7/',
            'required'     => false
        )

    );
 
    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'           => 'mkd',
        'default_path'     => '',
        'parent_slug'      => 'themes.php',
        'capability'       => 'edit_theme_options',
        'menu'             => 'install-required-plugins',
        'has_notices'      => true,
        'is_automatic'     => false,
        'message'          => '',
        'strings'          => array(
            'page_title'                      => esc_html__('Install Required Plugins', 'mkd'),
            'menu_title'                      => esc_html__('Install Plugins', 'mkd'),
            'installing'                      => esc_html__('Installing Plugin: %s', 'mkd'),
            'oops'                            => esc_html__('Something went wrong with the plugin API.', 'mkd'),
            'notice_can_install_required'     => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'mkd'),
            'notice_can_install_recommended'  => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'mkd'),
            'notice_cannot_install'           => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'mkd'),
            'notice_can_activate_required'    => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'mkd'),
            'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'mkd'),
            'notice_cannot_activate'          => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'mkd'),
            'notice_ask_to_update'            => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'mkd'),
            'notice_cannot_update'            => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'mkd'),
            'install_link'                    => _n_noop('Begin installing plugin', 'Begin installing plugins', 'mkd'),
            'activate_link'                   => _n_noop('Activate installed plugin', 'Activate installed plugins', 'mkd'),
            'return'                          => esc_html__('Return to Required Plugins Installer', 'mkd'),
            'plugin_activated'                => esc_html__('Plugin activated successfully.', 'mkd'),
            'complete'                        => esc_html__('All plugins installed and activated successfully. %s', 'mkd'),
            'nag_type'                        => 'updated'
        )
    );
    tgmpa( $plugins, $config );
}
 
/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
if(function_exists('vc_set_as_theme')) vc_set_as_theme(true);

// Initialising Shortcodes
if (class_exists('WPBakeryVisualComposerAbstract')) {
	function mkdRequireVcExtend(){
		require_once locate_template('/extendvc/extend-vc.php');
	}
	add_action('init', 'mkdRequireVcExtend',10);
}