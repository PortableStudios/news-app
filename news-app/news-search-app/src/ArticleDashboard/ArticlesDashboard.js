import React, { useState } from 'react';
import { inject, observer } from 'mobx-react';
import './article.scss';
import '../app.scss';
import './search-bar.scss';

const App = inject('newsStore')(observer((props) => {
    const { newsStore } = props,
        { articles } = newsStore,
        formattedDate = (day) => {
            const date = new Date(day),
                result = `${date.getDate()}/${(date.getMonth() + 1)}/${date.getFullYear()}`;
                
            return result;
        },
        ArticleView = (props) => {
            const { article } = props,
                [isPinned, setIsPinned] = useState(article.isPinned),
                { title, sectionTitle, webURL, publicationDate } = article,
                handlePinArticle = () => {
                    const pinnedStatus = !isPinned;
                    setIsPinned(pinnedStatus);
                    newsStore.pinArticle(article.id, pinnedStatus);
                }

            return (
                <div className='article__wrapper'>
                    <div className='article'>
                        { sectionTitle &&
                            <span className='article__section-tag'>
                                { sectionTitle }
                            </span>
                        }
                        { title &&
                            <h4 className='article'>
                                { title }
                            </h4>
                        }
                        { publicationDate &&
                            <span className='article__date'>
                                { formattedDate(publicationDate) }
                            </span>
                        }
                        { webURL && 
                            <a className='article__link' href={webURL} target="_blank">
                                Read Article
                            </a>
                        }
                        <button onClick={ (e) => { handlePinArticle(e.target.value); } } className='article__pin-button'>
                            { isPinned ? (
                                'Unpin Article'
                            ) : (
                                'Pin Article'
                            )}
                        </button>
                    </div>
                </div>
            )
        },
        ArticlesView = () => {
            return (
                <div className='articles-dashboard'>
                    <h2>
                        Articles
                    </h2>
                    <span>Found { articles.length } articles</span>
                    <>
                        { articles && articles.length > 0 ? articles.map((article, i) => {
                            return <ArticleView key={i} article={ article } />
                         }) :
                            <div>
                                <h3>No Articles Found</h3>
                            </div>
                        }
                    </>
                </div>
            )
        },
        handleSearchInput = (value) => {
            newsStore.searchArticles(value);
        };


    return (
        <div className="App container">
            <header className="App-header">
                <h1>
                    Search News Articles
                </h1>
                <div className="search-bar">
                    <label className="search-bar__label">
                        Search for a news article
                    </label>
                    <input className="search-bar__input" type="search" name="news-article" placeholder="search for an article" onChange={ (e) => { handleSearchInput(e.target.value); } } />
                </div>
                <ArticlesView />
            </header>
        </div>
    );
}));

export default App;
