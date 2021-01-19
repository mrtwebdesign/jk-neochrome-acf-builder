<?php defined('ABSPATH') || exit;

final class ACF_Builder
{

    private static $_instance = null;

    public function __construct()
    {

        add_action('init', [$this, 'init']);

        add_action('admin_head', [$this, 'enqueue_admin_styles']);

    }

    public function enqueue_admin_styles()
    {

        if (!wp_style_is('acf-repeater-builder-css')):

            wp_enqueue_style('acf-repeater-builder-css', get_template_directory_uri() . '/inc/assets/css/acf_repeater_builder.css');

        endif;

    }

    public static function instance()
    {

        if (is_null(self::$_instance)) :

            self::$_instance = new self();

        endif;

        return self::$_instance;

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
                        'label' => esc_html('Type of Background'),
                        'name' => 'option_builder_section_hero_type_of_bg',
                        'instructions' => esc_html('Select the type of Hero Header'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                        ),
                        'default_value' => 'image',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
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
                        'instructions' => esc_html('Select the Hero Header Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
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
                        'instructions' => esc_html('Select the Hero Header Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
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
                        'name' => 'option_builder_section_hero_headline',
                        'instructions' => esc_html('Input the Hero Main Headline'),
                        'type' => 'text',
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
                        'key' => 'field_builder_section_hero_sub_headline',
                        'label' => esc_html('Sub-Headline'),
                        'name' => 'option_builder_section_hero_sub_headline',
                        'instructions' => esc_html('Input the Sub-Headline'),
                        'type' => 'text',
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
                        'key' => 'field_builder_section_hero_secondary_headline',
                        'label' => esc_html('Secondary Headline'),
                        'name' => 'option_builder_section_hero_secondary_headline',
                        'instructions' => esc_html('Input the Secondary Headline'),
                        'type' => 'text',
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
                        'key' => 'field_builder_section_hero_more_info_headline',
                        'label' => esc_html('More Info Headline'),
                        'name' => 'option_builder_section_hero_more_info_headline',
                        'instructions' => esc_html('Input the More Info Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Main Headline'),
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
                        'key' => 'field_builder_section_hero_more_info_box_phone_toggle',
                        'label' => esc_html('Phone Field'),
                        'name' => 'option_builder_section_hero_more_info_box_phone_toggle',
                        'instructions' => esc_html('Enable/Disable More Info Phone Field'),
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'ui_on_text' => esc_html('Enable'),
                        'ui_off_text' => esc_html('Disable'),
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
                    array(
                        'key' => 'field_builder_section_hero_more_info_button_bg',
                        'label' => esc_html('Button Background Color'),
                        'name' => 'field_builder_section_hero_more_info_button_bg',
                        'instructions' => esc_html('Select the More Info Button Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#333333',
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
                        'key' => 'field_builder_section_buy_now_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_buy_now_main_headline',
                        'instructions' => esc_html('Input the Main Headline'),
                        'type' => 'text',
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
                        'key' => 'field_builder_section_buy_now_product_boxes',
                        'label' => esc_html('Product Boxes'),
                        'name' => 'option_builder_section_buy_now_product_boxes',
                        'type' => 'repeater',
                        'label_placement' => 'top',
                        'instructions' => esc_html('Add Box'),
                        'min' => 1,
                        'max' => 0,
                        'layout' => 'row',
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
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_content',
                                'label' => esc_html('Content'),
                                'name' => 'option_builder_section_buy_now_product_boxes_content',
                                'instructions' => esc_html('Input the Box Content'),
                                'placeholder' => esc_html('Box Content'),
                                'type' => 'textarea',
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_button_label',
                                'label' => esc_html('Button Label'),
                                'name' => 'option_builder_section_buy_now_product_boxes_button_label',
                                'instructions' => esc_html('Input the Button Label'),
                                'placeholder' => esc_html('Button Label'),
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_button_url',
                                'label' => esc_html('Button URL'),
                                'name' => 'option_builder_section_buy_now_product_boxes_button_url',
                                'instructions' => esc_html('Input the Button URL'),
                                'placeholder' => esc_html('Button URL'),
                                'type' => 'url',
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_button_bg',
                                'label' => esc_html('Button Background Color'),
                                'name' => 'option_builder_section_buy_now_product_boxes_button_bg',
                                'instructions' => esc_html('Select the Button Background Color'),
                                'type' => 'color_picker',
                                'default_value' => '#333333',
                            ),
                        )
                    ),

                    /* BUY NOW / GET MORE INFO SECTION END */

                    /* **************************************** */

                    /* GALLERY SECTION START */

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

                    /* FAQS SECTION END */

                    /* **************************************** */

                    /* RICH TEXT ROW SECTION START */

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
                        'default_value' => '#333333',
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
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
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
