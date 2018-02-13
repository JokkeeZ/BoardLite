app.controller('BoardListController', function($scope, Ajax) {
	$scope.firstList = [];
	$scope.secondList = [];

	Ajax.getBoards().success(function(result) {
		console.log(result);
		var split = Math.ceil(result.length / 2);

		for (let i = 0; i < split; ++i) {
			$scope.firstList.push(result[i]);
		}

		for (let i = split; i < result.length; ++i) {
			$scope.secondList.push(result[i]);
		}
	});
});
