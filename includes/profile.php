<?php
/**
 * Modify user profiles
 *
 * @package     CommentController\Profile
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Modify the output of profile.php
 *
 * @since       1.0.0
 * @param       object $user The current user info.
 * @return      void
 */
function comment_controller_profile_field( $user ) {
	$user = get_userdata( $user->ID );

	echo '<h3>' . esc_html__( 'Comment Controller', 'comment-controller' ) . '</h3>';

	if ( comment_controller_maybe_show_profile_option() ) {
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<label for="comment_controller-disable"><?php esc_html_e( 'Hide Comments', 'comment-controller' ); ?></label>
					</th>
					<td>
						<input name="comment_controller-disable" type="checkbox" id="comment_controller-disable" value="1" <?php checked( 1, $user->comment_controller_disable, true ); ?>/>
						<span class="description"><?php esc_html_e( 'Disable output of the comment field and all existing comments.', 'comment-controller' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label for="comment_controller-disallow"><?php esc_html_e( 'Disable Comments', 'comment-controller' ); ?></label>
				</th>
				<td>
					<input name="comment_controller-disallow" type="checkbox" id="comment_controller-disallow" value="1" <?php checked( 1, $user->comment_controller_disallow, true ); ?>/>
					<span class="description"><?php esc_html_e( 'Prevent users from commenting on my posts.', 'comment-controller' ); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
	wp_nonce_field( 'comment_controller_nonce', 'comment_controller_nonce' );
}
add_action( 'show_user_profile', 'comment_controller_profile_field' );
add_action( 'edit_user_profile', 'comment_controller_profile_field' );


/**
 * Process field updates on save
 *
 * @since       1.0.0
 * @param       int $user_id The ID of a given user.
 * @return      void
 */
function comment_controller_update_field( $user_id ) {
	if ( isset( $_REQUEST['comment_controller_nonce'] ) ) {
		check_admin_referer( 'comment_controller_nonce', 'comment_controller_nonce' );
	}

	if ( current_user_can( 'edit_user', $user_id ) ) {
		if ( isset( $_POST['comment_controller-disable'] ) ) {
			update_user_meta( $user_id, 'comment_controller_disable', true );
		} else {
			delete_user_meta( $user_id, 'comment_controller_disable' );
		}

		if ( isset( $_POST['comment_controller-disallow'] ) ) {
			update_user_meta( $user_id, 'comment_controller_disallow', true );
		} else {
			delete_user_meta( $user_id, 'comment_controller_disallow' );
		}
	}
}
add_action( 'personal_options_update', 'comment_controller_update_field' );
add_action( 'edit_user_profile_update', 'comment_controller_update_field' );
