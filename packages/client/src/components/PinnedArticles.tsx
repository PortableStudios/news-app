import type { Article } from '../types';

// Props that this component expects
interface PinnedArticlesProps {
  articles: Article[];
  onUnpinArticle: (articleId: string) => void;
  onClearAll: () => void;
}

function PinnedArticles({ articles, onUnpinArticle, onClearAll }: PinnedArticlesProps) {
  if (articles.length === 0) {
    return (
      <div style={{ 
        margin: '30px 0',
        padding: '20px',
        backgroundColor: '#f8f9fa',
        borderRadius: '8px',
        border: '2px dashed #dee2e6'
      }}>
        <h2 style={{ margin: '0 0 10px 0', color: '#6c757d' }}>📌 Pinned Articles</h2>
        <p style={{ margin: 0, color: '#6c757d', fontStyle: 'italic' }}>
          No articles pinned yet. Click the pin button on any article to save it here.
        </p>
      </div>
    );
  }

  return (
    <div style={{ 
      margin: '30px 0',
      padding: '20px',
      backgroundColor: '#fff3cd',
      borderRadius: '8px',
      border: '2px solid #ffeaa7'
    }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
        <h2 style={{ margin: 0, color: '#856404' }}>
          📌 Pinned Articles ({articles.length}/10)
        </h2>
        <button
          onClick={onClearAll}
          style={{
            backgroundColor: '#dc3545',
            color: 'white',
            border: 'none',
            borderRadius: '4px',
            padding: '8px 15px',
            fontSize: '14px',
            cursor: 'pointer',
            fontWeight: '500'
          }}
        >
          🗑️ Clear All
        </button>
      </div>
      
      <div style={{ 
        display: 'grid', 
        gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 300px))', 
        gap: '15px'
      }}>
        {articles.map((article) => (
          <div key={article.id} style={{ 
            border: '1px solid #ffeaa7', 
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
                onClick={() => onUnpinArticle(article.id)}
                style={{
                  backgroundColor: '#dc3545',
                  color: 'white',
                  border: 'none',
                  borderRadius: '4px',
                  padding: '5px 10px',
                  fontSize: '12px',
                  cursor: 'pointer',
                  fontWeight: '500'
                }}
              >
                📌 Unpin
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
  );
}

export default PinnedArticles;