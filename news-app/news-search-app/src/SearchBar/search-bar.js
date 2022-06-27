import React from 'react';
import './search-bar.scss';

const SearchBar = () => {
    return (
        <div className="search-bar">
            <label className="search-bar__label">
                Search for a news article
            </label>
            <input className="search-bar__input" type="search" name="news-article" />
        </div>
    )
};

export default SearchBar;