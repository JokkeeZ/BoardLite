app.controller('BoardListController', function($scope, Ajax) {
	$scope.boards = [];
	
	Ajax.getBoards().success(function(result) {
		$scope.boards = result;
		console.log(result);
	});
});
