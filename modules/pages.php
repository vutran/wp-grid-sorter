<?php

/**
 * Admin pages
 */
class WPGridSorter_Pages
{

    /**
     * @access protected
     * @var string
     */
    protected $path = '/';

    /**
     * Instantiates the module
     *
     * @access public
     * @param WPGridSorter $wpgs
     * @param string $path
     */
    public function __construct($wpgs, $path)
    {
        $this->wpgs = $wpgs;
        $this->path = $path;
    }

    /**
     * Retrieve the page template
     *
     * @access private
     * @param string $file
     * @return string
     */
    private function getTemplate($file)
    {
        ob_start();
        include(sprintf('%s/%s.php', $this->path, $file));
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * Displays the general settings page
     *
     * @access public
     * @return void
     */
    public function general()
    {
        if (isset($_GET['key'])) {
            echo $this->getTemplate('edit');
        } else {
            echo $this->getTemplate('general');
        }
    }

}

?>