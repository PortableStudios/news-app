import { useState, useEffect } from 'react';
import ArticleList from './components/ArticleList';
import Pagination from './components/Pagination';
import PinnedArticles from './components/PinnedArticles';
import type { Article } from './types';

function App() {
  // State to track what user types
  const [searchQuery, setSearchQuery] = useState('');
  // State to track search results - now properly typed!
  const [results, setResults] = useState<Article[]>([]);
  // State to track pagination
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(0);
  // State to track pinned articles
  const [pinnedArticles, setPinnedArticles] = useState<Article[]>([]);

  // Debounced search effect
  useEffect(() => {
    // Only search if query is long enough
    if (searchQuery.length > 2) {
      // Set up debounce timer
      const debounceTimer = setTimeout(() => {
        searchNews(searchQuery, 1); // Always start from page 1 for new searches
      }, 500); // 500ms delay

      // Cleanup function - cancels the timer if user keeps typing
      return () => clearTimeout(debounceTimer);
    } else {
      // Clear results if query is too short
      setResults([]);
      setCurrentPage(1);
      setTotalPages(0);
    }
  }, [searchQuery]); // Run effect whenever searchQuery changes

  // Function to call our server API
  const searchNews = async (query: string, page: number = 1) => {
    try {
      const response = await fetch(`http://localhost:5544/api/search?q=${query}&page=${page}`);
      const data = await response.json();

      setResults(data.response?.results || []);
      setTotalPages(data.response?.pages || 0);
      setCurrentPage(page);
    } catch (error) {
      console.error('API call failed:', error);
    }
  };

  // Handle page changes
  const handlePageChange = (newPage: number) => {
    if (searchQuery.length > 2) {
      searchNews(searchQuery, newPage);
    }
  };

  // Handle pinning an article (with limit)
  const handlePinArticle = (article: Article) => {
    if (!pinnedArticles.find(pinned => pinned.id === article.id)) {
      if (pinnedArticles.length >= 10) {
        alert('You can only pin up to 10 articles. Please unpin some articles first.');
        return;
      }
      setPinnedArticles(prev => [...prev, article]);
    }
  };

  // Clear all pinned articles
  const handleClearAllPins = () => {
    if (pinnedArticles.length > 0) {
      const confirmed = window.confirm(`Are you sure you want to unpin all ${pinnedArticles.length} articles?`);
      if (confirmed) {
        setPinnedArticles([]);
      }
    }
  };

  // Handle unpinning an article
  const handleUnpinArticle = (articleId: string) => {
    setPinnedArticles(prev => prev.filter(article => article.id !== articleId));
  };

  // Check if article is pinned
  const isArticlePinned = (articleId: string) => {
    return pinnedArticles.some(article => article.id === articleId);
  };

  return (
    <>
      <h1>Portable News App</h1>
      
      <input 
        placeholder="Search for news..."
        value={searchQuery}
        onChange={(e) => setSearchQuery(e.target.value)}
        style={{
          padding: '10px',
          fontSize: '16px',
          width: '400px',
          border: '2px solid #ddd',
          borderRadius: '8px',
          marginBottom: '20px'
        }}
      />
      

        {/* Show pinned articles - stays visible as search results change */}
        <PinnedArticles
            articles={pinnedArticles}
            onUnpinArticle={handleUnpinArticle}
            onClearAll={handleClearAllPins}
        />

      {/* Display all articles using our component */}
      <ArticleList 
        articles={results}
        onPinArticle={handlePinArticle}
        onUnpinArticle={handleUnpinArticle}
        isArticlePinned={isArticlePinned}
      />
      
      {/* Show pagination controls */}
      <Pagination 
        currentPage={currentPage}
        totalPages={totalPages}
        onPageChange={handlePageChange}
      />
      

    </>
  )
}

export default App
