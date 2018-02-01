app.controller('NavbarController', function($rootScope, $scope, Ajax, User, $window) {

	$scope.loggedIn = User.isLoggedIn();
	$scope.isAdmin = User.isAdmin();
	
	Ajax.getAppConfig().success(function(response) {
		$scope.appName = response.app_name;
	});

	$scope.logout = function() {
		User.destroy();
		$scope.loggedIn = false;
		$scope.isAdmin = false;

		$window.location.href = '#/';
		$window.location.reload(true);
	};

	// Bootstrap navbar collapse fix
	$scope.isCollapsed = true;
	$scope.$on('$routeChangeSuccess', function() {
		$scope.isCollapsed = true;
	});
});

app.controller('HeadController', function($rootScope, $scope, Ajax) {
	Ajax.getAppConfig().success(function(response) {
		$rootScope.appName = response.app_name;
	});
});
