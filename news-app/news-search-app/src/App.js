import React from 'react';
import SearchBar from './SearchBar/search-bar';
import './app.scss';

const App = () => {
  return (
    <div className="App container">
      <header className="App-header">
        <h1>
          Search News Articles
        </h1>
        <SearchBar />
      </header>
    </div>
  );
}

export default App;
