<div>
    <% loop $Results %>
        <!-- show results data -->
        <h1>$SectionName</h1>
        <% loop $Data %>
            <% loop $ArticleData %>
                <div id="article-$Pos-$ArticleData.SectionID" class="card" style="border: 1px solid grey; margin-bottom:1rem; padding:1rem;">
                    <h3>$ArticleData.WebsiteTitle</h3>
                    <p>Publication date: $ArticleData.PublicationDate</p>
                    <a href="$ArticleData.WebsiteURL" target="_blank">Click to see more URL</a>
                    <div class="btn" onclick="SaveArticle('article-$Pos-$ArticleData.SectionID')">Pin this article</div>
                </div>  
            <% end_loop %>
        <% end_loop %>
    <% end_loop %>
</div>