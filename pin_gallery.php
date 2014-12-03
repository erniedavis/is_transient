<?php
/* Template Name: Pin-Gallery */
// Report all PHP errors
error_reporting(-1);

// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
$wpdb->show_errors();
$pID = get_the_ID();
$page = get_page($post->ID);
$current_page_id = '';

if(isset($page->ID))
{
    $current_page_id = $page->ID;
}
get_header(); 
?>

<br class="clear"/>
</div>
<?php

//Get Page background style
$bg_style = get_post_meta($current_page_id, 'page_bg_style', true);

if($bg_style == 'Static Image')
{
    if(has_post_thumbnail($current_page_id, 'full'))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        $pp_page_bg = $image_thumb[0];
    }
    else
    {
    	$pp_page_bg = get_stylesheet_directory_uri().'/example/bg.jpg';
    }

    wp_enqueue_script("script-static-bg", get_stylesheet_directory_uri()."/templates/script-static-bg.php?bg_url=".$pp_page_bg, false, THEMEVERSION, true);
} // end if static image
else
{
    $page_bg_gallery_id = get_post_meta($current_page_id, 'page_bg_gallery_id', true);
    wp_enqueue_script("script-supersized-gallery", get_stylesheet_directory_uri()."/templates/script-supersized-gallery.php?gallery_id=".$page_bg_gallery_id, false, THEMEVERSION, true);
?>
<div id="thumb-tray" class="load-item">
  <div id="thumb-back"></div>
  <div id="thumb-forward"></div>
  <a id="prevslide" class="load-item"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/arrow_back.png" alt=""/></a> <a id="nextslide" class="load-item"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/arrow_forward.png" alt=""/></a> </div>
<input type="hidden" id="pp_image_path" name="pp_image_path" value="<?php echo get_stylesheet_directory_uri(); ?>/images/"/>
<?php
}
?>

<!-- Begin content -->
<?php
if($bg_style == 'Static Image')
{
?>
<div class="page_control_static"> <a id="page_minimize" href="#"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_zoom.png" alt=""/> </a> <a id="page_maximize" href="#"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_plus.png" alt=""/> </a> </div>
<?php
}
else
{
?>
<div class="page_control"> <a id="page_minimize" href="#"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_minus.png" alt=""/> </a> <a id="page_maximize" href="#"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_plus.png" alt=""/> </a> </div>
<?php
}
?>
<?php
$page_audio = get_post_meta($current_page_id, 'page_audio', true);

if(!empty($page_audio))
{
?>
<div class="page_audio"> <?php echo do_shortcode('[audio width="30" height="30" src="'.$page_audio.'"]'); ?> </div>
<?php
}
?>
<div id="page_content_wrapper">
<div class="inner"> 
 
  <!-- Begin main content -->
  <div class="inner_wrapper">
    <div id="page_caption">
      <h1 class="cufon">
        <?php the_title(); ?>
      </h1>
    </div>
    <?php 
	// quick function to check if transient is valid
		function is_transient($t) {
			if ( false === ( $value = get_transient( $t ) ) ) {
				return 0;
			}else{
				return 1;
			}
		}		
	
	// setup page content
	if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="sidebar_content full_width transparentbg">
      <?php //custom class and content set in page attributes -> template selection ?>
      <div class="pin_wrap">
        <?php
		// set default view
		$pin_view = 1;
		// if we are calling a gallery 
		if(isset($_GET['pin'])) { 
			$pin_id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['pin']); 
			$pin_count = $wpdb->get_var( "SELECT COUNT(*) FROM wp_posts WHERE ID = $pin_id AND post_status = 'publish' AND post_type = 'cq_pinterest'" );
			// if gallery exists and is active
			if($pin_count == 1) {
				// stop if
				$pin_view = 0;
				// call the function to check if a transient exists for requested gallery 
				// if not then set one and get it
				if(is_transient('value_'.$pin_id) == 0):
					// if this is our sample transient page
					if($current_page_id == 511){
						set_transient( 'value_'.$pin_id, do_shortcode('[pinterest_gallery id='.$pin_id.' /]'), 365 * DAY_IN_SECONDS );
					}else{
						echo do_shortcode('[pinterest_gallery id='.$pin_id.' /]');
					}
				endif;
				// pull requested gallery
				echo get_transient( 'value_'.$pin_id );	
			}else{
				// if there was an invalid gallery request
				// set default view
				$pin_view = 1;
			}
		}
		// default view
		if($pin_view == 1) {
			// is there a request to delete the set transient			
			if(isset($_GET['delete_t'])&&$_GET['delete_t']<>''): 
				// if it does exist then delete it
				if(is_transient($_GET['delete_t']) == 1):
					delete_transient( $_GET['delete_t'] );
				endif;
			endif;
			// setup our default view params and call them up
			$args = array('post_type' => 'cq_pinterest','post_status' => 'publish','orderby' => 'title','order' => 'DESC','nopaging' => 'true');
			$show_gal = get_posts( $args );		
			$key_1_value = array();
			//loop through the galllery cover images output html
			foreach ( $show_gal as $post ) {
			$key_1_value = get_post_meta( $post->ID, 'cq_pinterest_fields', true );
		?>
        <div class="pin_item_wrap one_third gallery3"> <img src="<?php echo $key_1_value[0]['thumb_front_url'][0]; ?>" alt="<?php echo $post->post_title; ?>" class="one_third_img">
          <div class="mask"> <a href="<?php echo get_permalink($pID); ?>?pin=<?php echo $post->ID; ?>" title="<?php echo $post->post_title . ' ' . get_bloginfo('name'); ?> Photo Gallery" >
            <h5><?php echo $post->post_title; ?></h5>
            <span class="button">View Project</span> </a> </div>
         <?php 
		// just for this demo *******************
		if($current_page_id == 511): 
		echo '<div class="transient_val">';
			// check if transient of gallery is valid
			// if not then give instructions on setting it
			// if yes then allow it to be deleted (this will go into site admin )		
			if (is_transient('value_'.$post->ID) == 0){
			//if ( false === ( $value = get_transient( 'value_'.$post->ID ) ) ) {
				echo "No Transient Set for this Gallery <br>(Load gallery to set Transient)";
			}else{
				echo '<a class="button" href="'. $_SERVER["REQUEST_URI"] .'?delete_t=' . 'value_'.$post->ID . '">Delete Transient [ value_'.$post->ID . ']</a>';
			}
		echo '</div>';
		endif;
		?>
        </div>
        <?php } ?>
        <?php } ?>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <!-- End main content --> 
</div>
<?php get_footer(); ?>
