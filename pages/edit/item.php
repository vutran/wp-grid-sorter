<li class="item <?php echo apply_filters('wpgs_grid_item_class', '', $post); ?>" data-id="<?php the_ID(); ?>">
    <?php if (has_post_thumbnail()) : the_post_thumbnail('thumbnail'); endif; ?>
    <h3>
        <?php if (strlen(get_the_title())) : ?>
            <?php the_title(); ?>
        <?php else : ?>
            (no title)
        <?php endif; ?>
    </h3>
    <div><?php the_content(); ?></div>
</li>