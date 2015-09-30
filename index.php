<?php
/*
Plugin Name: SPD-News Widget Pack
Plugin URI: http://code.tutsplus.com
Description: Widgets mit News verschiedener SPD-Gliederungen
Version: 0.1
Author: Steffen Voß
Author URI: https://kaffeeringe.de
Text Domain: spdnews
License: GPLv2
 
Copyright 2014  SPD-News Widget Pack
 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
$euURL = "https://reader.akdigitalegesellschaft.de/public.php?op=rss&id=30&is_cat=1&key=q9spje55f5d268e899f";
$dURL = "https://reader.akdigitalegesellschaft.de/public.php?op=rss&id=31&is_cat=1&key=8nl1zc55f5d2d55a8da";
$shURL = "https://reader.akdigitalegesellschaft.de/public.php?op=rss&id=32&is_cat=1&key=og57e255f5d2f487118";
$rdeckURL = "https://reader.akdigitalegesellschaft.de/public.php?op=rss&id=43&is_cat=1&key=qyzuia55f5d30f94023";
*/

class spdNewsHL_widget extends WP_Widget {
 
    public function __construct() {
     
        parent::__construct(
            'spdNewsHL_widget',
            __( 'SPD Lübeck', 'spdNewsdomain' ),
            array(
                'classname'   => 'spdNewsHL_widget',
                'description' => __( 'SPD News aus Lübeck.', 'spdNewsdomain' )
                )
        );
       
        load_plugin_textdomain( 'spdNewsdomain', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
    }
 
    /**  
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {    
         
        extract( $args );
         
        $title      = apply_filters( 'widget_title', $instance['title'] );
        $message    = $instance['message'];
         
        echo $before_widget;
         
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }?>

				<?php // Get RSS Feed(s)

					include_once( ABSPATH . WPINC . '/feed.php' );
					$rss = fetch_feed('https://reader.akdigitalegesellschaft.de/public.php?op=rss&id=38&is_cat=1&key=j0nwjn55facac2e1590');
					$maxitems = 0;

					if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

							// Figure out how many total items there are, but limit it to 5. 
							$maxitems = $rss->get_item_quantity( 6 ); 

							// Build an array of all the items, starting with element 0 (first element).
							$rss_items = $rss->get_items( 0, $maxitems );

					endif;
					
					if ( $maxitems == 0 ): ?>
						<p>Keine Einträge</p>
					<?php else:
						$count = 1 ;
						foreach ( $rss_items as $item ):
							if ($count == 1) { ?>
								<h3>
									<?php if ($source = $item->get_source()) { echo $source->get_title();	} ?> <a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</h3>
								<?php	
									// remove leading date from spd-net-sh feed texts
									$probe = strip_tags( $item->get_content() );
									if ($probe[2]=="." && $probe[10]==":") {
									$text = substr($probe, 12);			
									} else {
										$text = strip_tags($probe);	
									}

									$teaser = explode ( ".", $text); 
								?>
								<p><?php echo($teaser[0]); ?>…&nbsp;<a target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>weiterlesen</a></p>
							<ul>	
							<?php } else { ?>
								<li>
								  <?php if ($source = $item->get_source()) { echo $source->get_title();	} ?><br><a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</li>
						  <?php }
						$count++;
						endforeach;
					endif;
				?>
				</ul>
				<?php echo $after_widget;
						   
				}
 
  
    /**
      * Sanitize widget form values as they are saved.
      *
      * @see WP_Widget::update()
      *
      * @param array $new_instance Values just sent to be saved.
      * @param array $old_instance Previously saved values from database.
      *
      * @return array Updated safe values to be saved.
      */
    public function update( $new_instance, $old_instance ) {        
         
        $instance = $old_instance;
         
        $instance['title'] = strip_tags( $new_instance['title'] );
         
        return $instance;
         
    }
  
    /**
      * Back-end widget form.
      *
      * @see WP_Widget::form()
      *
      * @param array $instance Previously saved values from database.
      */
    public function form( $instance ) {    
     
        $title      = esc_attr( $instance['title'] );
        ?>
         
        <p>
            <label for='<?php echo $this->get_field_id('title'); ?>'><?php _e('Title:'); ?></label> 
            <input class='widefat' id='<?php echo $this->get_field_id('title'); ?>' name='<?php echo $this->get_field_name('title'); ?>' type='text' value='<?php echo $title; ?>' />
        </p>
     
    <?php 
    }
     
}
 
/* Register the widget */
add_action( 'widgets_init', function(){
     register_widget( 'spdNewsHL_widget' );
});

class spdNewsRDECK_widget extends WP_Widget {
 
