app.controller('AdminController', function($scope, User, ajaxRequest) {
	ajaxRequest.getBoards().success(function(response) {
		$scope.boards = response;
	});
	
	$scope.deleteBoard = function(id) {
		ajaxRequest.deleteBoard(id).success(function(response) {
			for (var i = 0; i < $scope.boards.length; i++) {
				if ($scope.boards[i].id == id) {
					$scope.boards.splice(i);
					break;
				}
			}
		});
	};
	
	$scope.createBoard = function() {
		$scope.nameEmpty = ($scope.name === undefined);
		$scope.prefixEmpty = ($scope.prefix === undefined);
		$scope.descEmpty = ($scope.desc === undefined);
		$scope.tagEmpty = ($scope.tag === undefined);
		
		if ($scope.nameEmpty || $scope.prefixEmpty || $scope.descEmpty || $scope.tagEmpty) {
			return;
		}
		
		ajaxRequest.createBoard($scope.name, $scope.desc, $scope.prefix, $scope.tag).success(function(response) {
			if (response.status) {
				ajaxRequest.getBoards().success(function(response) {
					$scope.boards = response;
				});
			}
		});
	};
});