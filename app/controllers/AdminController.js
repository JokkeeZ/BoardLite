app.controller('AdminController', function($scope, User, Ajax) {

	function getBoards() {
		Ajax.getBoards().success(function(result) {
			$scope.boards = result;
		});
	}
	getBoards();

	$scope.deleteBoard = function(id) {
		let result = confirm('Selected board and its threads will be deleted permanently.');
		if (!result) return;

		Ajax.deleteBoard(id).success(function(result) {
			if (result.success) {
				for (let i = 0; i < $scope.boards.length; ++i) {
					if ($scope.boards[i].id === id) {
						$scope.boards.splice(i);
						break;
					}
				}
			}
		});
	};
	
	$scope.createBoard = function(name, desc, prefix, tag) {
		Ajax.createBoard(name, desc, prefix, tag).success(function(result) {
			if (result.success) {
				getBoards();
			}
		});
	};
});