    public function __construct() {
     
        parent::__construct(
            'spdNewsRDECK_widget',
            __( 'SPD Rendsburg-Eckernförde', 'spdNewsdomain' ),
            array(
                'classname'   => 'spdNewsRDECK_widget',
                'description' => __( 'SPD News aus Rendsburg-Eckernförde.', 'spdNewsdomain' )
                )
        );
       
        load_plugin_textdomain( 'spdNewsdomain', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
    }
 
    /**  
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {    
         
        extract( $args );
         
        $title      = apply_filters( 'widget_title', $instance['title'] );
        $message    = $instance['message'];
         
        echo $before_widget;
         
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }?>

				<?php // Get RSS Feed(s)

					include_once( ABSPATH . WPINC . '/feed.php' );
					$rss = fetch_feed('https://reader.akdigitalegesellschaft.de/public.php?op=rss&id=43&is_cat=1&key=qyzuia55f5d30f94023');
					$maxitems = 0;

					if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

							// Figure out how many total items there are, but limit it to 5. 
							$maxitems = $rss->get_item_quantity( 6 ); 

							// Build an array of all the items, starting with element 0 (first element).
							$rss_items = $rss->get_items( 0, $maxitems );

					endif;
					
					if ( $maxitems == 0 ): ?>
						<p>Keine Einträge</p>
					<?php else:
						$count = 1 ;
						foreach ( $rss_items as $item ):
							if ($count == 1) { ?>
								<h3>
									<?php if ($source = $item->get_source()) { echo $source->get_title();	} ?> <a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</h3>
								<?php	
									// remove leading date from spd-net-sh feed texts
									$probe = strip_tags( $item->get_content() );
									if ($probe[2]=="." && $probe[10]==":") {
									$text = substr($probe, 12);			
									} else {
										$text = strip_tags($probe);	
									}

									$teaser = explode ( ".", $text); 
								?>
								<p><?php echo($teaser[0]); ?>…&nbsp;<a target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>weiterlesen</a></p>
							<ul>	
							<?php } else { ?>
								<li>
								  <?php if ($source = $item->get_source()) { echo $source->get_title();	} ?><br><a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</li>
						  <?php }
						$count++;
						endforeach;
					endif;
				?>
				</ul>
				<?php echo $after_widget;
						   
				}
 
  
    /**
      * Sanitize widget form values as they are saved.
      *
      * @see WP_Widget::update()
      *
      * @param array $new_instance Values just sent to be saved.
      * @param array $old_instance Previously saved values from database.
      *
      * @return array Updated safe values to be saved.
      */
    public function update( $new_instance, $old_instance ) {        
         
        $instance = $old_instance;
         
        $instance['title'] = strip_tags( $new_instance['title'] );
         
        return $instance;
         
    }
  
    /**
      * Back-end widget form.
      *
      * @see WP_Widget::form()
      *
      * @param array $instance Previously saved values from database.
      */
    public function form( $instance ) {    
     
        $title      = esc_attr( $instance['title'] );
        ?>
         
        <p>
            <label for='<?php echo $this->get_field_id('title'); ?>'><?php _e('Title:'); ?></label> 
            <input class='widefat' id='<?php echo $this->get_field_id('title'); ?>' name='<?php echo $this->get_field_name('title'); ?>' type='text' value='<?php echo $title; ?>' />
        </p>
     
    <?php 
    }
     
}
 
/* Register the widget */
add_action( 'widgets_init', function(){
     register_widget( 'spdNewsRDECK_widget' );
});


class spdNewsSH_widget extends WP_Widget {
 
