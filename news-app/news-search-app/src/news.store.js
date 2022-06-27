import gql from 'graphql-tag';
import { observable, makeObservable, action } from 'mobx';

const ReadArticles = gql`
    query ReadArticles {
        readArticles {
            id
            title
            sectionID
            sectionTitle
            webURL
            publicationDate
            isPinned
        }
    }`,
    SearchArticles = gql`
        query SearcArticles(
            $query: String
        ) {
            searchArticles(
                query: $query
            ) {
                id
                title
                sectionID
                sectionTitle
                webURL
                publicationDate
                isPinned
            }
        }
    `,
    PinArticle = gql`
        mutation PinArticle(
            $id: ID
            $isPinned: Boolean
        ) {
            pinArticle(
                id: $id,
                isPinned: $isPinned
            ) {
                id
            }
        }
`;

export class NewsStore {
    static instance;
    @observable httpStore = false;
    @observable loading = false;
    @observable errorMessage = false;
    @observable articles = [];

    constructor(httpStore) {
        this.httpStore = httpStore;
        makeObservable(this);
    }

    static getInstance(httpStore) {
        return NewsStore.instance || (NewsStore.instance = new NewsStore(httpStore));
    }

    // setPagination(pageInfo, count) {
    //     this.hasNextPage = pageInfo.hasNextPage;
    //     this.totalCount = pageInfo.totalCount;
    //     this.currentCount = this.currentCount + count;
    // }

    @action async fetchArticles(query, limit = 0, offset = 0) {
        const result = await this.httpStore.apolloClient
            .query({
                query: ReadArticles,
                variables: {
                    query: query ? query : null
                },
                fetchPolicy: 'no-cache'
            }).catch(result => {
                const errors = result.graphQLErrors ? result.graphQLErrors.map(error => error.message) : result;
                this.errorMessage = errors.join(', ');
            });

        if (result) {
            this.errorMessage = false;
            const articleResults = result.data.readArticles;

            this.articles = [...articleResults];
        } else {
            this.errorMessage = result.data.Message;
        }
    };

    @action async searchArticles(query, limit = 0, offset = 0) {
        const result = await this.httpStore.apolloClient
            .query({
                query: SearchArticles,
                variables: {
                    query: query ? query : null
                },
                fetchPolicy: 'no-cache'
            }).catch(result => {
                const errors = result.graphQLErrors ? result.graphQLErrors.map(error => error.message) : result;
                this.errorMessage = errors.join(', ');
            });

        if (result) {
            this.errorMessage = false;
            const articleResults = result.data.searchArticles;

            this.articles = [...articleResults];
        } else {
            this.errorMessage = result.data.Message;
        }

        this.loading = false;
    };

    @action async pinArticle(articleID, isPinned) {
        const result = await this.httpStore.apolloClient
            .mutate({
                mutation: PinArticle,
                variables: {
                    id: articleID ? articleID : null,
                    isPinned: isPinned ? isPinned : null
                },
            }).catch(result => {
                const errors = result.graphQLErrors ? result.graphQLErrors.map(error => error.message) : result;
                this.errorMessage = errors.join(', ');
            });
    };
}
