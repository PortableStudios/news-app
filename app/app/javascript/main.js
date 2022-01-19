/** detect when type in input and send query to Page Controller */
$('#autocomplete_input').on('keyup', function() {
    LoadData(this.value, jQuery(this).siblings('.results'));
});

// load results from controller
function LoadData(value, results_element)
{
    // check if input is empty
    if (value !="")
    {
        
        results_element.load(
            //url
            "home/autocomplete",
            //data
            "text="+value,
            function(data,status){
                console.log(status);
            }
        )
    };
}