    public function __construct() {
     
        parent::__construct(
            'spdNewsSH_widget',
            __( 'SPD SH', 'spdNewsdomain' ),
            array(
                'classname'   => 'spdNewsSH_widget',
                'description' => __( 'SPD News aus SH.', 'spdNewsdomain' )
                )
        );
       
        load_plugin_textdomain( 'spdNewsdomain', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
    }
 
    /**  
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {    
         
        extract( $args );
         
        $title      = apply_filters( 'widget_title', $instance['title'] );
        $message    = $instance['message'];
         
        echo $before_widget;
         
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }?>

				<?php // Get RSS Feed(s)
					include_once( ABSPATH . WPINC . '/feed.php' );
					$rss = fetch_feed('https://reader.akdigitalegesellschaft.de/public.php?op=rss&id=32&is_cat=1&key=og57e255f5d2f487118');
					$maxitems = 0;

					if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

							// Figure out how many total items there are, but limit it to 5. 
							$maxitems = $rss->get_item_quantity( 6 ); 

							// Build an array of all the items, starting with element 0 (first element).
							$rss_items = $rss->get_items( 0, $maxitems );

					endif;
					
					if ( $maxitems == 0 ): ?>
						<p>Keine Einträge</p>
					<?php else:
						$count = 1 ;
						foreach ( $rss_items as $item ):
							if ($count == 1) { ?>
								<h3><?php if ($source = $item->get_source()) { echo $source->get_title();	} ?>:
									<a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</h3>
								<?php	
									// remove leading date from spd-net-sh feed texts
									$probe = strip_tags( $item->get_content() );
									if ($probe[2]=="." && $probe[10]==":") {
									$text = substr($probe, 12);			
									} else {
										$text = strip_tags($probe);	
									}

									$teaser = explode ( ".", $text); 
								?>
								<p><?php echo($teaser[0]); ?>…&nbsp;<a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>weiterlesen</a></p>
							<ul>	
							<?php } else { ?>
								<li>
								  <?php if ($source = $item->get_source()) { echo $source->get_title();	} ?><br><a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</li>
						  <?php }
						$count++;
						endforeach;
					endif;
				?>
				</ul>
				<?php echo $after_widget;
						   
				}
 
  
    /**
      * Sanitize widget form values as they are saved.
      *
      * @see WP_Widget::update()
      *
      * @param array $new_instance Values just sent to be saved.
      * @param array $old_instance Previously saved values from database.
      *
      * @return array Updated safe values to be saved.
      */
    public function update( $new_instance, $old_instance ) {        
         
        $instance = $old_instance;
         
        $instance['title'] = strip_tags( $new_instance['title'] );
         
        return $instance;
         
    }
  
    /**
      * Back-end widget form.
      *
      * @see WP_Widget::form()
      *
      * @param array $instance Previously saved values from database.
      */
    public function form( $instance ) {    
     
        $title      = esc_attr( $instance['title'] );
        ?>
         
        <p>
            <label for='<?php echo $this->get_field_id('title'); ?>'><?php _e('Title:'); ?></label> 
            <input class='widefat' id='<?php echo $this->get_field_id('title'); ?>' name='<?php echo $this->get_field_name('title'); ?>' type='text' value='<?php echo $title; ?>' />
        </p>
     
    <?php 
    }
     
}
 
/* Register the widget */
add_action( 'widgets_init', function(){
     register_widget( 'spdNewsSH_widget' );
});


class spdNewsDeutschland_widget extends WP_Widget {
 
    public function __construct() {
     
        parent::__construct(
            'spdNewsDeutschland_widget',
            __( 'SPD Deutschland', 'spdNewsdomain' ),
            array(
                'classname'   => 'spdNewsDeutschland_widget',
                'description' => __( 'SPD News aus Deutschland.', 'spdNewsdomain' )
                )
        );
       
        load_plugin_textdomain( 'spdNewsdomain', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
    }
 
    /**  
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {    
         
        extract( $args );
         
        $title      = apply_filters( 'widget_title', $instance['title'] );
        $message    = $instance['message'];
         
        echo $before_widget;
         
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }?>

				<?php // Get RSS Feed(s)
					include_once( ABSPATH . WPINC . '/feed.php' );
					$rss = fetch_feed('https://reader.akdigitalegesellschaft.de/public.php?op=rss&id=31&is_cat=1&key=8nl1zc55f5d2d55a8da');
					$maxitems = 0;

					if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

							// Figure out how many total items there are, but limit it to 5. 
							$maxitems = $rss->get_item_quantity( 6 ); 

							// Build an array of all the items, starting with element 0 (first element).
							$rss_items = $rss->get_items( 0, $maxitems );

					endif;
					
					if ( $maxitems == 0 ): ?>
						<p>Keine Einträge</p>
					<?php else:
						$count = 1 ;
						foreach ( $rss_items as $item ):
							if ($count == 1) { ?>
								<h3>
									<?php if ($source = $item->get_source()) { echo $source->get_title();	} ?> <a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</h3>
								<?php	
									// remove leading date from spd-net-sh feed texts
									$probe = strip_tags( $item->get_content() );
									if ($probe[2]=="." && $probe[10]==":") {
									$text = substr($probe, 12);			
									} else {
										$text = strip_tags($probe);	
									}

									$teaser = explode ( ".", $text); 
								?>
								<p><?php echo($teaser[0]); ?>…&nbsp;<a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>weiterlesen</a></p>
							<ul>	
							<?php } else { ?>
								<li>
								  <?php if ($source = $item->get_source()) { echo $source->get_title();	} ?><br><a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</li>
						  <?php }
						$count++;
						endforeach;
					endif;
				?>
				</ul>
				<?php echo $after_widget;
						   
				}
 
  
    /**
      * Sanitize widget form values as they are saved.
      *
      * @see WP_Widget::update()
      *
      * @param array $new_instance Values just sent to be saved.
      * @param array $old_instance Previously saved values from database.
      *
      * @return array Updated safe values to be saved.
      */
    public function update( $new_instance, $old_instance ) {        
         
        $instance = $old_instance;
         
        $instance['title'] = strip_tags( $new_instance['title'] );
         
        return $instance;
         
    }
  
    /**
      * Back-end widget form.
      *
      * @see WP_Widget::form()
      *
      * @param array $instance Previously saved values from database.
      */
    public function form( $instance ) {    
     
        $title      = esc_attr( $instance['title'] );
        ?>
         
        <p>
            <label for='<?php echo $this->get_field_id('title'); ?>'><?php _e('Title:'); ?></label> 
            <input class='widefat' id='<?php echo $this->get_field_id('title'); ?>' name='<?php echo $this->get_field_name('title'); ?>' type='text' value='<?php echo $title; ?>' />
        </p>
     
    <?php 
    }
     
}
 
/* Register the widget */
add_action( 'widgets_init', function(){
     register_widget( 'spdNewsDeutschland_widget' );
}); 
 
class spdNewsEuropa_widget extends WP_Widget {
 
