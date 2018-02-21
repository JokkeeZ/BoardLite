app.controller('BoardController', function($scope, $routeParams, Ajax, $window, extensionProvider, $sce, User) {
	$scope.paginationIndex = 0;
	$scope.isAdmin = User.isAdmin();

	Ajax.getBoards().success(function(result) {
		let exists = false;
		angular.forEach(result, function(o) {
			if (o.prefix === $routeParams.prefix) {
				exists = true;
				$scope.boardData = o;
			}
		});

		if (!exists) $window.location.href = '#/404';
	});

	$scope.threadCache = [];
	function getThreads() {
		Ajax.getThreads($routeParams.prefix).success(function (result) {
			if (!result.success) return;

			for (let i = 0; i < result.data.length; ++i) {
				let item = result.data[i];
				item.fileType = extensionProvider.getFileType(item.img_url);

				item.content = wrapMessage(item.content);
				if (item.title === 'undefined' || item.title.length <= 1) {
					item.title = item.content;
				}
				else {
					item.title = wrapMessage(item.title);
				}
			}

			$scope.threadCache = chunkArray(result.data, 10);
			$scope.threads = $scope.threadCache[$scope.paginationIndex];
		});
	}

	getThreads();

	function chunkArray(array, chunkSize) {
		let tempArray = [];
		for (let index = 0; index < array.length; index += chunkSize) {
			let chunk = array.slice(index, index + chunkSize);
			tempArray.push(chunk);
		}

		return tempArray;
	}

	function wrapMessage(message) {
		let msg = message.substring(0, 50);
		return msg += (msg.length === 50) ? '...' : '';
	}

	$scope.updatePaginationIndex = function(idx) {
		$scope.paginationIndex = idx;
		getThreads();

		console.log($scope.threadCache.length + ' tc, ' + $scope.paginationIndex + ' idx');
	};

	$scope.createThread = function(data) {
		Ajax.createThread(data.file, data.title, data.message, $routeParams.prefix).success(function(result) {
			if (result.success) {
				$window.location.href = '#/thread/' + result.data + '/';
				$scope.messageEmpty = false;
			}
		});
	};
});
