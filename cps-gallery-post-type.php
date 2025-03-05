<?php
/*
Plugin Name: CPS Gallery Filter
Description: A plugin to filter and display gallery items by category with AJAX pagination.
Version: 1.0
Author: Shuvo Gosh
*/

// Enqueue style
function cps_gallery_custom_styles()
{
    global $post;

    wp_enqueue_style('cps-gallery-styles', plugin_dir_url(__FILE__) . 'assets/cps-gallery-styles.css', null, '1.0');
    // Lightbox2 CSS
    wp_enqueue_style('lightbox2-css', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css');

}
add_action('wp_enqueue_scripts', 'cps_gallery_custom_styles');
// Enqueue scripts
function cps_gallery_enqueue_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script(
        'cps-gallery-filter-script',
        plugins_url('js/cps-gallery-filter.js', __FILE__),
        array('jquery'),
        '1.0',
        true
    );
    wp_localize_script(
        'cps-gallery-filter-script',
        'cps_gallery_ajax',
        array(
            'ajax_url' => admin_url('admin-ajax.php')
        )
    );
    // Lightbox2 JS
    wp_enqueue_script('lightbox2-js', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'cps_gallery_enqueue_scripts');

// CPS Gallery Frontend
require_once plugin_dir_path(__FILE__) . 'include/cps-gallery-filter-function.php';

// Register custom post type
function gallery_post_type_add()
{
    $labels = array(
        'name' => __('Gallery', 'cps-gallery'),
        'singular_name' => __('Gallery', 'cps-gallery'),
        'add_new' => __('New Gallery Item', 'cps-gallery'),
        'add_new_item' => __('Add New Gallery Item', 'cps-gallery'),
        'edit_item' => __('Edit Gallery Item', 'cps-gallery'),
        'new_item' => __('New Gallery Item', 'cps-gallery'),
        'search_items' => __('Search Gallery Item', 'cps-gallery'),
        'not_found' => __('No Gallery Item Found', 'cps-gallery'),
        'not_found_in_trash' => __('No Gallery Item found in Trash', 'cps-gallery'),
    );

    $args = array(
        'labels' => $labels,
        'has_archive' => true,
        'public' => true,
        'publicly_queryable' => false,
        'hierarchical' => false,
        'supports' => array(
            'title',
            'editor',
            'author',
            'custom-fields',
            'thumbnail',
            'page-attributes'
        ),
        'rewrite' => array('slug' => 'cps-gallery'),
        'show_in_rest' => true
    );
    register_post_type('cps-gallery', $args);
}
add_action('init', 'gallery_post_type_add');

// Register custom taxonomy
function cps_gallery_taxonomy()
{
    $labels = array(
        'name' => _x('Categories', 'taxonomy general name'),
        'singular_name' => _x('Category', 'taxonomy singular name'),
        'search_items' => __('Search Categories'),
        'all_items' => __('All Categories'),
        'parent_item' => __('Parent Category'),
        'parent_item_colon' => __('Parent Category:'),
        'edit_item' => __('Edit Category'),
        'update_item' => __('Update Category'),
        'add_new_item' => __('Add New Category'),
        'new_item_name' => __('New Category Name'),
        'menu_name' => __('Categories'),
    );
    register_taxonomy(
        'cps_gallery_category',
        array('cps-gallery'),
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'cps_gallery_category'),
        )
    );
}
add_action('init', 'cps_gallery_taxonomy', 0);

// Add meta box for popup image link
function cps_gallery_add_meta_box()
{
    add_meta_box(
        'cps_gallery_popup_link',
        'Popup Image Link',
        'cps_gallery_popup_link_callback',
        'cps-gallery',
        'normal'
    );
}
add_action('add_meta_boxes', 'cps_gallery_add_meta_box');

// Meta box callback
function cps_gallery_popup_link_callback($post)
{
    wp_nonce_field('cps_gallery_save_popup_link', 'cps_gallery_popup_link_nonce');
    $value = get_post_meta($post->ID, 'gallery_item_popup', true);
    echo '<label for="cps_gallery_popup_link_field">Popup Image Link:</label>';
    echo '<input type="text" id="cps_gallery_popup_link_field" name="cps_gallery_popup_link_field" value="' . esc_attr($value) . '" size="25" />';
}

// Save meta box data
function cps_gallery_save_popup_link($post_id)
{
    if (!isset($_POST['cps_gallery_popup_link_nonce'])) {
        return $post_id;
    }
    $nonce = $_POST['cps_gallery_popup_link_nonce'];
    if (!wp_verify_nonce($nonce, 'cps_gallery_save_popup_link')) {
        return $post_id;
    }
    if (isset($_POST['cps_gallery_popup_link_field'])) {
        update_post_meta($post_id, 'gallery_item_popup', sanitize_text_field($_POST['cps_gallery_popup_link_field']));
    }
}
add_action('save_post', 'cps_gallery_save_popup_link');