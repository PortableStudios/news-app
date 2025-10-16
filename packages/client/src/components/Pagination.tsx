interface PaginationProps {
  currentPage: number;
  totalPages: number;
  onPageChange: (page: number) => void;
}

function Pagination({ currentPage, totalPages, onPageChange }: PaginationProps) {
  if (totalPages <= 1) return null; // Don't show pagination if only 1 page

  return (
    <div style={{ 
      display: 'flex', 
      justifyContent: 'center', 
      alignItems: 'center', 
      gap: '10px',
      margin: '20px 0',
      padding: '10px'
    }}>
      {/* Previous button */}
      <button 
        onClick={() => onPageChange(currentPage - 1)}
        disabled={currentPage <= 1}
        style={{
          padding: '8px 12px',
          backgroundColor: currentPage <= 1 ? '#f5f5f5' : '#007bff',
          color: currentPage <= 1 ? '#999' : 'white',
          border: 'none',
          borderRadius: '4px',
          cursor: currentPage <= 1 ? 'not-allowed' : 'pointer'
        }}
      >
        ← Previous
      </button>

      {/* Page info */}
      <span style={{ margin: '0 15px', fontSize: '14px' }}>
        Page {currentPage} of {totalPages}
      </span>

      {/* Next button */}
      <button 
        onClick={() => onPageChange(currentPage + 1)}
        disabled={currentPage >= totalPages}
        style={{
          padding: '8px 12px',
          backgroundColor: currentPage >= totalPages ? '#f5f5f5' : '#007bff',
          color: currentPage >= totalPages ? '#999' : 'white',
          border: 'none',
          borderRadius: '4px',
          cursor: currentPage >= totalPages ? 'not-allowed' : 'pointer'
        }}
      >
        Next →
      </button>
    </div>
  );
}

export default Pagination;