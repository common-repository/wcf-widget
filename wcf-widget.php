<?php
/*
Plugin Name: Wordpress Cross Feed Widget
Description: A Wordpress Widget Plugin For Connecting Websites Feed
Version: 1.0
Author: Sumit Mishra
Author URI: http://webbenchers.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

class Wcf_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name with description
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'wcf_widget', 
			'description' => 'A Wordpress Widget Plugin For Connecting Websites Feed',
		);
		parent::__construct( 'wcf_widget', 'Wcf Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function get_first_image_url($html){
		 if (preg_match('/<img.+?src="(.+?)"/', $html, $matches)){
		 return $matches[1];
		 }
	}
	
	
	public function form( $instance ) {
		// outputs the options form on admin
		if ( isset( $instance['wcf-url'] ) ) {
			$wcf_url = $instance['wcf-url'];
			}
		if(isset( $instance['wcf-title']))
		{
			$wcf_title = $instance['wcf-title'];
		}
		if(isset( $instance['wcf-title-length'])){
			$wcf_title_length= $instance['wcf-title-length'];
		}
		if(isset( $instance['wcf-total-feed-posts'])){
			$wcf_total_feed_posts= $instance['wcf-total-feed-posts'];
		}
			else {
			$wcf_url = "";
			$wcf_title = "";
			$wcf_title_length = "";
			$wcf_total_feed_posts = "";
			}
		//$wcf_url = ! empty( $instance['wcf-url'] ) ? $instance['wcf-url'] : " ";
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wcf-title' ) ); ?>"><?php esc_attr_e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wcf-title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wcf-title' ) ); ?>" type="text" value="<?php echo esc_attr( $wcf_title ); ?>" placeholder="<?php echo esc_attr(__( 'Please Enter feed Title')); ?>" />
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wcf-url' ) ); ?>"><?php esc_attr_e( 'Url:' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wcf-url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wcf-url' ) ); ?>" type="text" value="<?php echo esc_attr( $wcf_url ); ?>" placeholder="<?php echo esc_attr(__( 'Please Enter feed Url')); ?>" />
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wcf-title-length' ) ); ?>"><?php esc_attr_e( 'Title Length:' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wcf-title-length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wcf-title-length' ) ); ?>" type="text" value="<?php echo esc_attr( $wcf_title_length ); ?>" placeholder="<?php echo esc_attr(__( 'Please Enter feed Title Length')); ?>" />
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wcf-total-feed-posts' ) ); ?>"><?php esc_attr_e( 'Number Of Posts:' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wcf-total-feed-posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wcf-total-feed-posts' ) ); ?>" type="text" value="<?php echo esc_attr( $wcf_total_feed_posts ); ?>" placeholder="<?php echo esc_attr(__( 'Number of Feed Posts to show')); ?>" />
		</p>
		<?php
	}

	
		/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		//$instance = array();
		$instance = $old_instance;
		$instance['wcf-url'] = ( ! empty( $new_instance['wcf-url'] ) ) ? sanitize_text_field( $new_instance['wcf-url'] ) : '';
		$instance['wcf-title'] = ( ! empty( $new_instance['wcf-title'] ) ) ? sanitize_text_field( $new_instance['wcf-title'] ) : '';
		$instance['wcf-title-length'] = ( ! empty( $new_instance['wcf-title-length'] ) ) ? sanitize_text_field( $new_instance['wcf-title-length'] ) : '';
		$instance['wcf-total-feed-posts'] = ( ! empty( $new_instance['wcf-total-feed-posts'] ) ) ? sanitize_text_field( $new_instance['wcf-total-feed-posts'] ) : '';

		return $instance;
	}
	
	 
	 	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	
	 
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		if ( ! empty( $instance['wcf-url'] ) ) {
				include_once( ABSPATH . WPINC . '/feed.php' );
				$rss = fetch_feed($instance['wcf-url']);
				//print_r($rss);
				$maxitems = 0;
				
			if ( ! is_wp_error( $rss ) ) : 
				if($instance['wcf-total-feed-posts'] != ""){
					$get_total_num_of_feed = $instance['wcf-total-feed-posts'];
					$maxitems = $rss->get_item_quantity( $get_total_num_of_feed ); 
				}
				else{
					$maxitems = $rss->get_item_quantity(3); 
				}
				$rss_items = $rss->get_items( 0, $maxitems );
			endif;
			//echo "<input type='hidden' value='print_r($rss_items)' />";
		
			if ( $maxitems == 0 ) :
				echo "";
			else : 
				echo '<div class="wcf_wid-block_wrap">';
				echo '<div class="wcf_wid-block-title-wrap"><h4 class="wcf_wid-block-title">';
				
				if($instance['wcf-title'] !=""){
					echo '<span>'.$instance['wcf-title'].'</span>';
				}
				else{
					echo '<span>Cross Site Feeds</span>';
				}
				echo '</h4></div>';
				
				echo '<div class="wcf_wid_inner-block">';
			
			foreach ( $rss_items as $item ) :
	
				echo '<div class="wcf_wid-span12">
				<div class="wcf_wid-img-module"><a href="'.esc_url( $item->get_permalink() ).'" title="'.esc_html( $item->get_title() ).'" target="_blank">
				<img class="wcf_wid-thumb" src="'. $this->get_first_image_url($item->get_description()).'" style="width:100px;height:70px;"/></a></div>
				<div class="wcf_feed-item-details"><h3 class="wcf_feed-title wcf_wid-module-title"><a rel="bookmark" href="'.esc_url( $item->get_permalink() ).'" title="'.esc_html( $item->get_title() ).'" target="_blank">';
				if($instance["wcf-title-length"] != "") {
					echo substr(esc_html( $item->get_title() ),0,$instance["wcf-title-length"]);
				}
				else{
					echo esc_html( $item->get_title() );
				}
				echo '</a></h3>
				<div class="wcf_wid-module-meta-info"><span class="wcf_wid-post-date"><time class="wcf_feed-post-date" datetime="'.$item->get_date('j F Y | g:i a').'">'.$item->get_date('j F Y').'</time></span></div></div>
				</div>';
				
			endforeach;
			echo '</div>';
			echo '</div>';
		endif;
		
		
			
		}
	}



}

add_action( 'widgets_init', 'get_wcf_widget_registered');

 function get_wcf_widget_registered(){
	register_widget( 'Wcf_Widget' );
}