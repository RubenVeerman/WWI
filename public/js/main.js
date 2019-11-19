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