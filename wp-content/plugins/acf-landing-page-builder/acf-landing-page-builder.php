<?php
/**
 * Plugin Name: ACF Landing Page Builder
 * Description: Landing Page Builder
 * Version: 1.0.0
 * Author: Alexander Poslushnyak
 * Author URI: https://github.com/alexposlushnyak
 * Text Domain: acf-page-builder
 */

if (!defined('ABSPATH')) :
    exit;
endif;

DEFINE('CORE_PLUGIN_VERSION', '1.0.0');

define("CORE_PLUGIN_PATH", plugin_dir_path(__FILE__));

define("CORE_PLUGIN_URL", plugin_dir_url(__FILE__));

final class ACF_Builder
{

    private static $_instance = null;

    public function __construct()
    {

        add_action('init', [$this, 'init']);

        add_action('admin_head', [$this, 'enqueue_admin_styles']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);

        add_filter('the_content', [$this, 'insert_in_post']);

    }

    public function insert_in_post($content)
    {

        if (ACF_Builder::get_field('field_repeater_builder_toggle')):

            ACF_Builder::single_landing_template();

        else:

            return $content;

        endif;

    }

    public static function the_option($param)
    {

        if (function_exists('the_field')) :

            return the_field($param, 'option');

        endif;

    }

    public static function get_option($param)
    {

        if (function_exists('get_field')) :

            return get_field($param, 'option');

        endif;

    }

    public static function the_field($param, $id = null)
    {

        if ($id == null) :

            $id = get_the_ID();

        endif;

        if (function_exists('the_field')) :

            return the_field($param, $id);

        endif;

    }

    public static function get_field($param, $id = null)
    {

        if ($id == null) :

            $id = get_the_ID();

        endif;

        if (function_exists('get_field')) :

            return get_field($param, $id);

        endif;

    }

    public static function the_sub_field($param)
    {

        if (function_exists('the_sub_field')) :

            return the_sub_field($param);

        endif;

    }

    public static function get_sub_field($param)
    {

        if (function_exists('get_sub_field')) :

            return get_sub_field($param);

        endif;

    }

    public function enqueue_styles()
    {

        if (!wp_style_is('acf-repeater-builder-frontend-css')):

            wp_enqueue_style('acf-repeater-builder-frontend-css', CORE_PLUGIN_URL . '/inc/assets/css/acf_repeater_builder_frontend.css');

        endif;

    }

    public function enqueue_admin_styles()
    {

        if (!wp_style_is('acf-repeater-builder-css')):

            wp_enqueue_style('acf-repeater-builder-css', CORE_PLUGIN_URL . '/inc/assets/css/acf_repeater_builder.css');

        endif;

    }

    public static function instance()
    {

        if (is_null(self::$_instance)) :

            self::$_instance = new self();

        endif;

        return self::$_instance;

    }

