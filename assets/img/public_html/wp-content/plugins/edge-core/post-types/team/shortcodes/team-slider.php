<?php
namespace EdgeCore\CPT\Shortcodes\Team;

use EdgeCore\Lib;

class TeamSlider implements Lib\ShortcodeInterface {
    private $base;

    public function __construct() {
        $this->base = 'edge_team_slider';

        add_action('vc_before_init', array($this, 'vcMap'));

        //Team category filter
        add_filter( 'vc_autocomplete_edge_team_slider_category_callback', array( &$this, 'teamCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Team category render
        add_filter( 'vc_autocomplete_edge_team_slider_category_render', array( &$this, 'teamCategoryAutocompleteRender', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Team selected projects filter
        add_filter( 'vc_autocomplete_edge_team_slider_selected_projects_callback', array( &$this, 'teamIdAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Team selected projects render
        add_filter( 'vc_autocomplete_edge_team_slider_selected_projects_render', array( &$this, 'teamIdAutocompleteRender', ), 10, 1 ); // Render exact team. Must return an array (label,value)
    }

    public function getBase() {
        return $this->base;
    }

    public function vcMap() {
        vc_map(array(
            'name'                      => esc_html__('Edge Team Slider', 'edge-core'),
            'base'                      => $this->base,
            'category'                  => esc_html__('by EDGE', 'edge-core'),
            'icon'                      => 'icon-wpb-team-slider extended-custom-icon',
            'allowed_container_element' => 'vc_row',
            'params' => array(
                array(
                    'type'        => 'dropdown',
                    'param_name'  => 'number_of_columns',
                    'heading'     => esc_html__('Number of Columns in Row', 'edge-core'),
                    'value'       => array(
						esc_html__('Three', 'edge-core') => '3',
						esc_html__('Four', 'edge-core') => '4',
						esc_html__('Five', 'edge-core') => '5',
						esc_html__('Six', 'edge-core') => '6'
                    )
                ),
                array(
                    'type'        => 'textfield',
                    'param_name'  => 'number_of_items',
                    'heading'     => esc_html__( 'Number of team members per page', 'edge-core' ),
                    'description' => esc_html__( 'Set number of items for your team list. Enter -1 to show all.', 'edge-core' ),
                    'value'       => '-1'
                ),
                array(
                    'type'        => 'autocomplete',
                    'param_name'  => 'category',
                    'heading'     => esc_html__( 'One-Category Team List', 'edge-core' ),
                    'description' => esc_html__( 'Enter one category slug (leave empty for showing all categories)', 'edge-core' )
                ),
                array(
                    'type'        => 'autocomplete',
                    'param_name'  => 'selected_projects',
                    'heading'     => esc_html__( 'Show Only Projects with Listed IDs', 'edge-core' ),
                    'settings'    => array(
                        'multiple'      => true,
                        'sortable'      => true,
                        'unique_values' => true
                    ),
                    'description' => esc_html__( 'Delimit ID numbers by comma (leave empty for all)', 'edge-core' )
                ),
	            array(
		            'type'        => 'dropdown',
		            'param_name'  => 'order_by',
		            'heading'     => esc_html__('Order By', 'edge-core'),
		            'value'       => array_flip(adorn_edge_get_query_order_by_array()),
		            'save_always' => true
	            ),
	            array(
		            'type'       => 'dropdown',
		            'param_name' => 'order',
		            'heading'    => esc_html__('Order', 'edge-core'),
		            'value'      => array_flip(adorn_edge_get_query_order_array()),
		            'save_always' => true
	            ),
                array(
                    'type'        => 'dropdown',
                    'param_name'  => 'team_member_layout',
                    'heading'     => esc_html__( 'Team Member Layout', 'edge-core' ),
                    'value'       => array(
                        esc_html__( 'Info Bellow', 'edge-core' ) => 'info-bellow',
                        esc_html__( 'Info on Hover' )             => 'info-hover'
                    ),
                    'group'       => esc_html__( 'Content Layout', 'edge-core' )
                ),
                array(
                    'type'        => 'dropdown',
                    'param_name'  => 'dots',
                    'heading'     => esc_html__('Show Navigation Dots', 'edge-core'),
                    'value'       => array_flip(adorn_edge_get_yes_no_select_array(false)),
                    'save_always' => true,
                )
            )
        ));
    }

    public function render($atts, $content = null) {
        $default_atts = array(
            'number_of_columns'     => '3',
            'number_of_items'       => '-1',
            'category'              => '',
            'selected_projects'     => '',
            'tag'                   => '',
            'order_by'              => 'date',
            'order'                 => 'ASC',
            'team_member_layout'    => 'info-bellow',
            'dots'                  => 'no',
            'team_slider'           => 'yes'
        );

        $params = shortcode_atts($default_atts, $atts);

        $params['content'] = $content;

        $html = '';
        $html .= '<div class="edge-team-slider-holder">';
        $html .= adorn_edge_execute_shortcode('edge_team_list', $params);
        $html .= '</div>';

        return $html;
    }

    /**
     * Filter team categories
     *
     * @param $query
     *
     * @return array
     */
    public function teamCategoryAutocompleteSuggester( $query ) {
        global $wpdb;
        $post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS team_category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'team-category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

        $results = array();
        if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
            foreach ( $post_meta_infos as $value ) {
                $data          = array();
                $data['value'] = $value['slug'];
                $data['label'] = ( ( strlen( $value['team_category_title'] ) > 0 ) ? esc_html__( 'Category', 'edge-core' ) . ': ' . $value['team_category_title'] : '' );
                $results[]     = $data;
            }
        }

        return $results;
    }

    /**
     * Find team category by slug
     * @since 4.4
     *
     * @param $query
     *
     * @return bool|array
     */
    public function teamCategoryAutocompleteRender( $query ) {
        $query = trim( $query['value'] ); // get value from requested
        if ( ! empty( $query ) ) {
            // get team category
            $team_category = get_term_by( 'slug', $query, 'team-category' );
            if ( is_object( $team_category ) ) {

                $team_category_slug = $team_category->slug;
                $team_category_title = $team_category->name;

                $team_category_title_display = '';
                if ( ! empty( $team_category_title ) ) {
                    $team_category_title_display = esc_html__( 'Category', 'edge-core' ) . ': ' . $team_category_title;
                }

                $data          = array();
                $data['value'] = $team_category_slug;
                $data['label'] = $team_category_title_display;

                return ! empty( $data ) ? $data : false;
            }

            return false;
        }

        return false;
    }

    /**
     * Filter teams by ID or Title
     *
     * @param $query
     *
     * @return array
     */
    public function teamIdAutocompleteSuggester( $query ) {
        global $wpdb;
        $team_id = (int) $query;
        $post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT ID AS id, post_title AS title
					FROM {$wpdb->posts}
					WHERE post_type = 'team-member' AND ( ID = '%d' OR post_title LIKE '%%%s%%' )", $team_id > 0 ? $team_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

        $results = array();
        if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
            foreach ( $post_meta_infos as $value ) {
                $data = array();
                $data['value'] = $value['id'];
                $data['label'] = esc_html__( 'Id', 'edge-core' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'edge-core' ) . ': ' . $value['title'] : '' );
                $results[] = $data;
            }
        }

        return $results;
    }

    /**
     * Find team by id
     * @since 4.4
     *
     * @param $query
     *
     * @return bool|array
     */
    public function teamIdAutocompleteRender( $query ) {
        $query = trim( $query['value'] ); // get value from requested
        if ( ! empty( $query ) ) {
            // get team
            $team = get_post( (int) $query );
            if ( ! is_wp_error( $team ) ) {

                $team_id = $team->ID;
                $team_title = $team->post_title;

                $team_title_display = '';
                if ( ! empty( $team_title ) ) {
                    $team_title_display = ' - ' . esc_html__( 'Title', 'edge-core' ) . ': ' . $team_title;
                }

                $team_id_display = esc_html__( 'Id', 'edge-core' ) . ': ' . $team_id;

                $data          = array();
                $data['value'] = $team_id;
                $data['label'] = $team_id_display . $team_title_display;

                return ! empty( $data ) ? $data : false;
            }

            return false;
        }

        return false;
    }
}