<?php
/**
 * User Sidebar template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

global $current_user, $cp_options;

$logout_url = cp_logout_url();
?>

<!-- right sidebar -->
<div class="content_right">

	<div class="shadowblock_out">

		<div class="shadowblock">

			<h2 class="dotted"><?php _e( 'Dashboard', CPSR_TD ); ?></h2>

			<div class="recordfromblog">

			<?php if ( has_nav_menu( 'theme_dashboard' ) ) : wp_nav_menu( array( 'theme_location' => 'theme_dashboard', 'menu_id' => 'menu-dashboard', 'container' => false ) ); else : ?>

				<ul>
					<li><a href="<?php echo CP_DASHBOARD_URL; ?>"><?php _e( 'My Ads', CPSR_TD ); ?></a></li>
					<li><a href="<?php echo CP_DASHBOARD_ORDERS_URL; ?>"><?php _e( 'My Orders', CPSR_TD ); ?></a></li>
					<li><a href="<?php echo CP_PROFILE_URL; ?>"><?php _e( 'Edit Profile', CPSR_TD ); ?></a></li>
					<?php if ( current_user_can( 'edit_others_posts' ) ) { ?><li><a href="<?php echo admin_url(); ?>"><?php _e( 'WordPress Admin', CPSR_TD ); ?></a></li><?php } ?>
					<li><a href="<?php echo $logout_url; ?>"><?php _e( 'Log Out', CPSR_TD ); ?></a></li>
				</ul>

			<?php endif; ?>

			</div><!-- /recordfromblog -->

		</div><!-- /shadowblock -->

	</div><!-- /shadowblock_out -->


	<div class="shadowblock_out">

		<div class="shadowblock account-info">

			<h2 class="dotted"><?php _e( 'Account Information', CPSR_TD ); ?></h2>

			<div class="avatar"><?php appthemes_get_profile_pic( $current_user->ID, $current_user->user_email, 140 ); ?></div>

				<ul class="user-info">
					<li><h3 class="single"><a href="<?php echo get_author_posts_url( $current_user->ID ); ?>"><?php echo $current_user->display_name; ?></a></h3></li>
					<li><strong><?php _e( 'Member Since:', CPSR_TD ); ?></strong> <?php echo appthemes_display_date( $current_user->user_registered, 'datetime', true ); ?></li>
					<li><strong><?php _e( 'Last Login:', CPSR_TD ); ?></strong> <?php echo appthemes_get_last_login( $current_user->ID ); ?></li>
				</ul>

				<?php if ( $cp_options->enable_membership_packs ) { ?>
				<?php $membership = cp_get_membership_package( $current_user->active_membership_pack ); ?>
				<ul class="membership-pack">
					<?php if ( $membership && ( appthemes_days_between_dates( $current_user->membership_expires ) < 0 ) ) { ?>
						<li><?php printf( __( 'Your membership pack "%1$s" expired on %2$s.', CPSR_TD ), $membership->pack_name, appthemes_display_date( $current_user->membership_expires ) ); ?></li>
						<li><a href="<?php echo CP_MEMBERSHIP_PURCHASE_URL; ?>"><?php _e( 'Renew Your Membership Pack', CPSR_TD ); ?></a></li>
					<?php } elseif( $membership ) { ?>
						<li><strong><?php _e( 'Membership Pack:', CPSR_TD ); ?></strong> <?php echo $membership->pack_name; ?></li>
						<li><strong><?php _e( 'Membership Expires:', CPSR_TD ); ?></strong> <?php echo appthemes_display_date( $current_user->membership_expires ); ?></li>
						<li><a href="<?php echo CP_MEMBERSHIP_PURCHASE_URL; ?>"><?php _e( 'Renew or Extend Your Membership Pack', CPSR_TD ); ?></a></li>
					<?php } else { ?>
						<li><a href="<?php echo CP_MEMBERSHIP_PURCHASE_URL; ?>" class="btn_orange"><?php _e( 'Purchase a Membership Pack', CPSR_TD ); ?></a></li>
					<?php } ?>
				</ul>
				<?php } ?>

				<ul class="user-details">
					<li><div class="fa fa-envelope emailico"></div><a href="mailto:<?php echo $current_user->user_email; ?>"><?php echo $current_user->user_email; ?></a></li>
					<?php if ( ! empty( $current_user->twitter_id ) ) { ?><li><div class="fa fa-twitter-square twitterico"></div><a href="http://twitter.com/<?php echo esc_attr( $current_user->twitter_id ); ?>" target="_blank"><?php _e( 'Twitter', CPSR_TD ); ?></a></li><?php } ?>
					<?php if ( ! empty( $current_user->facebook_id ) ) { ?><li><div class="fa fa-facebook-square facebookico"></div><a href="<?php echo appthemes_make_fb_profile_url( $current_user->facebook_id ); ?>" target="_blank"><?php _e( 'Facebook', CPSR_TD ); ?></a></li><?php } ?>
					<?php if ( ! empty( $current_user->user_url ) ) { ?><li><div class="fa fa-globe globeico"></div><a href="<?php echo esc_attr( $current_user->user_url ); ?>" target="_blank"><?php echo esc_html( $current_user->user_url ); ?></a></li><?php } ?>
				</ul>

				<?php cp_author_info( 'sidebar-user' ); ?>

		</div><!-- /shadowblock -->

	</div><!-- /shadowblock_out -->



	<div class="shadowblock_out">

		<div class="shadowblock">

			<h2 class="dotted"><?php _e( 'Account Statistics', CPSR_TD ); ?></h2>

			<ul class="user-stats">

<?php
// calculate the total count of live ads for current user
$rows = $wpdb->get_results( $wpdb->prepare( "
	SELECT post_status, COUNT(ID) as count
	FROM $wpdb->posts
	WHERE post_author = %d
	AND post_type = '".APP_POST_TYPE."'
	GROUP BY post_status", $current_user->ID
) );

$stats = array();
foreach ( $rows as $row ) {
	$stats[ $row->post_status ] = $row->count;
}

$post_count_live = isset( $stats['publish'] ) ? $stats['publish'] : 0;
$post_count_pending = isset( $stats['pending'] ) ? $stats['pending'] : 0;
$post_count_offline = isset( $stats['draft'] ) ? $stats['draft'] : 0;
$post_count_total = $post_count_live + $post_count_pending + $post_count_offline;
?>

				<li><?php _e( 'Live Listings:', CPSR_TD ); ?> <strong><?php echo $post_count_live; ?></strong></li>
				<li><?php _e( 'Pending Listings:', CPSR_TD ); ?> <strong><?php echo $post_count_pending; ?></strong></li>
				<li><?php _e( 'Offline Listings:', CPSR_TD ); ?> <strong><?php echo $post_count_offline; ?></strong></li>
				<li><?php _e( 'Total Listings:', CPSR_TD ); ?> <strong><?php echo $post_count_total; ?></strong></li>

			</ul>

		</div><!-- /shadowblock -->

	</div><!-- /shadowblock_out -->


	<?php appthemes_before_sidebar_widgets( 'user' ); ?>

	<?php if ( ! dynamic_sidebar( 'sidebar_user' ) ) : ?>

	<!-- no dynamic sidebar so don't do anything -->

	<?php endif; ?>

	<?php appthemes_after_sidebar_widgets( 'user' ); ?>

</div><!-- /content_right -->