    public static function single_landing_template()
    {

        ?>

        <div class="neo-landing-wrapper">

            <?php

            if (have_rows('field_repeater_builder')):

                while (have_rows('field_repeater_builder')) : the_row();

                    $section_type = ACF_Builder::get_sub_field('field_builder_section_type');

                    if ($section_type === 'hero-section'):

                        $field_builder_section_hero_type_of_bg = ACF_Builder::get_sub_field('field_builder_section_hero_type_of_bg');

                        $field_builder_section_hero_bg_color = ACF_Builder::get_sub_field('field_builder_section_hero_bg_color');

                        $field_builder_section_hero_bg_image = ACF_Builder::get_sub_field('field_builder_section_hero_bg_image');

                        $field_builder_section_hero_main_headline = ACF_Builder::get_sub_field('field_builder_section_hero_main_headline');

                        $field_builder_section_hero_main_headline_color = ACF_Builder::get_sub_field('field_builder_section_hero_main_headline_color');

                        $field_builder_section_hero_sub_headline = ACF_Builder::get_sub_field('field_builder_section_hero_sub_headline');

                        $field_builder_section_hero_sub_headline_color = ACF_Builder::get_sub_field('field_builder_section_hero_sub_headline_color');

                        $field_builder_section_hero_secondary_headline = ACF_Builder::get_sub_field('field_builder_section_hero_secondary_headline');

                        $field_builder_section_hero_secondary_headline_color = ACF_Builder::get_sub_field('field_builder_section_hero_secondary_headline_color');

                        $field_builder_section_hero_more_info_box_toggle = ACF_Builder::get_sub_field('field_builder_section_hero_more_info_box_toggle');

                        $field_builder_section_hero_more_info_headline = ACF_Builder::get_sub_field('field_builder_section_hero_more_info_headline');

                        $field_builder_section_hero_more_info_headline_color = ACF_Builder::get_sub_field('field_builder_section_hero_more_info_headline_color');

                        $field_builder_section_hero_propositions_color = ACF_Builder::get_sub_field('field_builder_section_hero_propositions_color');

                        $field_builder_section_hero_more_info_secondary_headline = ACF_Builder::get_sub_field('field_builder_section_hero_more_info_secondary_headline');

                        $field_builder_section_hero_more_info_secondary_headline_color = ACF_Builder::get_sub_field('field_builder_section_hero_more_info_secondary_headline_color');

                        $field_builder_section_hero_more_info_box_phone_toggle = ACF_Builder::get_sub_field('field_builder_section_hero_more_info_box_phone_toggle');

                        $field_builder_section_hero_more_info_button_label = ACF_Builder::get_sub_field('field_builder_section_hero_more_info_button_label');

                        $field_builder_section_hero_more_info_button_bg = ACF_Builder::get_sub_field('field_builder_section_hero_more_info_button_bg');

                        $field_builder_section_hero_more_info_button_color = ACF_Builder::get_sub_field('field_builder_section_hero_more_info_button_color');

                        ?>

                        <section class="neo-section hero-section"
                                 style="<?php if ($field_builder_section_hero_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_hero_bg_color); ?><?php elseif ($field_builder_section_hero_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_hero_bg_image,'full')); ?>);<?php endif; ?>">

                            <div class="neo-container inner-wrapper">

                                <div class="left-side">

                                    <?php if (!empty($field_builder_section_hero_sub_headline)): ?>

                                        <p class="sub-headline"
                                           style="color: <?php echo esc_attr($field_builder_section_hero_sub_headline_color); ?>;">

                                            <?php echo esc_html($field_builder_section_hero_sub_headline); ?>

                                        </p>

                                    <?php endif; ?>

                                    <?php if (!empty($field_builder_section_hero_main_headline)): ?>

                                        <h1 class="hero-main-heading"
                                            style="color: <?php echo esc_attr($field_builder_section_hero_main_headline_color); ?>;">

                                            <?php echo esc_html($field_builder_section_hero_main_headline); ?>

                                        </h1>

                                    <?php endif; ?>

                                    <?php

                                    if (have_rows('field_builder_section_hero_propositions')): ?>

                                        <ul class="propositions-list">

                                            <?php

                                            while (have_rows('field_builder_section_hero_propositions')) : the_row();

                                                $field_builder_section_hero_proposition_title = self::get_sub_field('field_builder_section_hero_proposition_title');

                                                if (!empty($field_builder_section_hero_proposition_title)):

                                                    ?>

                                                    <li>

                                                        <p class="proposition-title"
                                                           style="color: <?php echo esc_attr($field_builder_section_hero_propositions_color); ?>;">

                                                            <?php echo esc_html($field_builder_section_hero_proposition_title); ?>

                                                        </p>

                                                    </li>

                                                <?php

                                                endif;

                                            endwhile; ?>

                                        </ul>

                                    <?php

                                    endif;

                                    ?>

                                    <?php if (!empty($field_builder_section_hero_secondary_headline)): ?>

                                        <p class="secondary-headline" style="color: <?php echo esc_attr($field_builder_section_hero_secondary_headline_color); ?>;">

                                            <?php echo esc_html($field_builder_section_hero_secondary_headline); ?>

                                        </p>

                                    <?php endif; ?>

                                </div>

                                <?php if ($field_builder_section_hero_more_info_box_toggle): ?>

                                    <div class="right-side">

                                        <div class="info-box-wrapper">

                                            <h2 class="info-box-title" style="color: <?php echo esc_attr($field_builder_section_hero_more_info_headline_color); ?>;">

                                                <?php echo esc_html($field_builder_section_hero_more_info_headline); ?>

                                            </h2>

                                            <p class="info-box-secondary-headline" style="color: <?php echo esc_attr($field_builder_section_hero_more_info_secondary_headline_color); ?>;">

                                                <?php echo esc_html($field_builder_section_hero_more_info_secondary_headline); ?>

                                            </p>

                                            <div class="form-wrapper">


                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </section>

                    <?php

                    elseif ($section_type === 'buy-now-get-more-info-section'):

                        $field_builder_section_buy_now_type_of_bg = self::get_sub_field('field_builder_section_buy_now_type_of_bg');

                        $field_builder_section_buy_now_bg_color = self::get_sub_field('field_builder_section_buy_now_bg_color');

                        $field_builder_section_buy_now_bg_image = self::get_sub_field('field_builder_section_buy_now_bg_image');

                        $field_builder_section_buy_now_main_headline = self::get_sub_field('field_builder_section_buy_now_main_headline');

                        ?>

                        <section class="neo-section buy-now-get-more-info-section"
                                 style="<?php if ($field_builder_section_buy_now_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_buy_now_bg_color); ?><?php elseif ($field_builder_section_buy_now_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_buy_now_bg_image,'full')); ?>);<?php endif; ?>">

                            <div class="neo-container inner-wrapper">

                                <?php if (!empty($field_builder_section_buy_now_main_headline)): ?>

                                    <h2 class="section-title">

                                        <?php echo esc_html($field_builder_section_buy_now_main_headline); ?>

                                    </h2>

                                <?php endif; ?>

                                <?php if (have_rows('field_builder_section_buy_now_product_boxes')): ?>

                                    <div class="boxes-list">

                                        <?php

                                        while (have_rows('field_builder_section_buy_now_product_boxes')) : the_row();

                                            $field_builder_section_buy_now_product_boxes_title = self::get_sub_field('field_builder_section_buy_now_product_boxes_title');

                                            $field_builder_section_buy_now_product_boxes_content = self::get_sub_field('field_builder_section_buy_now_product_boxes_content');

                                            $field_builder_section_buy_now_product_boxes_button_label = self::get_sub_field('field_builder_section_buy_now_product_boxes_button_label');

                                            $field_builder_section_buy_now_product_boxes_button_url = self::get_sub_field('field_builder_section_buy_now_product_boxes_button_url');

                                            $field_builder_section_buy_now_product_boxes_button_bg = self::get_sub_field('field_builder_section_buy_now_product_boxes_button_bg');

                                            ?>

                                            <div class="box">

                                                <div class="box-inner">

                                                    <?php if (!empty($field_builder_section_buy_now_product_boxes_title)): ?>

                                                        <h4 class="box-title">

                                                            <?php echo esc_html($field_builder_section_buy_now_product_boxes_title); ?>

                                                        </h4>

                                                    <?php endif; ?>

                                                    <?php if (!empty($field_builder_section_buy_now_product_boxes_content)): ?>

                                                        <p class="box-content">

                                                            <?php echo esc_html($field_builder_section_buy_now_product_boxes_content); ?>

                                                        </p>

                                                    <?php endif; ?>

                                                    <?php if (!empty($field_builder_section_buy_now_product_boxes_button_label)): ?>

                                                        <div class="button-wrapper">

                                                            <a class="neo-button"
                                                               href="<?php echo esc_url($field_builder_section_buy_now_product_boxes_button_url); ?>"
                                                               style="background-color: <?php echo esc_attr($field_builder_section_buy_now_product_boxes_button_bg); ?>">

                                                                <?php echo esc_html($field_builder_section_buy_now_product_boxes_button_label); ?>

                                                            </a>

                                                        </div>

                                                    <?php endif; ?>

                                                </div>

                                            </div>

                                        <?php

                                        endwhile; ?>

                                    </div>

                                <?php

                                endif;

                                ?>

                            </div>

                        </section>

                    <?php

                    elseif ($section_type === 'gallery-section'):

                        $field_builder_section_gallery_main_headline = ACF_Builder::get_sub_field('field_builder_section_gallery_main_headline');

                        $field_builder_section_gallery_type_of_bg = ACF_Builder::get_sub_field('field_builder_section_gallery_type_of_bg');

                        $field_builder_section_gallery_bg_color = ACF_Builder::get_sub_field('field_builder_section_gallery_bg_color');

                        $field_builder_section_gallery_bg_image = ACF_Builder::get_sub_field('field_builder_section_gallery_bg_image');

                        $field_builder_section_gallery_gallery = ACF_Builder::get_sub_field('field_builder_section_gallery_gallery');

                        ?>

                        <section class="neo-section gallery-section"
                                 style="<?php if ($field_builder_section_gallery_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_gallery_bg_color); ?><?php elseif ($field_builder_section_gallery_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_gallery_bg_image,'full')); ?>);<?php endif; ?>">

                            <div class="neo-container inner-wrapper">

                                <?php if (!empty($field_builder_section_gallery_main_headline)): ?>

                                    <h2 class="section-title">

                                        <?php echo esc_html($field_builder_section_gallery_main_headline); ?>

                                    </h2>

                                <?php endif; ?>

                                <?php if (!empty($field_builder_section_gallery_gallery)): ?>

                                    <div class="gallery-wrapper">

                                        <?php foreach ($field_builder_section_gallery_gallery as $gallery): ?>

                                            <div class="gallery-item">

                                                <div class="inner-item">

                                                    <div class="image-wrapper"
                                                         style="background-image: url('<?php echo esc_url($gallery); ?>')">

                                                    </div>

                                                </div>

                                            </div>

                                        <?php endforeach; ?>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </section>

                    <?php

                    elseif ($section_type === 'faqs-section'):

                        $field_builder_section_faqs_type_of_bg = ACF_Builder::get_sub_field('field_builder_section_faqs_type_of_bg');

                        $field_builder_section_faqs_bg_color = ACF_Builder::get_sub_field('field_builder_section_faqs_bg_color');

                        $field_builder_section_faqs_bg_image = ACF_Builder::get_sub_field('field_builder_section_faqs_bg_image');

                        $field_builder_section_faqs_main_headline = ACF_Builder::get_sub_field('field_builder_section_faqs_main_headline');

                        $field_builder_section_faqs_secondary_headline = ACF_Builder::get_sub_field('field_builder_section_faqs_secondary_headline');

                        ?>

                        <section class="neo-section faqs-section"
                                 style="<?php if ($field_builder_section_faqs_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_faqs_bg_color); ?><?php elseif ($field_builder_section_faqs_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_faqs_bg_image,'full')); ?>);<?php endif; ?>">

                            <div class="neo-container inner-wrapper">

                                <?php if (!empty($field_builder_section_faqs_main_headline)): ?>

                                    <h2 class="section-title">

                                        <?php echo esc_html($field_builder_section_faqs_main_headline); ?>

                                    </h2>

                                <?php endif; ?>

                                <?php if (!empty($field_builder_section_faqs_secondary_headline)): ?>

                                    <p class="section-subtitle">

                                        <?php echo esc_html($field_builder_section_faqs_secondary_headline); ?>

                                    </p>

                                <?php endif; ?>

                                <?php if (have_rows('field_builder_section_faqs_builder')): ?>

                                    <div class="faqs-wrapper">

                                        <?php while (have_rows('field_builder_section_faqs_builder')) : the_row(); ?>

                                            <?php

                                            $field_builder_section_faqs_builder_category_name = ACF_Builder::get_sub_field('field_builder_section_faqs_builder_category_name');

                                            ?>

                                            <div class="faq-category">

                                                <h5 class="category-name">

                                                    <?php echo esc_html($field_builder_section_faqs_builder_category_name); ?>

                                                </h5>

                                                <?php if (have_rows('field_builder_section_faqs_builder_category_questions_answers')): ?>

                                                    <div class="questions-wrapper">

                                                        <?php while (have_rows('field_builder_section_faqs_builder_category_questions_answers')) : the_row(); ?>

                                                            <?php

                                                            $field_builder_section_faqs_builder_category_question = ACF_Builder::get_sub_field('field_builder_section_faqs_builder_category_question');

                                                            $field_builder_section_faqs_builder_category_answer = ACF_Builder::get_sub_field('field_builder_section_faqs_builder_category_answer');

                                                            ?>

                                                            <div class="question-wrapper">

                                                                <?php if (!empty($field_builder_section_faqs_builder_category_question)): ?>

                                                                    <h6 class="question-title">

                                                                        <?php echo esc_html($field_builder_section_faqs_builder_category_question); ?>

                                                                    </h6>

                                                                <?php endif; ?>

                                                                <?php if (!empty($field_builder_section_faqs_builder_category_answer)): ?>

                                                                    <p class="answer-text">

                                                                        <?php echo esc_html($field_builder_section_faqs_builder_category_answer); ?>

                                                                    </p>

                                                                <?php endif; ?>

                                                            </div>

                                                        <?php endwhile; ?>

                                                    </div>

                                                <?php endif; ?>

                                            </div>

                                        <?php endwhile; ?>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </section>

                    <?php

                    elseif ($section_type === 'rich-text-row-section'):

                        $field_builder_section_rich_text_row_type_of_bg = ACF_Builder::get_sub_field('field_builder_section_rich_text_row_type_of_bg');

                        $field_builder_section_rich_text_row_bg_color = ACF_Builder::get_sub_field('field_builder_section_rich_text_row_bg_color');

                        $field_builder_section_rich_text_row_bg_image = ACF_Builder::get_sub_field('field_builder_section_rich_text_row_bg_image');

                        $field_builder_section_rich_text_row_wsw = ACF_Builder::get_sub_field('field_builder_section_rich_text_row_wsw');

                        $field_builder_section_rich_text_row_button_label = ACF_Builder::get_sub_field('field_builder_section_rich_text_row_button_label');

                        $field_builder_section_rich_text_row_button_url = ACF_Builder::get_sub_field('field_builder_section_rich_text_row_button_url');

                        $field_builder_section_rich_text_row_button_bg = ACF_Builder::get_sub_field('field_builder_section_rich_text_row_button_bg');

                        ?>

                        <section class="neo-section rich-text-row-section"
                                 style="<?php if ($field_builder_section_rich_text_row_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_rich_text_row_bg_color); ?><?php elseif ($field_builder_section_rich_text_row_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_rich_text_row_bg_image,'full')); ?>);<?php endif; ?>">

                            <div class="neo-container inner-wrapper">

                                <?php if (!empty($field_builder_section_rich_text_row_wsw)): ?>

                                    <div class="rich-text">

                                        <?php echo esc_html($field_builder_section_rich_text_row_wsw); ?>

                                    </div>

                                <?php endif; ?>

                                <?php if (!empty($field_builder_section_rich_text_row_button_label)): ?>

                                    <div class="button-wrapper">

                                        <a class="neo-button"
                                           href="<?php echo esc_url($field_builder_section_rich_text_row_button_url); ?>"
                                           style="background-color: <?php echo esc_attr($field_builder_section_rich_text_row_button_bg); ?>">

                                            <?php echo esc_html($field_builder_section_rich_text_row_button_label); ?>

                                        </a>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </section>

                    <?php

                    else:

                        ?>


                    <?php

                    endif;

                endwhile;

            endif;

            ?>

        </div>

        <?php

    }

