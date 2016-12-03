app.controller('BoardController', function($scope, $routeParams, ajaxRequest, $window, extensionProvider, $sce) {
	$scope.prefix = $routeParams.prefix;
	ajaxRequest.getBoards().success(function(data) {
		var exists = false;
		for (var i = 0; i < data.length; i++) {
			if (data[i].prefix == $routeParams.prefix) {
				exists = true;
				$scope.boardData = data[i];
				break;
			}
		}

		if (!exists) {
			$window.location.href = '#/404';
		}
	});

	ajaxRequest.getThreads($routeParams.prefix).success(function(data) {
		if (!data.success) {
			return;
		}
		
		for (var i = 0; i < data.data.length; i++) {
			var url = data.data[i].img_url.toString();
			var fileType = extensionProvider.getFileType(url);
			data.data[i].fileType = fileType;

			data.data[i].title = $sce.trustAsHtml(data.data[i].title);

			if (data.data[i].title.toString().length <= 1 || data.data[i].title.toString() == 'undefined') {
				data.data[i].title = $sce.trustAsHtml(data.data[i].content.toString().substring(0, 5) + '..');
			}
		}
		$scope.threads = data.data;
	});

	$scope.createThread = function() {
		$scope.messageEmpty = false;
		if ($scope.message === undefined) {
			$scope.messageEmpty = true;
			angular.element(document.querySelector('#message')).focus();
			return;
		} else {
			$scope.messageEmpty = false;
		}
		
		ajaxRequest.createThread($scope.myFile, $scope.title, $scope.message, $routeParams.prefix).success(function(data) {
			if (data.success) {
				$window.location.href = '#/thread/' + data.data + '/';
				$scope.messageEmpty = false;
			}
		});
	};
});