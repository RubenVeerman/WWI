$("#searchBar").on("input", function ()
{
    $("#searchBar").autocomplete({
        source: function (request, response){
                $.get(`./api.php?query=${request.term}`, function(data) {
                    console.log(data);
                    data = JSON.parse(data);
                    console.log(data);
                    response(data);
                });
    }
    });
});

$(document).ready(function() {
    // executes when HTML-Document is loaded and DOM is ready
    console.log("document is ready");


    $( ".card" ).hover(
        function() {
            $(this).addClass('shadow-lg ').css('cursor', 'pointer');
        }, function() {
            $(this).removeClass('shadow-lg');
        }
    );

// document ready
});

