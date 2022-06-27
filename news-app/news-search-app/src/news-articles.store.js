import { observable, makeObservable, action } from 'mobx';

export class NewsArticleStore {
    static instance;
    @observable loading = false;
    @observable errorMessage = false;
    @observable newsArticles = [];

    static getInstance() {
        return NewsArticleStore.instance || (NewsArticleStore.instance = new NewsArticleStore());
    }

    // setPagination(pageInfo, count) {
    //     this.hasNextPage = pageInfo.hasNextPage;
    //     this.totalCount = pageInfo.totalCount;
    //     this.currentCount = this.currentCount + count;
    // }
}
