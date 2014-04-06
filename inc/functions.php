<?php

/**
 * Register a new custom sort key
 *
 * @access public
 * @param string $sortKey                       A unique key for sorting
 * @param array $args (default: array())        An array of options
 * @param string $args['name']                  A readable name to display in the CMS
 * @param string $args['post_type']             The post type to sort
 * @return void
 */
function wp_grid_sorter_register_sort_key($postType, $args=array())
{
    global $wp_grid_sorter;
    $wp_grid_sorter->mappings->register($postType, $args);
}

?>