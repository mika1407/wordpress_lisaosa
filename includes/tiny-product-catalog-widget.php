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
    }

    public function widget($args, $instance) {

    }
}

function tpcatalog_register_widget() {
    register_widget('Tpcatalog_Widget');
}

add_action('widgets_init', 'tpcatalog_register_widget');

?>