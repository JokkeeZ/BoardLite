app.config(function($routeProvider) {
	$routeProvider
	.when('/', {
		templateUrl: 'views/index.html',
		controller: 'BoardListController'
	})
	.when('/board/:prefix/', {
		templateUrl: 'views/board.html',
		controller: 'BoardController'
	})
	.when('/thread/:id/', {
		templateUrl: 'views/thread.html',
		controller: 'ThreadController'
	})
	.when('/rules', {
		templateUrl: 'views/rules.html',
		controller: 'RuleController'
	})
	.when('/register', {
		templateUrl: 'views/register.html',
		controller: 'RegisterController'
	})
	.when('/login', {
		templateUrl: 'views/login.html',
		controller: 'LoginController'
	})
	.when('/adminpanel', {
		templateUrl: 'views/admin/adminpanel.html',
		controller: 'AdminController'
	})
	.otherwise({
		templateUrl: 'views/404.html'
	});
});