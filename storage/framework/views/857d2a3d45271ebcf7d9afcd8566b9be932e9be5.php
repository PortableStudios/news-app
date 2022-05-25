<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <title>News Aggregator</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="description" content="" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
</head>



<style>
    #sticky, #results,     .iptext{
        width:95%;
        margin: .5em;
    }
    #sticky{
        margin-bottom: 1em;
    }


    .stickyArticle{
        background:rgba(0,255,0,.1);
        border:1px solid rgba(0,128,0,.5);
        padding: .5em;

        margin-bottom: .5em;
    }
    .ArticleEntry{
        border:1px solid rgba(0,0,0,.2);
        padding: .5em;

        margin-bottom: .5em;

    }
    .iptext{
        font-family: monospace;
        font-weight:bold;
        font-size: 1.2em;
        border:none;
        background: rgba(0,0,128,.15);
        padding:.1em;

    }

    #loader{ float:right; width: 1em;height:1em;}
    .spinner-border{
        color: rgba(0,0,0,.2);
    }
    .removePin, .pinButton{
        display:inline-block;
        float:right;
    }
    .publishedInfo{
        font-size:.9em; letter-spacing: -1px;
    }

</style>
<body>
<div class="container">
    <h1>Search the news...</h1>

    <div id='loader'><div class="spinner-border" role="status"></div></div>
    <input class='iptext' type="text" id="query"  onkeydown="return /[a-z, -]/i.test(event.key)"/> <? // filter special chars at input  ?>
    <div id="sticky"></div>
    <div id="results"></div>
</div>

<script>

    $(document).ready(function(){

        // init display state
        $('#query').focus();
        $('#sticky').hide();
        $('#loader').hide();

// ================= EVENT HANDLER for SEARCH FIELD =================
        $('#query').keyup(function() {

            var str = $('#query').val(); // what's been entered?


            if(!str.length){ // nothing?? if the user deleted all chars from the search they probably expect a blank result (rather than the NewsProvider's default)
                $('#results').html('');
                return;
            }



            // Let's ajax...

            var apiEndpoint = '/api/news/';
            $('#loader').show();

            $.ajax({
                dataType: "json",
                url: apiEndpoint,
                data: {'search': str},
                success:

                    function (json) {   // this JSON is an array of arrays (providers & messages)

                        $('#loader').hide();



                        $('#results').empty(); // otherwise we get the same results again and again

                        $.each( json, function( key, newsJSON ) {

                            if (newsJSON.status == 'ok') {
                                var articleCount = 0;
                                var Publication = newsJSON.name;

                                $.each(newsJSON, function (key, val) {


                                    if(val.id){ // - there are other elements in the array root. this filters them


                                        var idx = usableID(val.id); // filters unusable chars
                                        articleCount++;
                                        var SectionID = val.sectionId;

                                        // Results are to be grouped by Sections. Unless already existing we create a DIV for each section
                                        // and fill it up with content later
                                        if (!$('#' + SectionID).length) {
                                            $('#results').append("<div id='" + SectionID + "'></div>");
                                        }

                                        var d = convertUTCDateToLocalDate(new Date(val.webPublicationDate));
                                        var options = {  year: 'numeric', month: 'numeric', day: 'numeric' };

                                        var theDate = d.toLocaleString('en-AU',options);

                                        // prep the HTML string to inject into the specific Section DIV...
                                        var html = '<div class="ArticleEntry ' + SectionID + '" id="div_' + idx + '"><strong>' + val.webTitle + '</strong>';
                                        html += '<a class="pinButton btn-sm btn btn-success" id="' + idx + '" href="#">+</a>';
                                        html += '<div class="publishedInfo">Published on ' + theDate + ' in ' + SectionID + ' by ' + Publication+'</div>';
                                        html += '<a class="deepLinkButton btn-sm btn btn-primary" target="_blank" href="' + val.webUrl + '">More</a>';
                                        html += '</div>';

                                        $('#' + SectionID).append(html); // ... and inject it
                                    } // end if val.id

                                }); // end loop articles

                                if(!articleCount){
                                    $('#results').append("Looks like '"+newsJSON.name+ "' has no entries for <em>"+ str+'</em>');
                                }


                            } else { // end if OK
                                $('#results').append("There was a problem with fetching data from "+newsJSON.name);
                            }
                        }); // end loop news providers
                    } // end success
            }); // end .ajax
        }); // end key up


// ================= PIN ENTRY =============================
        $('body').on('click', '.pinButton', function() {
            var id = $(this).attr('id');
            $('#div2_'+id).remove(); // in case this has been "stickied before"
            $(this).remove();

            var  html =  '<div class="stickyArticle"  id="div2_'+id+'">';
            html += '<a class="removePin btn-sm btn btn-danger" id="remove_'+id+'" href="#">-</a>';
            html += $('#div_'+id).html();
            html += '</div>';

            $('#sticky').prepend(html);
            $('#sticky').show();
            $('#div_'+id).remove();
        });



// ================= REMOVE PINNED ENTRY =============================
        $('body').on('click', '.removePin', function() {
            var id = $(this).attr('id').replace('remove_','');
            $('#div2_'+id).remove();
        });


// UTILITIES AND HELPERS
        function usableID(Text){
            if(!Text){ return }
            return Text.toLowerCase()
                .replace(/ /g,'-')
                .replace(/[^\w-]+/g,'');
        }



        function convertUTCDateToLocalDate(date) {
            var newDate = new Date(date.getTime()+date.getTimezoneOffset()*60*1000);

            var offset = date.getTimezoneOffset() / 60;
            var hours = date.getHours();

            newDate.setHours(hours - offset);

            return newDate;
        }



    });</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\portable\news-app\resources\views/NewsHomepage.blade.php ENDPATH**/ ?>