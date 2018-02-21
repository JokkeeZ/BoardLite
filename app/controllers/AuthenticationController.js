app.controller('AuthenticationController', function($scope, Ajax, User, $window) {
	
	$scope.loggedIn = User.isLoggedIn();
	$scope.admin = $scope.loggedIn && User.isAdmin();

	$scope.loginUser = function(data) {
		Ajax.loginUser(data.name, data.pass).success(function(result) {
			$scope.success = result.success;

			if ($scope.success) {
				User.set(result.user);

				$window.location.href = '#/';
				$window.location.reload(true);
			}
		});
	};

	$scope.createUser = function(data) {
		Ajax.createUser(data.name, data.pass).success(function (result) {
			$scope.success = result.success;
			
			if ($scope.success) {

				// Login user.
				$scope.loginUser(data);
			}
		});
	};

	$scope.logout = function () {
		User.destroy();

		$window.location.href = '#/';
		$window.location.reload(true);
	};
});
