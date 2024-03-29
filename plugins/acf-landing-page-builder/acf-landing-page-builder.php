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

/**
 *
 */
DEFINE('CORE_PLUGIN_VERSION', '1.0.0');

/**
 *
 */
define("CORE_PLUGIN_PATH", plugin_dir_path(__FILE__));

/**
 *
 */
define("CORE_PLUGIN_URL", plugin_dir_url(__FILE__));

/**
 * Class ACF_Builder
 */
final class ACF_Builder
{

    /**
     * @var null
     */
    private static $_instance = null;

    /**
     * ACF_Builder constructor.
     */
    public function __construct()
    {

        add_filter('wp_mail_from_name', 'custom_wpse_mail_from_name');

        function custom_wpse_mail_from_name($original_email_from)
        {

            return 'Launchpad';

        }

        add_action('init', [$this, 'init']);

        add_action('init', [$this, 'acf_init']);

        add_action('admin_head', [$this, 'enqueue_admin_styles']);

        add_action('admin_head', [$this, 'enqueue_admin_scripts']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_filter('the_content', [$this, 'insert_in_post']);

        function my_login_redirect($redirect_to, $request, $user)
        {

            global $user;

            if (isset($user->roles) && is_array($user->roles)) {

                if (in_array('author', $user->roles) && !empty(get_option('acf_builder_last_post_user_' . $user->ID))) {

                    $data_login = get_option('acf_builder_last_post_user_' . $user->ID);

                    print_r($user->ID);

                    return get_permalink($data_login);

                } else {

                    return home_url();

                }

            } else {

                return $redirect_to;

            }

        }

        add_action('save_post', 'save_post_handler', 10, 3);

        function save_post_handler($post_ID, $post, $update)
        {

            if (ACF_Builder::get_field('field_repeater_builder_toggle', $post_ID)):

                update_option('acf_builder_last_post_user_' . get_current_user_id(), $post_ID);

            endif;

        }

        add_filter('login_redirect', 'my_login_redirect', 10, 3);

    }

    /**
     * @param $content
     *
     * @return mixed
     */
    public function insert_in_post($content)
    {

        if (ACF_Builder::get_field('field_repeater_builder_toggle')):

            ACF_Builder::single_landing_template();

        else:

            return $content;

        endif;

    }

    /**
     * @param $param
     *
     * @return mixed
     */
    public static function the_option($param)
    {

        if (function_exists('the_field')) :

            return the_field($param, 'option');

        endif;

    }

    /**
     * @param $param
     *
     * @return mixed
     */
    public static function get_option($param)
    {

        if (function_exists('get_field')) :

            return get_field($param, 'option');

        endif;

    }

    /**
     * @param $param
     * @param null $id
     *
     * @return mixed
     */
    public static function the_field($param, $id = null)
    {

        if ($id == null) :

            $id = get_the_ID();

        endif;

        if (function_exists('the_field')) :

            return the_field($param, $id);

        endif;

    }

    /**
     * @param $param
     * @param null $id
     *
     * @return mixed
     */
    public static function get_field($param, $id = null)
    {

        if ($id == null) :

            $id = get_the_ID();

        endif;

        if (function_exists('get_field')) :

            return get_field($param, $id);

        endif;

    }

    /**
     * @param $param
     *
     * @return mixed
     */
    public static function the_sub_field($param)
    {

        if (function_exists('the_sub_field')) :

            return the_sub_field($param);

        endif;

    }

    /**
     * @param $param
     *
     * @return mixed
     */
    public static function get_sub_field($param)
    {

        if (function_exists('get_sub_field')) :

            return get_sub_field($param);

        endif;

    }

    /**
     *
     */
    public function enqueue_styles()
    {

        if (!wp_style_is('acf-repeater-builder-frontend-css')):

            wp_enqueue_style('acf-repeater-builder-frontend-css', CORE_PLUGIN_URL . '/inc/assets/css/acf_repeater_builder_frontend.css');


        endif;

        wp_enqueue_style('fancybox-css', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css');

        wp_enqueue_style('fa', CORE_PLUGIN_URL . '/inc/assets/css/all.min.css');

    }

    /**
     *
     */
    public function enqueue_scripts()
    {
        if (!wp_script_is('acf-repeater-builder-frontend-js')):

            wp_enqueue_script('acf-repeater-builder-frontend-js', CORE_PLUGIN_URL . '/inc/assets/js/acf_repeater_builder_frontend.js', array('jquery', 'imagesloaded'));

            wp_localize_script('acf-repeater-builder-frontend-js', 'jk_ajax', array(
                'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'
            ));

        endif;

        wp_enqueue_script('fancybox-js', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array('jquery', 'imagesloaded'));

    }

    /**
     *
     */
    public function enqueue_admin_styles()
    {

        if (!wp_style_is('acf-repeater-builder-css')):

            wp_enqueue_style('acf-repeater-builder-css', CORE_PLUGIN_URL . '/inc/assets/css/acf_repeater_builder.css');

        endif;

    }

    /**
     *
     */
    public function enqueue_admin_scripts()
    {

        if (!wp_script_is('acf-builder-admin-js')):

            wp_enqueue_script('acf-builder-admin-js', CORE_PLUGIN_URL . '/inc/assets/js/acf_repeater_builder.js', array('jquery', 'imagesloaded'));

        endif;

    }

    /**
     * @return ACF_Builder|null
     */
    public static function instance()
    {

        if (is_null(self::$_instance)) :

            self::$_instance = new self();

        endif;

        return self::$_instance;

    }

    /**
     *
     */
    public function theme_options()
    {

        $field_container_width = self::get_field('field_container_width');

        $field_container_offset = self::get_field('field_container_offset');

        $field_sections_offset = self::get_field('field_sections_offset');

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
			--field-container-width: {$field_container_width}px;
			--field-container-offset: {$field_container_offset}px;
			--field-sections-offset: {$field_sections_offset}px;
			--field-builder-default-headings-color: {$field_builder_default_headings_color};
			--field-builder-default-text-color: {$field_builder_default_text_color};
			--field-builder-default-button-color: {$field_builder_default_button_color};
			--field-builder-default-button-bg-color: {$field_builder_default_button_bg_color};
			--field-content-font: {$field_content_font};
			--field-text-font-size: {$field_text_font_size}px;
			--field-text-font-size-lg: {$field_text_font_size_lg}px;
			--field-text-font-size-sm: {$field_text_font_size_sm}px;
			--field-text-font-weight: {$field_text_font_weight};
			--field-text-font-line-height: {$field_text_font_line_height};
			--field-heading-font: {$field_heading_font};
			--field-heading-font-weight: {$field_heading_font_weight};
			--field-heading-font-line-height: {$field_heading_font_line_height};
			--field-h1-size: {$field_h1_size}rem;
			--field-h1-size-lg: {$field_h1_size_lg}rem;
			--field-h1-size-sm: {$field_h1_size_sm}rem;
			--field-h2-size: {$field_h2_size}rem;
			--field-h2-size-lg: {$field_h2_size_lg}rem;
			--field-h2-size-sm: {$field_h2_size_sm}rem;
			--field-h3-size: {$field_h3_size}rem;
			--field-h3-size-lg: {$field_h3_size_lg}rem;
			--field-h3-size-sm: {$field_h3_size_sm}rem;
			--field-h4-size: {$field_h4_size}rem;
			--field-h4-size-lg: {$field_h4_size_lg}rem;
			--field-h4-size-sm: {$field_h4_size_sm}rem;
			--field-h5-size: {$field_h5_size}rem;
			--field-h5-size-lg: {$field_h5_size_lg}rem;
			--field-h5-size-sm: {$field_h5_size_sm}rem;
			--field-h6-size: {$field_h6_size}rem;
			--field-h6-size-lg: {$field_h6_size_lg}rem;
			--field-h6-size-sm: {$field_h6_size_sm}rem;
		}";

        wp_add_inline_style('acf-repeater-builder-frontend-css', $variables);

        self::load_google_font(array($field_heading_font, $field_content_font));

    }

    /**
     * @param array $fontsArr
     * @param array $fontsSubset
     */
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

    /**
     *
     */
    public function init()
    {
        add_action('admin_head', [$this, 'theme_options']);

        add_action('wp_enqueue_scripts', [$this, 'theme_options']);

        add_action('wp_ajax_nopriv_ajax_form', [$this, 'ajax_form']);

        add_action('wp_ajax_ajax_form', [$this, 'ajax_form']);

        add_action('wp_ajax_nopriv_ajax_news_form', [$this, 'ajax_news_form']);

        add_action('wp_ajax_ajax_news_form', [$this, 'ajax_news_form']);

    }

    /**
     *
     */
    public function acf_init()
    {

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

            $fonts_not_parsed = array('ABeeZee', 'Abel', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akronim', 'Aladin', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya SC', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amarante', 'Amaranth', 'Amatic SC', 'Amethysta', 'Anaheim', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo Black', 'Archivo Narrow', 'Arial Black', 'Arial Narrow', 'Arimo', 'Arizonia', 'Armata', 'Artifika', 'Arvo', 'Asap', 'Asset', 'Astloch', 'Asul', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Balthazar', 'Bangers', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Bell MT', 'Bell MT Alt', 'Belleza', 'BenchNine', 'Bentham', 'Berkshire Swash', 'Bevan', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'Bitter', 'Black Ops One', 'Bodoni', 'Bokor', 'Bonbon', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buenard', 'Butcherman', 'Butcherman Caps', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Calibri', 'Calligraffitti', 'Cambo', 'Cambria', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Caudex', 'Cedarville Cursive', 'Ceviche One', 'Changa One', 'Chango', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chivo', 'Cinzel', 'Cinzel Decorative', 'Clara', 'Clicker Script', 'Coda', 'Codystar', 'Combo', 'Comfortaa', 'Coming Soon', 'Concert One', 'Condiment', 'Consolas', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Corsiva', 'Courgette', 'Courier New', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Creepster Caps', 'Crete Round', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cutive', 'Cutive Mono', 'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Days One', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Dhyana', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'Dr Sugiyama', 'Droid Arabic Kufi', 'Droid Arabic Naskh', 'Droid Sans', 'Droid Sans Mono', 'Droid Sans TV', 'Droid Serif', 'Duru Sans', 'Dynalight', 'EB Garamond', 'Eagle Lake', 'Eater', 'Eater Caps', 'Economica', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Engagement', 'Englebert', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Expletus Sans', 'Fanwood Text', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Finger Paint', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'GFS Didot', 'GFS Neohellenic', 'Gabriela', 'Gafata', 'Galdeano', 'Galindo', 'Garamond', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One', 'Gilda Display', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas', 'Goudy Bookletter 1911', 'Graduate', 'Grand Hotel', 'Gravitas One', 'Great Vibes', 'Griffy', 'Gruppo', 'Gudea', 'Habibi', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Headland One', 'Helvetica Neue', 'Henny Penny', 'Herr Von Muellerhoff', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Irish Grover', 'Irish Growler', 'Istok Web', 'Italiana', 'Italianno', 'Jacques Francois', 'Jacques Francois Shadow', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Josefin Sans', 'Josefin Sans Std Light', 'Josefin Slab', 'Joti One', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kameron', 'Karla', 'Kaushan Script', 'Kavoon', 'Keania One', 'Kelly Slab', 'Kenia', 'Khmer', 'Kite One', 'Knewave', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'La Belle Aurore', 'Lancelot', 'Lateef', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Lemon One', 'Libre Baskerville', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Lobster', 'Lobster Two', 'Lohit Bengali', 'Lohit Devanagari', 'Lohit Tamil', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Magra', 'Maiden Orange', 'Mako', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Marko One', 'Marmelad', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Miniver', 'Miss Fajardose', 'Miss Saint Delafield', 'Modern Antiqua', 'Molengo', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedford', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Muli', 'Mystery Quest', 'Neucha', 'Neuton', 'New Rocker', 'News Cycle', 'Niconne', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Nosifer Caps', 'Nothing You Could Do', 'Noticia Text', 'Noto Sans', 'Noto Sans UI', 'Noto Serif', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'OFL Sorts Mill Goudy TT', 'Odor Mean Chey', 'Offside', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orienta', 'Original Surfer', 'Oswald', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Ovo', 'Oxygen', 'Oxygen Mono', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Patua One', 'Paytone One', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Poppins', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Port Lligat Sans', 'Port Lligat Slab', 'Prata', 'Preahvihear', 'Press Start 2P', 'Princess Sofia', 'Prociono', 'Prosto One', 'Proxima Nova', 'Proxima Nova Tabular Figures', 'Puritan', 'Purple Purse', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Raleway', 'Raleway Dots', 'Rambla', 'Rammetto One', 'Ranchers', 'Rancho', 'Rationale', 'Redressed', 'Reenie Beanie', 'Revalia', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Slab', 'Rochester', 'Rock Salt', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sail', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita One', 'Sarina', 'Satisfy', 'Scada', 'Scheherazade', 'Schoolbell', 'Seaweed Script', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shojumaru', 'Short Stack', 'Siamreap', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slackey', 'Smokum', 'Smythe', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Special Elite', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Stalemate', 'Stalin One', 'Stalinist One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'Stoke', 'Strait', 'Sue Ellen Francisco', 'Sunshiney', 'Supermercado One', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tahoma', 'Tangerine', 'Taprom', 'Tauri', 'Telex', 'Tenor Sans', 'Terminal Dosis', 'Terminal Dosis Light', 'Text Me One', 'Thabit', 'The Girl Next Door', 'Tienne', 'Tinos', 'Titan One', 'Titillium Web', 'Trade Winds', 'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Vampiro One', 'Varela', 'Varela Round', 'Vast Shadow', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'Wire One', 'Yanone Kaffeesatz', 'Yellowtail', 'Yeseva One', 'Yesteryear', 'Zeyada', 'jsMath cmbx10', 'jsMath cmex10', 'jsMath cmmi10', 'jsMath cmr10', 'jsMath cmsy10', 'jsMath cmti10');

            $fonts_parsed = array();

            foreach ($fonts_not_parsed as $font):

                $fonts_parsed[$font] = $font;

            endforeach;

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

            // Landing Builder Tab

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_custom_settings_landing_builder_tab',
                'label' => esc_html('Landing Builder'),
                'name' => 'option_repeater_builder_custom_settings_landing_builder_tab',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'parent' => 'group_repeater_builder_settings',
                'placement' => 'top',
                'endpoint' => 0,
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
                            'width' => '25%',
                            'class' => '',
                        ),
                        'choices' => array(
                            'hero-section' => esc_html('Hero Section'),
                            'buy-now-get-more-info-section' => esc_html('Buy Now / Get More Info'),
                            'gallery-section' => esc_html('Gallery'),
                            'faqs-section' => esc_html('FAQs'),
                            'rich-text-row-section' => esc_html('Rich Text Row'),
                            'simple-text-row-section' => esc_html('Simple Text Row'),
                        ),
                        'default_value' => 'rich-text-row-section',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                    ),

