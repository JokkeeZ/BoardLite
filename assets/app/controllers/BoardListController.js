app.controller('BoardListController', function($scope, ajaxRequest) {
	$scope.firstList = [];
	$scope.secondList = [];
	
	ajaxRequest.getBoards().success(function(data) {
		var split = Math.ceil(data.length / 2);
		for (var i = 0; i < split; i++) {
			$scope.firstList.push(data[i]);
		}

		for (var i = split; i < data.length; i++) {
			$scope.secondList.push(data[i]);
		}
	});
});