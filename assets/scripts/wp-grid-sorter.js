/**
 * WordPress Grid Sorter
 */
var wpgs = (function(x) {

    /**
     * Initialize Draggability
     *
     * @access private
     * @return void
     */
    x.makeDraggable = function(grid)
    {
        var itemElems = grid.getItemElements();
        // for each item...
        for ( var i=0, len = itemElems.length; i < len; i++ ) {
          var elem = itemElems[i];
          // make element draggable with Draggabilly
          var draggie = new Draggabilly(elem);
          // bind Draggabilly events to Packery
          grid.bindDraggabillyEvents(draggie);
        }
    };

    /**
     * Appends a new message
     *
     * @access public
     * @param string status             (Enumeration: "success", "error")
     * @param string message
     * @return void
     */
    x.showMessage = function(status, message)
    {
        statusClass = (status === "success") ? 'updated' : 'error';
        var
            html = '<div id="message" class="' + statusClass + ' below-h2"><p>' + message + '</div>',
            alert = $(html);
        $('.wpgs-notifications').html(alert);
    };

    return x;

}(wpgs || {}));


/**
 * jQuery document ready callback
 */
jQuery(function($) {

    var
        // Retrieve the grid container
        container = document.querySelector('.wpgs-grid');
        // Set the packery options
        packeryOptions = {
            itemSelector : '.wpgs-item',
            gutter : 0,
            columnWidth: container.querySelector('.grid-sizer')
        },
        // Instantiate the Packery grid
        packeryGrid = new Packery(container, packeryOptions);

    // Initialize Draggability for the packery grid
    wpgs.makeDraggable(packeryGrid);

    // Bind the submit callback
    $('.wpgs-form').on('submit', function(e) {
        e.preventDefault();
        // Show spinner
        var spinner = $(this).find('.spinner');
        spinner.show();
        // Submit the form
        $(this).ajaxSubmit({
            success : function(ret) {
                // Hide spinner
                spinner.hide();
                wpgs.showMessage(ret.status, ret.response.message);
            }
        });
    });

});