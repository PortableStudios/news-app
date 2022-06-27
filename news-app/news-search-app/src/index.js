import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import reportWebVitals from './reportWebVitals';
import { HTTPStore } from './http.store';
import { NewsStore } from './news.store';
import { Provider } from 'mobx-react';

const root = ReactDOM.createRoot(document.getElementById('root')),
  httpStore = HTTPStore.getInstance(),
  newsStore = NewsStore.getInstance(httpStore),
  rootStores = {
    httpStore,
    newsStore
  };

newsStore.fetchArticles();

root.render(
  <React.StrictMode>
    <Provider {...rootStores}>
      <App />
    </Provider>
  </React.StrictMode>
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
