<?php

/*

Plugin name: Shortcode
Plugin URI: http://www.maxpagels.com/projects/shortcode
Description: A plugin that adds a bunch of useful shortcodes that you can use in your blog posts and pages.
Version: 0.5.6
Author: Max Pagels
Author URI: http://www.maxpagels.com

Copyright 2009 Max Pagels (email : max.pagels1@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

function post_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT count(id)
                         FROM $wpdb->posts
                         WHERE post_type = 'post'
                         AND post_status = 'publish'");
}

function future_post_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT count(id)
                         FROM $wpdb->posts
                         WHERE post_type = 'post'
                         AND post_status = 'future'");
}

function draft_post_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT count(id)
                         FROM $wpdb->posts
                         WHERE post_type = 'post'
                         AND post_status = 'draft'");
}

function name_of_longest_post() {
  global $wpdb;
  return $wpdb->get_var("SELECT DISTINCT post_title FROM $wpdb->posts
                         WHERE LENGTH(post_content) in (SELECT MAX(LENGTH(post_content)) 
                                                        FROM $wpdb->posts 
                                                        WHERE post_type = 'post' 
                                                        AND post_status = 'publish')");
}

function length_of_longest_post() {
  global $wpdb;
  return $wpdb->get_var("SELECT MAX(LENGTH(post_content)) 
                         FROM $wpdb->posts 
                         WHERE post_type = 'post' 
                         AND post_status = 'publish'");
}

function length_of_all_posts() {
  global $wpdb;
  return $wpdb->get_var("SELECT SUM(LENGTH(post_content)) 
                         FROM $wpdb->posts 
                         WHERE post_type = 'post' 
                         AND post_status = 'publish'");
}

function page_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT count(id)
                         FROM $wpdb->posts
                         WHERE post_type = 'page'
                         AND post_status = 'publish'");
}

function category_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT count(*)
                         FROM $wpdb->term_taxonomy
                         WHERE taxonomy = 'category'
                         AND count > 0");
}

function category_per_post_avg() {
  global $wpdb;
  
  $catcount = $wpdb->get_var("SELECT SUM(count)
                              FROM $wpdb->term_taxonomy
                              WHERE taxonomy = 'category'
                              AND count > 0");
                              
  $postcount = $wpdb->get_var("SELECT count(id)
                               FROM $wpdb->posts
                               WHERE post_type = 'post'
                               AND post_status = 'publish'");
                               
  return round(($catcount / $postcount), 2);
}


function tag_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT COUNT(*)
                         FROM $wpdb->term_taxonomy
                         WHERE taxonomy = 'post_tag'
                         AND count > 0");
}

function tag_per_post_avg() {
  global $wpdb;
  
  $tagcount = $wpdb->get_var("SELECT SUM(count)
                              FROM $wpdb->term_taxonomy
                              WHERE taxonomy = 'post_tag'
                              AND count > 0");
                              
  $postcount = $wpdb->get_var("SELECT count(id)
                               FROM $wpdb->posts
                               WHERE post_type = 'post'
                               AND post_status = 'publish'");
                               
  return round(($tagcount / $postcount), 2);
}

function comment_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT COUNT(*)
                         FROM $wpdb->comments
                         WHERE comment_approved = 1");
}

function age_in_days() {
  global $wpdb;
  $date = $wpdb->get_var("SELECT post_date_gmt
                         FROM $wpdb->posts
                         WHERE ID = (SELECT MIN(ID) FROM $wpdb->posts WHERE post_status = 'publish')");
  $then = strtotime($date);
  $now = strtotime(gmdate("M d Y H:i:s", time()));
  return round(($now-$then) / (24*60*60));
}

function posts_per_day_avg() {
  return round((post_count() / age_in_days()), 2);
}

function characters_per_post_avg() {
  return round(length_of_all_posts() / post_count());
}

function photos_in_gallery() {
  global $wpdb;
  return $wpdb->get_var("SELECT COUNT(*)
                         FROM $wpdb->posts
                         WHERE post_mime_type like 'image%'");
}
/*
* Unfinished function - do NOT use
function populartags($atts) {
  extract(shortcode_atts(array('amount' => 10,
                               'fontsizemin' => 11,
                               'fontsizemax' => 11,
                               'format' => 'flat'), $atts));
  return wp_tag_cloud("smallest=".$fontsizemin."px&largest=".$fontsizemax."px&number=".$amount."px&format=$format&echo=0");
}
*/

add_shortcode('postcount', 'post_count');
add_shortcode('nameoflongestpost', 'name_of_longest_post');
add_shortcode('longestpostlength', 'length_of_longest_post');
add_shortcode('allpostslength', 'length_of_all_posts');
add_shortcode('pagecount', 'page_count');
add_shortcode('catcount', 'category_count');
add_shortcode('catperpostavg', 'category_per_post_avg');
add_shortcode('tagcount', 'tag_count');
add_shortcode('tagperpostavg', 'tag_per_post_avg');
add_shortcode('commentcount', 'comment_count');
add_shortcode('ageindays', 'age_in_days');
add_shortcode('postsperdayavg', 'posts_per_day_avg');
add_shortcode('charsperpostavg', 'characters_per_post_avg');
add_shortcode('futpostcount', 'future_post_count');
add_shortcode('draftpostcount', 'draft_post_count');
add_shortcode('photosingallery', 'photos_in_gallery');
/*
* Unfinished function - do NOT use
add_
shortcode('populartags', 'populartags');
*/
?>
