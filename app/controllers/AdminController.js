app.controller('AdminController', function($scope, User, Ajax) {

	function getBoards() {
		Ajax.getBoards().success(function(result) {
			$scope.boards = result;
		});
	};
	getBoards();

	$scope.deleteBoard = function(id) {
		var result = confirm('Selected board and its threads + replies will be deleted permanently.');
		if (!result) return;

		Ajax.deleteBoard(id).success(function(result) {
			if (result.success) {
				for (let i = 0; i < $scope.boards.length; ++i) {
					if ($scope.boards[i].id == id) {
						$scope.boards.splice(i);
						break;
					}
				}
			}
		});
	};
	
	$scope.createBoard = function() {
		$scope.nameEmpty = ($scope.name === undefined);
		$scope.prefixEmpty = ($scope.prefix === undefined);
		$scope.descEmpty = ($scope.desc === undefined);
		$scope.tagEmpty = ($scope.tag === undefined);

		if ($scope.nameEmpty
			|| $scope.prefixEmpty
			|| $scope.descEmpty
			|| $scope.tagEmpty)
			return;

		Ajax.createBoard($scope.name, $scope.desc, $scope.prefix, $scope.tag).success(function(result) {
			if (result.success) {
				getBoards();
			}
		});
	};
});
