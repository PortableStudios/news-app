<!-- DRAFT view -->
<!DOCTYPE html>
<html>
<head>
    <title>News App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-4">
  <div class="card">
    <div class="card-header text-center font-weight-bold">
      News App
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('news.search') }}">
            <div>
                <label for="searchbox">Search</label>
                <input type="text" id="query" name="query" class="form-control">
            </div>
        </form>
    </div>

    <!-- Display after the search result -->
    @if (isset($results) && count($results) > 0)
      <div class="card-body">
      <form method="POST" action="{{ route('news.pinned') }}">
        <div>
          <table>
            <thead>
              <tr> 
                <th>Article</th>
                <th>Published Date</th>
                <th>Pin</th>
              </tr>
            </thead>
          @foreach($results as $item)
            <tr>
              <td><a href="{{ $item['article_url'] }}">{{ $item['title'] }}</td>
              <td>{{ $item['published_date'] }}</td>
              <input type="hidden" id="title" name="title" value="{{ $item['title'] }}">
              <input type="hidden" id="published_date" name="published_date" value="{{ $item['published_date'] }}">
              <input type="hidden" id="content_ref_id" name="content_ref_id" value="{{ $item['content_ref_id'] }}">
              <input type="hidden" id="article_url" name="article_url" value="{{ $item['article_url'] }}">
              <td><input type="submit" id="pin" value="Pin"></td>
            </tr>
          @endforeach
          </table>
        </div>
      </div>
    @endif

    <!-- Pinned Results -->
    <div class="card-body">
        <div>
          <table>
            <thead>
              <tr>                
                <th>Article</th>
                <th>Published Date</th>
              </tr>
            </thead>
            @if (isset($pinned_results))
              @foreach($pinned_results as $pinned)
                <tr>
                  <td><a href="{{ $pinned['article_url'] }}">{{ $pinned['title'] }}</td>
                  <td>{{ $pinned['published_date'] }}</td>
                </tr>
              @endforeach
            @endif
          </table>
        </div>
      </div>
  </div>
</div>  
</body>
</html>