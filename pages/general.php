<?php

global $wp_grid_sorter;

?>

<div class="wrap">

    <h1>Grid Sorter</h1>

    <div class="wpgs-notifications"></div>

    <table class="wp-list-table widefat fixed pages">
        <tbody>
            <?php if ($wp_grid_sorter->mappings->hasKeys()) : ?>
                <?php foreach ($wp_grid_sorter->mappings->getKeys() as $sortKey => $sortArgs) : ?>
                <tr>
                    <th scope="row" class="column-title">
                        <strong>
                            <?php echo $sortArgs['name']; ?>
                            <?php if (isset($sortArgs['post_type'])) : ?>
                                (Post Type: <?php echo $sortArgs['post_type']; ?>)
                            <?php elseif (isset($sortArgs['query']) && $sortArgs['query'] instanceof WP_Query) : ?>
                                (Custom WP_Query)
                            <?php endif; ?>
                        </strong>
                        <div class="row-actions">
                            <span class="edit"><a href="<?php echo admin_url('admin.php?page=wp-grid-sorter/pages/general&key=' . $sortKey); ?>" title="Edit">Edit</a></span>
                        </div>
                    </th>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>