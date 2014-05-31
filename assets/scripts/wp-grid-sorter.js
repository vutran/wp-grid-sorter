/**
 * WordPress Grid Sorter
 */
var wpgs = (function(x) {

    var _grid = false;

    /**
     * Event callbacks
     *
     * @access private
     * @var object
     */
    var _events = {

            /**
             * callback when the layout is finished reordering
             */
            onLayoutComplete : function()
            {
                // Reorder the packery grid
                wpgs.updateOrder();
            },

            /**
             * callback when the an item is repositioned
             */
            onDragItemPositioned : function()
            {
                // Reorder the packery grid
                wpgs.updateOrder();
            }

        };

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
        // If the grid is not intiailized
        if (!_grid) {
            var
                // Retrieve the grid container
                container = document.querySelector('.wpgs-grid'),
                // Set the packery options
                packeryOptions = {
                    gutter : 0,
                    columnWidth : container ? container.querySelector('.grid-sizer') : 200,
                    rowHeight : container ? container.querySelector('.grid-sizer') : 200,
                    itemSelector : '.item'
                };
            // Instantiate the Packery grid
            _grid = (container) ? new Packery(container, packeryOptions) : false;
            if (_grid) {
                // Set the packery grid
                wpgs.setPackery(_grid);
                // Initialize Draggability for the packery grid
                wpgs.makeDraggable(_grid);
            }
        }
        return _grid;
    }

    /**
     * Initialize Draggability
     *
     * @access private
     * @param element packeryGrid           The Packery instance
     * @return void
     */
    x.makeDraggable = function(packeryGrid)
    {
        var itemElems = packeryGrid.getItemElements(), i = 0;
        // for each item...
        for ( i = 0, len = itemElems.length; i < len; i++ ) {
            // Make the item draggable in the given grid
            x.makeElementDraggable(itemElems[i], packeryGrid);
        }
    };

    /**
     * Initialize Draggability for the single item
     *
     * @access public
     * @param element element               The element to make draggable
     * @param element packeryGrid           The Packery instance
     * @return void
     */
    x.makeElementDraggable = function(element, packeryGrid)
    {
      // make element draggable with Draggabilly
      var draggie = new Draggabilly(element);
      // bind Draggabilly events to Packery
      packeryGrid.bindDraggabillyEvents(draggie);
      // Update the order when state is changed
      packeryGrid.on('layoutComplete', _events.onLayoutComplete);
      packeryGrid.on('dragItemPositioned', _events.onDragItemPositioned);
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
        // Relayout
        x.getPackery().layout();
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

    /**
     * Add more items to the grid
     *
     * @access public
     * @param array items
     * @return void
     */
    x.addItems = function(items)
    {
        var
            packeryGrid = x.getPackery(),
            container = packeryGrid.element,
            i = 0,
            totalItems = items.length;
        // Wait till all images are loaded
        imagesLoaded(container, function() {
            // Append childs
            for (i = 0; i < totalItems; i++) {
                packeryGrid.element.appendChild(items[i]);
            }
            // Notify packery that items has been appended
            packeryGrid.appended(items);
            // Notift draggability that items has been appended
            x.makeDraggable(packeryGrid);
            // Wait a bit...
            setTimeout(function() {
                // Relayout
                x.getPackery().layout();
            }, 250);
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
        container = document.querySelector('.wpgs-grid'),
        packeryGrid = false;
    if (container) {
        // Wait till all images are loaded
        imagesLoaded(container, function() {
            // Init the packery grid
            wpgs.getPackery();

            // Update order
            wpgs.updateOrder();

            // Bind the submit callback
            $('.wpgs-form').on('submit', wpgs.saveOrder);
        });
    }
});