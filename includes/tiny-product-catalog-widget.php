<?php

class Tpcatalog_Widget extends WP_Widget {
    public function __construct() {
        parent:: __construct(
            'tpcatalog_widget',
            'Pieni tuotekatalogi',
            array(
                'customize_selective_refresh' => true
            )
        );
    }

    public function form($instance) {
        $defaults = array(
            'title' => '',
            'category' => 'all'
        );

        extract(wp_parse_args( (array) $instance, $defaults)); ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Otsikko</label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
            name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
        <label for="<?php echo esc_attr($this->get_field_id('category')); ?>">Kategoria</label>
        <select id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>" class="widefat">
        <?php
            $terms = get_terms(
                array(
                    'taxonomy' => 'tpcatalog_category',
                    'hide_empty' => false
                )
            );
            $options = array(
                'all' => 'Kaikki tuotekategoriat'
            );

            foreach($terms as $term) :
                $options[$term->slug] = $term->name;
            endforeach;

            foreach($options as $key => $name) :
                echo '<option value="' . esc_attr($key) . '"' . selected($category, $key, false) . '>' . $name . '</option>';
            endforeach;
        ?>
        </select>
        </p>
    <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = isset($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
        $instance['category'] = isset($new_instance['category']) ? wp_strip_all_tags($new_instance['category']) : '';
        return $instance;
    }

    public function widget($args, $instance) {

        extract($args);

        $title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        $category = isset($instance['category']) ?  $instance['category'] : 'all';

        echo $before_widget;

        echo '<div class="wp-widget-tpcatalog">';

        if($title) :
            echo $before_title . $title . $after_title;
        endif;

        if($category == 'all'):
          $args = array(
              'post_type' => 'tpcatalog_product',
              'post_status' => 'publish',
              'orderby' => 'rand',
              'posts_per_page' => '1'
          );
        else:
            $args = array(
                'post_type' => 'tpcatalog_product',
                'tax_query' => array(
                    array(
                          'taxonomy' => 'tpcatalog_category',
                          'field' => 'slug',
                          'terms' => $category
                    )
                ),
                'post_status' => 'publish',
                'orderby' => 'rand',
                'posts_per_page' => '1'
            );
        endif;

    $text = '';
        $loop = new WP_Query($args);
        if ($loop->have_posts()):
            while($loop->have_posts()) : $loop->the_post();
            $price = get_post_meta(get_the_ID(), '_tpcatalog_meta_price', true);
            $text .= '<section class="tiny-product"><h3>' . get_the_title() . '</h3>';
            $text .= '<p>' . $price . '</p>';
            $text .= get_the_post_thumbnail();
            $text .= '<p>' . get_the_content() . '</p></section>';
            endwhile;
        else:
            $text .= '<p>Tuotteita ei löytynyt.</p>';
        endif;

        echo $text;

        wp_reset_postdata();

        echo '</div>';

        echo $after_widget;
    }
}

function tpcatalog_register_widget() {
    register_widget('Tpcatalog_Widget');
}

add_action('widgets_init', 'tpcatalog_register_widget');

?>