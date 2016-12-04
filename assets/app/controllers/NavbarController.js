app.controller('NavbarController', function($scope, ajaxRequest) {
	ajaxRequest.getAppConfig().success(function(response) {
		$scope.appName = response.app_name;
	});
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