<?php
/**
 * @package     Alethemes Framework (Import/Export tool)
 * @author      Alexandr Sochirca (CRIK0VA)
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Aletheme_export_import_options' ) ) {

    class Aletheme_export_import_options
    {

        public $ale_import_results;

        function aletheme_export_import_options()
        {
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_filter('upload_mimes', array(&$this, 'ale_add_mime_types'));
        }

        function admin_menu()
        {
            $page = add_theme_page(
                __('Import/Export', 'aletheme'),
                __('Import/Export', 'aletheme'),
                'manage_options',
                'backup-options',
                array(&$this, 'options_page')
            );

            add_action("load-{$page}", array(&$this, 'import_export'));
        }

        function ale_add_mime_types( $mime_types ) {
            $mime_types['dat'] = 'application/json';
            return $mime_types;
        }

        function ale_available_widgets() {

            global $wp_registered_widget_controls;

            $widget_controls = $wp_registered_widget_controls;

            $available_widgets = array();

            foreach ( $widget_controls as $widget ) {

                if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

                    $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                    $available_widgets[$widget['id_base']]['name'] = $widget['name'];

                }
            }
            return apply_filters( 'ale_available_widgets', $available_widgets );

        }

        function ale_generate_export_data() {

            // Get all available widgets site supports
            $available_widgets = $this->ale_available_widgets();

            // Get all widget instances for each widget
            $widget_instances = array();
            foreach ( $available_widgets as $widget_data ) {

                // Get all instances for this ID base
                $instances = get_option( 'widget_' . $widget_data['id_base'] );

                // Have instances
                if ( ! empty( $instances ) ) {

                    // Loop instances
                    foreach ( $instances as $instance_id => $instance_data ) {

                        // Key is ID (not _multiwidget)
                        if ( is_numeric( $instance_id ) ) {
                            $unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;
                            $widget_instances[$unique_instance_id] = $instance_data;
                        }

                    }

                }

            }

            // Gather sidebars with their widget instances
            $sidebars_widgets = get_option( 'sidebars_widgets' ); // get sidebars and their unique widgets IDs
            $sidebars_widget_instances = array();
            foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

                // Skip inactive widgets
                if ( 'wp_inactive_widgets' == $sidebar_id ) {
                    continue;
                }

                // Skip if no data or not an array (array_version)
                if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
                    continue;
                }

                // Loop widget IDs for this sidebar
                foreach ( $widget_ids as $widget_id ) {

                    // Is there an instance for this widget ID?
                    if ( isset( $widget_instances[$widget_id] ) ) {

                        // Add to array
                        $sidebars_widget_instances[$sidebar_id][$widget_id] = $widget_instances[$widget_id];

                    }

                }

            }

            // Filter pre-encoded data
            $data = apply_filters( 'ale_unencoded_export_data', $sidebars_widget_instances );

            // Encode the data for file contents
            $encoded_data = json_encode( $data );

            // Return contents
            return apply_filters( 'ale_generate_export_data', $encoded_data );

        }

        function import_export()
        {
            if (isset($_GET['action']) && ($_GET['action'] == 'download')) {
                header("Cache-Control: public, must-revalidate");
                header("Pragma: hack");
                header("Content-Type: text/plain");
                header('Content-Disposition: attachment; filename="theme-options-' . date("dMy") . '.dat"');
                echo serialize($this->_get_options());
                die();
            }
            if (isset($_POST['upload']) && check_admin_referer('alethemesframework_restoreOptions', 'alethemesframework_restoreOptions')) {
                if ($_FILES["file"]["error"] > 0) {
                    wp_die(
                        __( 'Select a <strong>.dat</strong> file with Theme Options to upload.', 'aletheme' ),
                        '',
                        array( 'back_link' => true )
                    );
                } else {


                    $widget_file = json_decode(file_get_contents($_FILES["file"]["tmp_name"]));
                    if(!is_object($widget_file)){
                        $options = unserialize(file_get_contents($_FILES["file"]["tmp_name"]));

                        if ($options) {
                            foreach ($options as $option) {
                                update_option($option->option_name, unserialize($option->option_value));
                            }
                        }
                    } else {
                        wp_die(
                            __( 'Import data could not be read. Please try a different file. Make sure you upload a <strong>Theme Options</strong> file and not Widgets file.', 'aletheme' ),
                            '',
                            array( 'back_link' => true )
                        );
                    }

                }
                wp_redirect(admin_url('themes.php?page=backup-options&success_options=true'));
                exit;
            }
            if (isset($_POST['upload_widget']) && check_admin_referer('alethemeswidget_restoreOptions', 'alethemeswidget_restoreOptions')) {
                if ($_FILES["ale_import_file"]["error"] > 0) {

                    // Show error if no file.
                    wp_die(
                        __( 'Select a <strong>.dat</strong> file with Widgets to upload.', 'aletheme' ),
                        '',
                        array( 'back_link' => true )
                    );
                } else {

                    $uploaded_file = $_FILES['ale_import_file'];

                    // Check file type
                    // This will also fire if no file uploaded
                    $wp_filetype = wp_check_filetype_and_ext( $uploaded_file['tmp_name'], $uploaded_file['name'], false );
                    if ( 'dat' != $wp_filetype['ext'] && ! wp_match_mime_types( 'dat', $wp_filetype['type'] ) ) {
                        wp_die(
                            __( 'You must upload a <b>.dat</b> file generated by this theme.', 'aletheme' ),
                            '',
                            array( 'back_link' => true )
                        );
                    }

                    // Check and move file to uploads dir, get file data
                    // Will show die with WP errors if necessary (file too large, quota exceeded, etc.)
                    $overrides = array( 'test_form' => false );
                    $file_data = wp_handle_upload( $uploaded_file, $overrides );
                    if ( isset( $file_data['error'] ) ) {
                        wp_die(
                            $file_data['error'],
                            '',
                            array( 'back_link' => true )
                        );
                    }

                    // Process import file
                    $this->ale_process_import_file( $file_data['file'] );

                }
                wp_redirect(admin_url('themes.php?page=backup-options&success_widget=true'));
                exit;
            }
            if ( ! empty( $_GET['export'] ) ) {

                // Generate export file contents
                $file_contents = $this->ale_generate_export_data();
                $filesize = strlen( $file_contents );

                // Headers to prompt "Save As"
                header( 'Content-Type: application/octet-stream' );
                header( 'Content-Disposition: attachment; filename="widgets-' . date("dMy") . '.dat"' );
                header( 'Expires: 0' );
                header( 'Cache-Control: must-revalidate' );
                header( 'Pragma: public' );
                header( 'Content-Length: ' . $filesize );

                // Clear buffering just in case
                @ob_end_clean();
                flush();

                // Output file contents
                echo $file_contents;

                // Stop execution
                exit;

            }
        }

        function ale_process_import_file( $file ) {

            global $ale_import_results;

            // File exists?
            if ( ! file_exists( $file ) ) {
                wp_die(
                    __( 'Import file could not be found. Please try again.', 'aletheme' ),
                    '',
                    array( 'back_link' => true )
                );
            }

            // Get file contents and decode
            $data = file_get_contents( $file );
            $data = json_decode( $data );

            // Delete import file
            unlink( $file );

            // Import the widget data
            // Make results available for display on import/export page
            $this->ale_import_results = $this->ale_import_data( $data );


        }

        function ale_import_data( $data ) {

            global $wp_registered_sidebars;

            // Have valid data?
            // If no data or could not decode
            if ( empty( $data ) || ! is_object( $data ) ) {
                wp_die(
                    __( 'Import data could not be read. Please try a different file. Make sure you upload a <strong>Widgets</strong> file and not Theme Options file.', 'aletheme' ),
                    '',
                    array( 'back_link' => true )
                );
            }

            // Hook before import
            do_action( 'ale_before_import' );
            $data = apply_filters( 'ale_import_data', $data );

            // Get all available widgets site supports
            $available_widgets = $this->ale_available_widgets();

            // Get all existing widget instances
            $widget_instances = array();
            foreach ( $available_widgets as $widget_data ) {
                $widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
            }

            // Begin results
            $results = array();

            // Loop import data's sidebars
            foreach ( $data as $sidebar_id => $widgets ) {

                // Skip inactive widgets
                // (should not be in export file)
                if ( 'wp_inactive_widgets' == $sidebar_id ) {
                    continue;
                }

                // Check if sidebar is available on this site
                // Otherwise add widgets to inactive, and say so
                if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
                    $sidebar_available = true;
                    $use_sidebar_id = $sidebar_id;
                    $sidebar_message_type = 'success';
                    $sidebar_message = '';
                } else {
                    $sidebar_available = false;
                    $use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
                    $sidebar_message_type = 'error';
                    $sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)', 'aletheme' );
                }

                // Result for sidebar
                $results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
                $results[$sidebar_id]['message_type'] = $sidebar_message_type;
                $results[$sidebar_id]['message'] = $sidebar_message;
                $results[$sidebar_id]['widgets'] = array();

                // Loop widgets
                foreach ( $widgets as $widget_instance_id => $widget ) {

                    $fail = false;

                    // Get id_base (remove -# from end) and instance ID number
                    $id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
                    $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

                    // Does site support this widget?
                    if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
                        $fail = true;
                        $widget_message_type = 'error';
                        $widget_message = __( 'Site does not support widget', 'aletheme' ); // explain why widget not imported
                    }

                    // Filter to modify settings object before conversion to array and import
                    // Leave this filter here for backwards compatibility with manipulating objects (before conversion to array below)
                    // Ideally the newer ale_widget_settings_array below will be used instead of this
                    $widget = apply_filters( 'ale_widget_settings', $widget ); // object

                    // Convert multidimensional objects to multidimensional arrays
                    // Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
                    // Without this, they are imported as objects and cause fatal error on Widgets page
                    // If this creates problems for plugins that do actually intend settings in objects then may need to consider other approach: https://wordpress.org/support/topic/problem-with-array-of-arrays
                    // It is probably much more likely that arrays are used than objects, however
                    $widget = json_decode( json_encode( $widget ), true );

                    // Filter to modify settings array
                    // This is preferred over the older ale_widget_settings filter above
                    // Do before identical check because changes may make it identical to end result (such as URL replacements)
                    $widget = apply_filters( 'ale_widget_settings_array', $widget );

                    // Does widget with identical settings already exist in same sidebar?
                    if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

                        // Get existing widgets in this sidebar
                        $sidebars_widgets = get_option( 'sidebars_widgets' );
                        $sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

                        // Loop widgets with ID base
                        $single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
                        foreach ( $single_widget_instances as $check_id => $check_widget ) {

                            // Is widget in same sidebar and has identical settings?
                            if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

                                $fail = true;
                                $widget_message_type = 'warning';
                                $widget_message = __( 'Widget already exists', 'aletheme' ); // explain why widget not imported

                                break;

                            }

                        }

                    }

                    // No failure
                    if ( ! $fail ) {

                        // Add widget instance
                        $single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
                        $single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
                        $single_widget_instances[] = $widget; // add it

                        // Get the key it was given
                        end( $single_widget_instances );
                        $new_instance_id_number = key( $single_widget_instances );

                        // If key is 0, make it 1
                        // When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
                        if ( '0' === strval( $new_instance_id_number ) ) {
                            $new_instance_id_number = 1;
                            $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                            unset( $single_widget_instances[0] );
                        }

                        // Move _multiwidget to end of array for uniformity
                        if ( isset( $single_widget_instances['_multiwidget'] ) ) {
                            $multiwidget = $single_widget_instances['_multiwidget'];
                            unset( $single_widget_instances['_multiwidget'] );
                            $single_widget_instances['_multiwidget'] = $multiwidget;
                        }

                        // Update option with new widget
                        update_option( 'widget_' . $id_base, $single_widget_instances );

                        // Assign widget instance to sidebar
                        $sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
                        $new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
                        $sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
                        update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

                        // Success message
                        if ( $sidebar_available ) {
                            $widget_message_type = 'success';
                            $widget_message = __( 'Imported', 'aletheme' );
                        } else {
                            $widget_message_type = 'warning';
                            $widget_message = __( 'Imported to Inactive', 'aletheme' );
                        }

                    }

                    // Result for widget instance
                    $results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
                    $results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = ! empty( $widget['title'] ) ? $widget['title'] : __( 'No Title', 'aletheme' ); // show "No Title" if widget instance is untitled
                    $results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
                    $results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

                }

            }

            // Hook after import
            do_action( 'ale_after_import' );

            // Return results
            return apply_filters( 'ale_import_results', $results );

        }

        function options_page()
        { ?>

            <div class="wrap">
                <h2><?php _e('Import/Export Theme\'s Content', 'aletheme'); ?></h2>

                <div class="cf">
                    <div class="item-importer">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div id="backup-options">
                                <h3><?php _e('Export Theme Options', 'aletheme'); ?>:</h3>
                                <p>
                                    <?php _e( 'Click below to generate a <b>.dat</b> file for all active widgets.', 'aletheme' ); ?>
                                </p>
                                <p>
                                    <a href="?page=backup-options&action=download" class="button button-primary"><?php _e( 'Export Theme Options', 'aletheme' ); ?></a>
                                </p>

                                
                                <h3><?php _e('Import Theme Options', 'aletheme'); ?>:</h3>
                                <p>
                                    <?php _e( 'Please select a <b>.dat</b> file (<span class="red">with Theme Options</span>) generated by this theme.', 'aletheme' ); ?>
                                </p>
                                <p>
                                    <input type="file" name="file"/>
                                    <input type="submit" name="upload" id="upload" class="button-primary" value="<?php _e('Import Theme Options', 'aletheme'); ?>"/>
                                </p>
                                <?php if (function_exists('wp_nonce_field')) {
                                    wp_nonce_field(
                                        'alethemesframework_restoreOptions',
                                        'alethemesframework_restoreOptions'
                                    );
                                }

                                if(isset($_GET["success_options"]) && $_GET["success_options"]==true) {
                                    echo "<p class='saved success'>".__('Theme Options were imported with success.','aletheme')."</p>";
                                }?>

                            </div>
                        </form>
                    </div>

                    <div class="item-importer">
                        <h3 class="title"><?php _e( 'Export Widgets', 'aletheme' ); ?></h3>
                        <p>
                            <?php _e( 'Click below to generate a <b>.dat</b> file for all active widgets.', 'aletheme' ); ?>
                        </p>
                        <p>
                            <a href="?page=backup-options&export=1" id="wie-export-button" class="button button-primary"><?php _e( 'Export Widgets', 'aletheme' ); ?></a>
                        </p>



                        <h3 class="title"><?php _e( 'Import Widgets', 'aletheme' ); ?></h3>
                        <p>
                            <?php _e( 'Please select a <b>.dat</b> file (<span class="red">with widgets</span>) generated by this theme.', 'aletheme' ); ?>
                        </p>
                        <form method="post" enctype="multipart/form-data">
                            <p>
                                <input type="file" name="ale_import_file" id="wie-import-file" />
                                <input type="submit" name="upload_widget" id="upload_widget" class="button-primary" value="<?php _e('Import Widgets', 'aletheme'); ?>"/>
                            </p>
                            <?php if (function_exists('wp_nonce_field')) {
                                wp_nonce_field('alethemeswidget_restoreOptions', 'alethemeswidget_restoreOptions');
                            }

                            if(isset($_GET["success_widget"]) && $_GET["success_widget"]==true) {
                                echo "<p class='saved success'>".__('Widgets were imported with success.','aletheme')."</p>";
                            }?>

                        </form>
                    </div>

                    <div class="item-importer">
                        <h3 class="title"><?php _e( 'XML WP Demo Content', 'aletheme' ); ?></h3>
                        <p><?php _e('You should install the plugin <strong>WordPress Importer</strong> and import the xml file with demo content. This file will import into your theme all demo posts, pages and media files.','aletheme'); ?> </p>
                        <p><?php _e('You can install the plugin manually or go to <strong>Tools > Import > WordPress</strong> and install/import the demo content file.','aletheme'); ?></p>
                        <p><?php _e('Also make sure you checked the option "Download and import file attachments".','aletheme'); ?></p>
                        <p><a href="https://wordpress.org/plugins/wordpress-importer/" target="_blank" class="button-primary"><?php _e('Download WordPress Importer','aletheme'); ?></a></p>
                    </div>
                </div>

            </div>

        <?php }

        function _display_options()
        {
            $options = unserialize($this->_get_options());
        }

        function _get_options()
        {
            global $wpdb;
            return $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name = 'alethemesframework'"); // edit 'option_name' to match theme options
        }
    }
}

new Aletheme_export_import_options();
