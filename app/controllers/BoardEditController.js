app.controller('BoardEditController', function($scope, $routeParams, Ajax) {
	Ajax.getBoards().success(function(result) {
		for (let i = 0; i < result.length; ++i) {
			if (result[i].id === $routeParams.id) {
				$scope.name = result[i].name;
				$scope.prefix = result[i].prefix;
				$scope.desc = result[i].description;
				$scope.tag = result[i].tag;
				break;
			}
		}
	});

	$scope.updateBoard = function(data) {
		Ajax.updateBoard($routeParams.id, data.name, data.desc, data.prefix, data.tag)
			.success(function(result) {
			$scope.updateSuccess = result.success;
		});
	}
});
