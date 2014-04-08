<?php

global $wp_grid_sorter, $post;

$sortKey = esc_attr($_GET['key']);
$sortArgs = $wp_grid_sorter->mappings->getArgs($sortKey);

// Query for ordered posts
$postArgs = array(
    'post_type' => $sortArgs['post_type'],
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_key' => $sortKey
);
$postQuery = new WP_Query($postArgs);

// Query for unordered posts
$unorderedArgs = array(
    'post_type' => $sortArgs['post_type'],
    'meta_query' => array(
        array(
            'key' => $sortKey,
            'compare' => 'NOT EXISTS'
        )
    )
);
$unorderedQuery = new WP_Query($unorderedArgs);

?>

<div class="wrap">

    <h1>Editing <?php echo $sortArgs['name']; ?></h1>

    <p>Drag each grid item around and click Update to save your order.</p>

    <div class="wpgs-notifications"></div>

    <form class="wpgs-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
    <input type="hidden" name="action" value="wpgs_save_order" />
    <input type="hidden" name="sort_key" value="<?php echo $sortKey; ?>" />

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div id="post-body-content">
                    <ol class="wpgs-grid">
                        <?php if ($postQuery->have_posts()) : ?>
                            <?php while ($postQuery->have_posts()) : $postQuery->the_post(); ?>
                                <li class="item <?php echo apply_filters('wpgs_grid_item_class', '', $post); ?>" data-id="<?php the_ID(); ?>">
                                    <?php if (has_post_thumbnail()) : the_post_thumbnail('thumbnail'); endif; ?>
                                    <h3><?php the_title(); ?></h3>
                                    <div><?php the_content(); ?></div>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        <?php if ($unorderedQuery->have_posts()) : ?>
                            <?php while ($unorderedQuery->have_posts()) : $unorderedQuery->the_post(); ?>
                                <li class="item <?php echo apply_filters('wpgs_grid_item_class', '', $post); ?>" data-id="<?php the_ID(); ?>">
                                    <?php if (has_post_thumbnail()) : the_post_thumbnail('thumbnail'); endif; ?>
                                    <h3><?php the_title(); ?></h3>
                                    <div><?php the_content(); ?></div>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ol>
                </div>
                <div id="postbox-container-1" class="postbox-container">
                    <div class="meta-box-sortables">
                        <div id="submitdiv" class="postbox">
                            <div class="handlediv" title="Click to toggle"><br></div>
                            <h3 class="hndle"><span>Publish</span></h3>
                            <div class="inside">
                                <div class="submitbox" id="submitpost">
                                </div><div id="major-publishing-actions">
                                    <div id="publishing-action">
                                        <span class="spinner"></span>
                                        <button type="submit" class="button button-primary button-primary" id="publish" accesskey="p">Update</button>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </form>

</div>