<?php

/**
 * Template Tag Functions
 *
 * @link       https://github.com/misfist
 * @since      1.0.0
 *
 * @package    Bylines
 * @subpackage Bylines/includes
 */

/**
 * Get Guest Author(s)
 *
 * @param int $post_id
 * @param string $before
 * @param string $sep
 * @param string $after
 * @return string|null The author(s) display name.
 */
function get_the_guest_author( $post_id = null, $before, $sep, $after ) {}

/**
 * Get Guest Authors for Post
 *
 * @param  null $post_id
 * @param  array $args - $args = array( 'orderby' => 'name', 'order' => 'ASC', 'fields' => 'all' );
 * @return array|WP_Error
 */
function wp_get_post_guest_authors( $post_id = null, $args ) {}

/**
 * Get Guest Author Meta
 *
 * @param  string  $field
 * @param  int $author_id
 * @return string The authors field, otherwise an empty string
 */
function get_the_guest_author_meta( $field, $author_id = null ) {}

/**
 * Get Number of Posts by Author
 *
 * @param  int $author_id
 * @return int The number of posts by the author
 */
function get_the_guest_author_posts( $author_id = null ) {}

/**
 * List Guest Authors
 * List all the guest authors on the site
 *
 * @param array $args
 * @return void
 */
function wp_list_guest_authors( $args ) {}
