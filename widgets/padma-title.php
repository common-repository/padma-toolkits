<?php
 
class Element_Toolkits_Padma_Title extends \Elementor\Widget_Base {
 
 
    public function get_name() {
        return 'padma_title';
    }
 
    public function get_title() {
        return __( 'Padma Title', 'padma-toolkits' );
    }
 
    public function get_icon() {
        return 'fa fa-text-width';
    }
 
    public function get_categories() {
        return [ 'padma_addons' ];
    }
 
    protected function _register_controls() {
 
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Title Content', 'padma-toolkits' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
 
        $this->add_control(
            'title',
            [
                'label'   => __( 'Title Text', 'padma-toolkits' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Section Title', 'padma-toolkits' ),
            ]
        );

        $this->add_control(
            'text_align',
            [
                'label' => __( 'Slider Content Alignment', 'padma-toolkits' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Text Left', 'padma-toolkits' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Text Center', 'padma-toolkits' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Text Right', 'padma-toolkits' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
            ]
        );
        

        $this->end_controls_section();
    }
 
    protected function render() {
    $settings = $this->get_settings_for_display(); 
    ?>
        <section class="section-title text-<?php echo esc_attr($settings['text_align'] ); ?>">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="line-animations">
                            <div class="line-bg"></div>
                        </div>
                        <h2><?php echo esc_html( $settings['title'] ); ?></h2>
                        <div class="line-animations2">
                            <div class="line-bg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<?php 
    }
}