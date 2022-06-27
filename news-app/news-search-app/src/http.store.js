import gql from 'graphql-tag';
import { observable, makeAutoObservable } from 'mobx';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';
import ApolloClient from 'apollo-client';
import { ApolloLink } from 'apollo-link';

export class HTTPStore {
    static instance: HTTPStore;

    @observable apolloClient = null;
    @observable error = false;
    @observable loading = false;
    baseURL;

    constructor () {
        this.setApolloClient();

        makeAutoObservable(this);
    }

    get isHydrated() {
        return isHydrated(this);
    }

    static getInstance() {
        return HTTPStore.instance || (HTTPStore.instance = new HTTPStore());
    }

    /**
     * Get a generic error message
     */
    static getError() {
        return 'An error occurred. Please try again later.';
    }

    /**
     * get the graphql endpoint used by the app
     */
    getApiUri() {
        const location = window.location.host;

        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            return '//localhost/graphql';
        }

        return `//${location}/graphql`;
    }

    httpLink = new HttpLink({
        uri: this.getApiUri(),
        credentials: 'same-origin'
    });

    authLink = new ApolloLink((operation, forward) => {
        return forward(operation);
    });

    setApolloClient() {
        this.apolloClient = new ApolloClient({
            link: this.authLink.concat(this.httpLink),
            cache: new InMemoryCache().restore(window.__APOLLO_STATE__)
        });
    }

    /**
     * Catch Graphgql servererror
     * this will catch any eg 500 server errors
     * but not any errors returned in the payload data
     * (for a 200 response)
     *
     * @param result
     * @param store
     */
    handleServerError(result, store) {
        if (result.graphQLErrors) {
            const errors = result.graphQLErrors.map(error => error.message);
            store.error = {
                message: errors.join(', '),
                code: 500
            };
        } else if (result.networkError) {
            store.error = {
                message: result.networkError,
                code: 500
            };
        } else {
            store.error = {
                message: result,
                code: 500
            };
        }

        store.loading = false;
    }
};