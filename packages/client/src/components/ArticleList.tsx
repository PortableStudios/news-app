import type { Article } from '../types';

// Props that this component expects
interface ArticleListProps {
  articles: Article[];
  onPinArticle: (article: Article) => void;
  onUnpinArticle: (articleId: string) => void;
  isArticlePinned: (articleId: string) => boolean;
}

function ArticleList({ articles, onPinArticle, onUnpinArticle, isArticlePinned }: ArticleListProps) {
  if (articles.length === 0) {
    return <p>No articles found</p>;
  }

  // Group articles by section
  const groupedArticles = articles.reduce((groups, article) => {
    const section = article.sectionName;
    if (!groups[section]) {
      groups[section] = [];
    }
    groups[section].push(article);
    return groups;
  }, {} as Record<string, Article[]>);

  return (
    <div>
      <h2>Search Results ({articles.length} articles)</h2>
      
      {Object.entries(groupedArticles).map(([sectionName, sectionArticles]) => (
        <div key={sectionName} style={{ marginBottom: '30px' }}>
          <h3 style={{ 
            backgroundColor: '#f0f0f0', 
            padding: '10px', 
            borderLeft: '4px solid #007bff' 
          }}>
            {sectionName} ({sectionArticles.length})
          </h3>
          
          <div style={{ 
            display: 'grid', 
            gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 300px))', 
            gap: '15px', 
            padding: '0 20px' 
          }}>
            {sectionArticles.map((article) => (
              <div key={article.id} style={{ 
                border: '1px solid #ddd', 
                padding: '15px', 
                borderRadius: '8px',
                backgroundColor: '#fff',
                boxShadow: '0 2px 4px rgba(0,0,0,0.1)',
                display: 'flex',
                flexDirection: 'column',
                height: '200px'
              }}>
                <div style={{ flex: '1', display: 'flex', flexDirection: 'column' }}>
                  <div style={{ marginBottom: '10px' }}>
                    <strong style={{ fontSize: '12px', color: '#888', textTransform: 'uppercase' }}>
                      Title:
                    </strong>
                    <h4 style={{ 
                      margin: '5px 0 0 0', 
                      fontSize: '16px',
                      lineHeight: '1.3',
                      overflow: 'hidden',
                      display: '-webkit-box',
                      WebkitLineClamp: 3,
                      WebkitBoxOrient: 'vertical'
                    }}>
                      {article.webTitle}
                    </h4>
                  </div>
                </div>
                
                <div style={{ marginBottom: '10px' }}>
                  <strong style={{ fontSize: '12px', color: '#888', textTransform: 'uppercase' }}>
                    Publication Date:
                  </strong>
                  <p style={{ 
                    color: '#666', 
                    fontSize: '14px', 
                    margin: '5px 0 0 0'
                  }}>
                    {new Date(article.webPublicationDate).toLocaleDateString('en-GB')}
                  </p>
                </div>
                
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <button
                    onClick={() => {
                      if (isArticlePinned(article.id)) {
                        onUnpinArticle(article.id);
                      } else {
                        onPinArticle(article);
                      }
                    }}
                    style={{
                      backgroundColor: isArticlePinned(article.id) ? '#dc3545' : '#28a745',
                      color: 'white',
                      border: 'none',
                      borderRadius: '4px',
                      padding: '5px 10px',
                      fontSize: '12px',
                      cursor: 'pointer',
                      fontWeight: '500'
                    }}
                  >
                    {isArticlePinned(article.id) ? '📌 Unpin' : '📌 Pin'}
                  </button>
                  
                  <a href={article.webUrl} target="_blank" rel="noopener noreferrer" 
                     style={{ 
                       color: '#007bff', 
                       textDecoration: 'none',
                       fontWeight: '500',
                       fontSize: '14px'
                     }}>
                    Read Article →
                  </a>
                </div>
              </div>
            ))}
          </div>
        </div>
      ))}
    </div>
  );
}

export default ArticleList;