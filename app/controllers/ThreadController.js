app.controller('ThreadController', function($scope, Ajax, $routeParams, $sce, extensionProvider, $window, User) {
	$scope.isAdmin = User.isAdmin();

	Ajax.getThreadStartPost($routeParams.id).success(function(result) {
		if (!result.success) {
			$window.location.href = '#/404';
			$window.location.reload(true);
			return;
		}

		$scope.startPost = result.data;
		$scope.startPost.title = $sce.trustAsHtml(wrapTitle(result.data));
		$scope.startPost.content = $sce.trustAsHtml(result.data.content);

		console.log(result);

		let url = $scope.startPost.img_url.toString();
		let fileType = extensionProvider.getFileType(url);
		$scope.startPost.fileType = fileType.type;
		$scope.startPost.fileExtension = fileType.extension;

		console.log('Thread start post loaded.');
	});

	function wrapTitle(result) {
		let title = result.title;
		if (title === 'undefined' || title === null || title.length <= 0) {
			if (result.content.length > 50) {
				return result.content.toString().substring(0, 50) + '...';
			}

			return result.content;
		}

		if (title.length > 50) {
			return title.substring(0, 50) + '...';
		}

		return title;
	}

	function getReplies() {
		Ajax.getThreadReplies($routeParams.id).success(function(result) {
			if (!result.success) {
				console.log(result);
				return;
			}
	
			for (let i = 0; i < result.data.length; ++i) {
				let matches = result.data[i].content.toString().match(/\d+/g);
				if (matches != null) {
					for (let j = 0; j < matches.length; ++j) {
						result.data[i].content = result.data[i].content.replace('&gt;&gt;' + matches[j],
							'<a href="#/thread/' + $routeParams.id + '/#msg_' + matches[j] +'">' + '>>' + matches[j] + '</a>')
					}
				}
	
				result.data[i].content = $sce.trustAsHtml(result.data[i].content);

				let url = result.data[i].img_url.toString();
				let fileType = extensionProvider.getFileType(url);
				result.data[i].fileType = fileType.type;
				result.data[i].fileExtension = fileType.extension;
				
				console.log('Replies loaded: ' + result.data.length);
			}
	
			$scope.replies = result.data;
		});
	}

	getReplies();

	$scope.postReply = function(data) {
		Ajax.addReply(data.file, data.message, $routeParams.id).then(function(result) {
			if (result.data.success) {
				data.message = '';
				getReplies();
			}
		});
	};

	$scope.answer = function(replyId) {
		angular.element(document.querySelector('#message')).append('>>' + replyId);
	};

	$scope.setThreadLockState = function() {
		let state = $scope.startPost.locked === '1' ? '0' : '1';

		Ajax.setThreadLockState($scope.startPost.id, state).then(function(result) {
			if (result.data.success) {
				$scope.startPost.locked = state;
			}
		});
	};
});
