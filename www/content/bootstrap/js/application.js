// NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
// IT'S ALL JUST JUNK FOR OUR DOCS!
// ++++++++++++++++++++++++++++++++++++++++++

!function($) {

    $(function() {

        /*  // tooltip demo
         $(this).tooltip({
         selector: "div[rel=tooltip]",
         placement: "bottom"
         })
         
         $("div[rel=popover]")
         .popover({
         html: true,
         trigger: 'manual',
         placement: 'bottom'
         })
         .hover(
         function(e) {
         $(this).popover('show');
         e.preventDefault();
         }, 
         function(e) {
         $(this).popover('hide');
         e.preventDefault();
         }
         );
         */

        var isVisible = false;
        var clickedAway = false;

        $("div[rel=popover]").popover({
            animation: true,
            html: true,
            trigger: 'click',
            placement: 'bottom',
            delay: "show: 100, hide: 100",
            width: '300px'
        }).click(function(e) {
            $(this).popover('show');
            isVisible = true;
            e.preventDefault();
        });

        $(document).click(function(e) {
            if (isVisible & clickedAway)
            {
                $('div[rel=popover]').popover('hide');
                isVisible = clickedAway = false
            }
            else
            {
                clickedAway = true
            }
           
        });



    })

}(window.jQuery)