app.controller('ThreadController', function($scope, ajaxRequest, $routeParams, $sce, extensionProvider, $window) {
	ajaxRequest.getThreadStartPost($routeParams.id).success(function(data) {
		if (data.success) {
			$scope.startPost = data.data;
			$scope.startPost.content = $sce.trustAsHtml(data.data.content);
			$scope.startPost.title = $sce.trustAsHtml(data.data.title);

			if ($scope.startPost.title == 'undefined' || $scope.startPost.title == '' || $scope.startPost.title == null) {
				$scope.startPost.title = $sce.trustAsHtml(data.data.content.toString().substring(0, 5));
			}
			
			var url = $scope.startPost.img_url.toString();
			var fileType = extensionProvider.getFileType(url);
			$scope.startPost.fileType = fileType.type;
			$scope.startPost.fileExtension = fileType.extension;
			
			console.log('Startpost loaded');
		}
	});

	ajaxRequest.getThreadReplys($routeParams.id).success(function(data) {
		if (data.data == 'Cannot get thread replys.') {
			return;
		}

		for (var i = 0; i < data.data.length; i++) {
			var matches = data.data[i].content.toString().match(/\d+/g);
			if (matches != null) {
				for (var x = 0; x < matches.length; x++) {
					data.data[i].content = data.data[i].content
						.replace('&gt;&gt;' + matches[x],
						'<a href="#/thread/' + $routeParams.id + '/#msg_' + matches[x] +'">' + '>>' + matches[x] + '</a>')
				}
			}

			data.data[i].content = $sce.trustAsHtml(data.data[i].content);

			var url = data.data[i].img_url.toString();
			var fileType = extensionProvider.getFileType(url);
			data.data[i].fileType = fileType.type;
			data.data[i].fileExtension = fileType.extension;
			
			console.log('Replies loaded: ' + data.data.length);
		}

		$scope.replys = data.data;
	});

	$scope.postReply = function() {
		$scope.messageEmpty = false;
		if ($scope.message === undefined) {
			$scope.messageEmpty = true;
			angular.element(document.querySelector('#message')).focus();
			return;
		} else {
			$scope.messageEmpty = false;
		}
		
		ajaxRequest.addReply($scope.myFile, $scope.message, $routeParams.id).then(function(data) {
			if (data.data.success) {
				console.log('Reply sended');
				$window.location.reload(true);
			}
		});
	};

	$scope.answer = function(replyId) {
		angular.element(document.querySelector('#message')).append('>>' + replyId);
	};
});