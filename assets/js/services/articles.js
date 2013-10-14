//Articles service used for articles REST endpoint
angular.module('mean.articles').factory("Articles", ['$resource', function($resource) {
    return $resource('index.php/articles/:articleId', {
        articleId: '@id'
    }, {
        update: {
            method: 'PUT'
        }
    });
}]);