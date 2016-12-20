app.controller('RegisterController', function($scope, ajaxRequest) {
	$scope.createUser = function() {
		$scope.nameEmpty = ($scope.name === undefined);
		$scope.passEmpty = ($scope.pass === undefined);
		
		if ($scope.passEmpty || $scope.nameEmpty) {
			return;
		}
		
		ajaxRequest.createUser($scope.name, $scope.pass).success(function(response) {
			$scope.success = response.status;
			console.log(response);
		});
	}
});
