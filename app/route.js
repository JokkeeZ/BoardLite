app.config(function($routeProvider) {
	$routeProvider
	.when('/', {
		adminOnly: false,
		templateUrl: 'views/index.html'
	})
	.when('/board/:prefix/', {
		adminOnly: false,
		templateUrl: 'views/board.html'
	})
	.when('/thread/:id/', {
		adminOnly: false,
		templateUrl: 'views/thread.html'
	})
	.when('/rules', {
		adminOnly: false,
		templateUrl: 'views/rules.html'
	})
	.when('/register', {
		adminOnly: false,
		templateUrl: 'views/register.html'
	})
	.when('/login', {
		adminOnly: false,
		templateUrl: 'views/login.html'
	})
	.when('/adminpanel', {
		adminOnly: true,
		templateUrl: 'views/admin/adminpanel.html'
	})
	.when('/adminpanel/edit/:id/', {
		adminOnly: true,
		templateUrl: 'views/admin/edit.html'
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
