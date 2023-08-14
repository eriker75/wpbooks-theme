<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://eribertmarquez.com
 * @since      1.0.0
 *
 * @package    WpBooks
 * @subpackage WpBooks/Theme
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    WpBooks
 * @subpackage WpBooks/inc
 * @author     Eribert Marquez <eriker1997@gmail.com>
 */

namespace Inc;

class Loader {
	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * El array de Shortcodes registrados en WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $shortcodes  Los Shortcodes registrados en WordPress para ejecutar cuando se carga el plugin.
	 */
    protected $shortcodes;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->actions 		= array();
		$this->filters 		= array();
		$this->shortcodes 	= array();
	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);
		return $hooks;
	}

	 /**
	 * Add a new shortcode to the array ($this->shortcodes) to iterate to addps in WordPress.
	 *
	 * @since    1.0.0
     * @access   public
     * 
	 * @param    string    $tag              The name of the WordPress shortcode being registered.
	 * @param    object    $component        A reference to the instance of the object in which the shortcode is defined.
	 * @param    string    $callback         The name of the method/function definition in the $component.
	 */
    public function add_shortcode( $tag, $component, $callback ) {
        $this->shortcodes = $this->add_s( $this->shortcodes, $tag, $component, $callback );
    }
    
    /**
	 * Utility function used to register shortcodes in a single iteration.
	 *
	 * @since    1.0.0
	 * @access   private
     * 
	 * @param    array     $shortcodes       The collection of shortcodes being registered.
	 * @param    string    $tag              A reference to the instance of the object in which the shortcode is defined.
	 * @param    object    $component        A reference to the instance of the object in which the shortcode is defined.
	 * @param    string    $callback         The name of the method/function definition in the $component.
     * 
	 * @return   array                       The collection of Shortcodes in WordPress to proceed to iterate.
	 */
    private function add_s( $shortcodes, $tag, $component, $callback ) {
        $shortcodes[] = [
            'tag'           => $tag,
            'component'     => $component,
            'callback'      => $callback
        ];
        return $shortcodes;     
    }

	/**
	 * Register the filters, shortcodes and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		foreach( $this->actions as $hook_u ) {
            extract( $hook_u, EXTR_OVERWRITE );
            add_action( $hook, [ $component, $callback ], $priority, $accepted_args );
        }
        
        foreach( $this->filters as $hook_u ) {
            extract( $hook_u, EXTR_OVERWRITE );
            add_filter( $hook, [ $component, $callback ], $priority, $accepted_args );
        }
        
        foreach( $this->shortcodes as $shortcode ) {
            extract( $shortcode, EXTR_OVERWRITE );
            add_shortcode( $tag, [ $component, $callback ] );
        }
	}
}