<?php
namespace EdgeCore\CPT\Shortcodes\Testimonials;

use EdgeCore\Lib;

class Testimonials implements Lib\ShortcodeInterface{
    private $base;

    public function __construct() {
        $this->base = 'edge_testimonials';

        add_action('vc_before_init', array($this, 'vcMap'));
	
	    //Testimonials category filter
	    add_filter( 'vc_autocomplete_edge_testimonials_category_callback', array( &$this, 'testimonialsCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array
	
	    //Testimonials category render
	    add_filter( 'vc_autocomplete_edge_testimonials_category_render', array( &$this, 'testimonialsCategoryAutocompleteRender', ), 10, 1 ); // Get suggestion(find). Must return an array
    }

    /**
     * Returns base for shortcode
     * @return string
     */
    public function getBase() {
        return $this->base;
    }

    /**
     * Maps shortcode to Visual Composer
     */
    public function vcMap() {
        if(function_exists('vc_map')) {
            vc_map( array(
                'name' => esc_html__('Edge Testimonials', 'edge-core'),
                'base' => $this->base,
                'category' => esc_html__('by EDGE', 'edge-core'),
                'icon' => 'icon-wpb-testimonials extended-custom-icon',
                'allowed_container_element' => 'vc_row',
                'params' => array(
	                array(
		                'type'       => 'dropdown',
		                'param_name' => 'type',
		                'heading'    => esc_html__('Type', 'edge-core'),
		                'value'      => array(
			                esc_html__('Standard', 'edge-core') => 'standard',
			                esc_html__('Boxed', 'edge-core') => 'boxed',
		                ),
		                'save_always' => true
	                ),
                    array(
                        'type'       => 'dropdown',
                        'param_name' => 'skin',
                        'heading'    => esc_html__('Skin', 'edge-core'),
                        'value'      => array(
                            esc_html__('Dark', 'edge-core') => 'dark',
                            esc_html__('Light', 'edge-core') => 'light',
                        ),
                        'save_always' => true
                    ),
                    array(
                        'type'        => 'textfield',
                        'param_name'  => 'number',
                        'heading'     => esc_html__('Number', 'edge-core'),
                        'description' => esc_html__('Number of Testimonials', 'edge-core')
                    ),
	                array(
		                'type'       => 'dropdown',
		                'param_name' => 'number_of_visible_items',
		                'heading'    => esc_html__('Number of Visible Items', 'edge-core'),
		                'value'      => array(
			                esc_html__('One', 'edge-core') => '1',
			                esc_html__('Two', 'edge-core') => '2',
			                esc_html__('Three', 'edge-core') => '3',
			                esc_html__('Four', 'edge-core') => '4'
		                ),
		                'save_always' => true,
		                'description' => esc_html__('Number of Testimonials visible at the same time', 'edge-core')
	                ),
                    array(
                        'type'       => 'dropdown',
                        'param_name' => 'position_placement',
                        'heading'    => esc_html__('Author\'s Job Position Placement', 'edge-core'),
                        'value'      => array(
                            esc_html__('Left', 'edge-core') => 'left',
                            esc_html__('Under', 'edge-core') => 'under'
                        ),
                        'save_always' => true,
                        'description' => esc_html__('Placement of Authors position', 'edge-core')
                    ),
                    array(
                        'type'        => 'autocomplete',
                        'param_name'  => 'category',
                        'heading'     => esc_html__('Category', 'edge-core'),
                        'description' => esc_html__('Enter one category slug (leave empty for showing all categories)', 'edge-core')
                    ),
	                array(
		                'type'       => 'colorpicker',
		                'param_name' => 'box_color',
		                'heading'    => esc_html__('Content Box Color', 'edge-core'),
		                'dependency' => Array('element' => 'type', 'value' => 'boxed')
	                ),
	                array(
		                'type'        => 'dropdown',
		                'param_name'  => 'show_navigation_arrows',
		                'heading'     => esc_html__('Show Side Navigation', 'edge-core'),
		                'value'       => array_flip(adorn_edge_get_yes_no_select_array(false, true)),
		                'save_always' => true
	                ),
                    array(
                        'type'        => 'dropdown',
                        'param_name'  => 'show_navigation_dots',
                        'heading'     => esc_html__('Show Bottom Navigation', 'edge-core'),
                        'value'       => array_flip(adorn_edge_get_yes_no_select_array(false, true)),
                        'save_always' => true
                    ),
                    array(
                        'type'        => 'textfield',
                        'param_name'  => 'slider_speed',
                        'heading'     => esc_html__('Slider Speed', 'edge-core'),
                        'description' => esc_html__('Default value is 5000 (ms)', 'edge-core')
                    ),
                    array(
                        'type'        => 'textfield',
                        'param_name'  => 'slider_animation',
                        'heading'     => esc_html__('Slider Slide Animation', 'edge-core'),
                        'description' => esc_html__('Speed of slide animation in milliseconds. Default value is 600.', 'edge-core')
                    )
                )
            ) );
        }
    }

    /**
     * Renders shortcodes HTML
     *
     * @param $atts array of shortcode params
     * @param $content string shortcode content
     * @return string
     */
    public function render($atts, $content = null) {
        $args = array(
	        'type'             => '',
	        'skin'             => '',
            'number'           => '-1',
            'number_of_visible_items' => '3',
            'category'         => '',
            'box_color' => '',
            'show_navigation_arrows' => 'yes',
            'show_navigation_dots'  => 'no',
            'slider_speed'     => '5000',
            'slider_animation' => '600',
            'position_placement' =>''
        );

        $params = shortcode_atts($args, $atts);
        extract($params);

	    $available_type_values = array('standard', 'boxed');
	    if (!isset($params['type']) || !in_array($params['type'], $available_type_values)) {
		    $params['type'] = 'standard';
	    }

	    $holder_classes = $this->getHolderClasses($params);

        $query_args = $this->getQueryParams($params);
        $query_results = new \WP_Query($query_args);

	    $params['box_styles'] = $this->getBoxStyles($params);
        $data_attr = $this->getDataParams($params);
    
        $html = '';
        $html .= '<div class="edge-testimonials-holder ' . $holder_classes . ' clearfix">';
    
            $html .= '<div class="edge-testimonials"' . $data_attr . '>';

            if ($query_results->have_posts()):
                while ($query_results->have_posts()) : $query_results->the_post();
                    $title = get_post_meta(get_the_ID(), 'edge_testimonial_title', true);
                    $text = get_post_meta(get_the_ID(), 'edge_testimonial_text', true);
                    $author = get_post_meta(get_the_ID(), 'edge_testimonial_author', true);
                    $position = get_post_meta(get_the_ID(), 'edge_testimonial_position', true);

                    $params['current_id'] = get_the_ID();
                    $params['title'] = $title;
                    $params['text'] = $text;
                    $params['author'] = $author;
                    $params['position'] = $position;

                    $html .= edge_core_get_cpt_shortcode_module_template_part('testimonials', 'testimonials-' . $params['type'], '', $params);

                endwhile;
            else:
                $html .= esc_html__('Sorry, no posts matched your criteria.', 'edge-core');
            endif;

            wp_reset_postdata();

            $html .= '</div>';
    
        $html .= '</div>';
    
        return $html;
    }

	/**
	 * Generates holder classes
	 *
	 * @param $params
	 *
	 * @return string
	 */
	private function getHolderClasses($params){
		$holderClasses = '';

		$holderClasses .= ' edge-testimonials-' . $params['type'];

		$holderClasses .= ' edge-testimonials-' . $params['skin'];

		return $holderClasses;
	}

    /**
     * Generates testimonials query attribute array
     *
     * @param $params
     *
     * @return array
     */
    private function getQueryParams($params) {
        $args = array(
            'post_status' => 'publish',
            'post_type' => 'testimonials',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => $params['number']
        );
        
        if ($params['category'] != '') {
            $args['testimonials-category'] = $params['category'];
        }
        
        return $args;
    }

	/**
	 * Returns array of box styles
	 *
	 * @param $params
	 *
	 * @return array
	 */
	public function getBoxStyles($params) {
		$styles = array();

		if($params['type'] === 'boxed' && !empty($params['box_color'])) {
			$styles[] = 'background-color: '.$params['box_color'];
		}

		return $styles;
	}

    /**
     * Generates testimonial data attribute array
     *
     * @param $params
     *
     * @return string
     */
    private function getDataParams($params) {
        $data_attr = '';
        
        if (!empty($params['number'])) {
            $data_attr .= ' data-number="'.$params['number'].'"';
        }

	    if (!empty($params['number_of_visible_items'])) {
		    $data_attr .= ' data-number-visible="'.$params['number_of_visible_items'].'"';
	    }
        
        if (!empty($params['slider_speed'])) {
            $data_attr .= ' data-speed="'.$params['slider_speed'].'"';
        }
        
        if (!empty($params['slider_animation'])) {
            $data_attr .= ' data-animation-speed="'.$params['slider_animation'].'"';
        }

	    if (!empty($params['show_navigation_arrows'])) {
		    $data_attr .= ' data-nav-arrows="'.$params['show_navigation_arrows'].'"';
	    }

        if (!empty($params['show_navigation_dots'])) {
            $data_attr .= ' data-nav-dots="'.$params['show_navigation_dots'].'"';
        }
        
        return $data_attr;
    }
	
	/**
	 * Filter testimonials categories
	 *
	 * @param $query
	 *
	 * @return array
	 */
	public function testimonialsCategoryAutocompleteSuggester( $query ) {
		global $wpdb;
		$post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS testimonials_category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'testimonials-category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );
		
		$results = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = ( ( strlen( $value['testimonials_category_title'] ) > 0 ) ? esc_html__( 'Category', 'edge-core' ) . ': ' . $value['testimonials_category_title'] : '' );
				$results[]     = $data;
			}
		}
		
		return $results;
		
	}
	
	/**
	 * Find testimonials category by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function testimonialsCategoryAutocompleteRender( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get portfolio category
			$testimonials_category = get_term_by( 'slug', $query, 'testimonials-category' );
			if ( is_object( $testimonials_category ) ) {
				
				$testimonials_category_slug = $testimonials_category->slug;
				$testimonials_category_title = $testimonials_category->name;
				
				$testimonials_category_title_display = '';
				if ( ! empty( $testimonials_category_title ) ) {
					$testimonials_category_title_display = esc_html__( 'Category', 'edge-core' ) . ': ' . $testimonials_category_title;
				}
				
				$data          = array();
				$data['value'] = $testimonials_category_slug;
				$data['label'] = $testimonials_category_title_display;
				
				return ! empty( $data ) ? $data : false;
			}
			
			return false;
		}
		
		return false;
	}
}