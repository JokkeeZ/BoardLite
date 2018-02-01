app.controller('LoginController', function($scope, Ajax, User, $window) {
	$scope.loginUser = function() {
		$scope.nameEmpty = ($scope.name === undefined);
		$scope.passEmpty = ($scope.pass === undefined);
		
		if ($scope.passEmpty || $scope.nameEmpty) return;

		Ajax.loginUser($scope.name, $scope.pass).success(function(result) {
			$scope.success = result.success;

			if ($scope.success) {
				User.set(result.user);
				$window.location.href = '#/';
				$window.location.reload(true);
			}
		});
	}
});
