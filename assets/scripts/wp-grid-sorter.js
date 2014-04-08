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
        var
            i = 0,
            items = x.getPackery().items,
            itemElements = x.getPackery().getItemElements(),
            orderNum = 1,
            itemsByOrder = [];
        // Set the order data attributes
        for (i = 0; i < itemElements.length; i++) {
            var element = jQuery(itemElements[i]);
            // Skip the grid sizer
            if (element.hasClass('grid-sizer')) { continue; }
            element
                .attr('data-order', orderNum)
                .data('order', orderNum);
            orderNum++;
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

    /**
     * Saves the current order of the packery grid
     *
     * @access public
     * @param Event e
     * @return void
     */
    x.saveOrder = function(e)
    {
        e.preventDefault();
        var
            theForm = jQuery(this),
            spinner = theForm.find('.spinner');
        // Show spinner
        spinner.show();
        // Submit the form
        theForm.ajaxSubmit({
            data : {
                posts : wpgs.getOrder()
            },
            success : function(ret) {
                // Hide spinner
                spinner.hide();
                x.showMessage(ret.status, ret.response.message);
            }
        });
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
            rowHeight : 200,
            columnWidth : 200
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
    $('.wpgs-form').on('submit', wpgs.saveOrder);

});