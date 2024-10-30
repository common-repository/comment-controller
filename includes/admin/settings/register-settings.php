<?php
/**
 * Register settings
 *
 * @package     CommentController\Admin\Settings\Register
 * @since       1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Setup the settings menu
 *
 * @since       1.1.0
 * @param       array $menu The default menu settings.
 * @return      array $menu Our defined settings
 */
function comment_controller_add_menu( $menu ) {
	$menu['type']       = 'submenu';
	$menu['page_title'] = __( 'Comment Controller Settings', 'comment-controller' );
	$menu['menu_title'] = __( 'Comment Controller', 'comment-controller' );

	return $menu;
}
add_filter( 'comment_controller_menu', 'comment_controller_add_menu' );


/**
 * Define our settings tabs
 *
 * @since       3.0.0
 * @param       array $tabs The default tabs.
 * @return      array $tabs Our defined tabs
 */
function comment_controller_settings_tabs( $tabs ) {
	$tabs['settings'] = __( 'Settings', 'comment-controller' );
	$tabs['support']  = __( 'Support', 'comment-controller' );

	return $tabs;
}
add_filter( 'comment_controller_settings_tabs', 'comment_controller_settings_tabs' );


/**
 * Define settings sections
 *
 * @since       3.0.0
 * @param       array $sections The default sections.
 * @return      array $sections Our defined sections
 */
function comment_controller_registered_settings_sections( $sections ) {
	$sections = array(
		'settings' => apply_filters(
			'comment_controller_settings_sections_settings',
			array(
				'main' => __( 'General Settings', 'comment-controller' ),
			)
		),
		'support'  => apply_filters( 'comment_controller_settings_sections_support', array() ),
	);

	return $sections;
}
add_filter( 'comment_controller_registered_settings_sections', 'comment_controller_registered_settings_sections' );


/**
 * Disable save button on unsavable tabs
 *
 * @since       3.0.0
 * @return      array $tabs The updated tabs
 */
function comment_controller_define_unsavable_tabs() {
	$tabs = array( 'support' );

	return $tabs;
}
add_filter( 'comment_controller_unsavable_tabs', 'comment_controller_define_unsavable_tabs' );


/**
 * Define our settings
 *
 * @since       3.0.0
 * @param       array $settings The default settings.
 * @return      array $settings Our defined settings
 */
function comment_controller_registered_settings( $settings ) {
	$new_settings = array(
		// General Settings.
		'settings' => apply_filters(
			'comment_controller_settings_settings',
			array(
				'main' => array(
					array(
						'id'   => 'settings_header',
						'name' => __( 'General Settings', 'comment-controller' ),
						'desc' => '',
						'type' => 'header',
					),
					array(
						'id'      => 'disabled_post_types',
						'name'    => __( 'Disable On Post Types', 'comment-controller' ),
						'type'    => 'multicheck',
						'desc'    => __( 'Specify post types to disable comments on', 'comment-controller' ),
						'options' => comment_controller_get_post_types(),
					),
					array(
						'id'      => 'disabled_roles',
						'name'    => __( 'Disable On Roles', 'comment-controller' ),
						'type'    => 'multicheck',
						'desc'    => __( 'Specify roles to disable comments for', 'comment-controller' ),
						'options' => comment_controller_get_roles(),
					),
				),
			)
		),
		'support'  => apply_filters(
			'comment_controller_settings_support',
			array(
				array(
					'id'   => 'support_header',
					'name' => __( 'Comment Controller Support', 'comment-controller' ),
					'desc' => '',
					'type' => 'header',
				),
				array(
					'id'   => 'system_info',
					'name' => __( 'System Info', 'comment-controller' ),
					'desc' => '',
					'type' => 'sysinfo',
				),
			)
		),
	);

	return array_merge( $settings, $new_settings );
}
add_filter( 'comment_controller_registered_settings', 'comment_controller_registered_settings' );
