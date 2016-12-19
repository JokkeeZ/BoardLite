app.directive('fileModel', function($parse) {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			var model = $parse(attrs.fileModel);
			var modelSetter = model.assign;

			element.bind('change', function() {
				scope.$apply(function(){
					modelSetter(scope, element[0].files[0]);
				});
			});
		}
	}
});

app.directive('adminDeleteThread', function(ajaxRequest, $window, $rootScope) {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			element.bind('click', function() {
				var threadId = attrs.adminDeleteThread;

				var confirmation = confirm($rootScope.lang.Do_You_Want_Delete_Thread_Confirm);
				if (confirmation) {
                    ajaxRequest.deleteThread(threadId).then(function(response) {
                        if (response.data.status) {
                            $window.location.reload(true);
                        }
                    });
				}
			});
		}
	}
});

app.run(function($rootScope, ajaxRequest, User, $window) {
	$rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
		if (current.$$route.adminOnly) {
			if (User.isLoggedIn() != true || User.isAdmin() != true) {
				$window.location.href = "#/";
			}
		}
		
		ajaxRequest.getLanguage().success(function(response) {
			$rootScope.lang = response.BoardLite_Texts;
		});
	});
});

app.factory('User', function($cookies, ajaxRequest) {
	return {
		get: function() {
			return $cookies.getObject('user');
		},
		set: function(obj) {
			$cookies.putObject('user', obj);
		},
		isAdmin: function() {
			if (this.isLoggedIn()) {
				var rank = this.get().rank;
				return rank == 1 || rank == 2;
			}
			return false;
		},
		isLoggedIn: function() {
			var usr = this.get();
			return usr !== undefined;
		},
		destroy: function() {
			$cookies.remove('user');

			ajaxRequest.logoutUser().success(function(response) {
				if (response.status) {
					console.debug('Session destroyed');	
				}
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
			return $http.get('static/ajax/boards/GetBoards.php');
		},
		getThreads: function(prefix) {
			return $http.get('static/ajax/threads/GetThreads.php?prefix=' + prefix);
		},
		getThreadReplys: function(threadId) {
			return $http.get('static/ajax/threads/GetReplys.php?id=' + threadId);
		},
		getThreadStartPost: function(threadId) {
			return $http.get('static/ajax/threads/GetThreadStartPost.php?id=' + threadId);
		},
		getLanguage: function() {
			return $http.get('static/ajax/GetLanguage.php');
		},
		createToken: function() {
			var formData = new FormData();
			formData.append('token_creation', true);
			return this.createPostRequest('static/ajax/CreateToken.php', formData);
		},
		deleteBoard: function(id) {
			var formData = new FormData();
			formData.append('id', id);
			formData.append('token', $rootScope.token);
			return this.createPostRequest('static/ajax/boards/DeleteBoard.php', formData);
		},
		updateBoard: function(id, name, desc, prefix, tag) {
			var formData = new FormData();
			formData.append('id', id);
			formData.append('name', name);
			formData.append('desc', desc);
			formData.append('prefix', prefix);
			formData.append('tag', tag);
			formData.append('token', $rootScope.token);
			return this.createPostRequest('static/ajax/boards/UpdateBoard.php', formData);
		},
		createUser: function(name, pass) {
			var formData = new FormData();
			formData.append('name', name);
			formData.append('pass', pass);
			formData.append('token', $rootScope.token);
			return this.createPostRequest('static/ajax/auth/CreateUser.php', formData);
		},
		loginUser: function(name, pass) {
			var formData = new FormData();
			formData.append('name', name);
			formData.append('pass', pass);
			formData.append('token', $rootScope.token);
			return this.createPostRequest('static/ajax/auth/LoginUser.php', formData);
		},
		logoutUser: function() {
			var formData = new FormData();
			formData.append('session_close', true);
			formData.append('token', $rootScope.token);
			return this.createPostRequest('static/ajax/auth/LogoutUser.php', formData);
		},
		createThread: function(file, title, message, prefix) {
			var formData = new FormData();
			formData.append('file', file);
			formData.append('title', title);
			formData.append('message', message);
			formData.append('prefix', prefix);
			formData.append('token', $rootScope.token);
			return this.createPostRequest('static/ajax/threads/CreateThread.php', formData);
		},
		deleteThread: function(id) {
			var formData = new FormData();
			formData.append('id', id);
			formData.append('token', $rootScope.token);
			return this.createPostRequest('static/ajax/threads/DeleteThread.php', formData);
		},
		createBoard: function(name, desc, prefix, tag) {
			var formData = new FormData();
			formData.append('name', name);
			formData.append('desc', desc);
			formData.append('prefix', prefix);
			formData.append('tag', tag);
			formData.append('token', $rootScope.token);
			return this.createPostRequest('static/ajax/boards/CreateBoard.php', formData);
		},
		addReply: function(file, message, threadId) {
			var formData = new FormData();
			formData.append("file", file);
			formData.append("message", message);
			formData.append("thread_id", threadId);
			formData.append('token', $rootScope.token);
			return this.createPostRequest('static/ajax/threads/AddReply.php', formData);
		},
		createPostRequest: function(url, formData) {
			return $http.post(url, formData, {
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