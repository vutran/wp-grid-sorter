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
            'order' => array(
                'flags' => FILTER_REQUIRE_ARRAY,
                'filter' => FILTER_SANITIZE_NUMBER_INT
            )
        );
        $requestParams = filter_input_array(INPUT_POST, $filterArgs);
        // Verify for post data
        if ($requestParams['sort_key'] && is_array($requestParams['order']) && count($requestParams['order'])) {
            // Iterate and update each post's order
            foreach ($requestParams['order'] as $orderKey => $postId) {
                $orderNumber = $orderKey + 1;
                $this->wpgs->mappings->update($postId, $requestParams['sort_key'], $orderNumber);
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