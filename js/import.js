 
 $(document).ready(function () {
 // this is for the close button on alerts
 $('.close').on("click", function () {
    $('.alert').hide();
});

// Special errore messages can be retrieved form the the url GET that alert on load this hides them after 
// a while from he messages phph section 
setTimeout(
    function () {
        $('#special').fadeOut();
    }, 5000); // this ensures the alerts from get message  are hidden

});