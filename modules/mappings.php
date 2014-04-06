<?php

/**
 * Sort key mappings
 */
class WPGridSorter_Mappings
{

    /**
     * An array of sortable post keys
     *
     * @access protected
     * @var array
     */
    protected $keys = array();

    /**
     * Instantiates the module
     *
     * @access public
     * @param WPGridSorter $wpgs
     */
    public function __construct($wpgs)
    {
        $this->wpgs = $wpgs;
    }

    /**
     * Register a post type and key
     *
     * @access public
     * @param string $sortKey                       A unique key for sorting
     * @param array $args (default: array())        An array of options
     * @param string $args['name']                  A readable name to display in the CMS
     * @param string $args['post_type']             The post type to sort
     * @return void
     */
    public function register($sortKey, $args)
    {
        // Check if the sort key exists
        if (!array_key_exists($sortKey, $this->keys)) {
            // Create an array for the sort key
            $this->keys[$sortKey] = array();
        }
        // Check if the post type is set
        if (post_type_exists($args['post_type'])) {
            $this->keys[$sortKey] = $args;
        } else {
            error_log('Post type does not exist: ' . $args['post_type']);
        }
    }

    /**
     * Checks if keys are available
     *
     * @access public
     * @return bool
     */
    public function hasKeys()
    {
        return (is_array($this->keys) && count($this->keys)) ? true : false;
    }

    /**
     * Retrieve all sort keys
     *
     * @access public
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Retrieve the args for the given sort key
     *
     * @access public
     * @param string $key           The sort key
     * @return array
     */
    public function getArgs($key)
    {
        $returnArgs = false;
        if ($this->hasKeys()) {
            foreach ($this->getKeys() as $sortKey => $sortArgs) {
                // Skip un-matching
                if ($sortKey !== $key) { continue; }
                $returnArgs = $sortArgs;
            }
        }
        return $returnArgs;
    }

    /**
     * Updates the post order for the given sort key
     *
     * @param int $postId
     * @param string $sortKey
     * @param int $orderNumber
     * @return void
     */
    public function update($postId, $sortKey, $orderNumber)
    {
        update_post_meta($postId, $sortKey, $orderNumber);
    }

}

?>