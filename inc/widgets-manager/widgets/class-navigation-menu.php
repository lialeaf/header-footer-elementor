<?php
/**
 * Elementor Classes.
 *
 * @package header-footer-elementor
 */

namespace HFE\WidgetsManager\Widgets;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Nav Menu.
 */
class Navigation_Menu extends Widget_Base {


	/**
	 * Menu index.
	 *
	 * @access protected
	 * @var $nav_menu_index
	 */
	protected $nav_menu_index = 1;

	/**
	 * Retrieve the widget name.
	 *
	 * @since x.x.x
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'navigation-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since x.x.x
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Navigation Menu', 'header-footer-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since x.x.x
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'hfe-icon-navigation-menu-4';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since x.x.x
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'hfe-widgets' ];
	}

	/**
	 * Retrieve the list of scripts the navigation menu depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since x.x.x
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'hfe-nav-menu' ];
	}

	/**
	 * Retrieve the menu index.
	 *
	 * Used to get index of nav menu.
	 *
	 * @since x.x.x
	 * @access protected
	 *
	 * @return string nav index.
	 */
	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	/**
	 * Retrieve the list of available menus.
	 *
	 * Used to get the list of available menus.
	 *
	 * @since x.x.x
	 * @access private
	 *
	 * @return array get WordPress menus list.
	 */
	private function get_available_menus() {

		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Check if the Elementor is updated.
	 *
	 * @since x.x.x
	 *
	 * @return boolean if Elementor updated.
	 */
	public static function is_elementor_updated() {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Register Nav Menu controls.
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_general_content_controls();
		$this->register_style_content_controls();
		$this->register_dropdown_content_controls();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_menu',
			[
				'label' => __( 'Menu', 'header-footer-elementor' ),
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'        => __( 'Menu', 'header-footer-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'separator'    => 'after',
					/* translators: %s Nav menu URL */
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'header-footer-elementor' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s Nav menu URL */
					'raw'             => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'header-footer-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->end_controls_section();

			$this->start_controls_section(
				'section_layout',
				[
					'label' => __( 'Layout', 'header-footer-elementor' ),
				]
			);

			$this->add_control(
				'layout',
				[
					'label'   => __( 'Layout', 'header-footer-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => [
						'horizontal' => __( 'Horizontal', 'header-footer-elementor' ),
						'vertical'   => __( 'Vertical', 'header-footer-elementor' ),
						'expandible' => __( 'Expanded', 'header-footer-elementor' ),
					],
				]
			);

			$this->add_control(
				'navmenu_align',
				[
					'label'        => __( 'Alignment', 'header-footer-elementor' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => [
						'left'    => [
							'title' => __( 'Left', 'header-footer-elementor' ),
							'icon'  => 'eicon-h-align-left',
						],
						'center'  => [
							'title' => __( 'Center', 'header-footer-elementor' ),
							'icon'  => 'eicon-h-align-center',
						],
						'right'   => [
							'title' => __( 'Right', 'header-footer-elementor' ),
							'icon'  => 'eicon-h-align-right',
						],
						'justify' => [
							'title' => __( 'Justify', 'header-footer-elementor' ),
							'icon'  => 'eicon-h-align-stretch',
						],
					],
					'default'      => 'left',
					'condition'    => [
						'layout' => [ 'horizontal', 'vertical' ],
					],
					'prefix_class' => 'hfe-nav-menu__align-',
					'render_type'  => 'template',
				]
			);

			$this->add_responsive_control(
				'hamburger_align',
				[
					'label'                => __( 'Hamburger Align', 'uael' ),
					'type'                 => Controls_Manager::CHOOSE,
					'default'              => 'center',
					'options'              => [
						'left'   => [
							'title' => __( 'Left', 'uael' ),
							'icon'  => 'eicon-h-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'uael' ),
							'icon'  => 'eicon-h-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'uael' ),
							'icon'  => 'eicon-h-align-right',
						],
					],
					'selectors_dictionary' => [
						'left'   => 'margin-right: auto',
						'center' => 'margin: 0 auto',
						'right'  => 'margin-left: auto',
					],
					'selectors'            => [
						'{{WRAPPER}} .hfe-nav-menu__toggle' => '{{VALUE}}',
					],
					'default'              => 'center',
					'condition'            => [
						'layout' => [ 'expandible' ],
					],
					'label_block'          => false,
				]
			);

			$this->add_responsive_control(
				'hamburger_menu_align',
				[
					'label'        => __( 'Menu Items Align', 'header-footer-elementor' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => [
						'flex-start'    => [
							'title' => __( 'Left', 'header-footer-elementor' ),
							'icon'  => 'eicon-h-align-left',
						],
						'center'        => [
							'title' => __( 'Center', 'header-footer-elementor' ),
							'icon'  => 'eicon-h-align-center',
						],
						'flex-end'      => [
							'title' => __( 'Right', 'header-footer-elementor' ),
							'icon'  => 'eicon-h-align-right',
						],
						'space-between' => [
							'title' => __( 'Justify', 'header-footer-elementor' ),
							'icon'  => 'eicon-h-align-stretch',
						],
					],
					'default'      => 'space-between',
					'condition'    => [
						'layout' => 'expandible',
					],
					'selectors'    => [
						'{{WRAPPER}} li.menu-item a' => 'justify-content: {{VALUE}};',
					],
					'prefix_class' => 'hfe-menu-item-',
				]
			);

			$this->add_control(
				'submenu_icon',
				[
					'label'        => __( 'Submenu Icon', 'header-footer-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'arrow',
					'options'      => [
						'arrow'   => __( 'Arrows', 'header-footer-elementor' ),
						'plus'    => __( 'Plus Sign', 'header-footer-elementor' ),
						'classic' => __( 'Classic', 'header-footer-elementor' ),
					],
					'prefix_class' => 'hfe-submenu-icon-',
				]
			);

			$this->add_control(
				'submenu_animation',
				[
					'label'        => __( 'Submenu Animation', 'header-footer-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'none',
					'options'      => [
						'none'     => __( 'Default', 'header-footer-elementor' ),
						'slide_up' => __( 'Slide Up', 'header-footer-elementor' ),
					],
					'prefix_class' => 'hfe-submenu-animation-',
					'condition'    => [
						'layout' => 'horizontal',
					],
				]
			);

			$this->add_control(
				'heading_responsive',
				[
					'type'      => Controls_Manager::HEADING,
					'label'     => __( 'Responsive', 'header-footer-elementor' ),
					'separator' => 'before',
					'condition' => [
						'layout' => [ 'horizontal', 'vertical' ],
					],
				]
			);

		$this->add_control(
			'dropdown',
			[
				'label'        => __( 'Breakpoint', 'header-footer-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'tablet',
				'options'      => [
					'mobile' => __( 'Mobile (767px >)', 'header-footer-elementor' ),
					'tablet' => __( 'Tablet (1023px >)', 'header-footer-elementor' ),
					'none'   => __( 'None', 'header-footer-elementor' ),
				],
				'prefix_class' => 'hfe-nav-menu__breakpoint-',
				'condition'    => [
					'layout' => [ 'horizontal', 'vertical' ],
				],
				'render_type'  => 'template',
			]
		);

		$this->add_responsive_control(
			'resp_align',
			[
				'label'                => __( 'Alignment', 'header-footer-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'header-footer-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'header-footer-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'header-footer-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'              => 'center',
				'description'          => __( 'This is the alignement of menu icon on selected responsive breakpoints.', 'header-footer-elementor' ),
				'condition'            => [
					'layout'    => [ 'horizontal', 'vertical' ],
					'dropdown!' => 'none',
				],
				'selectors_dictionary' => [
					'left'   => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right'  => 'margin-left: auto',
				],
				'selectors'            => [
					'{{WRAPPER}} .hfe-nav-menu__toggle' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'full_width_dropdown',
			[
				'label'        => __( 'Full Width', 'header-footer-elementor' ),
				'description'  => __( 'Enable this option to stretch the Sub Menu to Full Width.', 'header-footer-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'header-footer-elementor' ),
				'label_off'    => __( 'No', 'header-footer-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'dropdown!' => 'none',
				],
				'render_type'  => 'template',
			]
		);

		if ( $this->is_elementor_updated() ) {
			$this->add_control(
				'dropdown_icon',
				[
					'label'       => __( 'Menu Icon', 'header-footer-elementor' ),
					'type'        => Controls_Manager::ICONS,
					'label_block' => 'true',
					'default'     => [
						'value'   => 'fas fa-align-justify',
						'library' => 'fa-solid',
					],
					'condition'   => [
						'dropdown!' => 'none',
					],
				]
			);
		} else {
			$this->add_control(
				'dropdown_icon',
				[
					'label'       => __( 'Icon', 'header-footer-elementor' ),
					'type'        => Controls_Manager::ICON,
					'label_block' => 'true',
					'default'     => 'fa fa-align-justify',
					'condition'   => [
						'dropdown!' => 'none',
					],
				]
			);
		}

		if ( $this->is_elementor_updated() ) {
			$this->add_control(
				'dropdown_close_icon',
				[
					'label'       => __( 'Close Icon', 'header-footer-elementor' ),
					'type'        => Controls_Manager::ICONS,
					'label_block' => 'true',
					'default'     => [
						'value'   => 'far fa-window-close',
						'library' => 'fa-regular',
					],
					'condition'   => [
						'dropdown!' => 'none',
					],
				]
			);
		} else {
			$this->add_control(
				'dropdown_close_icon',
				[
					'label'       => __( 'Close Icon', 'header-footer-elementor' ),
					'type'        => Controls_Manager::ICON,
					'label_block' => 'true',
					'default'     => 'fa fa-close',
					'condition'   => [
						'dropdown!' => 'none',
					],
				]
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function register_style_content_controls() {

		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label'     => __( 'Main Menu', 'header-footer-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout!' => 'expandible',
				],
			]
		);

			$this->add_responsive_control(
				'padding_horizontal_menu_item',
				[
					'label'       => __( 'Horizontal Padding', 'header-footer-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'range'       => [
						'px' => [
							'max' => 50,
						],
					],
					'default'     => [
						'size' => 15,
						'unit' => 'px',
					],
					'selectors'   => [
						'{{WRAPPER}} .menu-item a.hfe-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .menu-item a.hfe-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 20px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .hfe-nav-menu__layout-vertical .menu-item ul ul a.hfe-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 40px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .hfe-nav-menu__layout-vertical .menu-item ul ul ul a.hfe-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 60px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .hfe-nav-menu__layout-vertical .menu-item ul ul ul ul a.hfe-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 80px );padding-right: {{SIZE}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_responsive_control(
				'padding_vertical_menu_item',
				[
					'label'       => __( 'Vertical Padding', 'header-footer-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'range'       => [
						'px' => [
							'max' => 50,
						],
					],
					'default'     => [
						'size' => 15,
						'unit' => 'px',
					],
					'selectors'   => [
						'{{WRAPPER}} .menu-item a.hfe-menu-item, {{WRAPPER}} .menu-item a.hfe-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_responsive_control(
				'menu_space_between',
				[
					'label'       => __( 'Space Between', 'header-footer-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'range'       => [
						'px' => [
							'max' => 100,
						],
					],
					'selectors'   => [
						'body:not(.rtl) {{WRAPPER}} .hfe-nav-menu__layout-horizontal .hfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
						'body.rtl {{WRAPPER}} .hfe-nav-menu__layout-horizontal .hfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} nav:not(.hfe-nav-menu__layout-horizontal) .hfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-bottom: 0',
						'(tablet)body:not(.rtl) {{WRAPPER}}.hfe-nav-menu__breakpoint-tablet .hfe-nav-menu__layout-horizontal .hfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: 0px',
						'(mobile)body:not(.rtl) {{WRAPPER}}.hfe-nav-menu__breakpoint-mobile .hfe-nav-menu__layout-horizontal .hfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: 0px',
					],
					'render_type' => 'template',
					'condition'   => [
						'layout' => 'horizontal',
					],
				]
			);

			$this->add_responsive_control(
				'menu_row_space',
				[
					'label'       => __( 'Row Spacing', 'header-footer-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'range'       => [
						'px' => [
							'max' => 100,
						],
					],
					'selectors'   => [
						'body:not(.rtl) {{WRAPPER}} .hfe-nav-menu__layout-horizontal .hfe-nav-menu > li.menu-item' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					],
					'condition'   => [
						'layout' => 'horizontal',
					],
					'render_type' => 'template',
				]
			);

			$this->add_control(
				'pointer',
				[
					'label'     => __( 'Link Hover Effect', 'header-footer-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'none',
					'options'   => [
						'none'        => __( 'None', 'header-footer-elementor' ),
						'underline'   => __( 'Underline', 'header-footer-elementor' ),
						'overline'    => __( 'Overline', 'header-footer-elementor' ),
						'double-line' => __( 'Double Line', 'header-footer-elementor' ),
						'framed'      => __( 'Framed', 'header-footer-elementor' ),
						'text'        => __( 'Text', 'header-footer-elementor' ),
					],
					'condition' => [
						'layout' => [ 'horizontal' ],
					],
				]
			);

		$this->add_control(
			'animation_line',
			[
				'label'     => __( 'Animation', 'header-footer-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => [
					'fade'     => 'Fade',
					'slide'    => 'Slide',
					'grow'     => 'Grow',
					'drop-in'  => 'Drop In',
					'drop-out' => 'Drop Out',
					'none'     => 'None',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => [ 'underline', 'overline', 'double-line' ],
				],
			]
		);

		$this->add_control(
			'animation_framed',
			[
				'label'     => __( 'Frame Animation', 'header-footer-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => [
					'fade'    => 'Fade',
					'grow'    => 'Grow',
					'shrink'  => 'Shrink',
					'draw'    => 'Draw',
					'corners' => 'Corners',
					'none'    => 'None',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => 'framed',
				],
			]
		);

		$this->add_control(
			'animation_text',
			[
				'label'     => __( 'Animation', 'header-footer-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'grow',
				'options'   => [
					'grow'   => 'Grow',
					'shrink' => 'Shrink',
					'sink'   => 'Sink',
					'float'  => 'Float',
					'skew'   => 'Skew',
					'rotate' => 'Rotate',
					'none'   => 'None',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => 'text',
				],
			]
		);

		$this->add_control(
			'style_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'menu_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} a.hfe-menu-item, {{WRAPPER}} a.hfe-sub-menu-item',
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

				$this->start_controls_tab(
					'tab_menu_item_normal',
					[
						'label' => __( 'Normal', 'header-footer-elementor' ),
					]
				);

					$this->add_control(
						'color_menu_item',
						[
							'label'     => __( 'Text Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_3,
							],
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfe-menu-item, {{WRAPPER}} .sub-menu a.hfe-sub-menu-item' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_menu_item',
						[
							'label'     => __( 'Background Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfe-menu-item, {{WRAPPER}} .sub-menu, {{WRAPPER}} nav.hfe-dropdown, {{WRAPPER}} .hfe-dropdown-expandible' => 'background-color: {{VALUE}}',
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_hover',
					[
						'label' => __( 'Hover', 'header-footer-elementor' ),
					]
				);

					$this->add_control(
						'color_menu_item_hover',
						[
							'label'     => __( 'Text Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfe-menu-item:hover,
								{{WRAPPER}} .sub-menu a:hover,
								{{WRAPPER}} .menu-item.current-menu-item a.hfe-menu-item,
								{{WRAPPER}} .menu-item a.hfe-menu-item.highlighted,
								{{WRAPPER}} .menu-item a.hfe-menu-item:focus' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_menu_item_hover',
						[
							'label'     => __( 'Background Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfe-menu-item:hover,
								{{WRAPPER}} .sub-menu a:hover,
								{{WRAPPER}} .menu-item.current-menu-item a.hfe-menu-item,
								{{WRAPPER}} .menu-item a.hfe-menu-item.highlighted,
								{{WRAPPER}} .menu-item a.hfe-menu-item:focus' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'pointer_color_menu_item_hover',
						[
							'label'     => __( 'Link Hover Effect Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .hfe-nav-menu-layout:not(.hfe-pointer__framed) .menu-item.parent a.hfe-menu-item:before,
								{{WRAPPER}} .hfe-nav-menu-layout:not(.hfe-pointer__framed) .menu-item.parent a.hfe-menu-item:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .hfe-nav-menu-layout:not(.hfe-pointer__framed) .menu-item.parent .sub-menu .hfe-has-submenu-container a:after' => 'background-color: unset',
								'{{WRAPPER}} .hfe-pointer__framed .menu-item.parent a.hfe-menu-item:before,
								{{WRAPPER}} .hfe-pointer__framed .menu-item.parent a.hfe-menu-item:after' => 'border-color: {{VALUE}}',
							],
							'condition' => [
								'pointer!' => [ 'none', 'text' ],
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_active',
					[
						'label' => __( 'Active', 'header-footer-elementor' ),
					]
				);

					$this->add_control(
						'color_menu_item_active',
						[
							'label'     => __( 'Text Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item.current-menu-item a.hfe-menu-item' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_menu_item_active',
						[
							'label'     => __( 'Background Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item.current-menu-item a.hfe-menu-item' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'pointer_color_menu_item_active',
						[
							'label'     => __( 'Link Hover Effect Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .hfe-nav-menu:not(.hfe-pointer__framed) .menu-item.parent.current-menu-item a.hfe-menu-item:before,
								{{WRAPPER}} .hfe-nav-menu:not(.hfe-pointer__framed) .menu-item.parent.current-menu-item a.hfe-menu-item:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .hfe-nav-menu:not(.hfe-pointer__framed) .menu-item.parent .sub-menu .hfe-has-submenu-container a.current-menu-item:after' => 'background-color: unset',
								'{{WRAPPER}} .hfe-pointer__framed .menu-item.parent.current-menu-item a.hfe-menu-item:before,
								{{WRAPPER}} .hfe-pointer__framed .menu-item.parent.current-menu-item a.hfe-menu-item:after' => 'border-color: {{VALUE}}',
							],
							'condition' => [
								'pointer!' => [ 'none', 'text' ],
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function register_dropdown_content_controls() {

		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => __( 'Dropdown', 'header-footer-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'dropdown_description',
				[
					'raw'             => __( '<b>Note:</b> On desktop, below style options will apply to the submenu. On mobile, this will apply to the entire menu.', 'header-footer-elementor' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-descriptor',
					'condition'       => [
						'layout!' => [
							'expandible',
						],
					],
				]
			);

			$this->start_controls_tabs( 'tabs_dropdown_item_style' );

				$this->start_controls_tab(
					'tab_dropdown_item_normal',
					[
						'label' => __( 'Normal', 'header-footer-elementor' ),
					]
				);

					$this->add_control(
						'color_dropdown_item',
						[
							'label'     => __( 'Text Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .sub-menu a.hfe-sub-menu-item, 
								{{WRAPPER}} .elementor-menu-toggle,
								{{WRAPPER}} nav.hfe-dropdown li a.hfe-menu-item,
								{{WRAPPER}} nav.hfe-dropdown li a.hfe-sub-menu-item,
								{{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-menu-item,
								{{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-sub-menu-item' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'background_color_dropdown_item',
						[
							'label'     => __( 'Background Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#fff',
							'selectors' => [
								'{{WRAPPER}} .sub-menu,
								{{WRAPPER}} nav.hfe-dropdown,
								{{WRAPPER}} nav.hfe-dropdown-expandible,
								{{WRAPPER}} nav.hfe-dropdown .menu-item a.hfe-menu-item,
								{{WRAPPER}} nav.hfe-dropdown .menu-item a.hfe-sub-menu-item' => 'background-color: {{VALUE}}',
							],
							'separator' => 'none',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_hover',
					[
						'label' => __( 'Hover', 'header-footer-elementor' ),
					]
				);

					$this->add_control(
						'color_dropdown_item_hover',
						[
							'label'     => __( 'Text Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .sub-menu a.hfe-sub-menu-item:hover, 
								{{WRAPPER}} .elementor-menu-toggle:hover,
								{{WRAPPER}} nav.hfe-dropdown li a.hfe-menu-item:hover,
								{{WRAPPER}} nav.hfe-dropdown li a.hfe-sub-menu-item:hover,
								{{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-menu-item:hover,
								{{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-sub-menu-item:hover' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'background_color_dropdown_item_hover',
						[
							'label'     => __( 'Background Color', 'header-footer-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .sub-menu a.hfe-sub-menu-item:hover,
								{{WRAPPER}} nav.hfe-dropdown li a.hfe-menu-item:hover,
								{{WRAPPER}} nav.hfe-dropdown li a.hfe-sub-menu-item:hover,
								{{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-menu-item:hover,
								{{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-sub-menu-item:hover' => 'background-color: {{VALUE}}',
							],
							'separator' => 'none',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'dropdown_typography',
					'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
					'separator' => 'before',
					'selector'  => '
							{{WRAPPER}} .sub-menu li a.hfe-sub-menu-item,
							{{WRAPPER}} nav.hfe-dropdown li a.hfe-sub-menu-item,
							{{WRAPPER}} nav.hfe-dropdown li a.hfe-menu-item,
							{{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-menu-item,
							{{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-sub-menu-item',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'dropdown_border',
					'selector' => '{{WRAPPER}} nav.hfe-nav-menu__layout-horizontal .sub-menu, 
							{{WRAPPER}} nav:not(.hfe-nav-menu__layout-horizontal) .sub-menu.sub-menu-open,
							{{WRAPPER}} nav.hfe-dropdown,
						 	{{WRAPPER}} nav.hfe-dropdown-expandible',
				]
			);

			$this->add_responsive_control(
				'dropdown_border_radius',
				[
					'label'      => __( 'Border Radius', 'header-footer-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .sub-menu'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .sub-menu li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden;',
						'{{WRAPPER}} .sub-menu li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.hfe-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} nav.hfe-dropdown li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.hfe-dropdown li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.hfe-dropdown-expandible' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} nav.hfe-dropdown-expandible li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.hfe-dropdown-expandible li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'      => 'dropdown_box_shadow',
					'exclude'   => [
						'box_shadow_position',
					],
					'selector'  => '{{WRAPPER}} .hfe-nav-menu .sub-menu,
								{{WRAPPER}} nav.hfe-dropdown,
						 		{{WRAPPER}} nav.hfe-dropdown-expandible',
					'separator' => 'after',
				]
			);

			$this->add_responsive_control(
				'width_dropdown_item',
				[
					'label'       => __( 'Dropdown Width (px)', 'header-footer-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min' => 0,
							'max' => 500,
						],
					],
					'default'     => [
						'size' => '220',
						'unit' => 'px',
					],
					'selectors'   => [
						'{{WRAPPER}} ul.sub-menu' => 'width: {{SIZE}}{{UNIT}}',
					],
					'condition'   => [
						'layout' => 'horizontal',
					],
					'render_type' => 'template',
				]
			);

			$this->add_responsive_control(
				'padding_horizontal_dropdown_item',
				[
					'label'       => __( 'Horizontal Padding', 'header-footer-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'default'     => [
						'size' => 15,
						'unit' => 'px',
					],
					'selectors'   => [
						'{{WRAPPER}} .sub-menu li a.hfe-sub-menu-item,
						{{WRAPPER}} nav.hfe-dropdown li a.hfe-menu-item,
						{{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} nav.hfe-dropdown-expandible a.hfe-sub-menu-item,
						{{WRAPPER}} nav.hfe-dropdown li a.hfe-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 20px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .hfe-dropdown .menu-item ul ul a.hfe-sub-menu-item,
						{{WRAPPER}} .hfe-dropdown-expandible .menu-item ul ul a.hfe-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 40px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .hfe-dropdown .menu-item ul ul ul a.hfe-sub-menu-item,
						{{WRAPPER}} .hfe-dropdown-expandible .menu-item ul ul ul a.hfe-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 60px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .hfe-dropdown .menu-item ul ul ul ul a.hfe-sub-menu-item,
						{{WRAPPER}} .hfe-dropdown-expandible .menu-item ul ul ul ul a.hfe-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 80px );padding-right: {{SIZE}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_responsive_control(
				'padding_vertical_dropdown_item',
				[
					'label'       => __( 'Vertical Padding', 'header-footer-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'default'     => [
						'size' => 15,
						'unit' => 'px',
					],
					'range'       => [
						'px' => [
							'max' => 50,
						],
					],
					'selectors'   => [
						'{{WRAPPER}} .sub-menu a.hfe-sub-menu-item,
						 {{WRAPPER}} nav.hfe-dropdown li a.hfe-menu-item,
						 {{WRAPPER}} nav.hfe-dropdown li a.hfe-sub-menu-item,
						 {{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-menu-item,
						 {{WRAPPER}} nav.hfe-dropdown-expandible li a.hfe-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
					],
					'render_type' => 'template',
				]
			);

			$this->add_responsive_control(
				'distance_from_menu',
				[
					'label'       => __( 'Top Distance', 'header-footer-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors'   => [
						'{{WRAPPER}} nav.hfe-nav-menu__layout-horizontal ul.sub-menu, {{WRAPPER}} nav.hfe-nav-menu__layout-expandible.menu-is-active' => 'margin-top: {{SIZE}}px;',
						'{{WRAPPER}} .hfe-dropdown.menu-is-active' => 'margin-top: {{SIZE}}px;',
					],
					'condition'   => [
						'layout' => [ 'horizontal', 'vertical', 'expandible' ],
					],
					'render_type' => 'template',
				]
			);

			$this->add_control(
				'heading_dropdown_divider',
				[
					'label'     => __( 'Divider', 'header-footer-elementor' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'dropdown_divider_border',
				[
					'label'       => __( 'Border Style', 'header-footer-elementor' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'solid',
					'label_block' => false,
					'options'     => [
						'none'   => __( 'None', 'header-footer-elementor' ),
						'solid'  => __( 'Solid', 'header-footer-elementor' ),
						'double' => __( 'Double', 'header-footer-elementor' ),
						'dotted' => __( 'Dotted', 'header-footer-elementor' ),
						'dashed' => __( 'Dashed', 'header-footer-elementor' ),
					],
					'selectors'   => [
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child), 
						{{WRAPPER}} nav.hfe-dropdown li.menu-item:not(:last-child),
						{{WRAPPER}} nav.hfe-dropdown-expandible li.menu-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'divider_border_color',
				[
					'label'     => __( 'Border Color', 'header-footer-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#c4c4c4',
					'selectors' => [
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child), 
						{{WRAPPER}} nav.hfe-dropdown li.menu-item:not(:last-child),
						{{WRAPPER}} nav.hfe-dropdown-expandible li.menu-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);

			$this->add_control(
				'dropdown_divider_width',
				[
					'label'     => __( 'Border Width', 'header-footer-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'default'   => [
						'size' => '1',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child), 
						{{WRAPPER}} nav.hfe-dropdown li.menu-item:not(:last-child),
						{{WRAPPER}} nav.hfe-dropdown-expandible li.menu-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_toggle',
			[
				'label' => __( 'Menu Trigger & Close Icon', 'header-footer-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'toggle_style_normal',
			[
				'label' => __( 'Normal', 'header-footer-elementor' ),
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label'     => __( 'Color', 'header-footer-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} div.hfe-nav-menu-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_background_color',
			[
				'label'     => __( 'Background Color', 'header-footer-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfe-nav-menu-icon' => 'background-color: {{VALUE}}; padding: 0.35em;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_hover',
			[
				'label' => __( 'Hover', 'header-footer-elementor' ),
			]
		);

		$this->add_control(
			'toggle_hover_color',
			[
				'label'     => __( 'Color', 'header-footer-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} div.hfe-nav-menu-icon:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_hover_background_color',
			[
				'label'     => __( 'Background Color', 'header-footer-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfe-nav-menu-icon:hover' => 'background-color: {{VALUE}}; padding: 0.35em;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'toggle_size',
			[
				'label'     => __( 'Icon Size', 'header-footer-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .hfe-nav-menu-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'toggle_border_width',
			[
				'label'     => __( 'Border Width', 'header-footer-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .hfe-nav-menu-icon' => 'border-width: {{SIZE}}{{UNIT}}; padding: 0.35em;',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_border_radius',
			[
				'label'      => __( 'Border Radius', 'header-footer-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .hfe-nav-menu-icon' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Nav Menu output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$args = [
			'echo'        => false,
			'menu'        => $settings['menu'],
			'menu_class'  => 'hfe-nav-menu',
			'menu_id'     => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container'   => '',
			'walker'      => new Menu_Walker,
		];

		$menu_html = wp_nav_menu( $args );

		$this->add_render_attribute(
			'hfe-main-menu',
			'class',
			[
				'hfe-nav-menu',
				'hfe-layout-' . $settings['layout'],
			]
		);

		$this->add_render_attribute( 'hfe-main-menu', 'class', 'hfe-nav-menu-layout' );

		$this->add_render_attribute( 'hfe-main-menu', 'class', $settings['layout'] );

		$this->add_render_attribute( 'hfe-main-menu', 'data-layout', $settings['layout'] );

		if ( $settings['pointer'] ) {
			if ( 'horizontal' === $settings['layout'] || 'vertical' === $settings['layout'] ) {
				$this->add_render_attribute( 'hfe-main-menu', 'class', 'hfe-pointer__' . $settings['pointer'] );

				if ( in_array( $settings['pointer'], [ 'double-line', 'underline', 'overline' ], true ) ) {
					$key = 'animation_line';
					$this->add_render_attribute( 'hfe-main-menu', 'class', 'hfe-animation__' . $settings[ $key ] );
				} elseif ( 'framed' === $settings['pointer'] || 'text' === $settings['pointer'] ) {
					$key = 'animation_' . $settings['pointer'];
					$this->add_render_attribute( 'hfe-main-menu', 'class', 'hfe-animation__' . $settings[ $key ] );
				}
			}
		}

		if ( 'expandible' === $settings['layout'] ) {
			$this->add_render_attribute( 'hfe-nav-menu', 'class', 'hfe-dropdown-expandible' );
		}

		$this->add_render_attribute(
			'hfe-nav-menu',
			'class',
			[
				'hfe-nav-menu__layout-' . $settings['layout'],
				'hfe-nav-menu__submenu-' . $settings['submenu_icon'],
			]
		);

		$this->add_render_attribute( 'hfe-nav-menu', 'data-toggle-icon', $settings['dropdown_icon'] );

		$this->add_render_attribute( 'hfe-nav-menu', 'data-close-icon', $settings['dropdown_close_icon'] );

		$this->add_render_attribute( 'hfe-nav-menu', 'data-full-width', $settings['full_width_dropdown'] );

		?>
	<div <?php echo $this->get_render_attribute_string( 'hfe-main-menu' ); ?>>
		<div class="hfe-nav-menu__toggle elementor-clickable">
			<div class="hfe-nav-menu-icon">
				<?php if ( $this->is_elementor_updated() ) { ?>
					<i class="<?php echo esc_attr( $settings['dropdown_icon']['value'] ); ?>" aria-hidden="true" tabindex="0"></i>
				<?php } else { ?>
					<i class="<?php echo esc_attr( $settings['dropdown_icon'] ); ?>" aria-hidden="true" tabindex="0"></i>
				<?php } ?>
			</div>
		</div>
		<nav <?php echo $this->get_render_attribute_string( 'hfe-nav-menu' ); ?>><?php echo $menu_html; ?></nav>              
	</div>
		<?php
	}
}
