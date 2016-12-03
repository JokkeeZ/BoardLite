/**
 * Navbar controller mainly sets just brand text on navbar 
**/
app.controller('NavbarController', function($scope, ajaxRequest) {
	ajaxRequest.getAppConfig().success(function(data) {
		$scope.appName = data.app_name;
	});
});

/**
 * Head controller sets title text. 
**/
app.controller('HeadController', function($rootScope, $scope, ajaxRequest) {
	ajaxRequest.getAppConfig().success(function(data) {
		$scope.applicationTitle = data.app_name;
	});
});