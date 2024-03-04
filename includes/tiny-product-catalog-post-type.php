<?php
/*  register the post type: product*/
function tpcatalog_register_post_type() {
    add_theme_support('post-thumbnails');

    $labels = array(
        'name' => 'Tuotteet',
        'singular name' => 'Tuote',
        'add_new' => 'Lisää tuote',
        'add_new_item' => 'Lisää uusi tuote',
        'edit_item' => 'Muokkaa tuotetta',
        'new_item' => 'Uusi tuote',
        'view_item' => 'Selaa tuotteita',
        'search_items' => 'Etsi tuotteita',
        'not_found' => 'Tuotetta ei löytynyt',
        'not_found_in_trash' => 'Tuotetta ei löytynyt roskakorista'
    );

    $args = array(
        'labels' => $labels,
        'has_archive' => true,
        'public' => true,
        'hierarchical' => false,
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'custom-fields'
        ),
        'rewrite' => array('slug' => 'tuote'),
        'show_in_rest' => true
    );

    register_post_type('tpcatalog_product', $args);
}

add_action('init', 'tpcatalog_register_post_type');

/* Add price box */
function tpcatalog_add_custom_box() {
    add_meta_box(
      'tpcatalog_price_id',
      'Hinta',
      'tpcatalog_price_box_html',
      'tpcatalog_product'
    );
}
add_action('add_meta_boxes', 'tpcatalog_add_custom_box');

function tpcatalog_price_box_html($post) {
    $value = get_post_meta($post->ID, '_tpcatalog_meta_price', true);
    ?>
    <label for="tpcatalog_price">Hinta</label>
    <input type="text" name="tpcatalog_price" id="tpcatalog_price" value="<?php echo $value; ?>">
    <?php
}

/* save post meta */
function tpcatalog_save_postdata($post_id) {
    if(array_key_exists('tpcatalog_price', $_POST)):
        update_post_meta(
          $post_id,
          '_tpcatalog_meta_price',
          sanitize_text_field($_POST['tpcatalog_price'])
        );
    endif;
}

add_action('save_post', 'tpcatalog_save_postdata');

/* register new taxonomy: product category */
function tpcatalog_register_taxonomy() {
    $labels = array(
        'name' => 'Tuotekategoriat',
        'singular_name' => 'Tuotekategoria',
        'search_items' => 'Etsi tuotekategorioita',
        'all_items' => 'Kaikki tuotekategoriat',
        'edit_item' => 'Muokkaa tuotekategoriaa',
        'update_item' => 'Päivitä tuotekategoria',
        'add_new_item' => 'Lisää tuotekategoria',
        'new_item_name' => 'Uuden tuotekategorian nimi',
        'menu_name' => 'Tuotekategoriat'
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'sort' => true,
        'args' => array('orderby' => 'term_order'),
        'rewrite' => array('slug' => 'tuotteet'),
        'show_admin_column' => true,
        'show_in_rest' => true
    );

    register_taxonomy('tpcatalog_category', array('tpcatalog_product'), $args);
}
add_action('init', 'tpcatalog_register_taxonomy');

?>