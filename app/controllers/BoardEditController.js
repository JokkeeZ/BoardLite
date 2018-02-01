app.controller('BoardEditController', function($scope, $routeParams, Ajax) {
	Ajax
	.getBoards()
	.success(function(response) {
		if (response.length > 0) {
			for (let i = 0; i < response.length; ++i) {
				if (response[i].id === $routeParams.id) {
					$scope.name = response[i].name;
					$scope.prefix = response[i].prefix;
					$scope.desc = response[i].description;
					$scope.tag = response[i].tag;
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
		.success(function(response) {
			$scope.updateSuccess = response.success;
		});
	}
});