    public function init()
    {

        if (function_exists('acf_add_local_field_group')):

            acf_add_local_field_group(array(
                'key' => 'group_repeater_builder_settings',
                'title' => __('Landing Page Builder', 'glekk'),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'post',
                        ),
                    ),
                ),
                'position' => 'normal',
            ));

            acf_add_local_field_group(array(
                'key' => 'group_footer_post_settings',
                'title' => __('Footer Settings', 'glekk'),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'post',
                        ),
                    ),
                ),
                'position' => 'side',
            ));

        endif;

        if (function_exists('acf_add_local_field')):

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_toggle',
                'label' => esc_html('Enable/Disable Landing Page Builder'),
                'name' => 'option_repeater_builder_toggle',
                'type' => 'true_false',
                'conditional_logic' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => esc_html('Enable'),
                'ui_off_text' => esc_html('Disable'),
                'parent' => 'group_repeater_builder_settings',
            ));

            acf_add_local_field(array(
                'key' => 'field_repeater_builder',
                'label' => esc_html('Landing Page Builder'),
                'name' => 'option_repeater_builder',
                'type' => 'repeater',
                'label_placement' => 'top',
                'instructions' => esc_html('Build your own landing page'),
                'min' => 1,
                'max' => 0,
                'layout' => 'block',
                'button_label' => esc_html('Add new Section'),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_builder_section_type',
                        'label' => esc_html('Section Type'),
                        'name' => 'option_builder_section_type',
                        'type' => 'select',
                        'instructions' => esc_html('Select the section type'),
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => 0,
                        'choices' => array(
                            'hero-section' => esc_html('Hero Section'),
                            'buy-now-get-more-info-section' => esc_html('Buy Now / Get More Info'),
                            'gallery-section' => esc_html('Gallery'),
                            'faqs-section' => esc_html('FAQs'),
                            'rich-text-row-section' => esc_html('Rich Text Row'),
                        ),
                        'default_value' => 'rich-text-row-section',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                    ),

                    /* HERO SECTION START */

                    array(
                        'key' => 'field_builder_section_hero_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_hero_type_of_bg',
                        'instructions' => esc_html('Select the type of Section Background Style'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                        ),
                        'default_value' => 'image',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_bg_color',
                        'label' => esc_html('Background Color'),
                        'name' => 'option_builder_section_hero_bg_color',
                        'instructions' => esc_html('Select the Section Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_hero_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'color',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_bg_image',
                        'label' => esc_html('Background Image'),
                        'name' => 'option_builder_section_hero_bg_image',
                        'instructions' => esc_html('Select the Section Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_hero_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'image',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_hero_main_headline',
                        'instructions' => esc_html('Input the Hero Main Headline'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '66.66666%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Main Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_main_headline_color',
                        'label' => esc_html('Main Headline Color'),
                        'name' => 'option_builder_section_hero_main_headline_color',
                        'instructions' => esc_html('Select the Main Headline Color'),
                        'type' => 'color_picker',
                        'default_value' => '#161616',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_sub_headline',
                        'label' => esc_html('Sub-Headline'),
                        'name' => 'option_builder_section_hero_sub_headline',
                        'instructions' => esc_html('Input the Sub-Headline'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '66.66666%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Sub-Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_sub_headline_color',
                        'label' => esc_html('Sub Headline Color'),
                        'name' => 'option_builder_section_hero_sub_headline_color',
                        'instructions' => esc_html('Select the Sub Headline Color'),
                        'type' => 'color_picker',
                        'default_value' => '#565656',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_secondary_headline',
                        'label' => esc_html('Secondary Headline'),
                        'name' => 'option_builder_section_hero_secondary_headline',
                        'instructions' => esc_html('Input the Secondary Headline'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '66.66666%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Secondary Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_secondary_headline_color',
                        'label' => esc_html('Secondary Headline Color'),
                        'name' => 'option_builder_section_hero_secondary_headline_color',
                        'instructions' => esc_html('Select the Secondary Headline Color'),
                        'type' => 'color_picker',
                        'default_value' => '#565656',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_propositions_color',
                        'label' => esc_html('Propositions Color'),
                        'name' => 'option_builder_section_hero_propositions_color',
                        'instructions' => esc_html('Select the Propositions Color'),
                        'type' => 'color_picker',
                        'default_value' => '#565656',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_propositions',
                        'label' => esc_html('Propositions'),
                        'name' => 'option_builder_section_hero_propositions',
                        'type' => 'repeater',
                        'label_placement' => 'top',
                        'instructions' => esc_html('Add Propositions'),
                        'min' => 1,
                        'max' => 0,
                        'layout' => 'row',
                        'button_label' => esc_html('Add new Proposition'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                        'sub_fields' => array(
                            array(
                                'key' => 'field_builder_section_hero_proposition_title',
                                'label' => esc_html('Proposition Title'),
                                'name' => 'option_builder_section_hero_proposition_title',
                                'instructions' => esc_html('Input the Proposition Title'),
                                'placeholder' => esc_html('Proposition Title'),
                                'type' => 'text',
                            ),
                        )
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_box_toggle',
                        'label' => esc_html('More Info Box'),
                        'name' => 'option_builder_section_hero_more_info_box_toggle',
                        'instructions' => esc_html('Enable/Disable More Info Box'),
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => esc_html('Enable'),
                        'ui_off_text' => esc_html('Disable'),
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_box_phone_toggle',
                        'label' => esc_html('Phone Field'),
                        'name' => 'option_builder_section_hero_more_info_box_phone_toggle',
                        'instructions' => esc_html('Enable/Disable More Info Phone Field'),
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'ui_on_text' => esc_html('Enable'),
                        'ui_off_text' => esc_html('Disable'),
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_more_info_box_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_headline',
                        'label' => esc_html('More Info Main Headline'),
                        'name' => 'option_builder_section_hero_more_info_headline',
                        'instructions' => esc_html('Input the More Main Info Headline'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '66.66666%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('More Info Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_more_info_box_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_headline_color',
                        'label' => esc_html('More Info Main Headline Color'),
                        'name' => 'option_builder_section_hero_more_info_headline_color',
                        'instructions' => esc_html('Select the More Info Main Headline Color'),
                        'type' => 'color_picker',
                        'default_value' => '#161616',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_more_info_box_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_secondary_headline',
                        'label' => esc_html('More Info Secondary Headline'),
                        'name' => 'option_builder_section_hero_more_info_secondary_headline',
                        'instructions' => esc_html('Input the More Info Secondary Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Secondary Headline'),
                        'wrapper' => array(
                            'width' => '66.66666%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_more_info_box_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_secondary_headline_color',
                        'label' => esc_html('More Info Secondary Headline Color'),
                        'name' => 'option_builder_section_hero_more_info_secondary_headline_color',
                        'instructions' => esc_html('Select the More Info Secondary Headline Color'),
                        'type' => 'color_picker',
                        'default_value' => '#565656',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_more_info_box_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_button_label',
                        'label' => esc_html('Button Label'),
                        'name' => 'option_builder_section_hero_more_info_button_label',
                        'instructions' => esc_html('Input the More Info Button Label'),
                        'type' => 'text',
                        'placeholder' => esc_html('Button Label'),
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_more_info_box_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_button_bg',
                        'label' => esc_html('Button Background Color'),
                        'name' => 'field_builder_section_hero_more_info_button_bg',
                        'instructions' => esc_html('Select the More Info Button Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#147aff',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_more_info_box_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_button_color',
                        'label' => esc_html('Button Color'),
                        'name' => 'field_builder_section_hero_more_info_button_color',
                        'instructions' => esc_html('Select the More Info Button Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_more_info_box_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),

                    /* HERO SECTION END */

                    /* **************************************** */

                    /* BUY NOW / GET MORE INFO SECTION START */

                    array(
                        'key' => 'field_builder_section_buy_now_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_buy_now_type_of_bg',
                        'instructions' => esc_html('Select the type of Section Background Style'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                        ),
                        'default_value' => 'image',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_buy_now_bg_color',
                        'label' => esc_html('Background Color'),
                        'name' => 'option_builder_section_buy_now_bg_color',
                        'instructions' => esc_html('Select the Section Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_buy_now_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'color',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_buy_now_bg_image',
                        'label' => esc_html('Background Image'),
                        'name' => 'option_builder_section_buy_now_bg_image',
                        'instructions' => esc_html('Select the Section Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_buy_now_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'image',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_buy_now_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_buy_now_main_headline',
                        'instructions' => esc_html('Input the Main Headline'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '66.66666%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Main Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_buy_now_main_headline_color',
                        'label' => esc_html('Main Headline Color'),
                        'name' => 'option_builder_section_buy_now_main_headline_color',
                        'instructions' => esc_html('Select the Main Headline Color'),
                        'type' => 'color_picker',
                        'default_value' => '#161616',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_buy_now_product_boxes_title_color',
                        'label' => esc_html('Product Boxes Title Color'),
                        'name' => 'option_builder_section_buy_now_product_boxes_title_color',
                        'instructions' => esc_html('Select the Product Boxes Title Color'),
                        'type' => 'color_picker',
                        'default_value' => '#161616',
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_buy_now_product_boxes_content_color',
                        'label' => esc_html('Product Boxes Content Color'),
                        'name' => 'option_builder_section_buy_now_product_boxes_content_color',
                        'instructions' => esc_html('Select the Product Boxes Content Color'),
                        'type' => 'color_picker',
                        'default_value' => '#565656',
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_buy_now_product_boxes',
                        'label' => esc_html('Product Boxes'),
                        'name' => 'option_builder_section_buy_now_product_boxes',
                        'type' => 'repeater',
                        'label_placement' => 'top',
                        'instructions' => esc_html('Add Box'),
                        'min' => 1,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => esc_html('Add new Product Box'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                        'sub_fields' => array(
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_title',
                                'label' => esc_html('Title'),
                                'name' => 'option_builder_section_buy_now_product_boxes_title',
                                'instructions' => esc_html('Input the Box Title'),
                                'placeholder' => esc_html('Box Title'),
                                'type' => 'text',
                                'wrapper' => array(
                                    'width' => '50%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_content',
                                'label' => esc_html('Content'),
                                'name' => 'option_builder_section_buy_now_product_boxes_content',
                                'instructions' => esc_html('Input the Box Content'),
                                'placeholder' => esc_html('Box Content'),
                                'type' => 'textarea',
                                'wrapper' => array(
                                    'width' => '50%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_button_label',
                                'label' => esc_html('Button Label'),
                                'name' => 'option_builder_section_buy_now_product_boxes_button_label',
                                'instructions' => esc_html('Input the Button Label'),
                                'placeholder' => esc_html('Button Label'),
                                'type' => 'text',
                                'wrapper' => array(
                                    'width' => '33.33333%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_button_url',
                                'label' => esc_html('Button URL'),
                                'name' => 'option_builder_section_buy_now_product_boxes_button_url',
                                'instructions' => esc_html('Input the Button URL'),
                                'placeholder' => esc_html('Button URL'),
                                'type' => 'url',
                                'wrapper' => array(
                                    'width' => '33.33333%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_button_bg',
                                'label' => esc_html('Button Background Color'),
                                'name' => 'option_builder_section_buy_now_product_boxes_button_bg',
                                'instructions' => esc_html('Select the Button Background Color'),
                                'type' => 'color_picker',
                                'default_value' => '#147aff',
                                'wrapper' => array(
                                    'width' => '33.33333%',
                                    'class' => '',
                                ),
                            ),
                        )
                    ),

                    /* BUY NOW / GET MORE INFO SECTION END */

                    /* **************************************** */

                    /* GALLERY SECTION START */

                    array(
                        'key' => 'field_builder_section_gallery_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_gallery_type_of_bg',
                        'instructions' => esc_html('Select the type of Section Background Style'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                        ),
                        'default_value' => 'image',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'gallery-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_gallery_bg_color',
                        'label' => esc_html('Background Color'),
                        'name' => 'option_builder_section_gallery_bg_color',
                        'instructions' => esc_html('Select the Section Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_gallery_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'color',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'gallery-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_gallery_bg_image',
                        'label' => esc_html('Background Image'),
                        'name' => 'option_builder_section_gallery_bg_image',
                        'instructions' => esc_html('Select the Section Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_gallery_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'image',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'gallery-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_gallery_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_gallery_main_headline',
                        'instructions' => esc_html('Input the Main Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Main Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'gallery-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_gallery_gallery',
                        'label' => esc_html('Gallery'),
                        'name' => 'option_builder_section_gallery_gallery',
                        'type' => 'gallery',
                        'instructions' => esc_html('Add Gallery Items'),
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                        'insert' => 'append',
                        'library' => 'all',
                        'min' => 1,
                        'max' => '',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'gallery-section',
                                ),
                            ),
                        ),
                    ),

                    /* GALLERY SECTION END */

                    /* **************************************** */

                    /* FAQS SECTION START */

                    array(
                        'key' => 'field_builder_section_faqs_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_faqs_type_of_bg',
                        'instructions' => esc_html('Select the type of Section Background Style'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                        ),
                        'default_value' => 'image',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'faqs-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_faqs_bg_color',
                        'label' => esc_html('Background Color'),
                        'name' => 'option_builder_section_faqs_bg_color',
                        'instructions' => esc_html('Select the Section Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_faqs_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'color',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'faqs-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_faqs_bg_image',
                        'label' => esc_html('Background Image'),
                        'name' => 'option_builder_section_faqs_bg_image',
                        'instructions' => esc_html('Select the Section Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_faqs_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'image',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'faqs-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_faqs_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_faqs_main_headline',
                        'instructions' => esc_html('Input the Main Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Main Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'faqs-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_faqs_secondary_headline',
                        'label' => esc_html('Secondary Headline'),
                        'name' => 'option_builder_section_faqs_secondary_headline',
                        'instructions' => esc_html('Input the Secondary Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Secondary Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'faqs-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_faqs_builder',
                        'label' => esc_html('FAQs Builder'),
                        'name' => 'option_builder_section_faqs_builder',
                        'type' => 'repeater',
                        'label_placement' => 'top',
                        'instructions' => esc_html('Create your own FAQ'),
                        'min' => 1,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => esc_html('Add new Category'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'faqs-section',
                                ),
                            ),
                        ),
                        'sub_fields' => array(
                            array(
                                'key' => 'field_builder_section_faqs_builder_category_name',
                                'label' => esc_html('Category Name'),
                                'name' => 'option_builder_section_faqs_builder_category_name',
                                'instructions' => esc_html('Input the Category Name'),
                                'placeholder' => esc_html('Category Name'),
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_builder_section_faqs_builder_category_questions_answers',
                                'label' => esc_html('Questions & Answers'),
                                'name' => 'option_builder_section_faqs_builder_category_questions_answers',
                                'type' => 'repeater',
                                'label_placement' => 'top',
                                'instructions' => esc_html('Create List of Questions & Answers'),
                                'min' => 1,
                                'max' => 0,
                                'layout' => 'table',
                                'button_label' => esc_html('Add new Question / Answer'),
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_builder_section_faqs_builder_category_question',
                                        'label' => esc_html('Question'),
                                        'name' => 'option_builder_section_faqs_builder_category_question',
                                        'instructions' => esc_html('Input the Question'),
                                        'placeholder' => esc_html('Question'),
                                        'type' => 'text',
                                    ),
                                    array(
                                        'key' => 'field_builder_section_faqs_builder_category_answer',
                                        'label' => esc_html('Answer'),
                                        'name' => 'option_builder_section_faqs_builder_category_answer',
                                        'instructions' => esc_html('Input the Answer'),
                                        'placeholder' => esc_html('Answer'),
                                        'type' => 'textarea',
                                    ),
                                )
                            ),
                        )
                    ),

                    /* FAQS SECTION END */

                    /* **************************************** */

                    /* RICH TEXT ROW SECTION START */

                    array(
                        'key' => 'field_builder_section_rich_text_row_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_rich_text_row_type_of_bg',
                        'instructions' => esc_html('Select the type of Section Background Style'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                        ),
                        'default_value' => 'image',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_rich_text_row_bg_color',
                        'label' => esc_html('Background Color'),
                        'name' => 'option_builder_section_rich_text_row_bg_color',
                        'instructions' => esc_html('Select the Section Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_rich_text_row_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'color',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_rich_text_row_bg_image',
                        'label' => esc_html('Background Image'),
                        'name' => 'option_builder_section_rich_text_row_bg_image',
                        'instructions' => esc_html('Select the Section Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.33333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_rich_text_row_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'image',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_rich_text_row_wsw',
                        'label' => esc_html('Rich Text Row Section Field'),
                        'name' => 'option_builder_section_rich_text_row_wsw',
                        'type' => 'wysiwyg',
                        'instructions' => esc_html('Text Content'),
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                        'default_value' => '',
                        'delay' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_rich_text_row_button_label',
                        'label' => esc_html('Button Label'),
                        'name' => 'option_builder_section_rich_text_row_button_label',
                        'instructions' => esc_html('Input the Button Label'),
                        'placeholder' => esc_html('Button Label'),
                        'type' => 'text',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_rich_text_row_button_url',
                        'label' => esc_html('Button URL'),
                        'name' => 'option_builder_section_rich_text_row_button_url',
                        'instructions' => esc_html('Input the Button URL'),
                        'placeholder' => esc_html('Button URL'),
                        'type' => 'url',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_rich_text_row_button_bg',
                        'label' => esc_html('Button Background Color'),
                        'name' => 'option_builder_section_rich_text_row_button_bg',
                        'instructions' => esc_html('Select the Button Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#161616',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                    ),

                    /* RICH TEXT ROW SECTION END */

                    /* **************************************** */

                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_footer_social_list',
                'label' => esc_html('Social List'),
                'name' => 'option_footer_social_list',
                'type' => 'repeater',
                'label_placement' => 'top',
                'instructions' => esc_html('Add Social Media Items'),
                'min' => 1,
                'max' => 0,
                'layout' => 'block',
                'button_label' => esc_html('Add new Social'),
                'parent' => 'group_footer_post_settings',
                'conditional_logic' => array(),
                'sub_fields' => array(
                    array(
                        'key' => 'field_builder_section_footer_social_list_icon',
                        'label' => esc_html('Social Icon'),
                        'name' => 'option_builder_section_footer_social_list_icon',
                        'type' => 'font-awesome',
                        'instructions' => '',
                        'required' => 0,
                        'icon_sets' => array(
                            0 => 'fas',
                            1 => 'far',
                            2 => 'fab',
                        ),
                        'custom_icon_set' => '',
                        'default_label' => '',
                        'default_value' => '',
                        'save_format' => 'class',
                        'allow_null' => 0,
                        'show_preview' => 1,
                        'enqueue_fa' => 0,
                        'fa_live_preview' => '',
                        'choices' => array(),
                    ),
                    array(
                        'key' => 'field_builder_section_footer_social_list_url',
                        'label' => esc_html('Social URL'),
                        'name' => 'option_builder_section_footer_social_list_url',
                        'instructions' => esc_html('Input the Social Icon URL'),
                        'placeholder' => esc_html('Social URL'),
                        'type' => 'url',
                    ),
                ),
            ));

        endif;

    }

}

ACF_Builder::instance();
