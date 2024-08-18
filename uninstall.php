<?php
// Plugin Uninstall and database clean

if(!defined('WP_UNINSTALL_PLUGIN')){
    die;
}

// With wordpress Query
/*
$books= get_post(array('post_type'=>'book', 'numberposts'=>-1));

foreach($books as $book){
    wp_delete_post($book->ID, true);
}
*/

// SQL Query
global $wpdb;

$wpdb-> query("DELETE FROM whl_posts WHERE post_type='book'");
$wpdb-> query("DELETE FROM whl_posmeta WHERE post_id NOT IN(SELECT id FROM whl_posts)");
$wpdb-> query("DELETE FROM whl_term_releationships WHERE object_id NOT IN(SELECT id FROM whl_posts)");