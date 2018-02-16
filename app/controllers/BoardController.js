app.controller('BoardController', function($scope, $routeParams, Ajax, $window, extensionProvider, $sce, User) {

	$scope.paginationIndex = 0;

	$scope.isAdmin = User.isAdmin();
	Ajax.getBoards().success(function(result) {
		var exists = false;
		angular.forEach(result, function(o) {
			if (o.prefix == $routeParams.prefix) {
				exists = true;
				$scope.boardData = o;
			}
		});

		if (!exists) {
			$window.location.href = '#/404';
		}
	});

	$scope.threadCache = [];
	function getThreads() {
		Ajax.getThreads($routeParams.prefix).success(function (result) {
			if (!result.success) return;

			for (let i = 0; i < result.data.length; ++i) {
				let item = result.data[i];
				item.fileType = extensionProvider.getFileType(item.img_url);

				item.content = wrapMessage(item.content);
				if (item.title == 'undefined' || item.title.length <= 1) {
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

	function chunkArray(myArray, chunk_size) {
		var index = 0;
		var arrayLength = myArray.length;
		var tempArray = [];

		for (index = 0; index < arrayLength; index += chunk_size) {
			myChunk = myArray.slice(index, index + chunk_size);
			// Do something if you want with the group
			tempArray.push(myChunk);
		}

		return tempArray;
	}

	function wrapMessage(message) {
		let msg = message.substring(0, 50);

		if (msg.length == 50) {
			msg += '...';
		}

		return msg;
	}

	$scope.updatePaginationIndex = function(idx) {
		$scope.paginationIndex = idx;
		getThreads();

		console.log($scope.threadCache.length + ' tc, ' + $scope.paginationIndex + ' idx');
	};

	$scope.createThread = function() {
		$scope.messageEmpty = false;
		if ($scope.message === undefined) {
			$scope.messageEmpty = true;
			angular.element(document.querySelector('#message')).focus();
			return;
		}
		
		Ajax.createThread($scope.myFile, $scope.title, $scope.message, $routeParams.prefix).success(function(result) {
			if (result.success) {
				console.log('Thread created with id: ' + result.data);
				$window.location.href = '#/thread/' + result.data + '/';
				$scope.messageEmpty = false;
			}

			console.log(result);
		});
	};
});
