// TODO: Incase admin changes prefix on adminpanel, threads on that board are not loaded.
// In future load and create threads by board id.
app.controller('BoardController', function($scope, $routeParams, Ajax, $window, extensionProvider, $sce, User) {

	$scope.isAdmin = User.isAdmin();
	Ajax.getBoards().success(function(data) {
		var exists = false;
		angular.forEach(data, function(o) {
			if (o.prefix == $routeParams.prefix) {
				exists = true;
				$scope.boardData = o;
			}
		});

		if (!exists) {
			$window.location.href = '#/404';
		}
	});

	Ajax.getThreads($routeParams.prefix).success(function(response) {
		if (!response.success) return;

		angular.forEach(response.data, function(item, idx) {
			response.data[idx].fileType = extensionProvider.getFileType(item.img_url);

			response.data[idx].title = $sce.trustAsHtml(item.title);
			if (item.title.length <= 1 || item.title == 'undefined') {
				response.data[idx].title = $sce.trustAsHtml(item.content.toString().substring(0, 5) + '..');
			}
		});

		$scope.threads = response.data;

		console.log($scope.threads);
	});

	$scope.createThread = function() {
		$scope.messageEmpty = false;
		if ($scope.message === undefined) {
			$scope.messageEmpty = true;
			angular.element(document.querySelector('#message')).focus();
			return;
		}
		
		Ajax.createThread($scope.myFile, $scope.title, $scope.message, $routeParams.prefix).success(function(data) {
			if (data.success) {
				console.log('Thread created with id: ' + data.data);
				$window.location.href = '#/thread/' + data.data + '/';
				$scope.messageEmpty = false;
			}

			console.log(data);
		});
	};
});
