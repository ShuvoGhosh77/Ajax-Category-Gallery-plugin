<?php
// AJAX handler
add_action('wp_ajax_cps_gallery_filter', 'cps_gallery_filter_ajax');
add_action('wp_ajax_nopriv_cps_gallery_filter', 'cps_gallery_filter_ajax');
function cps_gallery_filter_ajax() {
    $cat_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : '';
    $paged  = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    cps_load_gallery_items($cat_id, $paged);
    wp_die();
}

// Shortcode
function cps_gallery_shortcode($atts) {
    $atts = shortcode_atts(array(
        'cat_id' => ''
    ), $atts, 'cps_gallery');

    $cat_id = !empty($atts['cat_id']) ? intval($atts['cat_id']) : '';

    $galleryNav = get_terms(array(
        'taxonomy' => 'cps_gallery_category',
        'hide_empty' => true
    ));

    ob_start();
    ?>
    <div class="filter-gallery-nav-wrapper">
        <div class="filter-gallery-nav">
            <ul>
                <li data-gallery-cat-id="" class="nav-active">ALL</li>
                <?php foreach ($galleryNav as $cat) : ?>
                    <li data-gallery-cat-id="<?php echo esc_attr($cat->term_id); ?>"><?php echo esc_html($cat->name); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div id="gallery-container" class="filter-gallery-item-container" data-cat-id="<?php echo esc_attr($cat_id); ?>">
        <div class="filter-gallery-item-output">
            <?php cps_load_gallery_items($cat_id, 1); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('cps_gallery', 'cps_gallery_shortcode');

// Load gallery items
function cps_load_gallery_items($cat_id, $paged) {
    $args = array(
        'post_type'      => 'cps-gallery',
        'posts_per_page' => 6,
        'paged'          => $paged,
    );

    if (!empty($cat_id)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'cps_gallery_category',
                'field'    => 'term_id',
                'terms'    => $cat_id,
            ),
        );
    }

    $gallery_query = new WP_Query($args);

    if ($gallery_query->have_posts()) {
        echo '<div class="filter-gallery-item-output-wrapper">';
        while ($gallery_query->have_posts()) {
            $gallery_query->the_post();
            $image_link = get_post_meta(get_the_ID(), 'gallery_item_popup', true);
            echo '<div class="gallery-item-wrapper">';
            echo '<a href="' . esc_url($image_link ? $image_link : get_the_post_thumbnail_url(get_the_ID(), 'full')) . '" data-lightbox="gallery" class="gallery-link">';
            echo '<div class="gallery-item-img">';
                 echo get_the_post_thumbnail(get_the_ID(),  array(400, 284));
                 echo '<span>Click to see Before & After</span>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }
        echo '</div>'; 
        // Pagination
        $total_pages = $gallery_query->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="filter-gallery-pagination">';
            echo '<ul class="pagination page-numbers">';

            // Display the first pages, then an ellipsis, then the last pages
            $page_range = 3;
            $shown_ellipsis = false;

            for ($i = 1; $i <= $total_pages; $i++) {
                // Show first 3 pages and last 3 pages
                if ($i <= $page_range || $i == $total_pages || ($i >= $paged - 1 && $i <= $paged + 1)) {
                    $class = ($i == $paged) ? 'class="page-link current"' : 'class="page-link"';
                    echo '<li><a href="#" ' . $class . ' data-page="' . $i . '">' . $i . '</a></li>';
                } elseif (!$shown_ellipsis && $i > $page_range && $i < $total_pages - 2) {
                    // Show ellipsis only once
                    echo '<li class="ellipsis">...</li>';
                    $shown_ellipsis = true;
                }
            }

            echo '</ul>';
            echo '</div>';
        }
    } else {
        echo '<div class="filter-gallery-item-output"><p>No gallery items found.</p></div>';
    }

    wp_reset_postdata();
}
