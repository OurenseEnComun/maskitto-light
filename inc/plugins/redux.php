<?php

/**
 * Register maskitto options
 */

if (!class_exists('maskitto_light_Redux_Framework_config')) {

    class maskitto_light_Redux_Framework_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) || true == Redux_Helpers::isTheme( get_template_directory().'/inc/plugins/redux.php' ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'maskitto-light'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'maskitto-light'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            $args['dev_mode'] = false;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'maskitto-light'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e( 'Current theme preview', 'maskitto-light' ); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e( 'Current theme preview', 'maskitto-light' ); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'maskitto-light'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'maskitto-light'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'maskitto-light') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'maskitto-light') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'maskitto-light'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once ABSPATH . '/wp-admin/includes/file.php';
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }


            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'icon'      => 'el-icon-cog',
                'title'     => __('General', 'maskitto-light'),
                'fields'    => array(

                    array(
                        'id'       => 'page-layout',
                        'type'     => 'radio',
                        'title'    => __('Page Layout', 'maskitto-light'), 
                        'subtitle' => __('Choose page layout type', 'maskitto-light'),
                        'options'  => array(
                            '1' => 'Standard', 
                            '2' => 'Boxed', 
                            '3' => 'Full'
                        ),
                        'default' => '1'
                    ),

                    array(
                        'id'        => 'minity-status',
                        'type'      => 'switch',
                        'title'     => __('Minify', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable compresed file version use', 'maskitto-light'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'logo-image',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Logo', 'maskitto-light'),
                        'subtitle'  => __('Upload your web site logo', 'maskitto-light'),
                        'default'   => array('url' => get_header_image() ),
                    ),

                    array(
                        'id'        => 'favicon-image',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Favicon', 'maskitto-light'),
                        'subtitle'  => __('Upload your web site favicon, recomended size 32x32px', 'maskitto-light'),
                    ),

                    array(
                        'id'       => 'loading-animation',
                        'type'     => 'radio',
                        'title'     => __('Loading screen with animation', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable option to to show loading screen', 'maskitto-light'),
                        'options'  => array(
                            '0' => 'Off', 
                            '1' => 'In every page', 
                            '2' => 'Only once', 
                        ),
                        'default' => '0'
                    ),

                    array(
                        'id'        => 'back-to-top',
                        'type'      => 'switch',
                        'title'     => __('Back To Top', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable "back to top" button', 'maskitto-light'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'footer-text',
                        'type'      => 'textarea',
                        'title'     => __('Footer Text', 'maskitto-light'),
                        'subtitle'  => __('Add additional information in footer', 'maskitto-light'),
                        'default'   => '',
                    ),

                    array(
                        'id'        => 'footer-logo',
                        'type'      => 'switch',
                        'title'     => __('Footer Logo', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable footer logo', 'maskitto-light'),
                        'default'   => true,
                    ),

                    $fields = array(
                        'id'       => 'custom-css',
                        'type'     => 'ace_editor',
                        'title'    => __('CSS Code', 'maskitto-light'),
                        'subtitle' => __('You can change CSS code for existing elements here', 'maskitto-light'),
                        'mode'     => 'css',
                        'theme'    => 'monokai',
                        'default'  => "#some-block{\npadding: 0;\n}"
                    )
                )
            );


            $this->sections[] = array(
                'icon'      => 'el-icon-fontsize',
                'title'     => __('Styling', 'maskitto-light'),
                'fields'    => array(

                    array(
                        'id'        => 'primary-color',
                        'type'      => 'color',
                        'title'     => __('Accent Color', 'maskitto-light'),
                        'subtitle'  => __('Define main accent color', 'maskitto-light'),
                        'default'   => '#e15454',
                        'validate'  => 'color',
                        'transparent' => false
                    ),

                    array(
                        'id'        => 'primary-color-hover',
                        'type'      => 'color',
                        'title'     => __('Accent Hover Color', 'maskitto-light'),
                        'subtitle'  => __('Define main accent hover color for links', 'maskitto-light'),
                        'default'   => '#cf3d3d',
                        'validate'  => 'color',
                        'transparent' => false
                    ),

                    array(
                        'id'        => 'body-background-color',
                        'type'      => 'color',
                        'title'     => __('Body Background Color', 'maskitto-light'),
                        'subtitle'  => __('Change background color', 'maskitto-light'),
                        'default'   => '#ffffff',
                        'validate'  => 'color',
                        'transparent' => false
                    ),

                    array(
                        'id'        => 'header-background-color',
                        'type'      => 'color',
                        'title'     => __('Header Background color (bottom bar)', 'maskitto-light'),
                        'subtitle'  => __('Change header background color', 'maskitto-light'),
                        'default'   => '#ffffff',
                        'validate'  => 'color',
                        'transparent' => false
                    ),

                    array(
                        'id'        => 'footer-background-color',
                        'type'      => 'color',
                        'title'     => __('Footer Background color', 'maskitto-light'),
                        'subtitle'  => __('Change footer background color', 'maskitto-light'),
                        'default'   => '#3C3C3C',
                        'validate'  => 'color',
                        'transparent' => false
                    ),

                    array(
                        'id'        => 'blog-background',
                        'type'      => 'background',
                        'title'     => __('Blog Background', 'maskitto-light'),
                        'subtitle'  => __('Change blog background image and color', 'maskitto-light'),
                        'default'   => array(
                            'background-color' => '#f2f4f5',
                            'background-image' => get_template_directory_uri().'/img/blogbg.png'
                        ),
                        'transparent' => false,
                        'background-repeat' => false,
                        'background-size' => false,
                        'background-attachment' => false,
                        'background-position' => false,
                    ),

                    array(
                        'id'        => 'body-font',
                        'type'      => 'typography',
                        'title'     => __('Body Font', 'maskitto-light'),
                        'subtitle'  => __('Change web site font', 'maskitto-light'),
                        'google'    => true,
                        'compiler' => false,
                        'default'   => array(
                            'color'         => '#565656',
                            'font-size'     => '13px',
                            'font-family'   => 'Open Sans',
                            'font-weight'   => 'Normal',
                        ),
                        'line-height' => false,
                        'text-align' => false,
                        'font-size' => false,
                        'font-style' => false,
                        'font-weight' => false,
                    ),

                    array(
                        'id'       => 'button-style',
                        'type'     => 'radio',
                        'title'    => __('Button Style', 'maskitto-light'), 
                        'subtitle' => __('Choose global button style', 'maskitto-light'),
                        'options'  => array(
                            '1' => 'Style 1', 
                            '2' => 'Style 2 (new)', 
                        ),
                        'default' => '1'
                    ),

                    array(
                        'id'       => 'title-style',
                        'type'     => 'radio',
                        'title'    => __('Title Style', 'maskitto-light'), 
                        'subtitle' => __('Choose global title style', 'maskitto-light'),
                        'options'  => array(
                            '1' => 'Style 1', 
                            '2' => 'Style 2 (new)', 
                        ),
                        'default' => '1'
                    ),

                    array(
                        'id'       => 'slideshow-widget-style',
                        'type'     => 'radio',
                        'title'    => __('Slideshow Widget Style', 'maskitto-light'), 
                        'subtitle' => __('Choose global slideshow widget style', 'maskitto-light'),
                        'options'  => array(
                            '1' => 'Style 1', 
                            '2' => 'Style 2 (new)', 
                        ),
                        'default' => '1'
                    ),

                )
            );


            $this->sections[] = array(
                'icon'      => 'el-icon-lines',
                'title'     => __('Header', 'maskitto-light'),
                'fields'    => array(

                    array(
                        'id'        => 'header-top-accent',
                        'type'      => 'switch',
                        'title'     => __('Social bar accent background', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable accent background color for social bar', 'maskitto-light'),
                        'default'   => false,
                    ),


                    array(
                        'id'       => 'header-layout',
                        'type'     => 'radio',
                        'title'    => __('Header Layout', 'maskitto-light'), 
                        'subtitle' => __('Choose header layout type', 'maskitto-light'),
                        'options'  => array(
                            '1' => 'Standard ', 
                            '5' => 'Standard Large', 
                            '3' => 'Centered (for square-like logo)',
                            '2' => 'Centered Large (for square-like logo)', 
                            '4' => 'Inverted (logo in right side and menu in left)',
                        ),
                        'default' => '1'
                    ),

                    array(
                        'id'       => 'nacigation-dropdown',
                        'type'     => 'radio',
                        'title'    => __('Navigation Dropdown', 'maskitto-light'), 
                        'subtitle' => __('Choose navigation dropdown action', 'maskitto-light'),
                        'options'  => array(
                            '1' => 'On hover - supports 3 navigation levels', 
                            '2' => 'On click (without parent URL) - supports 2 navigation levels', 
                        ),
                        'default' => '1'
                    ),

                    array(
                        'id'        => 'header-sticky',
                        'type'      => 'switch',
                        'title'     => __('Sticky Header', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable sticky header', 'maskitto-light'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'header-contacts',
                        'type'      => 'switch',
                        'title'     => __('Contact  Information', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable contact information in header', 'maskitto-light'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'header-contacts-mail',
                        'type'      => 'text',
                        'title'     => __('Contact Email', 'maskitto-light'),
                        'subtitle'  => __('Your website email adress', 'maskitto-light'),
                        'validate'  => 'email',
                        'default'   => 'info@mywebsite.com',
                    ),

                    array(
                        'id'        => 'header-contacts-phone',
                        'type'      => 'text',
                        'title'     => __('Contact Phone', 'maskitto-light'),
                        'subtitle'  => __('Your website phone number', 'maskitto-light'),
                        'msg'       => 'custom error message',
                        'default'   => '800-2312-323',
                    ),

                    array(
                        'id'        => 'header-social',
                        'type'      => 'switch',
                        'title'     => __('Social Icons', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable contact social icons in header', 'maskitto-light'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'header-social-links',
                        'type'      => 'switch',
                        'title'     => __('Open Social Links In New Tab', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable option to open social icon links in new tab', 'maskitto-light'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'header-search',
                        'type'      => 'switch',
                        'title'     => __('Header Search', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable header search form', 'maskitto-light'),
                        'default'   => false,
                    ),

                )
            );


            $this->sections[] = array(
                'icon'      => 'el-icon-twitter',
                'title'     => __('Social', 'maskitto-light'),
                'fields'    => array(

                    array(
                        'id'        => 'social-network-twitter',
                        'type'      => 'text',
                        'title'     => __('Twitter', 'maskitto-light'),
                        'subtitle'  => __('Your Twitter URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'default'   => 'http://www.twitter.com/TheShufflehound',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-facebook',
                        'type'      => 'text',
                        'title'     => __('Facebook', 'maskitto-light'),
                        'subtitle'  => __('Your Facebook page/profile URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-google',
                        'type'      => 'text',
                        'title'     => __('Google+', 'maskitto-light'),
                        'subtitle'  => __('Your Google+ URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-youtube',
                        'type'      => 'text',
                        'title'     => __('Youtube', 'maskitto-light'),
                        'subtitle'  => __('Your Youtube URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-instagram',
                        'type'      => 'text',
                        'title'     => __('Instagram', 'maskitto-light'),
                        'subtitle'  => __('Your Instagram URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-flickr',
                        'type'      => 'text',
                        'title'     => __('Flickr', 'maskitto-light'),
                        'subtitle'  => __('Your Flickr URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-foursquare',
                        'type'      => 'text',
                        'title'     => __('Foursquare', 'maskitto-light'),
                        'subtitle'  => __('Your Foursquare URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-skype',
                        'type'      => 'text',
                        'title'     => __('Skype', 'maskitto-light'),
                        'subtitle'  => __('Your Skype profile URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-vk',
                        'type'      => 'text',
                        'title'     => __('VK', 'maskitto-light'),
                        'subtitle'  => __('Your VK profile URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-wordpress',
                        'type'      => 'text',
                        'title'     => __('Wordpress', 'maskitto-light'),
                        'subtitle'  => __('Your Wordpress profile URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'default'   => 'https://wordpress.org/themes/maskitto-light',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-linkedin',
                        'type'      => 'text',
                        'title'     => __('LinkedIn', 'maskitto-light'),
                        'subtitle'  => __('Your LinkedIn profile URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-pinterest',
                        'type'      => 'text',
                        'title'     => __('Pinterest', 'maskitto-light'),
                        'subtitle'  => __('Your Pinterest profile URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-behance',
                        'type'      => 'text',
                        'title'     => __('Behance', 'maskitto-light'),
                        'subtitle'  => __('Your Behance profile URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                    array(
                        'id'        => 'social-network-tumblr',
                        'type'      => 'text',
                        'title'     => __('Tumblr', 'maskitto-light'),
                        'subtitle'  => __('Your Tumblr profile URL', 'maskitto-light'),
                        'validate'  => 'url',
                        'text_hint' => array(
                            'title'     => '',
                            'content'   => __('Please enter a valid URL in this field.', 'maskitto-light')
                        )
                    ),

                )
            );


            $this->sections[] = array(
                'icon'      => 'el-icon-file-edit',
                'title'     => __('Blog', 'maskitto-light'),
                'fields'    => array(

                    array(
                        'id'        => 'blog-widgets',
                        'type'      => 'switch',
                        'title'     => __('Show Widgets', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable blog page widgets in the right side', 'maskitto-light'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'blog-categories',
                        'type'      => 'switch',
                        'title'     => __('Show Categories', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable blog categories on top of blog pages', 'maskitto-light'),
                        'default'   => true,
                    ),

                    array(
                        'id'       => 'blog-layout',
                        'type'     => 'radio',
                        'title'    => __('Blog Style', 'maskitto-light'), 
                        'subtitle' => __('Choose blog style (includes blog and post page)', 'maskitto-light'),
                        'options'  => array(
                            '1' => 'Standard', 
                            '2' => 'Old', 
                        ),
                        'default' => '1'
                    ),

                    array(
                        'id'        => 'blog-thumb-status',
                        'type'      => 'switch',
                        'title'     => __('Blog Slide', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable "name of blog section" block', 'maskitto-light'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'blog-thumb-title',
                        'type'      => 'text',
                        'title'     => __('Blog Slide Name', 'maskitto-light'),
                        'subtitle'  => __('Enter name', 'maskitto-light'),
                        'default'   => 'A brief revision of my vision',
                    ),

                    array(
                        'id'        => 'blog-thumb-url',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Blog Slide Image', 'maskitto-light'),
                        'compiler'  => 'true',
                        'subtitle'  => __('Upload image', 'maskitto-light'),
                        'default'   => array('url' => get_template_directory_uri().'/img/bloghead.jpg'),
                    ),

                    array(
                        'id'        => 'blog-thumb-height',
                        'type'      => 'slider',
                        'title'     => __('Blog Slide Height', 'maskitto-light'),
                        'subtitle'  => __('Choose slide height', 'maskitto-light'),
                        "default"   => 280,
                        "min"       => 120,
                        "step"      => 1,
                        "max"       => 560,
                        'display_value' => 'label'
                    ),

                )
            );


            $this->sections[] = array(
                'icon'      => 'el-icon-envelope',
                'title'     => __('Contacts', 'maskitto-light'),
                'fields'    => array(

                    array(
                        'id'        => 'contacts-social-icons',
                        'type'      => 'switch',
                        'title'     => __('Contact Social Icons', 'maskitto-light'),
                        'subtitle'  => __('Enable or disable option to show social icons', 'maskitto-light'),
                        'default'   => true,
                    ),

                )
            );


            $this->sections[] = array(
                'icon'      => 'el-icon-puzzle',
                'title'     => __('Other', 'maskitto-light'),
                'fields'    => array(

                    array(
                        'id'        => 'admin-login-logo',
                        'type'      => 'media',
                        'url'       =>  true,
                        'title'     => __('Admin Login Page Logo', 'maskitto-light'),
                        'subtitle'  => __('Upload logo', 'maskitto-light'),
                    ),

                    array(
                        'id'        => 'preview-word-limit',
                        'type'      => 'slider',
                        'title'     => __('Preview word limit', 'maskitto-light'),
                        'subtitle'  => __('Limit preview words length in blog page or others', 'maskitto-light'),
                        "default"   => 55,
                        "min"       => 10,
                        "step"      => 1,
                        "max"       => 70,
                        'display_value' => 'label'
                    ),

                )
            );


            $this->sections[] = array(
                'title'     => __('Import / Export', 'maskitto-light'),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', 'maskitto-light'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );


        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'maskitto-light'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'maskitto-light')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'maskitto-light'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'maskitto-light')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'maskitto-light');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(


                'opt_name'         => 'maskitto_light',
                'display_name'     => $theme->get('Name'),
                'display_version'  => $theme->get('Version'),
                'menu_type'        => 'menu',
                'allow_sub_menu'   => true,
                'menu_title'       => __('Maskitto Light Options', 'maskitto-light'),
                'page_title'       => __('Maskitto Light Options', 'maskitto-light'),
                'google_api_key'   => '',
                'async_typography' => true,
                'admin_bar'        => true,
                'global_variable'  => '',
                'dev_mode'         => false,
                'customizer'       => true,

                'page_priority'    => null,
                'page_parent'      => 'themes.php',
                'page_permissions' => 'manage_options',
                'menu_icon'        => '',
                'last_tab'         => '',
                'page_icon'        => 'icon-themes',
                'page_slug'        => 'maskitto_options',
                'save_defaults'    => true,
                'default_show'     => false,
                'default_mark'     => '',
                'show_import_export' => true,
                'class'            => '',

                'transient_time'   => 60 * MINUTE_IN_SECONDS,
                'output'           => true,
                'output_tag'       => true,
                'footer_credit'    => '',
                'network_admin'    => false,
                'network_sites'    => true,

                'hints'            => array(
                    'icon'            => 'icon-question-sign',
                    'icon_position'   => 'right',
                    'icon_color'      => 'lightgray',
                    'icon_size'       => 'normal',
         
                    'tip_style'    => array(
                        'color'       => 'light',
                        'shadow'      => true,
                        'rounded'     => false,
                        'style'       => '',
                    ),
                    'tip_position' => array(
                        'my'          => 'top left',
                        'at'          => 'bottom right',
                    ),
                    'tip_effect'   => array(
                        'show'        => array(
                            'effect'      => 'slide',
                            'duration'    => '500',
                            'event'       => 'mouseover',
                        ),
                        'hide'        => array(
                            'effect'      => 'slide',
                            'duration'    => '500',
                            'event'       => 'click mouseleave',
                        ),
                    ),
                'intro_text'  => '',
                'footer_text' => '',
                )
            );

            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/TheShufflehound',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );

        }

    }
    
    global $reduxConfig;
    $reduxConfig = new maskitto_light_Redux_Framework_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('maskitto_light_my_custom_field')):
    function maskitto_light_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('maskitto_light_validate_callback_function')):
    function maskitto_light_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;

?>