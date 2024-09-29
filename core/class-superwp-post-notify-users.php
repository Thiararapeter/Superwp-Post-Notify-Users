<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This is the main class that is responsible for registering
 * the core functions, including the files and setting up all features. 
 * 
 * To add a new class, here's what you need to do: 
 * 1. Add your new class within the following folder: core/includes/classes
 * 2. Create a new variable you want to assign the class to (as e.g. public $helpers)
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new Superwp_Post_Notify_Users_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'Superwp_Post_Notify_Users' ) ) :

	/**
	 * Main Superwp_Post_Notify_Users Class.
	 *
	 * @package		SUPERWPPOS
	 * @subpackage	Classes/Superwp_Post_Notify_Users
	 * @since		1.0.7
	 * @author		Thiarara
	 */
	final class Superwp_Post_Notify_Users {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.7
		 * @var		object|Superwp_Post_Notify_Users
		 */
		private static $instance;

		/**
		 * SUPERWPPOS helpers object.
		 *
		 * @access	public
		 * @since	1.0.7
		 * @var		object|Superwp_Post_Notify_Users_Helpers
		 */
		public $helpers;

		/**
		 * SUPERWPPOS settings object.
		 *
		 * @access	public
		 * @since	1.0.7
		 * @var		object|Superwp_Post_Notify_Users_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.7
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'superwp-post-notify-users' ), '1.0.7' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.7
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'superwp-post-notify-users' ), '1.0.7' );
		}

		/**
		 * Main Superwp_Post_Notify_Users Instance.
		 *
		 * Insures that only one instance of Superwp_Post_Notify_Users exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.7
		 * @static
		 * @return		object|Superwp_Post_Notify_Users	The one true Superwp_Post_Notify_Users
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Superwp_Post_Notify_Users ) ) {
				self::$instance					= new Superwp_Post_Notify_Users;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Superwp_Post_Notify_Users_Helpers();
				self::$instance->settings		= new Superwp_Post_Notify_Users_Settings();

				//Fire the plugin logic
				new Superwp_Post_Notify_Users_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'SUPERWPPOS/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.7
		 * @return  void
		 */
		private function includes() {
			require_once SUPERWPPOS_PLUGIN_DIR . 'core/includes/classes/class-superwp-post-notify-users-helpers.php';
			require_once SUPERWPPOS_PLUGIN_DIR . 'core/includes/classes/class-superwp-post-notify-users-settings.php';

			require_once SUPERWPPOS_PLUGIN_DIR . 'core/includes/classes/class-superwp-post-notify-users-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.7
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.7
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'superwp-post-notify-users', FALSE, dirname( plugin_basename( SUPERWPPOS_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

	// Add the admin menu for the settings page
add_action('admin_menu', 'superwp_post_notify_users_menu');
function superwp_post_notify_users_menu() {
    add_menu_page(
        'Post Notify Users',
        'Post Notify Users',
        'manage_options',
        'superwp-post-notify-users',
        'superwp_post_notify_users_settings_page',
        'dashicons-email-alt',
        6
    );
}

// Create the settings page content
function superwp_post_notify_users_settings_page() {
    ?>
    <div class="wrap">
        <h1>Superwp Post Notify Users</h1>
        <p>This plugin notifies users with specific roles by email when a new post or an edited post is published. You can configure the user roles, post types, customize the email subject, template, and schedule the notifications.</p>

        <?php
        // Display error messages if any
        settings_errors('superwp_post_notify_users_messages');
        ?>

        <form method="post" action="options.php">
            <?php
            settings_fields('superwp_post_notify_users_settings');
            do_settings_sections('superwp-post-notify-users');
            submit_button('Save Settings');
            ?>
        </form>

        <h3>Available Email Placeholders:</h3>
        <ul>
            <li><code>[user]</code> - The user's name</li>
            <li><code>[user_email]</code> - The user's email</li>
            <li><code>[post_title]</code> - The post title</li>
            <li><code>[post_url]</code> - The post URL</li>
            <li><code>[post_author]</code> - The post author</li>
            <li><code>[post_date]</code> - The post publication date</li>
            <li><code>[site_name]</code> - The website name</li>
        </ul>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'superwp_post_notify_users_settings_init');
function superwp_post_notify_users_settings_init() {
    // Notification settings section
    add_settings_section('superwp_notify_users_section', 'Notification Settings', null, 'superwp-post-notify-users');
    
    // Roles to notify
    add_settings_field('superwp_notify_roles', 'Roles to Notify', 'superwp_notify_roles_render', 'superwp-post-notify-users', 'superwp_notify_users_section');
    register_setting('superwp_post_notify_users_settings', 'superwp_notify_roles', array('sanitize_callback' => 'superwp_validate_roles'));

    // Post types to notify
    add_settings_field('superwp_notify_post_types', 'Custom Post Types', 'superwp_notify_post_types_render', 'superwp-post-notify-users', 'superwp_notify_users_section');
    register_setting('superwp_post_notify_users_settings', 'superwp_notify_post_types', array('sanitize_callback' => 'superwp_validate_post_types'));

    // Notify on new and edited posts
    add_settings_field('superwp_notify_trigger', 'Notify on', 'superwp_notify_trigger_render', 'superwp-post-notify-users', 'superwp_notify_users_section');
    register_setting('superwp_post_notify_users_settings', 'superwp_notify_trigger', array('sanitize_callback' => 'superwp_validate_trigger'));

    // Email subject for new posts
    add_settings_field('superwp_email_subject_new', 'Email Subject for New Post', 'superwp_email_subject_new_render', 'superwp-post-notify-users', 'superwp_notify_users_section');
    register_setting('superwp_post_notify_users_settings', 'superwp_email_subject_new', array('sanitize_callback' => 'superwp_validate_email_subject'));

    // Email subject for edited posts
    add_settings_field('superwp_email_subject_edited', 'Email Subject for Edited Post', 'superwp_email_subject_edited_render', 'superwp-post-notify-users', 'superwp_notify_users_section');
    register_setting('superwp_post_notify_users_settings', 'superwp_email_subject_edited', array('sanitize_callback' => 'superwp_validate_email_subject'));

    // Email template for new posts
    add_settings_field('superwp_email_template_new', 'Email Template for New Post', 'superwp_email_template_new_render', 'superwp-post-notify-users', 'superwp_notify_users_section');
    register_setting('superwp_post_notify_users_settings', 'superwp_email_template_new', array('sanitize_callback' => 'superwp_validate_email_template'));

    // Email template for edited posts
    add_settings_field('superwp_email_template_edited', 'Email Template for Edited Post', 'superwp_email_template_edited_render', 'superwp-post-notify-users', 'superwp_notify_users_section');
    register_setting('superwp_post_notify_users_settings', 'superwp_email_template_edited', array('sanitize_callback' => 'superwp_validate_email_template'));

    // Notification delay
    add_settings_field('superwp_notify_delay', 'Schedule Notification (in hours)', 'superwp_notify_delay_render', 'superwp-post-notify-users', 'superwp_notify_users_section');
    register_setting('superwp_post_notify_users_settings', 'superwp_notify_delay', array('sanitize_callback' => 'superwp_validate_notify_delay'));
}

// Validation functions
function superwp_validate_roles($input) {
    if (empty($input) || !is_array($input)) {
        add_settings_error('superwp_post_notify_users_messages', 'superwp_notify_roles', 'You must select at least one role to notify.', 'error');
        return array(); // Return an empty array instead of the current option
    }
    return $input;
}

function superwp_validate_post_types($input) {
    if (empty($input) || !is_array($input)) {
        add_settings_error('superwp_post_notify_users_messages', 'superwp_notify_post_types', 'You must select at least one post type.', 'error');
        return array(); // Return an empty array instead of the current option
    }
    return $input;
}

function superwp_validate_trigger($input) {
    if (!in_array($input, array('new_post', 'edited_post', 'both'))) {
        add_settings_error('superwp_post_notify_users_messages', 'superwp_notify_trigger', 'Invalid trigger option selected.', 'error');
        return get_option('superwp_notify_trigger');
    }
    return $input;
}

function superwp_validate_email_subject($input) {
    if (empty($input)) {
        add_settings_error('superwp_post_notify_users_messages', 'superwp_email_subject', 'Email subject cannot be empty.', 'error');
        return get_option('superwp_email_subject');
    }
    return sanitize_text_field($input);
}

function superwp_validate_email_template($input) {
    if (empty($input)) {
        add_settings_error('superwp_post_notify_users_messages', 'superwp_email_template', 'Email template cannot be empty.', 'error');
        return get_option('superwp_email_template');
    }
    return wp_kses_post($input);
}

function superwp_validate_notify_delay($input) {
    $input = intval($input);
    if ($input < 0) {
        add_settings_error('superwp_post_notify_users_messages', 'superwp_notify_delay', 'Notification delay must be a non-negative number.', 'error');
        return get_option('superwp_notify_delay');
    }
    return $input;
}

// Render functions for settings fields
function superwp_notify_roles_render() {
    $roles = get_editable_roles();
    $saved_roles = get_option('superwp_notify_roles', array());

    foreach ($roles as $role_slug => $role_details) {
        $checked = in_array($role_slug, $saved_roles) ? 'checked' : '';
        echo '<label><input type="checkbox" name="superwp_notify_roles[]" value="'.esc_attr($role_slug).'" '.$checked.'> '.esc_html($role_details['name']).'</label><br>';
    }
    echo '<p class="description">Select at least one role to notify.</p>';
}

function superwp_notify_post_types_render() {
    $post_types = get_post_types(array('public' => true), 'objects');
    $saved_post_types = get_option('superwp_notify_post_types', array());

    foreach ($post_types as $post_type) {
        $checked = in_array($post_type->name, $saved_post_types) ? 'checked' : '';
        echo '<label><input type="checkbox" name="superwp_notify_post_types[]" value="'.esc_attr($post_type->name).'" '.$checked.'> '.esc_html($post_type->label).'</label><br>';
    }
    echo '<p class="description">Select at least one post type.</p>';
}

function superwp_notify_trigger_render() {
    $trigger_option = get_option('superwp_notify_trigger', 'new_post');
    ?>
    <label><input type="radio" name="superwp_notify_trigger" value="new_post" <?php checked($trigger_option, 'new_post'); ?> required> New Post</label><br>
    <label><input type="radio" name="superwp_notify_trigger" value="edited_post" <?php checked($trigger_option, 'edited_post'); ?> required> Edited Post</label><br>
    <label><input type="radio" name="superwp_notify_trigger" value="both" <?php checked($trigger_option, 'both'); ?> required> Both</label>
    <p class="description">Choose when to send notifications.</p>
    <?php
}

function superwp_email_subject_new_render() {
    $subject = get_option('superwp_email_subject_new', 'New Post Published: [post_title]');
    echo '<input type="text" name="superwp_email_subject_new" value="'.esc_attr($subject).'" class="regular-text">';
}

function superwp_email_subject_edited_render() {
    $subject = get_option('superwp_email_subject_edited', 'Post Edited: [post_title]');
    echo '<input type="text" name="superwp_email_subject_edited" value="'.esc_attr($subject).'" class="regular-text">';
}

function superwp_email_template_new_render() {
    $template = get_option('superwp_email_template_new', 'Hello [user], a new post titled [post_title] has been published. Read it here: [post_url].');
    echo '<textarea name="superwp_email_template_new" class="large-text" rows="5">'.esc_textarea($template).'</textarea>';
}

function superwp_email_template_edited_render() {
    $template = get_option('superwp_email_template_edited', 'Hello [user], the post titled [post_title] has been updated. Check it out here: [post_url].');
    echo '<textarea name="superwp_email_template_edited" class="large-text" rows="5">'.esc_textarea($template).'</textarea>';
}

function superwp_notify_delay_render() {
    $delay = get_option('superwp_notify_delay', 0);
    echo '<input type="number" name="superwp_notify_delay" value="'.esc_attr($delay).'" min="0" class="small-text">';
    echo '<p class="description">Set the notification delay (in hours) after the post is published or edited. 0 means immediate notification.</p>';
}

// Hook into post save to send notifications
add_action('save_post', 'superwp_notify_users_on_post_save', 10, 3);

function superwp_notify_users_on_post_save($post_id, $post, $update) {
    // Security check to ensure this is not an autosave or revision, and that the post is published
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id) || $post->post_status !== 'publish') {
        return;
    }

    // Ensure this does not affect API responses
    if (defined('DOING_AJAX') && DOING_AJAX || defined('DOING_CRON') && DOING_CRON) {
        return;
    }

    // Log the function call for debugging
    error_log('superwp_notify_users_on_post_save called for post ID: ' . $post_id);

    $trigger_option = get_option('superwp_notify_trigger', 'new_post');

    // Only notify based on the selected trigger (new post or edited post or both)
    if (($trigger_option === 'new_post' && $update) || ($trigger_option === 'edited_post' && !$update)) {
        return;
    }

    // Fetch roles and post types settings
    $roles = get_option('superwp_notify_roles', array());
    $post_types = get_option('superwp_notify_post_types', array());

    // Ensure the post type matches the allowed post types
    if (!in_array($post->post_type, $post_types)) {
        return;
    }

    // Get the user details for each role
    $users = get_users(array(
        'role__in' => $roles,
        'fields' => array('display_name', 'user_email')
    ));

    // Get the email subject and template based on whether it's a new post or edited post
    $email_subject = $update ? get_option('superwp_email_subject_edited', 'Post Edited: [post_title]') : get_option('superwp_email_subject_new', 'New Post Published: [post_title]');
    $email_template = $update ? get_option('superwp_email_template_edited', 'Hello [user], the post titled [post_title] has been updated. Check it out here: [post_url].') : get_option('superwp_email_template_new', 'Hello [user], a new post titled [post_title] has been published. Read it here: [post_url].');

    // Replace placeholders in the email template and subject
    foreach ($users as $user) {
        $email_content = str_replace(
            array('[user]', '[user_email]', '[post_title]', '[post_url]', '[post_author]', '[post_date]', '[site_name]'),
            array($user->display_name, $user->user_email, $post->post_title, get_permalink($post_id), get_the_author_meta('display_name', $post->post_author), get_the_date('', $post_id), get_bloginfo('name')),
            $email_template
        );

        $email_subject = str_replace(
            array('[post_title]', '[site_name]'),
            array($post->post_title, get_bloginfo('name')),
            $email_subject
        );

        // Schedule or send the email
        $delay = get_option('superwp_notify_delay', 0);
        if ($delay > 0) {
            wp_schedule_single_event(time() + $delay * 3600, 'superwp_send_email_notification', array($user->user_email, $email_subject, $email_content));
        } else {
            wp_mail($user->user_email, $email_subject, $email_content);
        }
    }

    // Log completion of the function
    error_log('superwp_notify_users_on_post_save completed for post ID: ' . $post_id);
}

endif; // End if class_exists check.