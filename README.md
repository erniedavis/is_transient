is_transient
============

Example of WP Transients API

Example of using WP Transient with WordPress sort codes. 

I have found this API Function a great way to "wrap up" plugin output and speed up complex SQL

For this example I have taken a custom page layout and dramaticly increased load time by caching the entire output. 

A preview of this plugin with and without using the Transient API

Without: http://www.chillcoots.com/custom-home-gallery-2/

With: http://www.chillcoots.com/transients/

Below is the code example: 
<?php //*** http://www.chillcoots.com/transients/ ** ?>
 <!-- Begin main content -->
  <div class="inner_wrapper">
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
