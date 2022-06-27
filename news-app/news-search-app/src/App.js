import React from 'react';
import ArticlesDashboard from './ArticleDashboard/ArticlesDashboard';
import './app.scss';

const App = () => {
    return (
        <div className="App container">
            <header className="App-header">
                <ArticlesDashboard />
            </header>
        </div>
    );
}

export default App;
