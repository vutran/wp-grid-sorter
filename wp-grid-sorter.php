<?php

/**
 * @package Grid Sorter
 * @version 1.0.0
 */
/*
Plugin Name: Grid Sorter
Description: Sort your grid
Author: Vu Tran
Version: 1.0.0
Author URI: http://vu-tran.com/
*/

class WPGridSorter
{

    /**
     * The plugins root path
     *
     * @access protected
     * @var string
     */
    protected $path = '';

    /**
     * Initializes the plugin
     *
     * @access public
     * @param string $path
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;

        // Load included files
        $this->loadInc();

        // Load modules
        $this->loadModules();

        // Load static assets
        $this->loadAssets();

        // Load modules
        $this->ajax = new WPGridSorter_Ajax($this);
        $this->mappings = new WPGridSorter_Mappings($this);
        $this->pages = new WPGridSorter_Pages($this, WP_GRID_SORTER_PATH . '/pages/');

        // Register admin pages
        add_action('admin_menu', array(&$this, 'registerPages'));
    }

    /**
     * Load included files
     *
     * @access private
     * @return void
     */
    private function loadInc()
    {
        require_once('inc/functions.php');
        require_once('inc/hooks.php');
    }

    /**
     * Load plugin modules
     *
     * @access private
     * @return void
     */
    private function loadModules()
    {
        require_once('modules/ajax.php');
        require_once('modules/mappings.php');
        require_once('modules/pages.php');
    }

    /**
     * Enqueues stylesheets and javascripts
     *
     * @access private
     * @return void
     */
    private function loadAssets()
    {
        // Register third-party packages
        if (is_admin()) {
            wp_register_script('imagesLoaded', plugins_url('assets/scripts/imagesLoaded.js', __FILE__), array('jquery'));
            wp_register_script('draggabilly', plugins_url('assets/scripts/draggabilly.pkgd.min.js', __FILE__), array('jquery'));
            wp_register_script('packery', plugins_url('assets/scripts/packery.pkgd.min.js', __FILE__), array('jquery'));
            // Register the stylesheets/scripts
            wp_register_style('wp-grid-sorter-main', plugins_url('assets/stylesheets/wp-grid-sorter.css', __FILE__));
            wp_register_script('wp-grid-sorter-main', plugins_url('assets/scripts/wp-grid-sorter.js', __FILE__), array('jquery', 'jquery-ui-sortable', 'jquery-form', 'imagesLoaded', 'draggabilly', 'packery'));

            // Enqueue the stylesheets/scripts
            wp_enqueue_style('wp-grid-sorter-main');
            wp_enqueue_script('wp-grid-sorter-main');
        }
    }

    /**
     * Register custom admin menu pages
     *
     * @access public
     * @return void
     */
    public function registerPages()
    {
        add_menu_page('Grid Sorter', 'Grid Sorter', 'edit_posts', 'wp-grid-sorter/pages/general', array(&$this->pages, 'general'));
    }

    /**
     * Retrieve the root plugin path
     *
     * @access public
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

}

add_action('init', 'wp_grid_sorter_init');
/**
 * Initializes the plugin
 *
 * @access public
 * @return void
 */
function wp_grid_sorter_init()
{
    // Load constants
    define('WP_GRID_SORTER_PATH', plugin_dir_path(__FILE__));

    global $wp_grid_sorter;
    // Instantiate the plugin
    $wp_grid_sorter = new WPGridSorter(WP_GRID_SORTER_PATH);
}