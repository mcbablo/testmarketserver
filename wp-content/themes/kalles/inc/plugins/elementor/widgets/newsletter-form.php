<?php
/**
 * Newsletter FORM ELEMENTOR WIDGET
 *
 * @since   1.1.3
 * @package Kalles
 */

use Elementor\Controls_Manager;

class Kalles_Elementor_Newsletter_Form_Widget extends \Elementor\Widget_Base {

    public function __construct($data = [], $args = null) {
       parent::__construct($data, $args);
    }

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'kalles-newsletter-form';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Kalles Newsletter', 'kalles' );
	}

	public function get_script_depends() {
		return ['kalles-elementor', 'klaviyo'];
	}

	public function get_style_depends() {
		return [];
	}
	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-mail';
	}

    /**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'mail', 'newsletter', 'klaviyo', 'mailchimp' );
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'kalles-elements' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {

       $this->start_controls_section(
			'newsletter-content',
			array(
				'label' => esc_html__( 'Content', 'kalles' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
		'newsletter-type',
			[
				'label'       => esc_html__( 'Platform Email Marketing', 'kalles' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'klaviyo' 	=> esc_html__( 'Klaviyo', 'kalles' ),
					'mailchimp' 	=> esc_html__( 'Mailchimp', 'kalles' )
				],
				'default'     => 'klaviyo',
			]
		);

		$this->add_control(
		'klaviyo_list_id',
			array(
				'label' => esc_html__( 'Klaviyo list ID', 'kalles' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => ['newsletter-type' => 'klaviyo'],
				'placeholder' => '',
				
             'description' => sprintf(__('<a href="%s" target="_blank">Find a List ID</a>', 'kalles'), esc_url('https://help.klaviyo.com/hc/en-us/articles/115005078647-Find-a-List-ID')),
			)
		);

		$this->add_control(
		'mailchimp_url',
			array(
				'label' => esc_html__( 'MailChimp form action URL', 'kalles' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => '',
				'condition' => ['newsletter-type' => 'mailchimp'],
             'description' => sprintf(__('<a href="%s" target="_blank">Find your MailChimp form action URL</a>', 'kalles'), esc_url('https://wiki.the4.co/?manual_kb=updated-get-mailchimp-action-url')),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'newsletter-design',
			array(
				'label' => esc_html__( 'Style', 'kalles' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style',
			[
				'label'       => esc_html__( 'Newsletter Design', 'kalles' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => [
					'1' => 'Design 1',
					'2' => 'Design 2',
					'3' => 'Design 3',
					'4' => 'Design 4',
					'5' => 'Design 5',
					'6' => 'Design 6'
				],
				'default'     => '1'
			]
		);

		$this->add_control(
			'form-align',
			[
				'label' => esc_html__( 'Content Alignment', 'kalles' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'kalles' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'kalles' ),
						'icon' => 'eicon-h-align-cener',
					],
					'right' => [
						'title' => esc_html__( 'End', 'kalles' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'toggle' => false,
			]
		);

		$this->add_responsive_control(
			'form-width',
			[
				'label'          => esc_html__( 'Content width', 'kalles' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ 'px', '%' ],
				'range'          => [
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 550,
						'max' => 1000,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .mc4wp-form-fields' => 'width: {{SIZE}}{{UNIT}}; max-width: 90%;',
				],
			]
		);
        $this->add_responsive_control(
            'button-width',
            [
                'label'          => esc_html__( 'Button width', 'kalles' ),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units'     => [ 'px', '%' ],
                'range'          => [
                    '%'  => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 500,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .newsletter_se .signup-newsletter-form .submit-btn' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
                ],
            ]
        );
        $this->add_control(
			'border_color',
			[
				'label'     => esc_html__( 'Border color', 'kalles' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .signup-newsletter-form input.input-text' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .signup-newsletter-form' => 'border-color: {{VALUE}}',
				],
				'default'	=> '#222222'
			]
		);

        $this->add_control(
			'input_color',
			[
				'label'     => esc_html__( 'Input color', 'kalles' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .signup-newsletter-form input.input-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .signup-newsletter-form input.input-text::-webkit-input-placeholder' => 'color: {{VALUE}}',
				],
				'default'	=> '#222222'
			]
		);
        $this->add_control(
            'input_bg_color',
            [
                'label'     => esc_html__( 'Input Background color', 'kalles' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .signup-newsletter-form input.input-text' => 'background: {{VALUE}}',
                ],
                'default'	=> 'rgba(255,255,255,0)'
            ]
        );

		$this->add_control(
			'btn_color',
			[
				'label'     => esc_html__( 'Button color', 'kalles' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .signup-newsletter-form .submit-btn' => 'color: {{VALUE}}',
				],
				'default'	=> '#FFFFFF'
			]
		);

		$this->add_control(
			'btn_background_color',
			[
				'label'     => esc_html__( 'Button background', 'kalles' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .signup-newsletter-form .submit-btn' => 'background-color: {{VALUE}}',
				],
				'default'	=> '#222222'
			]
		);

		$this->end_controls_section();

    }

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$platform = isset( $settings['newsletter-type'] ) ? $settings['newsletter-type'] : '';

		?>
		<div class="newsletter_se newl_des_<?php echo esc_attr( $settings[ 'style' ] ); ?> <?php echo esc_attr( $settings[ 'form-align' ] ); ?>"
			data-platform="<?php echo esc_attr($platform); ?>">
			<?php if ( $platform == 'klaviyo' ) :  ?>
				<form class="js_mail_agree mc4wp-form klaviyo_sub_frm pr z_100" 
						action="https://manage.kmail-lists.com/subscriptions/subscribe" 
						data-ajax-submit="https://manage.kmail-lists.com/ajax/subscriptions/subscribe" method="GET">
		        	<input type="hidden" name="g" value="<?php echo esc_attr($settings['klaviyo_list_id']) ?>">
		         <div class="mc4wp-form-fields">
		            <div class="signup-newsletter-form row no-gutters pr oh">
		               <div class="col col_email">
		               	<input type="email" name="email" placeholder="<?php echo esc_attr('Your email adress') ?>"  value="" class="input-text" required="required">
		               </div>
		               <div class="col-auto">
		               	<button type="submit" class="css_add_ld w__100 submit-btn truncate klaviyo_btn pr">
		               		<span><?php esc_html_e('Subcribe', 'kalles'); ?></span>
		               	</button>
		               </div>
		            </div>
		         </div>
		        <div class="mc4wp-response klaviyo_messages">
		          <div class="kalles-message kalles-success dn"><i class="t4_icon_t4-check-circle mr__5"></i><?php esc_html_e('Thanks for subscribing', 'kalles'); ?></div>
		          <div class="kalles-error kalles-message dn"></div>
		        </div>
		      </form>
		   <?php elseif ( $platform == 'mailchimp' ) :  ?>
		   	<?php $mailchimp_action_url = isset($settings['mailchimp_url']) ? $settings['mailchimp_url'] : ''; ?>
		   	<?php if ( $mailchimp_action_url ) : ?>
		   		<?php $mailchimp_action_url = str_replace('/post?u', '/post-json?u', $mailchimp_action_url) ?>
			   	<form role="form" 
			   			action="<?php echo esc_attr($mailchimp_action_url); ?>" 
			   			class="js_mail_agree pr z_100 mc4wp-form nt_ajax_mcsp" method="post">
			         <div class="mc4wp-form-fields">
			            <div class="signup-newsletter-form row no-gutters pr oh">
			               <div class="col col_email">
			               	<input type="email" name="EMAIL" placeholder="<?php echo esc_attr('Your email adress') ?>"  value="" class="input-text" required="required">
			               </div>
			               <div class="col-auto">
			               	<button type="submit" class="css_add_ld w__100 submit-btn truncate klaviyo_btn pr">
			               		<span><?php esc_html_e('Subcribe', 'kalles'); ?></span>
			               	</button>
			               </div>
			            </div>
			         </div>
			        <div class="mc4wp-response">
			          <div class="kalles-message kalles-success dn"><i class="t4_icon_t4-check-circle mr__5"></i><?php esc_html_e('Thanks for subscribing', 'kalles'); ?></div>
			          <div class="kalles-error kalles-message dn"></div>
			        </div>
			      </form>
			   <?php endif; ?>
		   <?php endif;  ?>

	   </div>
      <?php

	}
}
