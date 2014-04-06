jQuery(function($) {

    $('.wpgs-grid').sortable({
        items : "> .wpgs-item"
    });

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
                showMessage(ret.status, ret.response.message);
            }
        });
    });

    /**
     * Appends a new message
     *
     * @access public
     * @param string status             (Enumeration: "success", "error")
     * @param string message
     * @return void
     */
    var showMessage = function(status, message)
    {
        statusClass = (status === "success") ? 'updated' : 'error';
        var
            html = '<div id="message" class="' + statusClass + ' below-h2"><p>' + message + '</div>',
            alert = $(html);
        $('.wpgs-notifications').html(alert);
    };

});