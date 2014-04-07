<?php

add_action('wp_insert_post', 'wpgs_save_order_number', 10, 2);

/** 
 * Apply a new order number for the new post
 *
 * @access public
 * @global wpdb $wpdb
 * @global WPGridSorter $wp_grid_sorter
 * @param int $postId
 * @param object $post
 * @return void
 */
function wpgs_save_order_number($postId, $post)
{
    // Don't continue if it's a post revision
    if (wp_is_post_revision($postId)) {
        return false;
    }
    global $wpdb, $wp_grid_sorter;
    // Retrieve all keys for the given post type
    $postType = get_post_type($postId);
    // Retrieve all the sort keys by post type
    $sortKeys = $wp_grid_sorter->mappings->getKeysByPostType($postType);
    if ($sortKeys) {
        // Iterate through each sort key
        foreach ($sortKeys as $sortKey) {
            // Retrieve the highest order number
            $lastPost = $wpdb->get_row('SELECT * FROM ' . $wpdb->postmeta . ' WHERE meta_key = \'' . $sortKey . '\' ORDER BY CAST(meta_value as UNSIGNED INTEGER) DESC LIMIT 1');
            if ($lastPost && intval($lastPost->post_id) !== intval($postId)) {
                update_post_meta($post->ID, $sortKey, ($lastPost->meta_value + 1));
            }
        }
    }
}

add_filter('wpgs_grid_item_class', 'wpgs_apply_grid_item_class', 10, 2);

/**
 * Apply the grid item class based on the tile size
 *
 * @access public
 * @param string $value
 * @param WP_Post $post
 * @return void
 */
function wpgs_apply_grid_item_class($value, $post)
{
    return 'wpgs-item-tile-' . apply_filters('wpgs_item_tile_size', 1, $post);
}

?>