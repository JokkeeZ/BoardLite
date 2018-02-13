app.controller('BoardEditController', function($scope, $routeParams, Ajax) {
	Ajax.getBoards().success(function(result) {
		if (result.length > 0) {
			for (let i = 0; i < result.length; ++i) {
				if (result[i].id === $routeParams.id) {
					$scope.name = result[i].name;
					$scope.prefix = result[i].prefix;
					$scope.desc = result[i].description;
					$scope.tag = result[i].tag;
					break;
				}
			}
		}
	});

	$scope.updateBoard = function() {
		$scope.nameEmpty = ($scope.name === undefined);
		$scope.prefixEmpty = ($scope.prefix === undefined);
		$scope.descEmpty = ($scope.desc === undefined);
		$scope.tagEmpty = ($scope.tag === undefined);
		$scope.updateSuccess = false;

		if ($scope.nameEmpty
			|| $scope.prefixEmpty
			|| $scope.descEmpty
			|| $scope.tagEmpty)
			return;

		Ajax
		.updateBoard($routeParams.id, $scope.name, $scope.desc, $scope.prefix, $scope.tag)
		.success(function(result) {
			$scope.updateSuccess = result.success;
		});
	}
});
