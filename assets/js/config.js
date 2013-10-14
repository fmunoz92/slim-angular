//Setting up route
window.app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
        when('/articles', {
            templateUrl: 'assets/views/articles/list.html'
        }).
        when('/articles/create', {
            templateUrl: 'assets/views/articles/create.html'
        }).
        when('/articles/:articleId/edit', {
            templateUrl: 'assets/views/articles/edit.html'
        }).
        when('/articles/:articleId', {
            templateUrl: 'assets/views/articles/view.html'
        }).
        when('/', {
            templateUrl: 'assets/views/index.html'
        }).
        otherwise({
            redirectTo: '/'
        });
    }
]);

//Setting HTML5 Location Mode
window.app.config(['$locationProvider',
    function($locationProvider) {
        $locationProvider.hashPrefix("!");
    }
]);