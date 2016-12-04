app.directive('fileModel', function($parse) {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			var model = $parse(attrs.fileModel);
			var modelSetter = model.assign;

			element.bind('change', function(){
				scope.$apply(function(){
					modelSetter(scope, element[0].files[0]);
				});
			});
		}
	}
});

app.factory('ajaxRequest', function($http, $rootScope) {
	return {
		getAppConfig: function() {
			return $http.get('static/ajax/GetAppConfig.php');
		},
		getAppRules: function() {
			return $http.get('static/ajax/GetRules.php');
		},
		getBoards: function() {
			return $http.get('static/ajax/GetBoards.php');
		},
		getThreads: function(prefix) {
			return $http.get('static/ajax/GetThreads.php?prefix=' + prefix);
		},
		getThreadReplys: function(threadId) {
			return $http.get('static/ajax/GetReplys.php?id=' + threadId);
		},
		getThreadStartPost: function(threadId) {
			return $http.get('static/ajax/GetThreadStartPost.php?id=' + threadId);
		},
		createToken: function() {
			var formData = new FormData();
			formData.append('token_creation', true);
			return $http.post('static/ajax/CreateToken.php', formData, {
				transformRequest: angular.identity,
				headers: {
					'Content-Type': undefined,
					'Process-Data': false,
					'X-Requested-With': 'XMLHttpRequest'
				}
			});
		},
		createThread: function(file, title, message, prefix) {
			var formData = new FormData();
			formData.append('file', file);
			formData.append('title', title);
			formData.append('message', message);
			formData.append('prefix', prefix);
			formData.append('token', $rootScope.token);
			return $http.post('static/ajax/CreateThread.php', formData, {
				transformRequest: angular.identity,
				headers: {
					'Content-Type': undefined,
					'Process-Data': false,
					'X-Requested-With': 'XMLHttpRequest'
				}
			});
		},
		addReply: function(file, message, threadId) {
			var formData = new FormData();
			formData.append("file", file);
			formData.append("message", message);
			formData.append("thread_id", threadId);
			formData.append('token', $rootScope.token);
			return $http.post('static/ajax/AddReply.php', formData, {
				transformRequest: angular.identity,
				headers: {
					'Content-Type': undefined,
					'Process-Data': false,
					'X-Requested-With': 'XMLHttpRequest'
				}
			});
		}
	}
});

app.filter('nl2br', function($sce) {
	return function(msg) {
		var msg = (msg + '').replace(/(?:\\[rn]|[\r\n]+)+/g, '<br>');
		return $sce.trustAsHtml(msg);
	}
});

app.filter('bbcode', function($sce) {
	return function(msg) {
		var msg = (msg + '')
			.replace('[code]', '<pre>')
			.replace('[/code]', '</pre>')
			.replace('[b]', '<b>')
			.replace('[/b]', '</b>')
			.replace('[i]', '<i>')
			.replace('[/i]', '</i>')
			.replace('[spoiler]', '<span class="hover-text">')
			.replace('[/spoiler]', '</span>');

		return $sce.trustAsHtml(msg);
	}
});

app.factory('extensionProvider', function() {
	return {
		getFileType: function(fileUrl) {
			var splitted = fileUrl.toString().split('.');
			var ext = splitted[splitted.length - 1];
			
			if (ext == "mp4" || ext == "webm" || ext == "ogg" || ext == "mp3") {
				return { type: 'video', extension: ext };
			}
			else if (ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'bmp' || ext == 'gif') {
				return { type: 'image', extension: ext };
			}
			
			return { type: 'no_file', extension: '' };
		}
	}
});