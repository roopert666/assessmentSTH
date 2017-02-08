(function ($) {
    $(document).ready(function () {
        //reset form function
        $('#reset').click(function () {
            
            // clear table data
            // $('#results_table').empty();
            
        });

        // AJAX send function
        $('#next').click(function () {
            $('#processing').empty();
            document.getElementById('processing').innerHTML += "Pupil Records Data being processed.....";
            $.post(
                    PT_Ajax.ajaxurl,
                    {
                        // wp ajax action
                        type: 'POST',
                        action: 'ajax-inputtitleSubmit',

                        // vars
                        pupil_name: $('input[name=pupil_name]').val(),
                        pupil_class: $('input[name=pupil_class]').val(),
                        q1: $('input:checked').length,

                        // send the nonce along with the request
                        nextNonce: PT_Ajax.nextNonce
                    },
                    function (response) {
                        console.log("AJAX response: " + response);
                        document.getElementById('processing').innerHTML = response;
                    }
            );

            document.getElementById('out').innerHTML = $('input[name=pupil_name]').val() + "<br />";
            document.getElementById('out').innerHTML += $('input[name=pupil_class]').val() + "<br />";

            return false;
        });

        // AJAX send function
        $('#search').click(function () {
            
            document.getElementById('processing').innerHTML = "Pupil Records Data being processed......";
            $.post(
                    PT_Ajax.ajaxurl,
                    {
                        // wp ajax action
                        type: 'POST',
                        action: 'ajax-searchSubmit',
                        // dataType: "json",
                        // vars
                        pupil_name: $('input[name=pupil_name]').val(),
                        pupil_class: $('input[name=pupil_class]').val(),

                        // send the nonce along with the request
                        nextNonce: PT_Ajax.nextNonce
                    },
                    function (response) {
                        // console.log("AJAX response: " + response);
                        // document.getElementById('processing').innerHTML = response;
                        document.getElementById('processing').innerHTML += "Data processed."
                        $('#results').html(response);
                    }
            );

            // document.getElementById('out').innerHTML = $('input[name=pupil_name]').val() + "<br />";
            // document.getElementById('out').innerHTML += $('input[name=pupil_class]').val() + "<br />";

            return false;
        });

    });
})(jQuery);