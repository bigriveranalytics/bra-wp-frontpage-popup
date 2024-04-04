<?php
/**
 * Plugin Name: Frontpage Popup
 * Plugin URI: https://www.bigriveranalytics.com
 * Description: A custom plugin to add a modal to the frontpage of a Wordpress site.
 * Version: 1.0
 * Author: David Bergeron
 * Author URI: http://davidbergeron.dev
 */


// Add "Frontpage Popup" section to the admin menu
function fpp_add_admin_menu() {
	$page_hook_suffix = add_menu_page(
        'Frontpage Popup Settings', // Page title
        'Frontpage Popup', // Menu title
        'manage_options', // Capability
        'frontpage_popup_settings', // Menu slug
        'fpp_settings_page', // Function to display the settings page
        'dashicons-align-center', // Icon URL
        8 // Position
    );

    add_action('admin_enqueue_scripts', function($hook) use ($page_hook_suffix) {
        if ($hook !== $page_hook_suffix) {
            return;
        }

        wp_enqueue_style('frontpage-popup-styles', plugin_dir_url(__FILE__) . 'css/admin.css');
    });
}
add_action( 'admin_menu', 'fpp_add_admin_menu' );

// Init the settings we will need with to customize our modal.
function fpp_settings_init() {
	register_setting( 'fppPage', 'fpp_settings' );

    /* Declare defaults */
    add_option('fpp_settings', [
        'fpp_title_input' => 'Welcome to our site!',
        'fpp_body_textarea' => 'This is a custom modal that you can use to display important information to your users.',
        'fpp_accept_input' => 'Accept',
        'fpp_decline_input' => 'Decline',
        'fpp_decline_url' => 'https://www.google.com'
    ]);

    /* Create a settings group */
	add_settings_section(
		'fpp_fppPage_section',
		__( 'Customize your modal content', 'wordpress' ),
		'fpp_settings_section_callback',
		'fppPage'
	);


    /* Title */
	add_settings_field(
		'fpp_title_input',
		__( 'Modal Title', 'wordpress' ),
		'fpp_title_input_render',
		'fppPage',
		'fpp_fppPage_section'
	);

    /* Body */
	add_settings_field(
		'fpp_body_textarea',
		__( 'Modal Content', 'wordpress' ),
		'fpp_body_textarea_render',
		'fppPage',
		'fpp_fppPage_section'
	);

    /* Accept Button Text*/
    add_settings_field(
		'fpp_accept_input',
		__( 'Accept Button Text', 'wordpress' ),
		'fpp_accept_input_render',
		'fppPage',
		'fpp_fppPage_section'
	);

    /* Decline Button Text */
    add_settings_field(
		'fpp_decline_input',
		__( 'Decline Button Text', 'wordpress' ),
		'fpp_decline_input_render',
		'fppPage',
		'fpp_fppPage_section'
	);

     add_settings_field(
		'fpp_decline_url',
		__( 'Decline Redirect URL', 'wordpress' ),
		'fpp_decline_url_input_render',
		'fppPage',
		'fpp_fppPage_section'
	);
}
add_action( 'admin_init', 'fpp_settings_init' );

// Render the text input field for the modal title
function fpp_title_input_render() {
	$options = get_option( 'fpp_settings' );
	?>
	<input class="fpp_title-input" type='text' name='fpp_settings[fpp_title_input]' value='<?php echo $options['fpp_title_input']; ?>'>
	<?php
}

// Render the textarea for the modal content
function fpp_body_textarea_render() {
	$options = get_option( 'fpp_settings' );
	?>
	<textarea class="fpp_textarea" cols='40' rows='5' name='fpp_settings[fpp_body_textarea]'><?php echo $options['fpp_body_textarea']; ?></textarea>
	<?php
}

function fpp_accept_input_render() {
	$options = get_option( 'fpp_settings' );
	?>
	<input class="fpp_input" type='text' name='fpp_settings[fpp_accept_input]' value='<?php echo $options['fpp_accept_input']; ?>'>
	<?php
}

function fpp_decline_input_render() {
	$options = get_option( 'fpp_settings' );
	?>
	<input class="fpp_input" type='text' name='fpp_settings[fpp_decline_input]' value='<?php echo $options['fpp_decline_input']; ?>'>
	<?php
}

function fpp_decline_url_input_render() {
	$options = get_option( 'fpp_settings' );
	?>
	<input class="fpp_input" type='text' name='fpp_settings[fpp_decline_url]' value='<?php echo $options['fpp_decline_url']; ?>'>
    <div>This is where the user will be redirected if they decline to accept the terms.</div>
	<?php
}

// Callback for the settings section description
function fpp_settings_section_callback() {
	echo __( 'Please fill out the settings below to update your modal content.', 'wordpress' );
}

// The settings page layout
function fpp_settings_page() {
	?>
	<form action='options.php' method='post'>

		<h2 class="fpp_admin-title">Frontpage Popup Settings</h2>

		<?php
		settings_fields( 'fppPage' );
		do_settings_sections( 'fppPage' );
		submit_button();
		?>

	</form>
	<?php
}



function fpp_add_dialog() {
    if (is_using_divi_builder()) return;

    $options = get_option('fpp_settings');

    $title = $options['fpp_title_input'];
    $body = $options['fpp_body_textarea'];
    $yes_text = $options['fpp_accept_input'];
    $no_text = $options['fpp_decline_input'];
    $decline_url = $options['fpp_decline_url'];

    $logo_url = plugin_dir_url(__FILE__) . 'img/DGMTLogo.png';
    $header_bg_img_url = plugin_dir_url(__FILE__) . 'img/header-bg.png';

    if (is_front_page()) {
        ?>
        <dialog id="fpp-modal" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div class="modal-container">
                <header >
                    <div class="logo-container">
                        <img class="fpp-logo" src="<?= $logo_url ?>" alt="Data Governance and Management Logo" />
                        <span class="fpp-protocol">Entry Protocol</span>
                    </div>
                    <div class="fpp-title"><?= $title;?></div>
                </header>

                <p><?= nl2br($body); ?></p>

                <div class="fpp-btns">
                    <button id="fpp-accept"><?= $yes_text; ?></button>
                    <a id="fpp-decline" href="<?= $decline_url ?>"><?= $no_text; ?></a>
                </div>
            </div>
        </dialog>
        <style>
            #fpp-modal header::after {
                position: absolute;
                left:0;
                content: "";
                background: 0;
                background-image: url(<?= $header_bg_img_url ?>);
                background-repeat: repeat;
                width: 100%;
                height: 135px;
            }
        </style>
        <?php
    }
}
add_action('wp_footer', 'fpp_add_dialog');

// FRONTEND SECTION
// Register Javascript and CSS
function fpp_enqueue_scripts() {
    if (is_using_divi_builder()) return;

    if (is_front_page()) {
        // Enqueue JavaScript file
        wp_enqueue_script('fpp-script', plugin_dir_url(__FILE__) . 'js/main.js', [], '1.0', true);

        // Localize script with server-side data for checking acceptance
        wp_localize_script('fpp-script', 'popupParams', array(
            'accepted' => 'yes'
        ));

        wp_enqueue_style('fpp-modal-styles', plugin_dir_url(__FILE__) . 'css/modal.css', [], '1.0', 'all');
    }
}
add_action('wp_enqueue_scripts', 'fpp_enqueue_scripts');


/*
 * Check if the Divi Builder is being used
 *
 * Divi Builder uses a query string to load.
 * No, I don't know what et_fb and et_bfb stand for. Sorry :\
 */
function is_using_divi_builder() {
    return isset($_GET['et_fb']) || isset($_GET['et_bfb']);
}