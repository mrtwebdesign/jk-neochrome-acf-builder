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

            wp_enqueue_style('acf-repeater-builder-css', ASTRA_THEME_DIR . 'inc/assets/css/acf_repeater_builder.css');

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
                'title' => __('Repeater Builder', 'glekk'),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'page',
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
                'required' => 0,
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
                'required' => 0,
                'min' => 0,
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
                        'instructions' => esc_html('Select section type'),
                        'required' => 0,
                        'conditional_logic' => 0,
                        'choices' => array(
                            'hero-section' => esc_html('Hero Section'),
                            'buy-now-get-more-info-section' => esc_html('Buy Now / Get More Info'),
                            'gallery-section' => esc_html('Gallery'),
                            'faqs-section' => esc_html('FAQs'),
                            'rich-text-row-section' => esc_html('Rich Text Row'),
                            'footer-section' => esc_html('Footer'),
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
                        'instructions' => '',
                        'type' => 'button_group',
                        'required' => 0,
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                            'video' => esc_html('Video'),
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
                        'type' => 'text',
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
                        'type' => 'text',
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
                        'key' => 'field_builder_section_hero_bg_video',
                        'label' => esc_html('Background Video'),
                        'name' => 'option_builder_section_hero_bg_video',
                        'type' => 'text',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_hero_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'video',
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
                        'key' => 'field_builder_section_hero_',
                        'label' => esc_html('Hero Section Field'),
                        'name' => 'option_builder_section_hero_',
                        'type' => 'text',
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
                        'key' => 'field_builder_section_hero_',
                        'label' => esc_html('Hero Section Field'),
                        'name' => 'option_builder_section_hero_',
                        'type' => 'text',
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
                        'key' => 'field_builder_section_hero_',
                        'label' => esc_html('Hero Section Field'),
                        'name' => 'option_builder_section_hero_',
                        'type' => 'text',
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
                        'key' => 'field_builder_section_hero_',
                        'label' => esc_html('Hero Section Field'),
                        'name' => 'option_builder_section_hero_',
                        'type' => 'text',
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
                        'key' => 'field_builder_section_hero_',
                        'label' => esc_html('Hero Section Field'),
                        'name' => 'option_builder_section_hero_',
                        'type' => 'text',
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
                        'key' => 'field_builder_section_hero_',
                        'label' => esc_html('Hero Section Field'),
                        'name' => 'option_builder_section_hero_',
                        'type' => 'text',
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

                    /* HERO SECTION END */

                    /* **************************************** */

                    /* BUY NOW / GET MORE INFO SECTION START */

                    array(
                        'key' => 'field_builder_section_buy_now_',
                        'label' => esc_html('Buy Now / Get More Info Section Field'),
                        'name' => 'option_builder_section_buy_now_',
                        'type' => 'text',
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

                    /* BUY NOW / GET MORE INFO SECTION END */

                    /* **************************************** */

                    /* GALLERY SECTION START */

                    array(
                        'key' => 'field_builder_section_gallery_',
                        'label' => esc_html('Gallery Section Field'),
                        'name' => 'option_builder_section_gallery_',
                        'type' => 'text',
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
                        'key' => 'field_builder_section_faqs_',
                        'label' => esc_html('FAQs Section Field'),
                        'name' => 'option_builder_section_faqs_',
                        'type' => 'text',
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
                        'key' => 'field_builder_section_rich_text_row_',
                        'label' => esc_html('Rich Text Row Section Field'),
                        'name' => 'option_builder_section_rich_text_row_',
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

                    /* RICH TEXT ROW SECTION END */

                    /* **************************************** */

                    /* FOOTER SECTION START */

                    array(
                        'key' => 'field_builder_section_footer_',
                        'label' => esc_html('Footer Section Field'),
                        'name' => 'option_builder_section_footer_',
                        'type' => 'text',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'footer-section',
                                ),
                            ),
                        ),
                    ),

                    /* FOOTER SECTION END */

                ),
            ));

        endif;

    }

}

ACF_Builder::instance();
