'use strict';

    (function() {

        var intervalId;

        // Delete interval
        function deleteInterval () {
            $('[id^=copy]').on('click', function () {
                     clearTimeout(intervalId);
                 }
             );

            $('button.close').on('click', function () {
                intervalId = refreshButton();
            });
        }

        // Refresh page
        function refreshPage() {
            intervalId = refreshButton();
        }

        function refreshButton () {
            return setInterval(function () {
                $('#refreshButton').click();
                },15000);   // setup time
        }

     $(document).ready([refreshPage,deleteInterval]);
     $(document).on('pjax:complete',deleteInterval);
    })();

