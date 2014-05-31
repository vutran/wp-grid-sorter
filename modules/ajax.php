<?php

/**
 * AJAX callback module
 */
class WPGridSorter_Ajax
{

    /**
     * Instantiate the AJAX callback module
     *
     * @access public
     * @return void
     */
    public function __construct($wpgs)
    {
        $this->wpgs = $wpgs;
        add_action('wp_ajax_wpgs_save_order', array(&$this, 'saveOrder'));
    }

    /**
     * Saves the order for the current sort key
     *
     * @access public
     * @return string
     */
    public function saveOrder()
    {
        $ret = array(
            'status' => 'error',
            'response' => array(
                'message' => 'Error trying to save.'
            )
        );
        // Filter args
        $filterArgs = array(
            'sort_key' => FILTER_SANITIZE_STRING,
            'posts' => array(
                'flags' => FILTER_REQUIRE_ARRAY,
                'filter' => FILTER_SANITIZE_NUMBER_INT
            )
        );
        $requestParams = filter_input_array(INPUT_POST, $filterArgs);
        // Verify for post data
        if ($requestParams['sort_key'] && is_array($requestParams['posts']) && count($requestParams['posts'])) {
            // Iterate and update each post's order
            foreach ($requestParams['posts'] as $order) {
                $this->wpgs->mappings->update($order['postId'], $requestParams['sort_key'], $order['orderNum']);
            }
            $ret = array(
                'status' => 'success',
                'response' => array(
                    'message' => 'Saved successfully.'
                )
            );
        }
        header('Content-type: application/json');
        echo json_encode($ret);
        die();
    }

}

?>