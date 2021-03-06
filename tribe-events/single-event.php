<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 * 
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  2.1
 * @author Modern Tribe Inc.
 *
 */
 


if( ! defined( 'ABSPATH' ) ) {

	die('-1');

}	//end if( ! defined( 'ABSPATH' ) )

//check for vc_map function
if( function_exists( 'vc_map' ) ) {

	wp_enqueue_style( 'tribe_js_composer', plugins_url( '/js_composer/assets/css/js_composer.css' ) );

}	//end if( function_exists( 'vc_map' ) )

//reformat date
$event_id = get_the_ID();
$datedetails = str_replace('@', 'From', tribe_events_event_schedule_details() );
$datedetails = str_replace('-', 'To', $datedetails);

//reformat organizer, remove if 'Tekserve' is present
$organizer = tribe_get_organizer();
if( strpos( $organizer, 'Tekserve' ) ) {

	$organizer = '';

}
else {

	$organizer = '<div class="tribe-events-single-event-organizer"><h3>Presented by: '.$organizer.'</h3></div>';

}	//end if( strpos( $organizer, 'Tekserve' ) )

//reformat event link
$event_link = tribe_get_event_website_url();
if( !empty( $event_link ) ) {

	$event_link = '<div class="tribe-events-single-event-eventlink"><h3>Visit the <a href="'.$event_link.'">Event Page</a> provided by the presenter.</h3></div>';

}	//end if( !empty( $event_link ) )

?>

<div id="tribe-events-content" class="tribe-events-single">

	<p class="right"><a class="tribe-events-back button" style="color:#fff;" href="<?php echo tribe_get_events_link() ?>"> <?php _e( '&laquo; All Events', 'tribe-events-calendar' ) ?></a></p>

	<!-- Notices -->
	<?php tribe_events_the_notices() ?>
	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
	</div><!-- #tribe-events-header -->

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('vevent'); ?>>
			<!-- Event featured image -->
			<?php echo tribe_event_featured_image(); ?>
			<?php the_title( '<h2 class="tribe-events-single-event-title summary">', '</h2>' ); ?>
			<div class="tribe-events-schedule updated published tribe-clearfix">
				<h2>About This Event</h2>
				<h3><?php echo $datedetails ?></h3>
				<!-- Event meta -->
				<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
				<div class="tribe-events-single-event-venue"
					<h3>At <?php echo tribe_get_venue_link() ?> - <?php echo tribe_get_full_address(); ?>
				<?php  if( tribe_get_cost() ) :  ?>
					<span class="tribe-events-divider">|</span>
					<span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>
				<?php endif; ?>
					</h3>
				</div>
				<?php echo $organizer; ?>
				<?php echo $event_link ?>
		
				<?php echo tribe_events_event_recurring_info_tooltip(); ?>
		
			</div>
			<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content entry-content description">
				<?php the_content(); ?>
			</div>
			<!-- .tribe-events-single-event-description -->
			<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>

			
			</div>
			<!-- .hentry .vevent -->
		<?php /*  Changed out comments_template() function (native WP) in favor of Genesis version, genesis_get_comments_template()  */
		if( get_post_type() == TribeEvents::POSTTYPE && tribe_get_option( 'showComments','no' ) == 'yes' ) { genesis_get_comments_template(); } ?>

	<?php endwhile; ?>

	<!-- Event footer -->
    <div id="tribe-events-footer">
		<!-- Navigation -->
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php _e( 'Event Navigation', 'tribe-events-calendar' ) ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '&laquo; %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% &raquo;' ) ?></li>
		</ul><!-- .tribe-events-sub-nav -->
	</div><!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->
<script type="text/javascript">
	jQuery(function() {
	
		if (jQuery('ul.tribe-related-events').length > 0) {
		
			jQuery('.tribe-events-related-events-title').insertAfter('.tribe_events.entry');
			jQuery('.tribe-related-events').insertAfter('.tribe-events-related-events-title');
		
		}
	
	});
</script>
