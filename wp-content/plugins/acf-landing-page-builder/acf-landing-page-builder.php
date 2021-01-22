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

        add_action('init', [$this, 'acf_init']);

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

                    $section_type = self::get_sub_field('field_builder_section_type');

                    if ($section_type === 'hero-section'):

                        $field_builder_section_hero_type_of_bg = self::get_sub_field('field_builder_section_hero_type_of_bg');

                        $field_builder_section_hero_bg_color = self::get_sub_field('field_builder_section_hero_bg_color');

                        $field_builder_section_hero_bg_image = self::get_sub_field('field_builder_section_hero_bg_image');

                        $field_builder_section_hero_main_headline = self::get_sub_field('field_builder_section_hero_main_headline');

                        $field_builder_section_hero_main_headline_color = self::get_sub_field('field_builder_section_hero_main_headline_color');

                        $field_builder_section_hero_sub_headline = self::get_sub_field('field_builder_section_hero_sub_headline');

                        $field_builder_section_hero_sub_headline_color = self::get_sub_field('field_builder_section_hero_sub_headline_color');

                        $field_builder_section_hero_secondary_headline = self::get_sub_field('field_builder_section_hero_secondary_headline');

                        $field_builder_section_hero_secondary_headline_color = self::get_sub_field('field_builder_section_hero_secondary_headline_color');

                        $field_builder_section_hero_more_info_box_toggle = self::get_sub_field('field_builder_section_hero_more_info_box_toggle');

                        $field_builder_section_hero_more_info_headline = self::get_sub_field('field_builder_section_hero_more_info_headline');

                        $field_builder_section_hero_more_info_headline_color = self::get_sub_field('field_builder_section_hero_more_info_headline_color');

                        $field_builder_section_hero_propositions_color = self::get_sub_field('field_builder_section_hero_propositions_color');

                        $field_builder_section_hero_more_info_secondary_headline = self::get_sub_field('field_builder_section_hero_more_info_secondary_headline');

                        $field_builder_section_hero_more_info_secondary_headline_color = self::get_sub_field('field_builder_section_hero_more_info_secondary_headline_color');

                        $field_builder_section_hero_more_info_box_phone_toggle = self::get_sub_field('field_builder_section_hero_more_info_box_phone_toggle');

                        $field_builder_section_hero_more_info_button_label = self::get_sub_field('field_builder_section_hero_more_info_button_label');

                        $field_builder_section_hero_more_info_button_bg = self::get_sub_field('field_builder_section_hero_more_info_button_bg');

                        $field_builder_section_hero_more_info_button_color = self::get_sub_field('field_builder_section_hero_more_info_button_color');

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

                                        <p class="secondary-headline"
                                           style="color: <?php echo esc_attr($field_builder_section_hero_secondary_headline_color); ?>;">

                                            <?php echo esc_html($field_builder_section_hero_secondary_headline); ?>

                                        </p>

                                    <?php endif; ?>

                                </div>

                                <?php if ($field_builder_section_hero_more_info_box_toggle): ?>

                                    <div class="right-side">

                                        <div class="info-box-wrapper">

                                            <h2 class="info-box-title"
                                                style="color: <?php echo esc_attr($field_builder_section_hero_more_info_headline_color); ?>;">

                                                <?php echo esc_html($field_builder_section_hero_more_info_headline); ?>

                                            </h2>

                                            <p class="info-box-secondary-headline"
                                               style="color: <?php echo esc_attr($field_builder_section_hero_more_info_secondary_headline_color); ?>;">

                                                <?php echo esc_html($field_builder_section_hero_more_info_secondary_headline); ?>

                                            </p>

                                            <div class="form-wrapper">

                                                <form class="hero-contact-form">

                                                    <input type="name" class="input-field" name="name-field"
                                                           placeholder="<?php echo esc_html('Name'); ?>">

                                                    <input type="email" class="input-field" name="email-field"
                                                           placeholder="<?php echo esc_html('Email'); ?>">

                                                    <?php if (!$field_builder_section_hero_more_info_box_phone_toggle): ?>

                                                        <input type="text" class="input-field" name="phone-field"
                                                               placeholder="<?php echo esc_html('Phone'); ?>">

                                                    <?php endif; ?>

                                                    <button class="submit-button"
                                                            style="background-color: <?php echo esc_attr($field_builder_section_hero_more_info_button_bg); ?>; color: <?php echo esc_attr($field_builder_section_hero_more_info_button_color) ?>;">

                                                        <?php echo esc_html($field_builder_section_hero_more_info_button_label); ?>

                                                    </button>

                                                </form>

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

                        $field_builder_section_buy_now_main_headline_color = self::get_sub_field('field_builder_section_buy_now_main_headline_color');

                        $field_builder_section_buy_now_product_boxes_title_color = self::get_sub_field('field_builder_section_buy_now_product_boxes_title_color');

                        $field_builder_section_buy_now_product_boxes_content_color = self::get_sub_field('field_builder_section_buy_now_product_boxes_content_color');

                        ?>

                        <section class="neo-section buy-now-get-more-info-section"
                                 style="<?php if ($field_builder_section_buy_now_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_buy_now_bg_color); ?><?php elseif ($field_builder_section_buy_now_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_buy_now_bg_image,'full')); ?>);<?php endif; ?>">

                            <div class="neo-container inner-wrapper">

                                <?php if (!empty($field_builder_section_buy_now_main_headline)): ?>

                                    <h2 class="section-title"
                                        style="color:<?php echo esc_attr($field_builder_section_buy_now_main_headline_color); ?>">

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

                                            ?>

                                            <div class="box">

                                                <div class="box-inner">

                                                    <?php if (!empty($field_builder_section_buy_now_product_boxes_title)): ?>

                                                        <h4 class="box-title"
                                                            style="color:<?php echo esc_attr($field_builder_section_buy_now_product_boxes_title_color); ?>">

                                                            <?php echo esc_html($field_builder_section_buy_now_product_boxes_title); ?>

                                                        </h4>

                                                    <?php endif; ?>

                                                    <?php if (!empty($field_builder_section_buy_now_product_boxes_content)): ?>

                                                        <p class="box-content"
                                                           style="color: <?php echo esc_attr($field_builder_section_buy_now_product_boxes_content_color); ?>">

                                                            <?php echo esc_html($field_builder_section_buy_now_product_boxes_content); ?>

                                                        </p>

                                                    <?php endif; ?>

                                                    <?php if (!empty($field_builder_section_buy_now_product_boxes_button_label)): ?>

                                                        <div class="button-wrapper">

                                                            <a class="neo-button"
                                                               href="<?php echo esc_url($field_builder_section_buy_now_product_boxes_button_url); ?>">

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

                        $field_builder_section_gallery_main_headline = self::get_sub_field('field_builder_section_gallery_main_headline');

                        $field_builder_section_gallery_type_of_bg = self::get_sub_field('field_builder_section_gallery_type_of_bg');

                        $field_builder_section_gallery_bg_color = self::get_sub_field('field_builder_section_gallery_bg_color');

                        $field_builder_section_gallery_bg_image = self::get_sub_field('field_builder_section_gallery_bg_image');

                        $field_builder_section_gallery_gallery = self::get_sub_field('field_builder_section_gallery_gallery');

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

                        $field_builder_section_faqs_type_of_bg = self::get_sub_field('field_builder_section_faqs_type_of_bg');

                        $field_builder_section_faqs_bg_color = self::get_sub_field('field_builder_section_faqs_bg_color');

                        $field_builder_section_faqs_bg_image = self::get_sub_field('field_builder_section_faqs_bg_image');

                        $field_builder_section_faqs_main_headline = self::get_sub_field('field_builder_section_faqs_main_headline');

                        $field_builder_section_faqs_secondary_headline = self::get_sub_field('field_builder_section_faqs_secondary_headline');

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

                                            $field_builder_section_faqs_builder_category_name = self::get_sub_field('field_builder_section_faqs_builder_category_name');

                                            ?>

                                            <div class="faq-category">

                                                <h5 class="category-name">

                                                    <?php echo esc_html($field_builder_section_faqs_builder_category_name); ?>

                                                </h5>

                                                <?php if (have_rows('field_builder_section_faqs_builder_category_questions_answers')): ?>

                                                    <div class="questions-wrapper">

                                                        <?php while (have_rows('field_builder_section_faqs_builder_category_questions_answers')) : the_row(); ?>

                                                            <?php

                                                            $field_builder_section_faqs_builder_category_question = self::get_sub_field('field_builder_section_faqs_builder_category_question');

                                                            $field_builder_section_faqs_builder_category_answer = self::get_sub_field('field_builder_section_faqs_builder_category_answer');

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

                        $field_builder_section_rich_text_row_type_of_bg = self::get_sub_field('field_builder_section_rich_text_row_type_of_bg');

                        $field_builder_section_rich_text_row_bg_color = self::get_sub_field('field_builder_section_rich_text_row_bg_color');

                        $field_builder_section_rich_text_row_bg_image = self::get_sub_field('field_builder_section_rich_text_row_bg_image');

                        $field_builder_section_rich_text_row_wsw = self::get_sub_field('field_builder_section_rich_text_row_wsw');

                        $field_builder_section_rich_text_row_button_label = self::get_sub_field('field_builder_section_rich_text_row_button_label');

                        $field_builder_section_rich_text_row_button_url = self::get_sub_field('field_builder_section_rich_text_row_button_url');

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
                                           style="background-color: <?php echo esc_attr($field_builder_section_rich_text_row_button_bg); ?>; color:<?php echo esc_attr($field_builder_section_rich_text_row_button_color); ?>">

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

    public function theme_options()
    {

        $field_builder_default_headings_color = self::get_field('field_builder_default_headings_color');

        $field_builder_default_text_color = self::get_field('field_builder_default_text_color');

        $field_builder_default_button_color = self::get_field('field_builder_default_button_color');

        $field_builder_default_button_bg_color = self::get_field('field_builder_default_button_bg_color');

        $field_content_font = self::get_field('field_content_font');

        $field_text_font_size = self::get_field('field_text_font_size');

        $field_text_font_size_lg = self::get_field('field_text_font_size_lg');

        $field_text_font_size_sm = self::get_field('field_text_font_size_sm');

        $field_text_font_weight = self::get_field('field_text_font_weight');

        $field_text_font_line_height = self::get_field('field_text_font_line_height');

        $field_heading_font = self::get_field('field_heading_font');

        $field_heading_font_weight = self::get_field('field_heading_font_weight');

        $field_heading_font_line_height = self::get_field('field_heading_font_line_height');

        $field_h1_size = self::get_field('field_h1_size');

        $field_h1_size_lg = self::get_field('field_h1_size_lg');

        $field_h1_size_sm = self::get_field('field_h1_size_sm');

        $field_h2_size = self::get_field('field_h2_size');

        $field_h2_size_lg = self::get_field('field_h2_size_lg');

        $field_h2_size_sm = self::get_field('field_h2_size_sm');

        $field_h3_size = self::get_field('field_h3_size');

        $field_h3_size_lg = self::get_field('field_h3_size_lg');

        $field_h3_size_sm = self::get_field('field_h3_size_sm');

        $field_h4_size = self::get_field('field_h4_size');

        $field_h4_size_lg = self::get_field('field_h4_size_lg');

        $field_h4_size_sm = self::get_field('field_h4_size_sm');

        $field_h5_size = self::get_field('field_h5_size');

        $field_h5_size_lg = self::get_field('field_h5_size_lg');

        $field_h5_size_sm = self::get_field('field_h5_size_sm');

        $field_h6_size = self::get_field('field_h6_size');

        $field_h6_size_lg = self::get_field('field_h6_size_lg');

        $field_h6_size_sm = self::get_field('field_h6_size_sm');

        $variables = "
		:root {
			--field-builder-default-headings-color: {$field_builder_default_headings_color};
			--field-builder-default-text-color: {$field_builder_default_text_color};
			--field-builder-default-button-color: {$field_builder_default_button_color};
			--field-builder-default-button-bg-color: {$field_builder_default_button_bg_color};
			--field-content-font: {$field_content_font};
			--field-text-font-size: {$field_text_font_size};
			--field-text-font-size-lg: {$field_text_font_size_lg};
			--field-text-font-size-sm: {$field_text_font_size_sm};
			--field-text-font-weight: {$field_text_font_weight};
			--field-text-font-line-height: {$field_text_font_line_height};
			--field-heading-font: {$field_heading_font};
			--field-heading-font-weight: {$field_heading_font_weight};
			--field-heading-font-line-height: {$field_heading_font_line_height};
			--field-h1-size: {$field_h1_size};
			--field-h1-size-lg: {$field_h1_size_lg};
			--field-h1-size-sm: {$field_h1_size_sm};
			--field-h2-size: {$field_h2_size};
			--field-h2-size-lg: {$field_h2_size_lg};
			--field-h2-size-sm: {$field_h2_size_sm};
			--field-h3-size: {$field_h3_size};
			--field-h3-size-lg: {$field_h3_size_lg};
			--field-h3-size-sm: {$field_h3_size_sm};
			--field-h4-size: {$field_h4_size};
			--field-h4-size-lg: {$field_h4_size_lg};
			--field-h4-size-sm: {$field_h4_size_sm};
			--field-h5-size: {$field_h5_size};
			--field-h5-size-lg: {$field_h5_size_lg};
			--field-h5-size-sm: {$field_h5_size_sm};
			--field-h6-size: {$field_h6_size};
			--field-h6-size-lg: {$field_h6_size_lg};
			--field-h6-size-sm: {$field_h6_size_sm};
		}";

        wp_add_inline_style('acf-repeater-builder-frontend-css', $variables);

        self::load_google_font(array($field_heading_font, $field_content_font));

    }

    public function load_google_font($fontsArr = array('Poppins', 'Roboto'), $fontsSubset = array('latin', 'latin-ext'))
    {

        if ($fontsArr && $fontsSubset):

            $fontsSubset = implode(',', $fontsSubset);

            $fonts_url = '';

            $fonts = array();

            foreach ($fontsArr as $font):

                $fonts[] = '' . $font . ':100,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i';

            endforeach;

            $fonts = array_unique($fonts);

            if ($fonts) :

                $fonts_url = add_query_arg(array(
                    'family' => urlencode(implode('|', $fonts)),
                    'subset' => urlencode($fontsSubset),
                ),
                    'https://fonts.googleapis.com/css');

            endif;

            wp_enqueue_style('neo-fonts', $fonts_url, array());

        endif;

    }

    public function init()
    {

        add_action('admin_head', [$this, 'theme_options']);

        add_action('wp_enqueue_scripts', [$this, 'theme_options']);

    }

    public function acf_init(){

        if (function_exists('acf_add_local_field_group')):

            acf_add_local_field_group(array(
                'key' => 'group_repeater_builder_settings',
                'title' => __('Landing Page Builder'),
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

        endif;

        if (function_exists('acf_add_local_field')):

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_toggle',
                'label' => esc_html('Enable/Disable Landing Page Builder'),
                'name' => 'option_repeater_builder_toggle',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => esc_html('Enable'),
                'ui_off_text' => esc_html('Disable'),
                'parent' => 'group_repeater_builder_settings',
                'wrapper' => array(
                    'width' => '50%',
                    'class' => '',
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_custom_settings_toggle',
                'label' => esc_html('Enable/Disable Custom Settings'),
                'name' => 'option_repeater_builder_custom_settings_toggle',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => esc_html('Enable'),
                'ui_off_text' => esc_html('Disable'),
                'parent' => 'group_repeater_builder_settings',
                'wrapper' => array(
                    'width' => '50%',
                    'class' => '',
                ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_builder_default_headings_color',
                'label' => esc_html('Default Headings Color'),
                'name' => 'option_builder_default_headings_color',
                'instructions' => esc_html('Select the Default Headings Color'),
                'type' => 'color_picker',
                'default_value' => '#161616',
                'wrapper' => array(
                    'width' => '25%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_builder_default_text_color',
                'label' => esc_html('Default Text Color'),
                'name' => 'option_builder_default_text_color',
                'instructions' => esc_html('Select the Default Text Color'),
                'type' => 'color_picker',
                'default_value' => '#565656',
                'wrapper' => array(
                    'width' => '25%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_builder_default_button_color',
                'label' => esc_html('Default Button Color'),
                'name' => 'option_builder_default_button_color',
                'instructions' => esc_html('Select the Default Button Color'),
                'type' => 'color_picker',
                'default_value' => '#ffffff',
                'wrapper' => array(
                    'width' => '25%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_builder_default_button_bg_color',
                'label' => esc_html('Default Button Background Color'),
                'name' => 'option_builder_default_button_bg_color',
                'instructions' => esc_html('Select the Default Button Background Color'),
                'type' => 'color_picker',
                'default_value' => '#147aff',
                'wrapper' => array(
                    'width' => '25%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_text_font_size',
                'label' => esc_html('Text Font Size'),
                'name' => 'option_text_font_size',
                'type' => 'number',
                'placeholder' => esc_html('Font Size'),
                'instructions' => esc_html('Input the text font size'),
                'append' => 'px',
                'min' => '10',
                'max' => '24',
                'step' => '1',
                'default_value' => '18',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_text_font_size_lg',
                'label' => esc_html('Text Font Size (LG)'),
                'name' => 'option_text_font_size_lg',
                'type' => 'number',
                'placeholder' => esc_html('Font Size (LG)'),
                'instructions' => esc_html('Input the text font size (LG)'),
                'append' => 'px',
                'min' => '10',
                'max' => '24',
                'step' => '1',
                'default_value' => '16',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_text_font_size_sm',
                'label' => esc_html('Text Font Size (SM)'),
                'name' => 'option_text_font_size_sm',
                'type' => 'number',
                'placeholder' => esc_html('Font Size (SM)'),
                'instructions' => esc_html('Input the text font size (SM)'),
                'append' => 'px',
                'min' => '10',
                'max' => '24',
                'step' => '1',
                'default_value' => '14',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_content_font',
                'label' => esc_html('Content Font'),
                'name' => 'option_content_font',
                'instructions' => esc_html('Select the Content Font'),
                'type' => 'select',
                'default' => 'Roboto',
                'required' => 0,
                'choices' => array('ABeeZee', 'Abel', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akronim', 'Aladin', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya SC', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amarante', 'Amaranth', 'Amatic SC', 'Amethysta', 'Anaheim', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo Black', 'Archivo Narrow', 'Arial Black', 'Arial Narrow', 'Arimo', 'Arizonia', 'Armata', 'Artifika', 'Arvo', 'Asap', 'Asset', 'Astloch', 'Asul', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Balthazar', 'Bangers', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Bell MT', 'Bell MT Alt', 'Belleza', 'BenchNine', 'Bentham', 'Berkshire Swash', 'Bevan', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'Bitter', 'Black Ops One', 'Bodoni', 'Bokor', 'Bonbon', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buenard', 'Butcherman', 'Butcherman Caps', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Calibri', 'Calligraffitti', 'Cambo', 'Cambria', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Caudex', 'Cedarville Cursive', 'Ceviche One', 'Changa One', 'Chango', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chivo', 'Cinzel', 'Cinzel Decorative', 'Clara', 'Clicker Script', 'Coda', 'Codystar', 'Combo', 'Comfortaa', 'Coming Soon', 'Concert One', 'Condiment', 'Consolas', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Corsiva', 'Courgette', 'Courier New', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Creepster Caps', 'Crete Round', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cutive', 'Cutive Mono', 'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Days One', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Dhyana', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'Dr Sugiyama', 'Droid Arabic Kufi', 'Droid Arabic Naskh', 'Droid Sans', 'Droid Sans Mono', 'Droid Sans TV', 'Droid Serif', 'Duru Sans', 'Dynalight', 'EB Garamond', 'Eagle Lake', 'Eater', 'Eater Caps', 'Economica', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Engagement', 'Englebert', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Expletus Sans', 'Fanwood Text', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Finger Paint', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'GFS Didot', 'GFS Neohellenic', 'Gabriela', 'Gafata', 'Galdeano', 'Galindo', 'Garamond', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One', 'Gilda Display', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas', 'Goudy Bookletter 1911', 'Graduate', 'Grand Hotel', 'Gravitas One', 'Great Vibes', 'Griffy', 'Gruppo', 'Gudea', 'Habibi', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Headland One', 'Helvetica Neue', 'Henny Penny', 'Herr Von Muellerhoff', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Irish Grover', 'Irish Growler', 'Istok Web', 'Italiana', 'Italianno', 'Jacques Francois', 'Jacques Francois Shadow', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Josefin Sans', 'Josefin Sans Std Light', 'Josefin Slab', 'Joti One', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kameron', 'Karla', 'Kaushan Script', 'Kavoon', 'Keania One', 'Kelly Slab', 'Kenia', 'Khmer', 'Kite One', 'Knewave', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'La Belle Aurore', 'Lancelot', 'Lateef', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Lemon One', 'Libre Baskerville', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Lobster', 'Lobster Two', 'Lohit Bengali', 'Lohit Devanagari', 'Lohit Tamil', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Magra', 'Maiden Orange', 'Mako', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Marko One', 'Marmelad', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Miniver', 'Miss Fajardose', 'Miss Saint Delafield', 'Modern Antiqua', 'Molengo', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedford', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Muli', 'Mystery Quest', 'Neucha', 'Neuton', 'New Rocker', 'News Cycle', 'Niconne', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Nosifer Caps', 'Nothing You Could Do', 'Noticia Text', 'Noto Sans', 'Noto Sans UI', 'Noto Serif', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'OFL Sorts Mill Goudy TT', 'Odor Mean Chey', 'Offside', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orienta', 'Original Surfer', 'Oswald', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Ovo', 'Oxygen', 'Oxygen Mono', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Patua One', 'Paytone One', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Poppins', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Port Lligat Sans', 'Port Lligat Slab', 'Prata', 'Preahvihear', 'Press Start 2P', 'Princess Sofia', 'Prociono', 'Prosto One', 'Proxima Nova', 'Proxima Nova Tabular Figures', 'Puritan', 'Purple Purse', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Raleway', 'Raleway Dots', 'Rambla', 'Rammetto One', 'Ranchers', 'Rancho', 'Rationale', 'Redressed', 'Reenie Beanie', 'Revalia', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Slab', 'Rochester', 'Rock Salt', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sail', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita One', 'Sarina', 'Satisfy', 'Scada', 'Scheherazade', 'Schoolbell', 'Seaweed Script', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shojumaru', 'Short Stack', 'Siamreap', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slackey', 'Smokum', 'Smythe', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Special Elite', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Stalemate', 'Stalin One', 'Stalinist One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'Stoke', 'Strait', 'Sue Ellen Francisco', 'Sunshiney', 'Supermercado One', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tahoma', 'Tangerine', 'Taprom', 'Tauri', 'Telex', 'Tenor Sans', 'Terminal Dosis', 'Terminal Dosis Light', 'Text Me One', 'Thabit', 'The Girl Next Door', 'Tienne', 'Tinos', 'Titan One', 'Titillium Web', 'Trade Winds', 'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Vampiro One', 'Varela', 'Varela Round', 'Vast Shadow', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'Wire One', 'Yanone Kaffeesatz', 'Yellowtail', 'Yeseva One', 'Yesteryear', 'Zeyada', 'jsMath cmbx10', 'jsMath cmex10', 'jsMath cmmi10', 'jsMath cmr10', 'jsMath cmsy10', 'jsMath cmti10'),
                'allow_null' => 0,
                'other_choice' => 0,
                'layout' => 'vertical',
                'return_format' => 'value',
                'ui' => 1,
                'ajax' => 1,
                'save_other_choice' => 0,
                'parent' => 'group_repeater_builder_settings',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_text_font_weight',
                'label' => esc_html('Text Font Weight'),
                'name' => 'option_text_font_weight',
                'type' => 'number',
                'placeholder' => esc_html('Font weight'),
                'instructions' => esc_html('Input the text font weight'),
                'min' => '300',
                'max' => '800',
                'step' => '100',
                'default_value' => '400',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_text_font_line_height',
                'label' => esc_html('Text Line Height'),
                'name' => 'option_text_line_height',
                'type' => 'number',
                'placeholder' => esc_html('Text Line Height'),
                'instructions' => esc_html('Input the Text Line Height'),
                'min' => '1',
                'max' => '3',
                'step' => '0.1',
                'default_value' => '1.7',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_heading_font',
                'label' => esc_html('Heading Font'),
                'name' => 'option_heading_font',
                'instructions' => esc_html('Select the Heading Font'),
                'type' => 'select',
                'default' => 'Poppins',
                'required' => 0,

                'choices' => array('ABeeZee', 'Abel', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akronim', 'Aladin', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya SC', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amarante', 'Amaranth', 'Amatic SC', 'Amethysta', 'Anaheim', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo Black', 'Archivo Narrow', 'Arial Black', 'Arial Narrow', 'Arimo', 'Arizonia', 'Armata', 'Artifika', 'Arvo', 'Asap', 'Asset', 'Astloch', 'Asul', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Balthazar', 'Bangers', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Bell MT', 'Bell MT Alt', 'Belleza', 'BenchNine', 'Bentham', 'Berkshire Swash', 'Bevan', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'Bitter', 'Black Ops One', 'Bodoni', 'Bokor', 'Bonbon', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buenard', 'Butcherman', 'Butcherman Caps', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Calibri', 'Calligraffitti', 'Cambo', 'Cambria', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Caudex', 'Cedarville Cursive', 'Ceviche One', 'Changa One', 'Chango', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chivo', 'Cinzel', 'Cinzel Decorative', 'Clara', 'Clicker Script', 'Coda', 'Codystar', 'Combo', 'Comfortaa', 'Coming Soon', 'Concert One', 'Condiment', 'Consolas', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Corsiva', 'Courgette', 'Courier New', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Creepster Caps', 'Crete Round', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cutive', 'Cutive Mono', 'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Days One', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Dhyana', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'Dr Sugiyama', 'Droid Arabic Kufi', 'Droid Arabic Naskh', 'Droid Sans', 'Droid Sans Mono', 'Droid Sans TV', 'Droid Serif', 'Duru Sans', 'Dynalight', 'EB Garamond', 'Eagle Lake', 'Eater', 'Eater Caps', 'Economica', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Engagement', 'Englebert', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Expletus Sans', 'Fanwood Text', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Finger Paint', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'GFS Didot', 'GFS Neohellenic', 'Gabriela', 'Gafata', 'Galdeano', 'Galindo', 'Garamond', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One', 'Gilda Display', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas', 'Goudy Bookletter 1911', 'Graduate', 'Grand Hotel', 'Gravitas One', 'Great Vibes', 'Griffy', 'Gruppo', 'Gudea', 'Habibi', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Headland One', 'Helvetica Neue', 'Henny Penny', 'Herr Von Muellerhoff', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Irish Grover', 'Irish Growler', 'Istok Web', 'Italiana', 'Italianno', 'Jacques Francois', 'Jacques Francois Shadow', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Josefin Sans', 'Josefin Sans Std Light', 'Josefin Slab', 'Joti One', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kameron', 'Karla', 'Kaushan Script', 'Kavoon', 'Keania One', 'Kelly Slab', 'Kenia', 'Khmer', 'Kite One', 'Knewave', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'La Belle Aurore', 'Lancelot', 'Lateef', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Lemon One', 'Libre Baskerville', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Lobster', 'Lobster Two', 'Lohit Bengali', 'Lohit Devanagari', 'Lohit Tamil', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Magra', 'Maiden Orange', 'Mako', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Marko One', 'Marmelad', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Miniver', 'Miss Fajardose', 'Miss Saint Delafield', 'Modern Antiqua', 'Molengo', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedford', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Muli', 'Mystery Quest', 'Neucha', 'Neuton', 'New Rocker', 'News Cycle', 'Niconne', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Nosifer Caps', 'Nothing You Could Do', 'Noticia Text', 'Noto Sans', 'Noto Sans UI', 'Noto Serif', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'OFL Sorts Mill Goudy TT', 'Odor Mean Chey', 'Offside', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orienta', 'Original Surfer', 'Oswald', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Ovo', 'Oxygen', 'Oxygen Mono', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Patua One', 'Paytone One', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Poppins', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Port Lligat Sans', 'Port Lligat Slab', 'Prata', 'Preahvihear', 'Press Start 2P', 'Princess Sofia', 'Prociono', 'Prosto One', 'Proxima Nova', 'Proxima Nova Tabular Figures', 'Puritan', 'Purple Purse', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Raleway', 'Raleway Dots', 'Rambla', 'Rammetto One', 'Ranchers', 'Rancho', 'Rationale', 'Redressed', 'Reenie Beanie', 'Revalia', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Slab', 'Rochester', 'Rock Salt', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sail', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita One', 'Sarina', 'Satisfy', 'Scada', 'Scheherazade', 'Schoolbell', 'Seaweed Script', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shojumaru', 'Short Stack', 'Siamreap', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slackey', 'Smokum', 'Smythe', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Special Elite', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Stalemate', 'Stalin One', 'Stalinist One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'Stoke', 'Strait', 'Sue Ellen Francisco', 'Sunshiney', 'Supermercado One', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tahoma', 'Tangerine', 'Taprom', 'Tauri', 'Telex', 'Tenor Sans', 'Terminal Dosis', 'Terminal Dosis Light', 'Text Me One', 'Thabit', 'The Girl Next Door', 'Tienne', 'Tinos', 'Titan One', 'Titillium Web', 'Trade Winds', 'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Vampiro One', 'Varela', 'Varela Round', 'Vast Shadow', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'Wire One', 'Yanone Kaffeesatz', 'Yellowtail', 'Yeseva One', 'Yesteryear', 'Zeyada', 'jsMath cmbx10', 'jsMath cmex10', 'jsMath cmmi10', 'jsMath cmr10', 'jsMath cmsy10', 'jsMath cmti10'),
                'allow_null' => 0,
                'other_choice' => 0,
                'layout' => 'vertical',
                'return_format' => 'value',
                'ui' => 1,
                'ajax' => 1,
                'save_other_choice' => 0,
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_heading_font_weight',
                'label' => esc_html('Heading Font Weight'),
                'name' => 'option_heading_font_weight',
                'type' => 'number',
                'placeholder' => esc_html('Heading Font Weight'),
                'instructions' => esc_html('Input the Heading Font Weight'),
                'min' => '300',
                'max' => '800',
                'step' => '100',
                'default_value' => '600',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_heading_font_line_height',
                'label' => esc_html('Heading Line Height'),
                'name' => 'option_heading_line_height',
                'type' => 'number',
                'placeholder' => esc_html('Heading Line Height'),
                'instructions' => esc_html('Input the Heading Line Height'),
                'min' => '1',
                'max' => '3',
                'step' => '0.1',
                'default_value' => '1.3',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h1_size',
                'label' => esc_html('H1 Font Size'),
                'name' => 'option_h1_size',
                'type' => 'number',
                'placeholder' => esc_html('H1'),
                'instructions' => esc_html('Input the size of H1'),
                'prepend' => 'H1',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '2.653',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h1_size_lg',
                'label' => esc_html('H1 Font Size (LG)'),
                'name' => 'option_h1_size_lg',
                'type' => 'number',
                'placeholder' => esc_html('H1 (LG)'),
                'instructions' => esc_html('Input the size of H1 (LG)'),
                'prepend' => 'H1',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '2.653',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h1_size_sm',
                'label' => esc_html('H1 Font Size (SM)'),
                'name' => 'option_h1_size_sm',
                'type' => 'number',
                'placeholder' => esc_html('H1 (SM)'),
                'instructions' => esc_html('Input the size of H1 (SM)'),
                'prepend' => 'H1',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '2.312',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h2_size',
                'label' => esc_html('H2 Font Size'),
                'name' => 'option_h2_size',
                'type' => 'number',
                'placeholder' => esc_html('H2'),
                'instructions' => esc_html('Input the size of H2'),
                'prepend' => 'H2',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '2.192',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h2_size_lg',
                'label' => esc_html('H2 Font Size (LG)'),
                'name' => 'option_h2_size_lg',
                'type' => 'number',
                'placeholder' => esc_html('H2 (LG)'),
                'instructions' => esc_html('Input the size of H2 (LG)'),
                'prepend' => 'H2',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '2.192',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h2_size_sm',
                'label' => esc_html('H2 Font Size (SM)'),
                'name' => 'option_h2_size_sm',
                'type' => 'number',
                'placeholder' => esc_html('H2 (SM)'),
                'instructions' => esc_html('Input the size of H2 (SM)'),
                'prepend' => 'H2',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.993',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h3_size',
                'label' => esc_html('H3 Font Size'),
                'name' => 'option_h3_size',
                'type' => 'number',
                'placeholder' => esc_html('H3'),
                'instructions' => esc_html('Input the size of H3'),
                'prepend' => 'H3',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '2.192',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h3_size_lg',
                'label' => esc_html('H3 Font Size (LG)'),
                'name' => 'option_h3_size_lg',
                'type' => 'number',
                'placeholder' => esc_html('H3 (LG)'),
                'instructions' => esc_html('Input the size of H3 (LG)'),
                'prepend' => 'H3',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.812',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h3_size_sm',
                'label' => esc_html('H3 Font Size (SM)'),
                'name' => 'option_h3_size_sm',
                'type' => 'number',
                'placeholder' => esc_html('H3 (SM)'),
                'instructions' => esc_html('Input the size of H3 (SM)'),
                'prepend' => 'H3',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.647',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h4_size',
                'label' => esc_html('H4 Font Size'),
                'name' => 'option_h4_size',
                'type' => 'number',
                'placeholder' => esc_html('H4'),
                'instructions' => esc_html('Input the size of H4'),
                'prepend' => 'H4',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.812',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h4_size_lg',
                'label' => esc_html('H4 Font Size (LG)'),
                'name' => 'option_h4_size_lg',
                'type' => 'number',
                'placeholder' => esc_html('H4 (LG)'),
                'instructions' => esc_html('Input the size of H4 (LG)'),
                'prepend' => 'H4',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.497',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h4_size_sm',
                'label' => esc_html('H4 Font Size (SM)'),
                'name' => 'option_h4_size_sm',
                'type' => 'number',
                'placeholder' => esc_html('H4 (SM)'),
                'instructions' => esc_html('Input the size of H4 (SM)'),
                'prepend' => 'H4',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.45',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h5_size',
                'label' => esc_html('H5 Font Size'),
                'name' => 'option_h5_size',
                'type' => 'number',
                'placeholder' => esc_html('H5'),
                'instructions' => esc_html('Input the size of H5'),
                'prepend' => 'H5',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.333',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h5_size_lg',
                'label' => esc_html('H5 Font Size (LG)'),
                'name' => 'option_h5_size_lg',
                'type' => 'number',
                'placeholder' => esc_html('H5 (LG)'),
                'instructions' => esc_html('Input the size of H5 (LG)'),
                'prepend' => 'H5',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.333',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h5_size_sm',
                'label' => esc_html('H5 Font Size (SM)'),
                'name' => 'option_h5_size_sm',
                'type' => 'number',
                'placeholder' => esc_html('H5 (SM)'),
                'instructions' => esc_html('Input the size of H5 (SM)'),
                'prepend' => 'H5',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.333',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h6_size',
                'label' => esc_html('H6 Font Size'),
                'name' => 'option_h6_size',
                'type' => 'number',
                'placeholder' => esc_html('H6'),
                'instructions' => esc_html('Input the size of H6'),
                'prepend' => 'H6',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.125',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h6_size_lg',
                'label' => esc_html('H6 Font Size (LG)'),
                'name' => 'option_h6_size_lg',
                'type' => 'number',
                'placeholder' => esc_html('H6 (LG)'),
                'instructions' => esc_html('Input the size of H6 (LG)'),
                'prepend' => 'H6',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.125',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_h6_size_sm',
                'label' => esc_html('H6 Font Size (SM)'),
                'name' => 'option_h6_size_sm',
                'type' => 'number',
                'placeholder' => esc_html('H6 (SM)'),
                'instructions' => esc_html('Input the size of H6 (SM)'),
                'prepend' => 'H6',
                'append' => 'rem',
                'min' => '1',
                'max' => '10',
                'step' => '0.001',
                'default_value' => '1.125',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_custom_settings_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
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
                        'label' => esc_html('Boxes Title Color'),
                        'name' => 'option_builder_section_buy_now_product_boxes_title_color',
                        'instructions' => esc_html('Select the Boxes Title Color'),
                        'type' => 'color_picker',
                        'default_value' => '#161616',
                        'wrapper' => array(
                            'width' => '25%',
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
                        'label' => esc_html('Boxes Content Color'),
                        'name' => 'option_builder_section_buy_now_product_boxes_content_color',
                        'instructions' => esc_html('Select the Boxes Content Color'),
                        'type' => 'color_picker',
                        'default_value' => '#565656',
                        'wrapper' => array(
                            'width' => '25%',
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
                                    'width' => '50%',
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
                                    'width' => '50%',
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
                'parent' => 'group_repeater_builder_settings',
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
