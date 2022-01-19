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
            "query="+value,
            function(data,status){
                if (status == "success")
                {
                    $(results_element).css("display","block");
                }
            }
        )
    };
}

// clone articles and push them to the bottom of the page (pinned articles)
function SaveArticle($article_id)
{
    var cloned_article = $("#"+$article_id).clone();
    $("#pinned_articles").show();
    cloned_article.appendTo($("#pinned_articles"));

}