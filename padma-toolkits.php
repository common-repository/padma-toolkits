<?php
/**
* plugin name: Padma Toolkits
* Plugin URI: https://ashathemes.com/
* Description: This plugin will enable blog Features in padma wordpress theme.
* Author: Ashathemes
* Author URI: https://profiles.wordpress.org/ashathemes
* Version: 1.0.2
* License: GPL2
* Text Domain: padma-toolkits
* Domain Path: /languages
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
 
define( 'PADMA_TOOLKITS_PATH', plugin_dir_path( __FILE__ ) );

final class Padma_Toolkits_Extension {
 
    const VERSION = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    const MINIMUM_PHP_VERSION = '5.6';
    private static $_instance = null;
    public static function instance() {
 
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
 
    }
 
    public function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }
 
    public function i18n() {
        load_plugin_textdomain( 'padma-toolkits' );
    }
 
    public function init() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }
 
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }
 
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_new_category' ] );
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_widget_styles' ] );
    }

    public function register_new_category($manager){
        $manager->add_category('padma_addons',[
            'title' => esc_html__('Padma Addons','padma-toolkits'),
            'icon' => 'fa fa-destop',
        ]);
    }

    function editor_widget_styles(){
        wp_enqueue_style( 'padma-editor-style',  plugins_url( '/assets/css/padma-editor-style.css', __FILE__ ), array(), '1.0.0', 'all');
        wp_enqueue_style('font-awesome',plugins_url('/assets/css/font-awesome.min.css',__FILE__), array(), '4.3.0', 'all');
    }


    public function admin_notice_missing_main_plugin() {
 
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
 
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'padma-toolkits' ),
            '<strong>' . esc_html__( 'Elementor Test Extension', 'padma-toolkits' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'padma-toolkits' ) . '</strong>'
        );
 
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
 
    }
 
    public function admin_notice_minimum_elementor_version() {
 
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
 
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'padma-toolkits' ),
            '<strong>' . esc_html__( 'Elementor Test Extension', 'padma-toolkits' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'padma-toolkits' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );
 
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
 
    }
 
    public function admin_notice_minimum_php_version() {
 
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
 
        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'padma-toolkits' ),
            '<strong>' . esc_html__( 'Elementor Test Extension', 'padma-toolkits' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'padma-toolkits' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );
 
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
 
    }

    public function init_widgets() {

        require_once( PADMA_TOOLKITS_PATH . '/widgets/padma-title.php' );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Element_Toolkits_Padma_Title() );

       require_once( PADMA_TOOLKITS_PATH . '/widgets/padma-blog-style.php' );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Element_Toolkits_Padma_Blog_Style_Post() );
    }
 
    public function widget_styles() {
        wp_enqueue_style( 'padma-blog-style', plugins_url( 'widgets/css/padma-blog-style.css', __FILE__ ) );
        wp_enqueue_style( 'padma-title', plugins_url( 'widgets/css/padma-title.css', __FILE__ ) );
    }
}
 
Padma_Toolkits_Extension::instance();




if ( ! function_exists( 'padma_single_post_cat' )) :
    function padma_single_post_cat($id = '') {
        $padma_categories = get_the_category($id);
        if($padma_categories && 'post' === get_post_type()):
            $padma_category = $padma_categories[mt_rand(0,count( $padma_categories)-1)];
        ?>
        <a href="<?php echo esc_url(get_category_link($padma_category)); ?>"><i class="fa fa-folder"></i><?php echo esc_html($padma_category->name); ?></a>

        <?php
        endif;
    }
endif;

if ( ! function_exists( 'padma_toolkits_posted_on' ) ) :
    function padma_toolkits_posted_on() { ;?>
        <span class="posted-on">
            <?php $post_date = get_the_date( 'F j, Y' ); 
            echo esc_html($post_date); ?>
        </span>
<?php }
endif;

function padma_excerpt_more( $more ) {
    return '.';
}
add_filter( 'excerpt_more', 'padma_excerpt_more' );