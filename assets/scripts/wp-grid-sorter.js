/**
 * WordPress Grid Sorter
 */
var wpgs = (function(x) {

    /**
     * Sets the packery grid
     *
     * @access public
     * @param element grid
     * @return void
     */
    x.setPackery = function(grid)
    {
        _grid = grid;
    };

    /**
     * Retrieve the packery grid
     *
     * @access public
     * @return object
     */
    x.getPackery = function()
    {
        return _grid;
    }

    /**
     * Initialize Draggability
     *
     * @access private
     * @return void
     */
    x.makeDraggable = function(grid)
    {
        var itemElems = grid.getItemElements(), i = 0;
        // for each item...
        for ( i = 0, len = itemElems.length; i < len; i++ ) {
          var elem = itemElems[i];
          // make element draggable with Draggabilly
          var draggie = new Draggabilly(elem);
          // bind Draggabilly events to Packery
          grid.bindDraggabillyEvents(draggie);

          // Update the order when state is changed
          grid.on('layoutComplete', wpgs.updateOrder);
          grid.on('dragItemPositioned', wpgs.updateOrder);
        }
    };

    /**
     * Updates the element order
     *
     * @access public
     * @param element grid
     * @return void
     */
    x.updateOrder = function()
    {
        var items = x.getPackery().getItemElements(), i = 0;
        console.log(items);
        for ( i = 0, len = items.length; i < len; i++ ) {
            var element = jQuery(items[i]);
            // Skip the grid sizer
            if (element.hasClass('grid-sizer')) { continue; }
            element
                .attr('data-order', i)
                .data('order', i);
        }
    };

    /**
     * Retrieve an array of the ordered post ID's
     *
     * Each element in the returned array will hold the postId and the orderNum
     *
     * @access public
     * @return array
     */
    x.getOrder = function()
    {
        var
            // Empty array to hold post ID's
            posts = [],
            // The packery item elements
            items = x.getPackery().getItemElements(),
            // Set index count
            i = 0;
        for (i = 0; i < items.length; i++) {
            var element = jQuery(items[i]);
            // Skip the grid sizer
            if (element.hasClass('grid-sizer')) { continue; }
            var orderData = {
                postId : parseInt(element.data('id'), 10),
                orderNum : parseInt(element.data('order'), 10)
            };
            posts.push(orderData);
        }
        console.log(posts);
        return posts;
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
        var html = '<div id="message" class="' + statusClass + ' below-h2"><p>' + message + '</div>';
        jQuery('.wpgs-notifications').html(html);
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
            gutter : 0,
            columnWidth: container.querySelector('.grid-sizer')
        },
        // Instantiate the Packery grid
        packeryGrid = new Packery(container, packeryOptions);

    // Initialize Draggability for the packery grid
    wpgs.makeDraggable(packeryGrid);

    // Set the packery grid
    wpgs.setPackery(packeryGrid);

    // Update order
    wpgs.updateOrder();

    // Bind the submit callback
    $('.wpgs-form').on('submit', function(e) {
        e.preventDefault();
        // Show spinner
        var spinner = $(this).find('.spinner');
        spinner.show();
        // Submit the form
        $(this).ajaxSubmit({
            data : {
                posts : wpgs.getOrder()
            },
            success : function(ret) {
                // Hide spinner
                spinner.hide();
                wpgs.showMessage(ret.status, ret.response.message);
            }
        });
    });

});