    public function __construct() {
     
        parent::__construct(
            'spdNewsEuropa_widget',
            __( 'SPD Europa', 'spdNewsdomain' ),
            array(
                'classname'   => 'spdNewsEuropa_widget',
                'description' => __( 'SPD News aus Europa.', 'spdNewsdomain' )
                )
        );
       
        load_plugin_textdomain( 'spdNewsdomain', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
    }
 
    /**  
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {    
         
        extract( $args );
         
        $title      = apply_filters( 'widget_title', $instance['title'] );
        $message    = $instance['message'];
         
        echo $before_widget;
         
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }?>

				<?php // Get RSS Feed(s)
					include_once( ABSPATH . WPINC . '/feed.php' );
					$rss = fetch_feed('https://reader.akdigitalegesellschaft.de/public.php?op=rss&id=30&is_cat=1&key=q9spje55f5d268e899f');
					$maxitems = 0;

					if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

							// Figure out how many total items there are, but limit it to 5. 
							$maxitems = $rss->get_item_quantity( 6 ); 

							// Build an array of all the items, starting with element 0 (first element).
							$rss_items = $rss->get_items( 0, $maxitems );

					endif;
					
					if ( $maxitems == 0 ): ?>
						<p>Keine Einträge</p>
					<?php else:
						$count = 1 ;
						foreach ( $rss_items as $item ):
							if ($count == 1) { ?>
								<h3>
									<?php if ($source = $item->get_source()) { echo $source->get_title();	} ?> <a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</h3>
								<?php	
									// remove leading date from spd-net-sh feed texts
									$probe = strip_tags( $item->get_content() );
									if ($probe[2]=="." && $probe[10]==":") {
									$text = substr($probe, 12);			
									} else {
										$text = strip_tags($probe);	
									}

									$teaser = explode ( ".", $text); 
								?>
								<p><?php echo($teaser[0]); ?>…&nbsp;<a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>weiterlesen</a></p>
							<ul>	
							<?php } else { ?>
								<li>
								  <?php if ($source = $item->get_source()) { echo $source->get_title();	} ?><br><a  target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>' ?>
								    <?php echo esc_html( $item->get_title() ); ?>
								  </a>
								</li>
						  <?php }
						$count++;
						endforeach;
					endif;
				?>
				</ul>
				<?php echo $after_widget;
						   
				}
 
  
    /**
      * Sanitize widget form values as they are saved.
      *
      * @see WP_Widget::update()
      *
      * @param array $new_instance Values just sent to be saved.
      * @param array $old_instance Previously saved values from database.
      *
      * @return array Updated safe values to be saved.
      */
    public function update( $new_instance, $old_instance ) {        
         
        $instance = $old_instance;
         
        $instance['title'] = strip_tags( $new_instance['title'] );
         
        return $instance;
         
    }
  
    /**
      * Back-end widget form.
      *
      * @see WP_Widget::form()
      *
      * @param array $instance Previously saved values from database.
      */
    public function form( $instance ) {    
     
        $title      = esc_attr( $instance['title'] );
        ?>
         
        <p>
            <label for='<?php echo $this->get_field_id('title'); ?>'><?php _e('Title:'); ?></label> 
            <input class='widefat' id='<?php echo $this->get_field_id('title'); ?>' name='<?php echo $this->get_field_name('title'); ?>' type='text' value='<?php echo $title; ?>' />
        </p>
     
    <?php 
    }
     
}
 
/* Register the widget */
add_action( 'widgets_init', function(){
     register_widget( 'spdNewsEuropa_widget' );
});