                    /* HERO SECTION START */

                    array(
                        'key' => 'field_builder_section_hero_bg_tab',
                        'label' => esc_html('Background'),
                        'name' => 'option_builder_section_hero_bg_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 1,
                    ),
                    array(
                        'key' => 'field_builder_section_hero_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_hero_type_of_bg',
                        'instructions' => esc_html('Select the style of Section Background'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                            'gradient' => esc_html('Gradient'),
                        ),
                        'default_value' => 'color',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                            'width' => '66.6666%',
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
                        'instructions' => esc_html('Select the Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                        'key' => 'field_builder_section_hero_bg_image_overlay',
                        'label' => esc_html('Background Image Overlay'),
                        'name' => 'option_builder_section_hero_bg_image_overlay',
                        'instructions' => esc_html('Select the depth of Overlay'),
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'type' => 'range',
                        'min' => '0',
                        'max' => '1',
                        'step' => '0.1',
                        'default_value' => '0.5',
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
                        'key' => 'field_builder_section_hero_bg_gradient_color_1',
                        'label' => esc_html('Gradient Color 1'),
                        'name' => 'option_builder_section_hero_bg_gradient_color_1',
                        'instructions' => esc_html('Select the Section Gradient Color 1'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_hero_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
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
                        'key' => 'field_builder_section_hero_bg_gradient_color_2',
                        'label' => esc_html('Gradient Color 2'),
                        'name' => 'option_builder_section_hero_bg_gradient_color_2',
                        'instructions' => esc_html('Select the Section Gradient Color 2'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_hero_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
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
                        'key' => 'field_builder_section_hero_content_tab',
                        'label' => esc_html('Content'),
                        'name' => 'option_builder_section_hero_content_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_builder_section_hero_logotype_image_toggle',
                        'label' => esc_html('Enable/Disable Logotype'),
                        'name' => 'option_builder_section_hero_logotype_image_toggle',
                        'instructions' => esc_html('Enable/Disable Logotype Image'),
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => esc_html('Enable'),
                        'ui_off_text' => esc_html('Disable'),
                        'wrapper' => array(
                            'width' => '33.33333333%',
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
                        'key' => 'field_builder_section_hero_logotype_image',
                        'label' => esc_html('Logotype Image'),
                        'name' => 'option_builder_section_hero_logotype_image',
                        'instructions' => esc_html('Select the Logotype Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.333333%',
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
                                    'field' => 'field_builder_section_hero_logotype_image_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_text_shadows',
                        'label' => esc_html('Text Shadows'),
                        'name' => 'option_builder_section_hero_text_shadows',
                        'instructions' => esc_html('Enable/Disable Text Shadows'),
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'ui_on_text' => esc_html('Enable'),
                        'ui_off_text' => esc_html('Disable'),
                        'wrapper' => array(
                            'width' => '33.333333%',
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
                        'key' => 'field_builder_section_hero_propositions_toggle',
                        'label' => esc_html('Enable/Disable Propositions'),
                        'name' => 'option_builder_section_hero_propositions_toggle',
                        'instructions' => esc_html('Enable/Disable Propositions List'),
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => esc_html('Enable'),
                        'ui_off_text' => esc_html('Disable'),
                        'wrapper' => array(
                            'width' => '25%',
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
                        'wrapper' => array(
                            'width' => '25%',
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
                                    'field' => 'field_builder_section_hero_propositions_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_propositions_offset',
                        'label' => esc_html('Propositions Offset'),
                        'name' => 'option_builder_section_hero_propositions_offset',
                        'instructions' => esc_html('Select the offset of propositions list'),
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                        ),
                        'type' => 'range',
                        'min' => '0',
                        'max' => '100',
                        'step' => '1',
                        'default_value' => '30',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_propositions_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_propositions_max_width',
                        'label' => esc_html('Propositions Max Width'),
                        'name' => 'option_builder_section_hero_propositions_max_width',
                        'instructions' => esc_html('Select the max-width of propositions list'),
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                        ),
                        'type' => 'range',
                        'min' => '0',
                        'max' => '100',
                        'step' => '1',
                        'default_value' => '100',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_propositions_toggle',
                                    'operator' => '==',
                                    'value' => '1',
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
                        'layout' => 'block',
                        'button_label' => esc_html('Add new Proposition'),
                        'sub_fields' => array(
                            array(
                                'key' => 'field_builder_section_hero_proposition_title',
                                'label' => esc_html('Proposition Title'),
                                'name' => 'option_builder_section_hero_proposition_title',
                                'instructions' => esc_html('Input the Proposition Title'),
                                'placeholder' => esc_html('Proposition Title'),
                                'type' => 'text',
                            ),
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                                array(
                                    'field' => 'field_builder_section_hero_propositions_toggle',
                                    'operator' => '==',
                                    'value' => '1',
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
                    ),
                    array(
                        'key' => 'field_builder_section_hero_more_info_box_tab',
                        'label' => esc_html('More Info'),
                        'name' => 'option_builder_section_hero_more_info_box_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
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
                            'width' => '25%',
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
                        'key' => 'field_builder_section_hero_more_info_box_subject',
                        'label' => esc_html('Subject Line'),
                        'name' => 'option_builder_section_hero_more_info_box_subject',
                        'instructions' => esc_html('Enter Subject Line'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Subject Line'),
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
                        'key' => 'field_builder_section_hero_more_info_box_email',
                        'label' => esc_html('E-Mail'),
                        'name' => 'option_builder_section_hero_more_info_box_email',
                        'instructions' => esc_html('Enter the destination email address where results are sent'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('example@domain.com'),
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
                        'key' => 'field_builder_section_hero_more_info_box_bg_color',
                        'label' => esc_html('Background Color'),
                        'name' => 'option_builder_section_hero_more_info_box_bg_color',
                        'instructions' => esc_html('Select the More Info Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '25%',
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
                            'width' => '50%',
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
                            'width' => '50%',
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
                            'width' => '50%',
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
                            'width' => '50%',
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
                        'key' => 'field_builder_section_hero_more_info_placeholder_color',
                        'label' => esc_html('More Info Placeholder Color'),
                        'name' => 'field_builder_section_hero_more_info_placeholder_color',
                        'instructions' => esc_html('Select the More Info Placeholder Color'),
                        'type' => 'color_picker',
                        'default_value' => '#666666',
                        'wrapper' => array(
                            'width' => '50%',
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
                        'key' => 'field_builder_section_buy_now_bg_tab',
                        'label' => esc_html('Background'),
                        'name' => 'field_builder_section_buy_now_bg_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 1,
                    ),
                    array(
                        'key' => 'field_builder_section_buy_now_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_buy_now_type_of_bg',
                        'instructions' => esc_html('Select the style of Section Background'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                            'gradient' => esc_html('Gradient'),
                        ),
                        'default_value' => 'color',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                            'width' => '66.6666%',
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
                        'instructions' => esc_html('Select the Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                        'key' => 'field_builder_section_buy_now_bg_image_overlay',
                        'label' => esc_html('Background Image Overlay'),
                        'name' => 'option_builder_section_buy_now_bg_image_overlay',
                        'instructions' => esc_html('Select the depth of Overlay'),
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'type' => 'range',
                        'min' => '0',
                        'max' => '1',
                        'step' => '0.1',
                        'default_value' => '0.5',
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
                        'key' => 'field_builder_section_buy_now_gradient_color_1',
                        'label' => esc_html('Gradient Color 1'),
                        'name' => 'option_builder_section_buy_now_gradient_color_1',
                        'instructions' => esc_html('Select the Section Gradient Color 1'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_buy_now_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
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
                        'key' => 'field_builder_section_buy_now_gradient_color_2',
                        'label' => esc_html('Gradient Color 2'),
                        'name' => 'option_builder_section_buy_now_gradient_color_2',
                        'instructions' => esc_html('Select the Section Gradient Color 2'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_buy_now_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
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
                        'key' => 'field_builder_section_buy_now_content_tab',
                        'label' => esc_html('Content'),
                        'name' => 'field_builder_section_buy_now_content_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_builder_section_buy_now_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_buy_now_main_headline',
                        'instructions' => esc_html('Input the Main Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Main Headline'),
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
                        'key' => 'field_builder_section_buy_now_main_headline_color',
                        'label' => esc_html('Main Headline Color'),
                        'name' => 'option_builder_section_buy_now_main_headline_color',
                        'instructions' => esc_html('Select the Main Headline Color'),
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
                        'key' => 'field_builder_section_buy_now_secondary_headline',
                        'label' => esc_html('Secondary Headline'),
                        'name' => 'option_builder_section_buy_now_secondary_headline',
                        'instructions' => esc_html('Input the Secondary Headline'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Secondary Headline'),
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
                        'key' => 'field_builder_section_buy_now_secondary_headline_color',
                        'label' => esc_html('Secondary Headline Color'),
                        'name' => 'option_builder_section_buy_now_secondary_headline_color',
                        'instructions' => esc_html('Select the Secondary Headline Color'),
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
                        'key' => 'field_builder_section_buy_now_product_boxes_title_color',
                        'label' => esc_html('Boxes Title Color'),
                        'name' => 'option_builder_section_buy_now_product_boxes_title_color',
                        'instructions' => esc_html('Select the Boxes Title Color'),
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
                        'key' => 'field_builder_section_buy_now_product_boxes_content_color',
                        'label' => esc_html('Boxes Content Color'),
                        'name' => 'option_builder_section_buy_now_product_boxes_content_color',
                        'instructions' => esc_html('Select the Boxes Content Color'),
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
                                'key' => 'field_builder_section_buy_now_product_boxes_image',
                                'label' => esc_html('Box Image'),
                                'name' => 'option_builder_section_buy_now_product_boxes_image',
                                'instructions' => esc_html('Select the Box Image'),
                                'type' => 'image',
                                'return_format' => 'id',
                                'preview_size' => 'medium',
                                'library' => 'all',
                                'wrapper' => array(
                                    'width' => '33.3333%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_title',
                                'label' => esc_html('Title'),
                                'name' => 'option_builder_section_buy_now_product_boxes_title',
                                'instructions' => esc_html('Input the Box Title'),
                                'placeholder' => esc_html('Box Title'),
                                'type' => 'text',
                                'wrapper' => array(
                                    'width' => '33.3333%',
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
                                    'width' => '33.3333%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_button_toggle',
                                'label' => esc_html('Enable/Disable Button'),
                                'name' => 'option_builder_section_buy_now_product_boxes_button_toggle',
                                'instructions' => esc_html('Enable/Disable Box Button'),
                                'type' => 'true_false',
                                'default_value' => 0,
                                'ui' => 1,
                                'ui_on_text' => esc_html('Enable'),
                                'ui_off_text' => esc_html('Disable'),
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
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_builder_section_hero_propositions_toggle',
                                            'operator' => '==',
                                            'value' => '1',
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_button_url',
                                'label' => esc_html('Button URL'),
                                'name' => 'option_builder_section_buy_now_product_boxes_button_url',
                                'instructions' => esc_html('Input the Button URL'),
                                'placeholder' => esc_html('Button URL'),
                                'type' => 'text',
                                'wrapper' => array(
                                    'width' => '33.33333%',
                                    'class' => '',
                                ),
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_builder_section_hero_propositions_toggle',
                                            'operator' => '==',
                                            'value' => '1',
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_buy_now_product_boxes_button_target',
                                'label' => esc_html('Button Target'),
                                'name' => 'option_builder_section_buy_now_product_boxes_button_target',
                                'instructions' => esc_html('Select the Button Target'),
                                'type' => 'select',
                                'choices' => array(
                                    '_blank' => esc_html('_blank'),
                                    '_self' => esc_html('_self'),
                                    '_parent' => esc_html('_parent'),
                                    '_top' => esc_html('_top'),
                                ),
                                'wrapper' => array(
                                    'width' => '33.33333%',
                                    'class' => '',
                                ),
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_builder_section_hero_propositions_toggle',
                                            'operator' => '==',
                                            'value' => '1',
                                        ),
                                    ),
                                ),
                            ),
                        )
                    ),

                    /* BUY NOW / GET MORE INFO SECTION END */

                    /* **************************************** */

                    /* GALLERY SECTION START */

                    array(
                        'key' => 'field_builder_section_gallery_bg_tab',
                        'label' => esc_html('Background'),
                        'name' => 'field_builder_section_gallery_bg_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'gallery-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 1,
                    ),
                    array(
                        'key' => 'field_builder_section_gallery_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_gallery_type_of_bg',
                        'instructions' => esc_html('Select the style of Section Background'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                            'gradient' => esc_html('Gradient'),
                        ),
                        'default_value' => 'color',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                            'width' => '66.6666%',
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
                        'instructions' => esc_html('Select the Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                        'key' => 'field_builder_section_gallery_bg_image_overlay',
                        'label' => esc_html('Background Image Overlay'),
                        'name' => 'option_builder_section_gallery_bg_image_overlay',
                        'instructions' => esc_html('Select the depth of Overlay'),
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'type' => 'range',
                        'min' => '0',
                        'max' => '1',
                        'step' => '0.1',
                        'default_value' => '0.5',
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
                        'key' => 'field_builder_section_gallery_gradient_color_1',
                        'label' => esc_html('Gradient Color 1'),
                        'name' => 'option_builder_section_gallery_gradient_color_1',
                        'instructions' => esc_html('Select the Section Gradient Color 1'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_gallery_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
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
                        'key' => 'field_builder_section_gallery_gradient_color_2',
                        'label' => esc_html('Gradient Color 2'),
                        'name' => 'option_builder_section_gallery_gradient_color_2',
                        'instructions' => esc_html('Select the Section Gradient Color 2'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_gallery_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
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
                        'key' => 'field_builder_section_gallery_content_tab',
                        'label' => esc_html('Content'),
                        'name' => 'field_builder_section_gallery_content_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'gallery-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_builder_section_gallery_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_buy_now_main_headline',
                        'instructions' => esc_html('Input the Main Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Main Headline'),
                        'wrapper' => array(
                            'width' => '50%',
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
                        'key' => 'field_builder_section_gallery_main_headline_color',
                        'label' => esc_html('Main Headline Color'),
                        'name' => 'option_builder_section_buy_now_main_headline_color',
                        'instructions' => esc_html('Select the Main Headline Color'),
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
                                    'value' => 'gallery-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_gallery_secondary_headline',
                        'label' => esc_html('Secondary Headline'),
                        'name' => 'option_builder_section_buy_now_secondary_headline',
                        'instructions' => esc_html('Input the Secondary Headline'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Secondary Headline'),
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
                        'key' => 'field_builder_section_gallery_secondary_headline_color',
                        'label' => esc_html('Secondary Headline Color'),
                        'name' => 'option_builder_section_buy_now_secondary_headline_color',
                        'instructions' => esc_html('Select the Secondary Headline Color'),
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
                        'return_format' => 'text',
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
                        'key' => 'field_builder_section_faqs_bg_tab',
                        'label' => esc_html('Background'),
                        'name' => 'field_builder_section_faqs_bg_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'faqs-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 1,
                    ),
                    array(
                        'key' => 'field_builder_section_faqs_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_faqs_type_of_bg',
                        'instructions' => esc_html('Select the style of Section Background'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                            'gradient' => esc_html('Gradient'),
                        ),
                        'default_value' => 'color',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                            'width' => '66.6666%',
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
                        'instructions' => esc_html('Select the Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                        'key' => 'field_builder_section_faqs_bg_image_overlay',
                        'label' => esc_html('Background Image Overlay'),
                        'name' => 'option_builder_section_faqs_bg_image_overlay',
                        'instructions' => esc_html('Select the depth of Overlay'),
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'type' => 'range',
                        'min' => '0',
                        'max' => '1',
                        'step' => '0.1',
                        'default_value' => '0.5',
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
                        'key' => 'field_builder_section_faqs_gradient_color_1',
                        'label' => esc_html('Gradient Color 1'),
                        'name' => 'option_builder_section_faqs_gradient_color_1',
                        'instructions' => esc_html('Select the Section Gradient Color 1'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_faqs_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
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
                        'key' => 'field_builder_section_faqs_gradient_color_2',
                        'label' => esc_html('Gradient Color 2'),
                        'name' => 'option_builder_section_faqs_gradient_color_2',
                        'instructions' => esc_html('Select the Section Gradient Color 2'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                        'key' => 'field_builder_section_faqs_content_tab',
                        'label' => esc_html('Content'),
                        'name' => 'field_builder_section_faqs_content_tab',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'faqs-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_builder_section_faqs_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_faqs_main_headline',
                        'instructions' => esc_html('Input the Main Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Main Headline'),
                        'wrapper' => array(
                            'width' => '50%',
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
                        'key' => 'field_builder_section_faqs_main_headline_color',
                        'label' => esc_html('Main Headline Color'),
                        'name' => 'option_builder_section_faqs_main_headline_color',
                        'instructions' => esc_html('Select the Main Headline Color'),
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
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
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
                        'key' => 'field_builder_section_faqs_secondary_headline_color',
                        'label' => esc_html('Secondary Headline Color'),
                        'name' => 'option_builder_section_faqs_secondary_headline_color',
                        'instructions' => esc_html('Select the Secondary Headline Color'),
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
                        'key' => 'field_builder_section_rich_text_row_tab_bg',
                        'label' => esc_html('Background'),
                        'name' => 'option_builder_section_rich_text_row_tab_bg',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'class' => 'text-row-tab',
                        ),
                        'placement' => 'top',
                        'endpoint' => 1,
                    ),
                    array(
                        'key' => 'field_builder_section_rich_text_row_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_rich_text_row_type_of_bg',
                        'instructions' => esc_html('Select the style of Section Background'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                            'gradient' => esc_html('Gradient'),
                        ),
                        'default_value' => 'color',
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
                            'width' => '66.66666%',
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
                        'instructions' => esc_html('Select the Background Image'),
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
                        'key' => 'field_builder_section_rich_text_row_bg_image_overlay',
                        'label' => esc_html('Background Image Overlay'),
                        'name' => 'option_builder_section_rich_text_row_bg_image_overlay',
                        'instructions' => esc_html('Select the depth of Overlay'),
                        'type' => 'range',
                        'min' => '0',
                        'max' => '1',
                        'step' => '0.1',
                        'default_value' => '0.5',
                        'wrapper' => array(
                            'width' => '33.3333%',
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
                        'key' => 'field_builder_section_rich_text_row_bg_gradient_color_1',
                        'label' => esc_html('Gradient Color 1'),
                        'name' => 'option_builder_section_rich_text_row_bg_gradient_color_1',
                        'instructions' => esc_html('Select the Section Gradient Color 1'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_rich_text_row_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
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
                        'key' => 'field_builder_section_rich_text_row_bg_gradient_color_2',
                        'label' => esc_html('Gradient Color 2'),
                        'name' => 'option_builder_section_rich_text_row_bg_gradient_color_2',
                        'instructions' => esc_html('Select the Section Gradient Color 2'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_rich_text_row_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
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
                        'key' => 'field_builder_section_rich_text_row_tab_content',
                        'label' => esc_html('Content'),
                        'name' => 'option_builder_section_rich_text_row_tab_content',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_builder_section_rich_text_row_columns',
                        'label' => esc_html('Columns'),
                        'name' => 'option_builder_section_rich_text_row_columns',
                        'type' => 'repeater',
                        'label_placement' => 'top',
                        'instructions' => esc_html('Add Column'),
                        'min' => 1,
                        'max' => 0,
                        'layout' => 'block',
                        'button_label' => esc_html('Add new Column'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                        'sub_fields' => array(
                            array(
                                'key' => 'field_builder_section_rich_text_row_wsw_column',
                                'label' => esc_html('Column Content'),
                                'name' => 'option_builder_section_rich_text_row_wsw_column',
                                'type' => 'wysiwyg',
                                'instructions' => esc_html('Type your column content'),
                                'tabs' => 'all',
                                'toolbar' => 'full',
                                'media_upload' => 1,
                                'default_value' => '',
                                'delay' => 0,
                            ),
                        )
                    ),
                    array(
                        'key' => 'field_builder_section_rich_text_row_max_width',
                        'label' => esc_html('Rich Text Max Width'),
                        'name' => 'option_builder_section_rich_text_row_max_width',
                        'instructions' => esc_html('Select the offset of rich text row'),
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                        'type' => 'range',
                        'min' => '0',
                        'max' => '100',
                        'step' => '1',
                        'default_value' => '100',
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
                        'key' => 'field_builder_section_rich_text_row_button_url',
                        'label' => esc_html('Button URL'),
                        'name' => 'option_builder_section_rich_text_row_button_url',
                        'instructions' => esc_html('Input the Button URL'),
                        'placeholder' => esc_html('Button URL'),
                        'type' => 'text',
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
                        'key' => 'field_builder_section_rich_text_row_button_target',
                        'label' => esc_html('Button Target'),
                        'name' => 'option_builder_section_rich_text_row_button_target',
                        'instructions' => esc_html('Select the Button Target'),
                        'type' => 'select',
                        'choices' => array(
                            '_blank' => esc_html('_blank'),
                            '_self' => esc_html('_self'),
                            '_parent' => esc_html('_parent'),
                            '_top' => esc_html('_top'),
                        ),
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

                    /* RICH TEXT ROW SECTION END */

                    /* **************************************** */

                    /* SIMPLE TEXT ROW SECTION START */

                    array(
                        'key' => 'field_builder_section_simple_text_row_tab_bg',
                        'label' => esc_html('Background'),
                        'name' => 'option_builder_section_simple_text_row_tab_bg',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'class' => 'text-row-tab',
                        ),
                        'placement' => 'top',
                        'endpoint' => 1,
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_type_of_bg',
                        'label' => esc_html('Section Background Style'),
                        'name' => 'option_builder_section_simple_text_row_type_of_bg',
                        'instructions' => esc_html('Select the style of Section Background'),
                        'type' => 'button_group',
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                            'gradient' => esc_html('Gradient'),
                        ),
                        'default_value' => 'color',
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
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_bg_color',
                        'label' => esc_html('Background Color'),
                        'name' => 'option_builder_section_simple_text_row_bg_color',
                        'instructions' => esc_html('Select the Section Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '66.66666%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_simple_text_row_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'color',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_bg_image',
                        'label' => esc_html('Background Image'),
                        'name' => 'option_builder_section_simple_text_row_bg_image',
                        'instructions' => esc_html('Select the Background Image'),
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
                                    'field' => 'field_builder_section_simple_text_row_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'image',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_bg_image_overlay',
                        'label' => esc_html('Background Image Overlay'),
                        'name' => 'option_builder_section_simple_text_row_bg_image_overlay',
                        'instructions' => esc_html('Select the depth of Overlay'),
                        'type' => 'range',
                        'min' => '0',
                        'max' => '1',
                        'step' => '0.1',
                        'default_value' => '0.5',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_simple_text_row_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'image',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_bg_gradient_color_1',
                        'label' => esc_html('Gradient Color 1'),
                        'name' => 'option_builder_section_simple_text_row_bg_gradient_color_1',
                        'instructions' => esc_html('Select the Section Gradient Color 1'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_simple_text_row_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_bg_gradient_color_2',
                        'label' => esc_html('Gradient Color 2'),
                        'name' => 'option_builder_section_simple_text_row_bg_gradient_color_2',
                        'instructions' => esc_html('Select the Section Gradient Color 2'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'wrapper' => array(
                            'width' => '33.3333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_simple_text_row_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'gradient',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_tab_content',
                        'label' => esc_html('Content'),
                        'name' => 'option_builder_section_simple_text_row_tab_content',
                        'type' => 'tab',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                        'placement' => 'top',
                        'endpoint' => 0,
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_simple_text_row_main_headline',
                        'instructions' => esc_html('Input the Hero Main Headline'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Main Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_main_headline_alignment',
                        'label' => esc_html('Main Headline Alignment'),
                        'name' => 'option_builder_section_simple_text_row_main_headline_alignment',
                        'instructions' => esc_html('Select the alignment of Main Headline'),
                        'type' => 'button_group',
                        'choices' => array(
                            'left' => esc_html('Left'),
                            'center' => esc_html('Center'),
                            'right' => esc_html('Right'),
                        ),
                        'default_value' => 'left',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_main_headline_color',
                        'label' => esc_html('Main Headline Color'),
                        'name' => 'option_builder_section_simple_text_row_main_headline_color',
                        'instructions' => esc_html('Select the Main Headline Color'),
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
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_sub_headline',
                        'label' => esc_html('Sub-Headline'),
                        'name' => 'option_builder_section_simple_text_row_sub_headline',
                        'instructions' => esc_html('Input the Sub-Headline'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Sub-Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_sub_headline_alignment',
                        'label' => esc_html('Sub Headline Alignment'),
                        'name' => 'option_builder_section_simple_text_row_sub_headline_alignment',
                        'instructions' => esc_html('Select the alignment of Sub Headline'),
                        'type' => 'button_group',
                        'choices' => array(
                            'left' => esc_html('Left'),
                            'center' => esc_html('Center'),
                            'right' => esc_html('Right'),
                        ),
                        'default_value' => 'left',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_sub_headline_color',
                        'label' => esc_html('Sub Headline Color'),
                        'name' => 'option_builder_section_simple_text_row_sub_headline_color',
                        'instructions' => esc_html('Select the Sub Headline Color'),
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
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_body',
                        'label' => esc_html('Content'),
                        'name' => 'option_builder_section_simple_text_row_body',
                        'instructions' => esc_html('Input the Body Content'),
                        'placeholder' => esc_html('Body Content'),
                        'type' => 'textarea',
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_body_alignment',
                        'label' => esc_html('Text Row Body Alignment'),
                        'name' => 'option_builder_section_simple_text_row_body_alignment',
                        'instructions' => esc_html('Select the alignment of Text Row Body'),
                        'type' => 'button_group',
                        'choices' => array(
                            'left' => esc_html('Left'),
                            'center' => esc_html('Center'),
                            'right' => esc_html('Right'),
                        ),
                        'default_value' => 'left',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'wrapper' => array(
                            'width' => '25%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_body_color',
                        'label' => esc_html('Body Color'),
                        'name' => 'option_builder_section_simple_text_row_body_color',
                        'instructions' => esc_html('Select the Body Color'),
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
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_simple_text_row_content_rows',
                        'label' => esc_html('Content Rows'),
                        'name' => 'option_builder_section_simple_text_row_content_rows',
                        'type' => 'repeater',
                        'label_placement' => 'top',
                        'instructions' => esc_html('Add Row'),
                        'min' => 1,
                        'max' => 0,
                        'layout' => 'row',
                        'button_label' => esc_html('Add new Content Row'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                        'sub_fields' => array(
                            array(
                                'key' => 'field_builder_section_content_rows_simple_text_row_heading',
                                'label' => esc_html('Heading'),
                                'name' => 'option_builder_section_content_rows_simple_text_row_heading',
                                'instructions' => esc_html('Input the Heading'),
                                'placeholder' => esc_html('Heading'),
                                'type' => 'text',
                                'wrapper' => array(
                                    'width' => '50%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_content_rows_simple_text_row_heading_alignment',
                                'label' => esc_html('Heading Alignment'),
                                'name' => 'option_builder_section_content_rows_simple_text_row_heading_alignment',
                                'instructions' => esc_html('Select the alignment of Heading'),
                                'type' => 'button_group',
                                'choices' => array(
                                    'left' => esc_html('Left'),
                                    'center' => esc_html('Center'),
                                    'right' => esc_html('Right'),
                                ),
                                'default_value' => 'left',
                                'layout' => 'horizontal',
                                'return_format' => 'value',
                                'wrapper' => array(
                                    'width' => '25%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_content_rows_simple_text_row_heading_color',
                                'label' => esc_html('Headline Color'),
                                'name' => 'option_builder_section_content_rows_simple_text_row_heading_color',
                                'instructions' => esc_html('Select the Heading Color'),
                                'type' => 'color_picker',
                                'default_value' => '#161616',
                                'wrapper' => array(
                                    'width' => '25%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_content_rows_simple_text_row_box_content',
                                'label' => esc_html('Body'),
                                'name' => 'field_builder_section_content_rows_simple_text_row_box_content',
                                'instructions' => esc_html('Input the Body'),
                                'placeholder' => esc_html('Body'),
                                'type' => 'textarea',
                                'wrapper' => array(
                                    'width' => '66.66666%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_content_rows_simple_text_row_box_content_alignment',
                                'label' => esc_html('Content Alignment'),
                                'name' => 'field_builder_section_content_rows_simple_text_row_box_content_alignment',
                                'instructions' => esc_html('Select the alignment of Content'),
                                'type' => 'button_group',
                                'choices' => array(
                                    'left' => esc_html('Left'),
                                    'center' => esc_html('Center'),
                                    'right' => esc_html('Right'),
                                ),
                                'default_value' => 'left',
                                'layout' => 'horizontal',
                                'return_format' => 'value',
                                'wrapper' => array(
                                    'width' => '25%',
                                    'class' => '',
                                ),
                            ),
                            array(
                                'key' => 'field_builder_section_content_rows_simple_text_row_body_color',
                                'label' => esc_html('Body Color'),
                                'name' => 'option_builder_section_content_rows_simple_text_row_body_color',
                                'instructions' => esc_html('Select the Body Color'),
                                'type' => 'color_picker',
                                'default_value' => '#565656',
                                'wrapper' => array(
                                    'width' => '25%',
                                    'class' => '',
                                ),
                            ),
                        )
                    ),
                    array(
                        'key' => 'field_repeater_builder_simple_text_row_email_form_toggle',
                        'label' => esc_html('Enable/Disable Newsletter Section'),
                        'name' => 'option_repeater_builder_simple_text_row_email_form_toggle',
                        'instructions' => esc_html('Enable/Disable Newsletter Section'),
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => esc_html('Enable'),
                        'ui_off_text' => esc_html('Disable'),
                        'wrapper' => array(
                            'width' => '33.3333333%',
                            'class' => '',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_repeater_builder_simple_text_row_email_form',
                        'label' => esc_html('E-Mail'),
                        'name' => 'option_repeater_builder_simple_text_row_email_form',
                        'instructions' => esc_html('Enter the destination email address where results are sent'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '33.3333333%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('example@domain.com'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                                array(
                                    'field' => 'field_repeater_builder_simple_text_row_email_form_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_repeater_builder_simple_text_row_email_form_subject_line',
                        'label' => esc_html('Subject Line'),
                        'name' => 'option_repeater_builder_simple_text_row_email_form_subject_line',
                        'instructions' => esc_html('Enter Subject Line'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '33.3333333%',
                            'class' => '',
                        ),
                        'placeholder' => esc_html('Subject Line'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'simple-text-row-section',
                                ),
                                array(
                                    'field' => 'field_repeater_builder_simple_text_row_email_form_toggle',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                    ),

                    /* SIMPLE TEXT ROW SECTION END */

                    /* **************************************** */

                ),
            ));

            // Container Tab

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_custom_settings_container_tab',
                'label' => esc_html('Container'),
                'name' => 'option_repeater_builder_custom_settings_container_tab',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
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
                'parent' => 'group_repeater_builder_settings',
                'placement' => 'top',
                'endpoint' => 0,
            ));

            acf_add_local_field(array(
                'key' => 'field_container_width',
                'label' => esc_html('Container Width'),
                'name' => 'option_container_width',
                'type' => 'number',
                'placeholder' => esc_html('Container Width'),
                'instructions' => esc_html('Input the Container Width'),
                'append' => 'px',
                'min' => '800',
                'max' => '1920',
                'step' => '1',
                'default_value' => '1470',
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
                'key' => 'field_container_offset',
                'label' => esc_html('Container Offset'),
                'name' => 'option_container_offset',
                'type' => 'number',
                'placeholder' => esc_html('Container Offset'),
                'instructions' => esc_html('Input the Container Offset'),
                'append' => 'px',
                'min' => '0',
                'max' => '100',
                'step' => '1',
                'default_value' => '15',
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
                'key' => 'field_sections_offset',
                'label' => esc_html('Sections Offset'),
                'name' => 'option_sections_offset',
                'type' => 'number',
                'placeholder' => esc_html('Sections Offset'),
                'instructions' => esc_html('Input the Sections Offset'),
                'append' => 'px',
                'min' => '0',
                'max' => '250',
                'step' => '1',
                'default_value' => '75',
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

            // Colors Tab

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_custom_settings_colors_tab',
                'label' => esc_html('Colors'),
                'name' => 'option_repeater_builder_custom_settings_colors_tab',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
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
                'parent' => 'group_repeater_builder_settings',
                'placement' => 'top',
                'endpoint' => 0,
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

            // General Typography Tab

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_custom_settings_general_typography_tab',
                'label' => esc_html('General Typography'),
                'name' => 'option_repeater_builder_custom_settings_general_typography_tab',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
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
                'parent' => 'group_repeater_builder_settings',
                'placement' => 'top',
                'endpoint' => 0,
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
                'choices' => $fonts_parsed,
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
                'choices' => $fonts_parsed,
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

            // Headings Typography Tab

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_custom_settings_headings_typography_tab',
                'label' => esc_html('Headings Typography'),
                'name' => 'option_repeater_builder_custom_settings_headings_typography_tab',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
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
                'parent' => 'group_repeater_builder_settings',
                'placement' => 'top',
                'endpoint' => 0,
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

            // Footer Tab

            // if (current_user_can('administrator')):

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_custom_settings_footer_tab',
                'label' => esc_html('Footer'),
                'name' => 'option_repeater_builder_custom_settings_footer_tab',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'parent' => 'group_repeater_builder_settings',
                'placement' => 'top',
                'endpoint' => 0,
            ));

            acf_add_local_field(array(
                'key' => 'field_footer_social_list',
                'label' => esc_html('Footer Social List'),
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
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_footer_social_list_url',
                        'label' => esc_html('Social URL'),
                        'name' => 'option_builder_section_footer_social_list_url',
                        'instructions' => esc_html('Input the Social Icon URL'),
                        'placeholder' => esc_html('Social URL'),
                        'type' => 'text',
                        'wrapper' => array(
                            'width' => '50%',
                            'class' => '',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_footer_logotype_image',
                'label' => esc_html('Logotype Image'),
                'name' => 'option_footer_logotype_image',
                'instructions' => esc_html('Select the Logotype Image'),
                'type' => 'image',
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'parent' => 'group_repeater_builder_settings',
                'wrapper' => array(
                    'width' => '50%',
                    'class' => '',
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_footer_company_name',
                'label' => esc_html('Company Name'),
                'name' => 'option_footer_company_name',
                'instructions' => esc_html('Input the Company Name'),
                'type' => 'text',
                'parent' => 'group_repeater_builder_settings',
                'placeholder' => esc_html('Company Name'),
                'wrapper' => array(
                    'width' => '50%',
                    'class' => '',
                ),
            ));

            //   endif;

            // Sharing Tab

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_share_buttons_tab',
                'label' => esc_html('Share Buttons'),
                'name' => 'option_repeater_builder_share_buttons_tab',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'parent' => 'group_repeater_builder_settings',
                'placement' => 'top',
                'endpoint' => 0,
            ));

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_share_buttons_toggle',
                'label' => esc_html('Enable/Disable Share Buttons'),
                'name' => 'option_repeater_builder_share_buttons_toggle',
                'instructions' => esc_html('Enable/Disable Share Buttons'),
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => esc_html('Enable'),
                'ui_off_text' => esc_html('Disable'),
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
            ));

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_share_buttons_facebook_toggle',
                'label' => esc_html('Facebook'),
                'name' => 'option_repeater_builder_share_buttons_facebook_toggle',
                'instructions' => esc_html('Facebook'),
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => esc_html('Enable'),
                'ui_off_text' => esc_html('Disable'),
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
                            'field' => 'field_repeater_builder_share_buttons_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_share_buttons_twitter_toggle',
                'label' => esc_html('Twitter'),
                'name' => 'option_repeater_builder_share_buttons_twitter_toggle',
                'instructions' => esc_html('Twitter'),
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => esc_html('Enable'),
                'ui_off_text' => esc_html('Disable'),
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
                            'field' => 'field_repeater_builder_share_buttons_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_share_buttons_alignment',
                'label' => esc_html('Section Background Style'),
                'name' => 'option_repeater_builder_share_buttons_alignment',
                'instructions' => esc_html('Select the alignment of share buttons'),
                'type' => 'button_group',
                'choices' => array(
                    'top-left' => esc_html('Top Left'),
                    'top-right' => esc_html('Top Right'),
                    'bottom-left' => esc_html('Bottom Left'),
                    'bottom-right' => esc_html('Bottom Right'),
                ),
                'default_value' => 'bottom-right',
                'layout' => 'horizontal',
                'return_format' => 'value',
                'wrapper' => array(
                    'width' => '33.33333%',
                    'class' => '',
                ),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_share_buttons_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ));

            // Offer Modal

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_offer_modal_tab',
                'label' => esc_html('Offer Modal'),
                'name' => 'option_repeater_builder_offer_modal_tab',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'parent' => 'group_repeater_builder_settings',
                'placement' => 'top',
                'endpoint' => 0,
            ));

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_offer_modal_toggle',
                'label' => esc_html('Enable/Disable Offer Modal'),
                'name' => 'option_repeater_builder_offer_modal_toggle',
                'instructions' => esc_html('Enable/Disable Offer Modal'),
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
                'key' => 'field_repeater_builder_offer_modal_delay',
                'label' => esc_html('Delay'),
                'name' => 'option_repeater_builder_offer_modal_delay',
                'type' => 'number',
                'placeholder' => esc_html('Delay (s)'),
                'instructions' => esc_html('Input the Delay (s)'),
                'append' => 'px',
                'min' => '0',
                'max' => '120',
                'step' => '1',
                'default_value' => '8',
                'wrapper' => array(
                    'width' => '50%',
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
                    ),
                ),
            ));

        endif;

    }

    /**
     *
     */
    public static function single_landing_template()
    {

        $hero_section_counter = 1;

        $get_more_info_section_counter = 1;

        $gallery_section_counter = 1;

        $faqs_section_counter = 1;

        $rich_text_section_counter = 1;

        $simple_text_row_section_counter = 1;

        ?>

        <div class="neo-landing-wrapper">

            <?php

            $field_repeater_builder_share_buttons_toggle = self::get_field('field_repeater_builder_share_buttons_toggle');

            $field_repeater_builder_share_buttons_facebook_toggle = self::get_field('field_repeater_builder_share_buttons_facebook_toggle');

            $field_repeater_builder_share_buttons_twitter_toggle = self::get_field('field_repeater_builder_share_buttons_twitter_toggle');

            $field_repeater_builder_share_buttons_alignment = self::get_field('field_repeater_builder_share_buttons_alignment');

            ?>

            <?php if (get_the_ID() === 171): ?>

                <div class="modal-window-wrapper">

                    <?php


                    ?>

                    <div class="modal-window"
                         style="<?php if ($field_builder_modal_window_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_modal_window_bg_color); ?><?php elseif ($field_builder_modal_window_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_modal_window_bg_image,'full')); ?>);<?php endif; ?>">

                        <?php if ($field_builder_modal_window_type_of_bg === 'image'): ?>

                            <span class="overlay"
                                  style="opacity: <?php echo esc_attr($field_builder_modal_window_bg_image_overlay); ?>"></span>

                        <?php endif; ?>

                        <?php if ($field_builder_modal_window_type_of_bg === 'gradient'): ?>

                            <span class="overlay"
                                  style="background:linear-gradient(45deg, <?php echo esc_attr($field_builder_modal_window_bg_gradient_color_1); ?>, <?php echo esc_attr($field_builder_modal_window_bg_gradient_color_2); ?>);"></span>

                        <?php endif; ?>

                        <div class="inner-wrapper">


                        </div>

                    </div>

                    <div class="close-modal-window-button">

                        <i class="fas fa-times"></i>

                    </div>

                </div>

            <?php endif; ?>

            <?php if ($field_repeater_builder_share_buttons_toggle): ?>

                <div class="share-wrapper <?php echo esc_attr($field_repeater_builder_share_buttons_alignment); ?>">

                    <?php if ($field_repeater_builder_share_buttons_facebook_toggle): ?>

                        <?php

                        $url = self::get_social_sharing_url(get_the_ID());

                        ?>

                        <a href="<?php echo esc_url(self::get_facebook_share_link('', $url, '', '')); ?>"
                           class="share-button share-button-facebook" style="background-color: #3b5998;">

                            <i class="fab fa-facebook-f"></i>

                        </a>

                    <?php endif; ?>

                    <?php if ($field_repeater_builder_share_buttons_twitter_toggle): ?>

                        <?php

                        $url = self::get_social_sharing_url(get_the_ID());

                        ?>

                        <a href="<?php echo esc_url(self::get_twitter_share_link('', $url, '', wp_trim_words(get_the_content(get_the_ID()), 10, ''))); ?>"
                           class="share-button share-button-twitter" style="background-color: #1DA1F2;">

                            <i class="fab fa-twitter"></i>

                        </a>

                    <?php endif; ?>

                </div>

            <?php endif; ?>

            <?php

            if (have_rows('field_repeater_builder')):

                while (have_rows('field_repeater_builder')) : the_row();

                    $section_type = self::get_sub_field('field_builder_section_type');

                    if ($section_type === 'hero-section'):

                        $field_builder_section_hero_type_of_bg = self::get_sub_field('field_builder_section_hero_type_of_bg');

                        $field_builder_section_hero_bg_color = self::get_sub_field('field_builder_section_hero_bg_color');

                        $field_builder_section_hero_bg_image = self::get_sub_field('field_builder_section_hero_bg_image');

                        $field_builder_section_hero_bg_image_overlay = self::get_sub_field('field_builder_section_hero_bg_image_overlay');

                        $field_builder_section_hero_bg_gradient_color_1 = self::get_sub_field('field_builder_section_hero_bg_gradient_color_1');

                        $field_builder_section_hero_bg_gradient_color_2 = self::get_sub_field('field_builder_section_hero_bg_gradient_color_2');

                        $field_builder_section_hero_main_headline = self::get_sub_field('field_builder_section_hero_main_headline');

                        $field_builder_section_hero_logotype_image = self::get_sub_field('field_builder_section_hero_logotype_image');

                        $field_builder_section_hero_logotype_image_toggle = self::get_sub_field('field_builder_section_hero_logotype_image_toggle');

                        $field_builder_section_hero_text_shadows = self::get_sub_field('field_builder_section_hero_text_shadows');

                        $field_builder_section_hero_main_headline_color = self::get_sub_field('field_builder_section_hero_main_headline_color');

                        $field_builder_section_hero_sub_headline = self::get_sub_field('field_builder_section_hero_sub_headline');

                        $field_builder_section_hero_sub_headline_color = self::get_sub_field('field_builder_section_hero_sub_headline_color');

                        $field_builder_section_hero_secondary_headline = self::get_sub_field('field_builder_section_hero_secondary_headline');

                        $field_builder_section_hero_secondary_headline_color = self::get_sub_field('field_builder_section_hero_secondary_headline_color');

                        $field_builder_section_hero_more_info_box_toggle = self::get_sub_field('field_builder_section_hero_more_info_box_toggle');

                        $field_builder_section_hero_more_info_box_bg_color = self::get_sub_field('field_builder_section_hero_more_info_box_bg_color');

                        $field_builder_section_hero_more_info_headline = self::get_sub_field('field_builder_section_hero_more_info_headline');

                        $field_builder_section_hero_more_info_headline_color = self::get_sub_field('field_builder_section_hero_more_info_headline_color');

                        $field_builder_section_hero_propositions_toggle = self::get_sub_field('field_builder_section_hero_propositions_toggle');

                        $field_builder_section_hero_propositions_color = self::get_sub_field('field_builder_section_hero_propositions_color');

                        $field_builder_section_hero_propositions_offset = self::get_sub_field('field_builder_section_hero_propositions_offset');

                        $field_builder_section_hero_propositions_max_width = self::get_sub_field('field_builder_section_hero_propositions_max_width');

                        $field_builder_section_hero_more_info_secondary_headline = self::get_sub_field('field_builder_section_hero_more_info_secondary_headline');

                        $field_builder_section_hero_more_info_secondary_headline_color = self::get_sub_field('field_builder_section_hero_more_info_secondary_headline_color');

                        $field_builder_section_hero_more_info_box_phone_toggle = self::get_sub_field('field_builder_section_hero_more_info_box_phone_toggle');

                        $field_builder_section_hero_more_info_box_subject = self::get_sub_field('field_builder_section_hero_more_info_box_subject');

                        $field_builder_section_hero_more_info_box_email = self::get_sub_field('field_builder_section_hero_more_info_box_email');

                        $field_builder_section_hero_more_info_button_label = self::get_sub_field('field_builder_section_hero_more_info_button_label');

                        $field_builder_section_hero_more_info_placeholder_color = self::get_sub_field('field_builder_section_hero_more_info_placeholder_color');

                        ?>

                        <section
                                class="neo-section hero-section <?php if (!empty($field_builder_section_hero_text_shadows)): ?>text-shadows<?php endif; ?>"
                                style="<?php if ($field_builder_section_hero_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_hero_bg_color); ?><?php elseif ($field_builder_section_hero_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_hero_bg_image,'full')); ?>);<?php endif; ?>"
                                id="hero<?php if ($hero_section_counter > 1): echo esc_attr('-' . $hero_section_counter); endif; ?>">

                            <?php $hero_section_counter++; ?>

                            <?php if ($field_builder_section_hero_type_of_bg === 'image'): ?>

                                <span class="overlay"
                                      style="opacity: <?php echo esc_attr($field_builder_section_hero_bg_image_overlay); ?>"></span>

                            <?php endif; ?>

                            <?php if ($field_builder_section_hero_type_of_bg === 'gradient'): ?>

                                <span class="overlay"
                                      style="background:linear-gradient(45deg, <?php echo esc_attr($field_builder_section_hero_bg_gradient_color_1); ?>, <?php echo esc_attr($field_builder_section_hero_bg_gradient_color_2); ?>);"></span>

                            <?php endif; ?>

                            <div class="neo-container inner-wrapper">

                                <?php if (!empty($field_builder_section_hero_logotype_image) && $field_builder_section_hero_logotype_image_toggle): ?>

                                    <div class="hero-logotype">

                                        <img src="<?php echo esc_url(wp_get_attachment_image_url($field_builder_section_hero_logotype_image, 'full')); ?>"
                                             alt="<?php echo esc_attr('Logotype'); ?>">

                                    </div>

                                <?php endif; ?>

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

                                    if (have_rows('field_builder_section_hero_propositions') && $field_builder_section_hero_propositions_toggle): ?>


                                        <ul class="propositions-list"
                                            style="margin-left: <?php echo esc_attr($field_builder_section_hero_propositions_offset); ?>px; max-width: <?php echo esc_attr($field_builder_section_hero_propositions_max_width); ?>%">

                                            <?php

                                            while (have_rows('field_builder_section_hero_propositions')) : the_row();

                                                $field_builder_section_hero_proposition_title = self::get_sub_field('field_builder_section_hero_proposition_title');

                                                if (!empty($field_builder_section_hero_proposition_title)):

                                                    ?>

                                                    <li style="color: <?php echo esc_attr($field_builder_section_hero_propositions_color); ?>;">

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

                                        <div class="info-box-wrapper"
                                             style="background-color:<?php echo esc_attr($field_builder_section_hero_more_info_box_bg_color); ?>">

                                            <h2 class="info-box-title"
                                                style="color: <?php echo esc_attr($field_builder_section_hero_more_info_headline_color); ?>;">

                                                <?php echo esc_html($field_builder_section_hero_more_info_headline); ?>

                                            </h2>

                                            <p class="info-box-secondary-headline"
                                               style="color: <?php echo esc_attr($field_builder_section_hero_more_info_secondary_headline_color); ?>;">

                                                <?php echo esc_html($field_builder_section_hero_more_info_secondary_headline); ?>

                                            </p>

                                            <div class="form-wrapper">

                                                <style>

                                                    .hero-contact-form input::placeholder {
                                                        color: <?php echo esc_attr($field_builder_section_hero_more_info_placeholder_color);?> !important;
                                                    }

                                                    .hero-contact-form #submit-ajax {
                                                        color: <?php echo esc_attr($field_builder_section_hero_more_info_placeholder_color);?> !important;
                                                    }

                                                </style>

                                                <form class="hero-contact-form" id="heroform" method="post"
                                                      data-post-url="<?php echo get_the_permalink(); ?>"
                                                      data-subject="<?php echo esc_attr($field_builder_section_hero_more_info_box_subject); ?>"
                                                      data-email="<?php echo esc_attr($field_builder_section_hero_more_info_box_email); ?>">

                                                    <input type="name" class="input-field" name="name-field"
                                                           id="name-field"
                                                           placeholder="<?php echo esc_html('Name'); ?>">

                                                    <input type="email" class="input-field" name="email-field"
                                                           id="email-field"
                                                           placeholder="<?php echo esc_html('Email'); ?>">

                                                    <?php if ($field_builder_section_hero_more_info_box_phone_toggle): ?>

                                                        <input type="text" class="input-field" name="phone-field"
                                                               id="phone-field"
                                                               placeholder="<?php echo esc_html('Phone'); ?>">

                                                    <?php endif; ?>

                                                    <button class="submit-button">

                                                        <?php echo esc_html($field_builder_section_hero_more_info_button_label); ?>

                                                    </button>

                                                    <div id="submit-ajax">

                                                    </div>

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

                        $field_builder_section_buy_now_bg_image_overlay = self::get_sub_field('field_builder_section_buy_now_bg_image_overlay');

                        $field_builder_section_buy_now_bg_gradient_color_1 = self::get_sub_field('field_builder_section_buy_now_gradient_color_1');

                        $field_builder_section_buy_now_bg_gradient_color_2 = self::get_sub_field('field_builder_section_buy_now_gradient_color_2');

                        $field_builder_section_buy_now_main_headline = self::get_sub_field('field_builder_section_buy_now_main_headline');

                        $field_builder_section_buy_now_main_headline_color = self::get_sub_field('field_builder_section_buy_now_main_headline_color');

                        $field_builder_section_buy_now_secondary_headline = self::get_sub_field('field_builder_section_buy_now_secondary_headline');

                        $field_builder_section_buy_now_secondary_headline_color = self::get_sub_field('field_builder_section_buy_now_secondary_headline_color');

                        $field_builder_section_buy_now_product_boxes_title_color = self::get_sub_field('field_builder_section_buy_now_product_boxes_title_color');

                        $field_builder_section_buy_now_product_boxes_content_color = self::get_sub_field('field_builder_section_buy_now_product_boxes_content_color');

                        ?>

                        <section class="neo-section buy-now-get-more-info-section"
                                 style="<?php if ($field_builder_section_buy_now_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_buy_now_bg_color); ?><?php elseif ($field_builder_section_buy_now_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_buy_now_bg_image,'full')); ?>);<?php endif; ?>"
                                 id="info<?php if ($get_more_info_section_counter > 1): echo esc_attr('-' . $get_more_info_section_counter); endif; ?>">

                            <?php $get_more_info_section_counter++; ?>

                            <?php if ($field_builder_section_buy_now_type_of_bg === 'image'): ?>

                                <span class="overlay"
                                      style="opacity: <?php echo esc_attr($field_builder_section_buy_now_bg_image_overlay); ?>"></span>

                            <?php endif; ?>

                            <?php if ($field_builder_section_buy_now_type_of_bg === 'gradient'): ?>

                                <span class="overlay"
                                      style="background:linear-gradient(45deg, <?php echo esc_attr($field_builder_section_buy_now_bg_gradient_color_1); ?>, <?php echo esc_attr($field_builder_section_buy_now_bg_gradient_color_2); ?>);"></span>

                            <?php endif; ?>

                            <div class="neo-container inner-wrapper">

                                <?php if (!empty($field_builder_section_buy_now_main_headline)): ?>

                                    <h2 class="section-title"
                                        style="color:<?php echo esc_attr($field_builder_section_buy_now_main_headline_color); ?>">

                                        <?php echo esc_html($field_builder_section_buy_now_main_headline); ?>

                                    </h2>

                                <?php endif; ?>

                                <?php if (!empty($field_builder_section_buy_now_secondary_headline)): ?>

                                    <p class="section-subtitle"
                                       style="color:<?php echo esc_attr($field_builder_section_buy_now_secondary_headline_color); ?>">

                                        <?php echo esc_html($field_builder_section_buy_now_secondary_headline); ?>

                                    </p>

                                <?php endif; ?>

                                <?php if (have_rows('field_builder_section_buy_now_product_boxes')): ?>

                                    <div class="boxes-list">

                                        <?php

                                        while (have_rows('field_builder_section_buy_now_product_boxes')) : the_row();

                                            $field_builder_section_buy_now_product_boxes_title = self::get_sub_field('field_builder_section_buy_now_product_boxes_title');

                                            $field_builder_section_buy_now_product_boxes_image = self::get_sub_field('field_builder_section_buy_now_product_boxes_image');

                                            $field_builder_section_buy_now_product_boxes_content = self::get_sub_field('field_builder_section_buy_now_product_boxes_content');

                                            $field_builder_section_buy_now_product_boxes_button_toggle = self::get_sub_field('field_builder_section_buy_now_product_boxes_button_toggle');

                                            $field_builder_section_buy_now_product_boxes_button_label = self::get_sub_field('field_builder_section_buy_now_product_boxes_button_label');

                                            $field_builder_section_buy_now_product_boxes_button_url = self::get_sub_field('field_builder_section_buy_now_product_boxes_button_url');

                                            $field_builder_section_buy_now_product_boxes_button_target = self::get_sub_field('field_builder_section_buy_now_product_boxes_button_target');

                                            ?>

                                            <div class="box">

                                                <div class="box-inner">

                                                    <?php if (!empty($field_builder_section_buy_now_product_boxes_image)): ?>

                                                        <div class="box-header"
                                                             style="background-image: url(<?php echo esc_url(wp_get_attachment_image_url($field_builder_section_buy_now_product_boxes_image, 'full')); ?>)">

                                                        </div>

                                                    <?php endif; ?>

                                                    <div class="box-body">

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

                                                        <?php if (!empty($field_builder_section_buy_now_product_boxes_button_label) && $field_builder_section_buy_now_product_boxes_button_toggle): ?>

                                                            <div class="button-wrapper">

                                                                <a class="neo-button"
                                                                   href="<?php echo esc_url($field_builder_section_buy_now_product_boxes_button_url); ?>"
                                                                   target="<?php echo esc_attr($field_builder_section_buy_now_product_boxes_button_target); ?>">

                                                                    <?php echo esc_html($field_builder_section_buy_now_product_boxes_button_label); ?>

                                                                </a>

                                                            </div>

                                                        <?php endif; ?>

                                                    </div>

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

                        $field_builder_section_gallery_secondary_headline = self::get_sub_field('field_builder_section_gallery_secondary_headline');

                        $field_builder_section_gallery_type_of_bg = self::get_sub_field('field_builder_section_gallery_type_of_bg');

                        $field_builder_section_gallery_bg_image_overlay = self::get_sub_field('field_builder_section_gallery_bg_image_overlay');

                        $field_builder_section_gallery_bg_gradient_color_1 = self::get_sub_field('field_builder_section_gallery_gradient_color_1');

                        $field_builder_section_gallery_bg_gradient_color_2 = self::get_sub_field('field_builder_section_gallery_gradient_color_2');

                        $field_builder_section_gallery_bg_color = self::get_sub_field('field_builder_section_gallery_bg_color');

                        $field_builder_section_gallery_bg_image = self::get_sub_field('field_builder_section_gallery_bg_image');

                        $field_builder_section_gallery_gallery = self::get_sub_field('field_builder_section_gallery_gallery');

                        ?>

                        <section class="neo-section gallery-section"
                                 style="<?php if ($field_builder_section_gallery_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_gallery_bg_color); ?><?php elseif ($field_builder_section_gallery_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_gallery_bg_image,'full')); ?>);<?php endif; ?>"
                                 id="gallery<?php if ($gallery_section_counter > 1): echo esc_attr('-' . $gallery_section_counter); endif; ?>">

                            <?php $gallery_section_counter++; ?>

                            <?php if ($field_builder_section_gallery_type_of_bg === 'image'): ?>

                                <span class="overlay"
                                      style="opacity: <?php echo esc_attr($field_builder_section_gallery_bg_image_overlay); ?>"></span>

                            <?php endif; ?>

                            <?php if ($field_builder_section_gallery_type_of_bg === 'gradient'): ?>

                                <span class="overlay"
                                      style="background:linear-gradient(45deg, <?php echo esc_attr($field_builder_section_gallery_bg_gradient_color_1); ?>, <?php echo esc_attr($field_builder_section_gallery_bg_gradient_color_2); ?>);"></span>

                            <?php endif; ?>

                            <div class="neo-container inner-wrapper">

                                <?php if (!empty($field_builder_section_gallery_main_headline)): ?>

                                    <h2 class="section-title">

                                        <?php echo esc_html($field_builder_section_gallery_main_headline); ?>

                                    </h2>

                                <?php endif; ?>

                                <?php if (!empty($field_builder_section_gallery_secondary_headline)): ?>

                                    <p class="section-subtitle">

                                        <?php echo esc_html($field_builder_section_gallery_secondary_headline); ?>

                                    </p>

                                <?php endif; ?>

                                <?php if (!empty($field_builder_section_gallery_gallery)): ?>

                                    <div class="gallery-wrapper">

                                        <?php foreach ($field_builder_section_gallery_gallery as $gallery): ?>

                                            <a class="gallery-item" data-fancybox="gallery"
                                               href="<?php echo esc_url(wp_get_attachment_image_url($gallery, 'large')); ?>">

                                                <div class="inner-item">

                                                    <div class="image-wrapper"
                                                         style="background-image: url('<?php echo esc_url(wp_get_attachment_image_url($gallery, 'large')); ?>')">

                                                    </div>

                                                </div>

                                            </a>

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

                        $field_builder_section_faqs_bg_image_overlay = self::get_sub_field('field_builder_section_faqs_bg_image_overlay');

                        $field_builder_section_faqs_bg_gradient_color_1 = self::get_sub_field('field_builder_section_faqs_gradient_color_1');

                        $field_builder_section_faqs_bg_gradient_color_2 = self::get_sub_field('field_builder_section_faqs_gradient_color_2');

                        $field_builder_section_faqs_main_headline = self::get_sub_field('field_builder_section_faqs_main_headline');

                        $field_builder_section_faqs_main_headline_color = self::get_sub_field('field_builder_section_faqs_main_headline_color');

                        $field_builder_section_faqs_secondary_headline = self::get_sub_field('field_builder_section_faqs_secondary_headline');

                        $field_builder_section_faqs_secondary_headline_color = self::get_sub_field('field_builder_section_faqs_secondary_headline_color');

                        ?>

                        <section class="neo-section faqs-section"
                                 style="<?php if ($field_builder_section_faqs_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_faqs_bg_color); ?><?php elseif ($field_builder_section_faqs_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_faqs_bg_image,'full')); ?>);<?php endif; ?>"
                                 id="faqs<?php if ($faqs_section_counter > 1): echo esc_attr('-' . $faqs_section_counter); endif; ?>">

                            <?php $faqs_section_counter++; ?>

                            <?php if ($field_builder_section_faqs_type_of_bg === 'image'): ?>

                                <span class="overlay"
                                      style="opacity: <?php echo esc_attr($field_builder_section_faqs_bg_image_overlay); ?>"></span>

                            <?php endif; ?>

                            <?php if ($field_builder_section_faqs_type_of_bg === 'gradient'): ?>

                                <span class="overlay"
                                      style="background:linear-gradient(45deg, <?php echo esc_attr($field_builder_section_faqs_bg_gradient_color_1); ?>, <?php echo esc_attr($field_builder_section_faqs_bg_gradient_color_2); ?>);"></span>

                            <?php endif; ?>

                            <div class="neo-container inner-wrapper">

                                <?php if (!empty($field_builder_section_faqs_main_headline)): ?>

                                    <h2 class="section-title"
                                        style="color:<?php echo esc_attr($field_builder_section_faqs_main_headline_color); ?>">

                                        <?php echo esc_html($field_builder_section_faqs_main_headline); ?>

                                    </h2>

                                <?php endif; ?>

                                <?php if (!empty($field_builder_section_faqs_secondary_headline)): ?>

                                    <p class="section-subtitle"
                                       style="color:<?php echo esc_attr($field_builder_section_faqs_secondary_headline_color); ?>">

                                        <?php echo esc_html($field_builder_section_faqs_secondary_headline); ?>

                                    </p>

                                <?php endif; ?>

                                <?php if (have_rows('field_builder_section_faqs_builder')): ?>

                                    <ul class="faqs-accordion">
                                        <?php while (have_rows('field_builder_section_faqs_builder')) : the_row(); ?>
                                            <?php

                                            $field_builder_section_faqs_builder_category_name = self::get_sub_field('field_builder_section_faqs_builder_category_name');

                                            ?>

                                            <li class="faq-item">

                                                <a class="toggle category-toggle"
                                                   href="#">

                                                    <h6 class="category-name">

                                                        <?php echo esc_html($field_builder_section_faqs_builder_category_name); ?>

                                                    </h6>

                                                </a>

                                                <?php if (have_rows('field_builder_section_faqs_builder_category_questions_answers')): ?>

                                                    <ul class="inner">

                                                        <?php while (have_rows('field_builder_section_faqs_builder_category_questions_answers')) : the_row(); ?>

                                                            <li class="inner-faq-item">

                                                                <?php

                                                                $field_builder_section_faqs_builder_category_question = self::get_sub_field('field_builder_section_faqs_builder_category_question');

                                                                $field_builder_section_faqs_builder_category_answer = self::get_sub_field('field_builder_section_faqs_builder_category_answer');

                                                                ?>


                                                                <a href="#" class="toggle answer-toggle">

                                                                    <h6 class="question">

                                                                        <?php echo esc_html($field_builder_section_faqs_builder_category_question); ?>

                                                                    </h6>

                                                                </a>

                                                                <div class="inner">

                                                                    <p class="answer">

                                                                        <?php echo esc_html($field_builder_section_faqs_builder_category_answer); ?>

                                                                    </p>

                                                                </div>

                                                            </li>

                                                        <?php endwhile; ?>

                                                    </ul>

                                                <?php endif; ?>

                                            </li>

                                        <?php endwhile; ?>

                                    </ul>

                                <?php endif; ?>

                            </div>

                        </section>

                    <?php

                    elseif ($section_type === 'rich-text-row-section'):

                        $field_builder_section_rich_text_row_type_of_bg = self::get_sub_field('field_builder_section_rich_text_row_type_of_bg');

                        $field_builder_section_rich_text_row_bg_color = self::get_sub_field('field_builder_section_rich_text_row_bg_color');

                        $field_builder_section_rich_text_row_bg_image = self::get_sub_field('field_builder_section_rich_text_row_bg_image');

                        $field_builder_section_rich_text_row_bg_image_overlay = self::get_sub_field('field_builder_section_rich_text_row_bg_image_overlay');

                        $field_builder_section_rich_text_row_bg_gradient_color_1 = self::get_sub_field('field_builder_section_rich_text_row_bg_gradient_color_1');

                        $field_builder_section_rich_text_row_bg_gradient_color_2 = self::get_sub_field('field_builder_section_rich_text_row_bg_gradient_color_2');

                        $field_builder_section_rich_text_row_max_width = self::get_sub_field('field_builder_section_rich_text_row_max_width');

                        $field_builder_section_rich_text_row_button_label = self::get_sub_field('field_builder_section_rich_text_row_button_label');

                        $field_builder_section_rich_text_row_button_url = self::get_sub_field('field_builder_section_rich_text_row_button_url');

                        $field_builder_section_rich_text_row_button_target = self::get_sub_field('field_builder_section_rich_text_row_button_target');

                        ?>

                        <section class="neo-section rich-text-row-section"
                                 style="<?php if ($field_builder_section_rich_text_row_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_rich_text_row_bg_color); ?><?php elseif ($field_builder_section_rich_text_row_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_rich_text_row_bg_image,'full')); ?>);<?php endif; ?>"
                                 id="rich-text<?php if ($rich_text_section_counter > 1): echo esc_attr('-' . $rich_text_section_counter); endif; ?>">

                            <?php $rich_text_section_counter++; ?>

                            <?php if ($field_builder_section_rich_text_row_type_of_bg === 'image'): ?>

                                <span class="overlay"
                                      style="opacity: <?php echo esc_attr($field_builder_section_rich_text_row_bg_image_overlay); ?>"></span>

                            <?php endif; ?>

                            <?php if ($field_builder_section_rich_text_row_type_of_bg === 'gradient'): ?>

                                <span class="overlay"
                                      style="background:linear-gradient(45deg, <?php echo esc_attr($field_builder_section_rich_text_row_bg_gradient_color_1); ?>, <?php echo esc_attr($field_builder_section_rich_text_row_bg_gradient_color_2); ?>);"></span>

                            <?php endif; ?>

                            <div class="neo-container inner-wrapper">

                                <div class="rich-columns"
                                     style="max-width: <?php echo esc_attr($field_builder_section_rich_text_row_max_width); ?>%;">

                                    <?php

                                    $count = count(self::get_sub_field('field_builder_section_rich_text_row_columns'));

                                    if (have_rows('field_builder_section_rich_text_row_columns')):

                                        while (have_rows('field_builder_section_rich_text_row_columns')) : the_row();

                                            ?>

                                            <?php $column_content = self::get_sub_field('field_builder_section_rich_text_row_wsw_column'); ?>

                                            <div class="column"
                                                 style="max-width: <?php echo 100 / $count; ?>%;">

                                                <?php echo $column_content; ?>

                                            </div>

                                        <?php

                                        endwhile;

                                    endif;

                                    ?>

                                </div>

                                <?php if (!empty($field_builder_section_rich_text_row_button_label)): ?>

                                    <div class="button-wrapper">

                                        <a class="neo-button"
                                           href="<?php echo esc_url($field_builder_section_rich_text_row_button_url); ?>"
                                           target="<?php echo esc_attr($field_builder_section_rich_text_row_button_target); ?>">

                                            <?php echo esc_html($field_builder_section_rich_text_row_button_label); ?>

                                        </a>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </section>

                    <?php elseif ($section_type === 'simple-text-row-section'):

                        $field_builder_section_simple_text_row_type_of_bg = self::get_sub_field('field_builder_section_simple_text_row_type_of_bg');

                        $field_builder_section_simple_text_row_bg_color = self::get_sub_field('field_builder_section_simple_text_row_bg_color');

                        $field_builder_section_simple_text_row_bg_image = self::get_sub_field('field_builder_section_simple_text_row_bg_image');

                        $field_builder_section_simple_text_row_bg_image_overlay = self::get_sub_field('field_builder_section_simple_text_row_bg_image_overlay');

                        $field_builder_section_simple_text_row_bg_gradient_color_1 = self::get_sub_field('field_builder_section_simple_text_row_bg_gradient_color_1');

                        $field_builder_section_simple_text_row_bg_gradient_color_2 = self::get_sub_field('field_builder_section_simple_text_row_bg_gradient_color_2');

                        $field_builder_section_simple_text_row_main_headline = self::get_sub_field('field_builder_section_simple_text_row_main_headline');

                        $field_builder_section_simple_text_row_main_headline_color = self::get_sub_field('field_builder_section_simple_text_row_main_headline_color');

                        $field_builder_section_simple_text_row_sub_headline = self::get_sub_field('field_builder_section_simple_text_row_sub_headline');

                        $field_builder_section_simple_text_row_sub_headline_color = self::get_sub_field('field_builder_section_simple_text_row_sub_headline_color');

                        $field_builder_section_simple_text_row_body = self::get_sub_field('field_builder_section_simple_text_row_body');

                        $field_builder_section_simple_text_row_body_color = self::get_sub_field('field_builder_section_simple_text_row_body_color');

                        $field_repeater_builder_simple_text_row_email_form_toggle = self::get_sub_field('field_repeater_builder_simple_text_row_email_form_toggle');

                        $field_builder_section_simple_text_row_main_headline_alignment = self::get_sub_field('field_builder_section_simple_text_row_main_headline_alignment');

                        $field_builder_section_simple_text_row_sub_headline_alignment = self::get_sub_field('field_builder_section_simple_text_row_sub_headline_alignment');

                        $field_builder_section_simple_text_row_body_alignment = self::get_sub_field('field_builder_section_simple_text_row_body_alignment');

                        $field_repeater_builder_simple_text_row_email_form = self::get_sub_field('field_repeater_builder_simple_text_row_email_form');

                        $field_repeater_builder_simple_text_row_email_form_subject_line = self::get_sub_field('field_repeater_builder_simple_text_row_email_form_subject_line');

                        ?>

                        <section class="neo-section simple-text-row-section"
                                 style="<?php if ($field_builder_section_simple_text_row_type_of_bg === 'color'): ?> background-color:<?php echo esc_attr($field_builder_section_simple_text_row_bg_color); ?><?php elseif ($field_builder_section_simple_text_row_type_of_bg === 'image'): ?>background-image:url(<?php echo esc_attr(wp_get_attachment_image_url($field_builder_section_simple_text_row_bg_image,'full')); ?>);<?php endif; ?>"
                                 id="simple-text<?php if ($simple_text_row_section_counter > 1): echo esc_attr('-' . $simple_text_row_section_counter); endif; ?>">

                            <?php if ($field_builder_section_simple_text_row_type_of_bg === 'image'): ?>

                                <span class="overlay"
                                      style="opacity: <?php echo esc_attr($field_builder_section_simple_text_row_bg_image_overlay); ?>"></span>

                            <?php endif; ?>

                            <?php if ($field_builder_section_simple_text_row_type_of_bg === 'gradient'): ?>

                                <span class="overlay"
                                      style="background:linear-gradient(45deg, <?php echo esc_attr($field_builder_section_simple_text_row_bg_gradient_color_1); ?>, <?php echo esc_attr($field_builder_section_simple_text_row_bg_gradient_color_2); ?>);"></span>

                            <?php endif; ?>

                            <?php $simple_text_row_section_counter++; ?>

                            <div class="neo-container inner-wrapper">

                                <h5 class="section-sub-headline"
                                    style="color: <?php echo esc_attr($field_builder_section_simple_text_row_sub_headline_color); ?>; text-align: <?php echo esc_attr($field_builder_section_simple_text_row_sub_headline_alignment); ?>">

                                    <?php echo esc_html($field_builder_section_simple_text_row_sub_headline); ?>

                                </h5>

                                <h2 class="section-simple-title"
                                    style="color: <?php echo esc_attr($field_builder_section_simple_text_row_main_headline_color); ?>; text-align: <?php echo esc_attr($field_builder_section_simple_text_row_main_headline_alignment); ?>">

                                    <?php echo esc_html($field_builder_section_simple_text_row_main_headline); ?>

                                </h2>

                                <p class="section-body-main"
                                   style="color: <?php echo esc_attr($field_builder_section_simple_text_row_body_color); ?>; text-align: <?php echo esc_attr($field_builder_section_simple_text_row_body_alignment); ?>">

                                    <?php echo esc_html($field_builder_section_simple_text_row_body); ?>

                                </p>

                                <?php if (have_rows('field_builder_section_simple_text_row_content_rows')): ?>

                                    <?php while (have_rows('field_builder_section_simple_text_row_content_rows')) : the_row(); ?>

                                        <?php

                                        $field_builder_section_content_rows_simple_text_row_heading = self::get_sub_field('field_builder_section_content_rows_simple_text_row_heading');

                                        $field_builder_section_content_rows_simple_text_row_heading_color = self::get_sub_field('field_builder_section_content_rows_simple_text_row_heading_color');

                                        $field_builder_section_content_rows_simple_text_row_box_content = self::get_sub_field('field_builder_section_content_rows_simple_text_row_box_content');

                                        $field_builder_section_content_rows_simple_text_row_body_color = self::get_sub_field('field_builder_section_content_rows_simple_text_row_body_color');

                                        $field_builder_section_content_rows_simple_text_row_heading_alignment = self::get_sub_field('field_builder_section_content_rows_simple_text_row_heading_alignment');

                                        $field_builder_section_content_rows_simple_text_row_box_content_alignment = self::get_sub_field('field_builder_section_content_rows_simple_text_row_box_content_alignment');

                                        ?>

                                        <h5 class="section-sub-headline"
                                            style="color: <?php echo esc_attr($field_builder_section_content_rows_simple_text_row_heading_color); ?>; text-align: <?php echo esc_attr($field_builder_section_content_rows_simple_text_row_heading_alignment); ?>">

                                            <?php echo esc_html($field_builder_section_content_rows_simple_text_row_heading); ?>

                                        </h5>

                                        <p class="section-body"
                                           style="color: <?php echo esc_attr($field_builder_section_content_rows_simple_text_row_body_color); ?>; text-align: <?php echo esc_attr($field_builder_section_content_rows_simple_text_row_box_content_alignment); ?>">

                                            <?php echo esc_html($field_builder_section_content_rows_simple_text_row_box_content); ?>

                                        </p>

                                    <?php endwhile; ?>

                                <?php endif; ?>

                                <?php if ($field_repeater_builder_simple_text_row_email_form_toggle): ?>

                                    <form class="newsletter-form" id="newsform"
                                          data-subject="<?php echo esc_attr($field_repeater_builder_simple_text_row_email_form_subject_line); ?>"
                                          data-post-url="<?php echo get_the_permalink(); ?>"
                                          data-email="<?php echo esc_attr($field_repeater_builder_simple_text_row_email_form); ?>">

                                        <input type="email" class="email-field" name="email-field"
                                               placeholder="<?php echo esc_attr('E-Mail Address'); ?>">

                                        <button type="submit" class="submit-button button">

                                            <?php echo esc_html('Submit'); ?>

                                        </button>

                                        <div id="submit-ajax-news">

                                        </div>

                                    </form>

                                <?php endif; ?>

                            </div>

                        </section>

                    <?php endif;

                endwhile;

            endif;

            ?>

            <footer class="neo-footer" id="footer">

                <div class="neo-container">

                    <div class="left-side">

                        <?php

                        if (have_rows('field_footer_social_list')):

                            ?>

                            <ul class="social-list">

                                <?php

                                while (have_rows('field_footer_social_list')) : the_row();

                                    $field_builder_section_footer_social_list_icon = self::get_sub_field('field_builder_section_footer_social_list_icon');

                                    $field_builder_section_footer_social_list_url = self::get_sub_field('field_builder_section_footer_social_list_url');

                                    ?>

                                    <li>

                                        <a href="<?php echo esc_url($field_builder_section_footer_social_list_url); ?>">

                                            <i class="<?php echo esc_attr($field_builder_section_footer_social_list_icon); ?>"></i>

                                        </a>

                                    </li>

                                <?php

                                endwhile;

                                ?>

                            </ul>

                        <?php

                        endif;

                        ?>

                        <div class="terms">

                            <?php

                            $company_name = self::get_field('field_footer_company_name') ? self::get_field('field_footer_company_name') : 'Lorem Company';

                            ?>

                            <a href="https://swellstartups.com/">

                                LaunchPad powered by Groundswell.

                            </a>

                            Terms of use: The products/services offered on this Landing page are
                            through <?php echo esc_attr($company_name); ?>. Groundswell assumes no liability or will
                            provide customer service for this product/service.

                            <p class="copyright">

                                Privacy Policy. Copyright @<?php echo esc_attr($company_name); ?>

                            </p>

                        </div>

                    </div>

                    <div class="right-side">

                        <div class="contact-info">

                            <h6 class="contact-title">

                                <?php echo esc_html('Contact Us'); ?>

                            </h6>

                            <div class="contact-item">

                                <p class="contact-item-name">

                                    <?php echo esc_html('Contact'); ?>

                                </p>

                                <a href="#" class="contact-item-value">

                                    <?php echo esc_html('email@company.com'); ?>

                                </a>

                            </div>

                            <div class="contact-item">

                                <p class="contact-item-name">

                                    <?php echo esc_html('Phone'); ?>

                                </p>

                                <a href="#" class="contact-item-value">

                                    <?php echo esc_html('123 123 123 123'); ?>

                                </a>

                            </div>

                        </div>

                        <div class="logotype-wrapper">

                            <?php

                            $company_logo = self::get_field('field_footer_logotype_image') ? self::get_field('field_footer_logotype_image') : 'http://via.placeholder.com/350x350';

                            ?>

                            <img src="<?php echo esc_url(wp_get_attachment_url($company_logo)) ?>"
                                 alt="<?php echo esc_attr('Logotype'); ?>">

                        </div>

                    </div>

                </div>

            </footer>

        </div>

        <?php

    }

    /**
     *
     */
    public function ajax_form()
    {

        $name = $_REQUEST['name-field'];

        $email = $_REQUEST['email-field'];

        $tel = $_REQUEST['phone-field'];

        $thm = $_POST['subject'];

        $mail_to = $_POST['email'];

        $post_url = $_POST['post_url'];

        $msg = "
        
        Name: " . $name . "
        
        Email: " . $email . "
        
        Phone: " . $tel . "
        
        Note this email came from your web page:" . $post_url;

        $headers = 'From: launchpad.swellstartups.com' . "\r\n";

        $headers .= "MIME-Version: 1.0\r\n";

        $headers .= "Content-type: text/html\r\n";

        if (wp_mail($mail_to, $thm, $msg)) :

            $response = "Thank you, we'll be in touch message";

        else:

            $response = 'Error sending';

        endif;

        echo $response;

        die();

    }

    /**
     *
     */
    public function ajax_news_form()
    {

        $email = $_REQUEST['email-field'];

        $thm = $_POST['subject'];

        $mail_to = $_POST['email'];

        $post_url = $_POST['post_url'];

        $msg = "
        
        Email: " . $email . "
        
        Note this email came from your web page:" . $post_url;

        $headers = 'From: launchpad.swellstartups.com' . "\r\n";

        $headers .= "MIME-Version: 1.0\r\n";

        $headers .= "Content-type: text/html\r\n";

        if (wp_mail($mail_to, $thm, $msg)) :

            $response = "Thank you, we'll be in touch message";

        else:

            $response = 'Error sending';

        endif;

        echo $response;

        die();

    }

    /**
     * @param $post_id
     *
     * @return string
     */
    public static function get_social_sharing_url($post_id)
    {

        return urlencode(get_permalink($post_id));

    }

    /**
     * @param $post_id
     *
     * @return string
     */
    public static function get_social_sharing_title($post_id)
    {

        return htmlspecialchars(urlencode(html_entity_decode(get_the_title($post_id), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');

    }

    /**
     * @param $post_id
     *
     * @return string
     */
    public static function get_social_sharing_excerpt($post_id)
    {

        if (empty(get_the_excerpt($post_id))):

            return htmlspecialchars(urlencode(html_entity_decode(wp_trim_words(get_the_content($post_id), 10, ''), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');

        else:

            return htmlspecialchars(urlencode(html_entity_decode(get_the_excerpt($post_id), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');

        endif;

    }

    /**
     * @param $post_id
     *
     * @return mixed
     */
    public static function get_social_sharing_thumbnail($post_id)
    {

        return wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');

    }

    /**
     * @param $post_title
     * @param $post_url
     * @param $post_thumb
     * @param $post_excerpt
     *
     * @return mixed
     */
    public static function get_twitter_share_link($post_title, $post_url, $post_thumb, $post_excerpt)
    {

        return esc_url('https://twitter.com/intent/tweet?text=' . $post_excerpt . '\x20' . $post_url);

    }

    /**
     * @param $post_title
     * @param $post_url
     * @param $post_thumb
     * @param $post_excerpt
     *
     * @return mixed
     */
    public static function get_facebook_share_link($post_title, $post_url, $post_thumb, $post_excerpt)
    {

        return esc_url('https://www.facebook.com/sharer.php?u=' . $post_url);

    }

}

ACF_Builder::instance();


