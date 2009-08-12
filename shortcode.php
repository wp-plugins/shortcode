<?php

/*

Plugin name: Shortcode
Plugin URI: http://www.maxpagels.com/projects/shortcode
Description: A plugin that adds a buch of useful shortodes that you can use in your blog posts and pages.
Version: 0.1
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
                         WHERE post_status = 'publish'");
}

function category_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT COUNT(*)
                         FROM $wpdb->term_taxonomy
                         WHERE taxonomy = 'category'
                         AND count > 0");
}

function tag_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT COUNT(*)
                         FROM $wpdb->term_taxonomy
                         WHERE taxonomy = 'post_tag'
                         AND count > 0");
}

function age_in_days() {
  global $wpdb;
  $wpdb->show_errors();
  $date = $wpdb->get_var("SELECT post_date_gmt
                         FROM $wpdb->posts
                         WHERE ID = (SELECT MIN(ID) FROM $wpdb->posts WHERE post_status = 'publish')");
  $then = strtotime($date);
  $now = strtotime(gmdate("M d Y H:i:s", time()));
  return round(($now-$then) / (24*60*60));
}

add_shortcode('postcount', 'post_count');
add_shortcode('catcount', 'category_count');
add_shortcode('tagcount', 'tag_count');
add_shortcode('ageindays', 'age_in_days');

?>