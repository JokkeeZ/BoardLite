app.controller('NavbarController', function($scope, ajaxRequest, User, $window) {
	
	$scope.loggedIn = User.isLoggedIn();
	$scope.isAdmin = User.isAdmin();
	
	ajaxRequest.getAppConfig().success(function(response) {
		$scope.appName = response.app_name;
	});
	
	$scope.logout = function() {
		User.destroy();
		$scope.loggedIn = false;
		$scope.isAdmin = false;
		
		$window.location.href = '#/';
		$window.location.reload(true);
	};
});

app.controller('HeadController', function($rootScope, $scope, ajaxRequest) {
	ajaxRequest.getAppConfig().success(function(response) {
		$scope.applicationTitle = response.app_name;
	});
	
	$rootScope.$on("$routeChangeSuccess", function(event, next, current) {
		ajaxRequest.createToken().success(function(response) {
			$rootScope.token = response.token;
		});
	});
});
