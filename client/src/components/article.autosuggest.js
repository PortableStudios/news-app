import React from 'react';
import Autosuggest from 'react-autosuggest';
import './autosuggest.css';

class ArticleAutoSuggest extends React.Component {
    constructor() {
        super();
        this.state = {
            value: '',
            suggestions: []
        };
    }

    handleClick = (event) => {
        event.stopPropagation();
        event.nativeEvent.stopImmediatePropagation();

        let pinned = localStorage.getItem("myPinned");
        let hash = event.target.getAttribute('data-hash');
        pinned = pinned ? pinned.split(',') : [];
        if (!pinned.includes(hash)) {
            pinned.push(hash);
        }
        localStorage.setItem('myPinned', pinned.toString())
        console.log ("Pinned:" + localStorage.getItem("myPinned"));
    }

    // Trigger suggestions
    getSuggestionValue = suggestion => suggestion.name;

    // Render Each Option
    renderSuggestion = suggestion => (
        <div>
            <a href={suggestion.url}>{suggestion.title} ({suggestion.publication_date})</a>
            <button onClick={this.handleClick} data-hash={suggestion.hash}>save</button>
        </div>
    );

    // OnChange event handler
    onChange = (event, { newValue }) => {
        this.setState({
            value: newValue
        });
    };

    // Suggestion rerender when user types
    onSuggestionsFetchRequested = ({ value }) => {
        fetch(`https://n7x58r0npj.execute-api.ap-southeast-2.amazonaws.com/Stage/search?query=${value}`)
            .then(response => response.json())
            .then(data => this.setState({
                suggestions: data
            }))
    }

    // Triggered on clear
    onSuggestionsClearRequested = () => {
        this.setState({
            suggestions: []
        });
    };

    render() {
        const { value, suggestions } = this.state;

        // Option props
        const inputProps = {
            placeholder: 'Search for Articles',
            value,
            onChange: this.onChange
        };

        // Adding AutoSuggest component
        return (
            <Autosuggest
                suggestions={suggestions}
                onSuggestionsFetchRequested={this.onSuggestionsFetchRequested}
                onSuggestionsClearRequested={this.onSuggestionsClearRequested}
                getSuggestionValue={this.getSuggestionValue}
                renderSuggestion={this.renderSuggestion}
                inputProps={inputProps}
            />
        );
    }
}

export default ArticleAutoSuggest;