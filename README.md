# WordPress Grid Sorter

Sort your WordPress posts with custom sort keys

# Functions

`wp_grid_sorter_register_sort_key($sortKey, $args)`

## Arguments

`post_type` Specify the post type for this key

# How to register a custom sort key

	<?php
    add_action('init', 'site_register_wp_grid_sorters');
    function site_register_wp_grid_sorters()
    {
        if (function_exists('wp_grid_sorter_register_sort_key')) {
            $args = array(
                'post_type' => 'post'
            );
            wp_grid_sorter_register_sort_key('home_page_featured', $args);   
        }
    }
    ?>