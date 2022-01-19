## Front end

- javascript: main.js
- templates: 
- Page.ss, for the input box, includes pinned articles section and results section
- PinnedArticles.ss, show updated list of pinned articles (article name, link)
- Results.ss, show updated searched results

## Back end
- PageController.php, use to link to Page.ss query and get results from results.php
- Results.php, process data received from the proxyAPI
- ProxyAPI.php, allow site to search CURL
- PinArticle.php, save pinned articles in cookies