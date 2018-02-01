app.controller('RegisterController', function($scope, Ajax) {
	
	$scope.createUser = function() {
		$scope.nameEmpty = ($scope.name === undefined);
		$scope.passEmpty = ($scope.pass === undefined);
		
		if ($scope.passEmpty || $scope.nameEmpty) {
			console.log('PassEmpty' + $scope.passEmpty);
			console.log('NameEmpty' + $scope.nameEmpty);
			return;
		}
		
		Ajax.createUser($scope.name, $scope.pass).success(function(result) {
			$scope.success = result.success;
			console.log(result);
		});
	}
});
