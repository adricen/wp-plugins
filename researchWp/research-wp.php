<?php
/*
Plugin Name: Research Wordpress
Plugin URI: https://love-open-design.com
Description: Allow a better research inside wordpress taking every words from every posts
Version: 1
Author: Adrien Centonze
Author URI: https://love-open-design.com/
Author Email: adrien.centonze@gmail.com
License: GPLv2 or later
*/

// print_r(['hello world','ajout du truc', 'inside stuffs']);

/*Fonction Auto-completion Search for Wordpress
Languages : PHP to jQuery, using jQueryUI -- must be initialized before this Gist, at least the .autocompletion function
Working methode :
-- the loading process can be in some other place. You cen use this function just for initialize the variable
  and load the auto-completion methode in your JS file.
  The variable availableTags will be in your Dom and accessible in every page.

Autor : @adricen
*/

// Adding Jquery UI Autocomplete
add_action( 'wp_enqueue_scripts', 'adding_script_styles', 20 );
function adding_script_styles() {
  wp_enqueue_style('style-search', plugin_dir_url( __FILE__ )."assets/css/style-search.css");
  wp_enqueue_script( 'autocomplete', plugin_dir_url( __FILE__ )."assets/js/jquery-ui-1.12.1.custom/jquery-ui.min.js", array(), false, false );
  wp_enqueue_script( 'wp-search-js', plugin_dir_url( __FILE__ )."assets/js/wp-search.js", array(), false, true );
}
add_action( 'wp_footer', 'auto_search' );
function auto_search(){
    global $wpdb;
    // the query
    $args = array('posts_per_page' => -1, 'order'=> 'ASC', 'orderby' => 'date');
    $the_query = new WP_Query( $args );

    // Formating the right array for js variable
    $varArray = "<script type='text/javascript'>var availableTags = [";
    // $url_pattern = '/((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.([a-zA-Z0-9\&\.\/\?\:@\-_=#])*/';
    // $url_replace = ' ';
    if ( $the_query->have_posts() ) :
        $theContent = '';
        while ( $the_query->have_posts() ) : $the_query->the_post();
            $post_id =  get_the_ID();
            $getContent = get_the_title($post_id) . " ";
            $getContent .= get_the_content($post_id) . " ";
            $getContent = preg_replace("/<img[^>]+\>/i", " ", $getContent);
            $getContent = preg_replace('#\[[^\]]+\]#', " ", $getContent);
            $getContent = strip_tags(strtolower($getContent));
            $getContent = str_replace( array( '?', ',', '.', ':', '!', '"','/>','&nbsp;'), ' ', $getContent );
            $theContent .= $getContent;
        endwhile;

        $theContent = explode(" ", $theContent);
        $theContent = array_unique($theContent);
        sort($theContent);
        foreach ($theContent as $key=>&$value) {
            if (strlen($value) < 6) {
                unset($theContent[$key]);
            }
        }
        for( $i=0; $i<count($theContent); $i++ ){
            if(isset($theContent[$i]) && !preg_match("/[0-9]{1,2}$/", $theContent[$i]) && strlen($theContent[$i]) > 3 ){
              $varArray .= '"'. preg_replace('/\s/', '',rtrim(strtolower($theContent[$i]))) .'",';
            }
        }
        wp_reset_postdata();
    else : endif;
        $varArray .= "];</script>";
        echo $varArray;
}
// add_action ('wp_head','search_bar');
// function search_bar() {
//   $search_bar = '<div class="et-top-search"><!-- /#et-search --><form role="search" class="et-search-form" method="get" action="/"><input type="search" class="et-search-field" placeholder="Search" value="" name="s" title="Search for:"><button class="et-search-submit"></button></form></div>';
//   echo $search_bar;
// }
