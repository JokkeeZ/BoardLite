app.config(function($routeProvider) {
	$routeProvider
	.when('/', {
		adminOnly: false,
		templateUrl: 'views/index.html',
		controller: 'BoardListController'
	})
	.when('/board/:prefix/', {
		adminOnly: false,
		templateUrl: 'views/board.html',
		controller: 'BoardController'
	})
	.when('/thread/:id/', {
		adminOnly: false,
		templateUrl: 'views/thread.html',
		controller: 'ThreadController'
	})
	.when('/rules', {
		adminOnly: false,
		templateUrl: 'views/rules.html',
		controller: 'RuleController'
	})
	.when('/register', {
		adminOnly: false,
		templateUrl: 'views/register.html',
		controller: 'RegisterController'
	})
	.when('/login', {
		adminOnly: false,
		templateUrl: 'views/login.html',
		controller: 'LoginController'
	})
	.when('/adminpanel', {
		adminOnly: true,
		templateUrl: 'views/admin/adminpanel.html',
		controller: 'AdminController'
	})
	.when('/adminpanel/edit/:id/', {
		adminOnly: true,
		templateUrl: 'views/admin/edit.html',
		controller: 'BoardEditController'
	})
	.when('/error', {
		adminOnly: false,
		templateUrl: 'views/error.html'
	})
	.otherwise({
		adminOnly: false,
		templateUrl: 'views/404.html'
	});
});
