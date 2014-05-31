# WordPress Grid Sorter

Sort your WordPress posts with custom sort keys

# Functions

`wp_grid_sorter_register_sort_key($sortKey, $args)`

## Arguments

`name` Specify the name to display in the CMS

`post_type` Specify the post type for this key

# Register a custom sort key by post type

    <?php
    add_action('init', 'site_register_wp_grid_sorters');
    function site_register_wp_grid_sorters()
    {
        if (function_exists('wp_grid_sorter_register_sort_key')) {
            $args = array(
                'name' => 'Sort All Posts',
                'post_type' => 'post'
            );
            wp_grid_sorter_register_sort_key('sort_all_posts', $args);   
        }
    }
    ?>

# Register a custom sort key by custom WP_Query
    <?php
    $args = array(
        'name' => 'Home Page Featured',
        'query' => new WP_Query(array(
            'post_type' => 'post',
            'meta_query' => array(
                array(
                    'key' => 'featured',
                    'value' => 1
                )
            )
        ))
    );
    wp_grid_sorter_register_sort_key('home_page_featured', $args);
    ?>

# Query posts and order by your custom sort key

This is automatically hooked into [`WP_Query`](http://codex.wordpress.org/Class_Reference/WP_Query). Please refer to the documentation for [order by a meta key](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters) in the WordPress Codex.

## Example:

    <?php
    $args = array(
        'post_type' => 'post',
        'order' => 'ASC',
        'orderby' => 'meta_value_num',
        'meta_key' => 'home_page_featured'
    );
    $myCustomQuery = new WP_Query($args);
    ?>