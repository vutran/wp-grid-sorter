<?php

/**
 * Register a new custom sort key
 *
 * @access public
 * @param string $postType                      Specify the post type to make sortable
 * @param array $args (default: array())        An array of options
 * @param string $args['sort_key']              A unique key for sorting
 * @return void
 */
function wp_grid_sorter_register_sort_key($postType, $args=array())
{
    global $wp_grid_sorter;
    $wp_grid_sorter->mappings->register($postType, $args);
}

?>