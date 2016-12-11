app.controller('LoginController', function($scope, ajaxRequest, User, $window) {
	$scope.loginUser = function() {
		$scope.nameEmpty = ($scope.name === undefined);
		$scope.passEmpty = ($scope.pass === undefined);
		
		if ($scope.passEmpty || $scope.nameEmpty) {
			return;
		}

		ajaxRequest.loginUser($scope.name, $scope.pass).success(function(response) {
			$scope.success = response.status;

			if ($scope.success) {
				User.set(response.user);
				$window.location.href = '#/';
				$window.location.reload(true);
			}
		});
	}
});
