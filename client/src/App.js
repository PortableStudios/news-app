import logo from './logo.svg';
import './App.css';
import ArticleAutoSuggest from "./components/article.autosuggest";

function App() {
  return (
    <div className="App">
      <header className="App-header">
        <h1>Search for Articles</h1>
        <ArticleAutoSuggest />
      </header>
    </div>
  );
}

export default App;
