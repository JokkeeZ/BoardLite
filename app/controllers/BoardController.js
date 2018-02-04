// TODO: Incase admin changes prefix on adminpanel, threads on that board are not loaded.
// In future load and create threads by board id.
app.controller('BoardController', function($scope, $routeParams, Ajax, $window, extensionProvider, $sce, User) {

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

	Ajax.getThreads($routeParams.prefix).success(function(result) {
		if (!result.success) return;

		angular.forEach(result.data, function(item, idx) {
			result.data[idx].fileType = extensionProvider.getFileType(item.img_url);

			if (item.title.length <= 1 || item.title == 'undefined') {
				item.title = wrapMessage(item.content);
			}
			else {
				item.title = wrapMessage(item.title);
			}

			item.content = wrapMessage(item.content);
		});

		$scope.threads = result.data;

		console.log($scope.threads);
	});

	function wrapMessage(message) {
		let msg = message.substring(0, 50);

		if (msg.length == 50) {
			msg += '...';
		}

		return msg;
	}

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
