const app = angular.module('boardLite', ['ngRoute', 'ngCookies']);

app.constant('PATH', 'static/global.php');

app.run(function($rootScope, Ajax, User, $window) {
	$rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
		if (current.$$route.adminOnly && (!User.isLoggedIn() || !User.isAdmin())) {
				$window.location.href = '#/';
				console.log('Redirecting non-admin user back..');
		}

		// Reduces ajax calls.
		if ($rootScope.lang === undefined) {
			Ajax.getLanguage().success(function(result) {
				$rootScope.lang = result.BoardLite_Texts;
				console.log('Language loaded..');
			});
		}
	});
})

.factory('User', function($cookies, Ajax) {
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

			Ajax.logoutUser().success(function(response) {
				if (response.success) {
					console.log('Session destroyed');
				}
			});
		}
	}
})

.factory('Ajax', function($http, $rootScope, PATH) {
	return {
		getAppConfig: function() {
			return $http.get(PATH + '?request=get_config');
		},
		getAppRules: function() {
			return $http.get(PATH + '?request=get_rules');
		},
		getBoards: function() {
			return $http.get(PATH + '?request=get_boards');
		},
		getThreads: function(prefix) {
			return $http.get(PATH + '?request=get_threads&prefix=' + prefix);
		},
		getThreadReplies: function(threadId) {
			return $http.get(PATH + '?request=get_thread_replies&id=' + threadId);
		},
		getThreadStartPost: function(threadId) {
			return $http.get(PATH + '?request=get_thread_start_post&id=' + threadId);
		},
		getLanguage: function() {
			return $http.get(PATH + '?request=get_lang');
		},
		deleteBoard: function(id) {
			var formData = new FormData();
			formData.append('id', id);
			formData.append('request', 'delete_board');
			return this.post(PATH, formData);
		},
		updateBoard: function(id, name, desc, prefix, tag) {
			var formData = new FormData();
			formData.append('id', id);
			formData.append('name', name);
			formData.append('desc', desc);
			formData.append('prefix', prefix);
			formData.append('tag', tag);
			formData.append('request', 'update_board');
			return this.post('static/global.phpp', formData);
		},
		createUser: function(name, pass) {
			var formData = new FormData();
			formData.append('name', name);
			formData.append('pass', pass);
			formData.append('request', 'create_user');
			return this.post(PATH, formData);
		},
		loginUser: function(name, pass) {
			var formData = new FormData();
			formData.append('name', name);
			formData.append('pass', pass);
			formData.append('request', 'login_user');
			return this.post(PATH, formData);
		},
		logoutUser: function() {
			var formData = new FormData();
			formData.append('session_close', true);
			formData.append('request', 'logout_user');
			return this.post(PATH, formData);
		},
		createThread: function(file, title, message, prefix) {
			var formData = new FormData();
			formData.append('file', file);
			formData.append('title', title);
			formData.append('message', message);
			formData.append('prefix', prefix);
			formData.append('request', 'create_thread');
			return this.post(PATH, formData);
		},
		deleteThread: function(id) {
			var formData = new FormData();
			formData.append('id', id);
			formData.append('request', 'delete_thread');
			return this.post(PATH, formData);
		},
		createBoard: function(name, desc, prefix, tag) {
			var formData = new FormData();
			formData.append('name', name);
			formData.append('desc', desc);
			formData.append('prefix', prefix);
			formData.append('tag', tag);
			formData.append('request', 'create_board');
			return this.post(PATH, formData);
		},
		addReply: function(file, message, threadId) {
			var formData = new FormData();
			formData.append('file', file);
			formData.append('message', message);
			formData.append("thread_id", threadId);
			formData.append('request', 'add_reply');
			return this.post(PATH, formData);
		},
		setThreadLockState: function(id, state) {
			var formData = new FormData();
			formData.append('id', id);
			formData.append('state', state);
			formData.append('request', 'lock_thread');
			return this.post(PATH, formData);
		},
		post: function(url, formData) {
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
})

.filter('nl2br', function($sce) {
	return function(msg) {
		var msg = (msg + '').replace(/(?:\\[rn]|[\r\n]+)+/g, '<br>');
		return $sce.trustAsHtml(msg);
	}
})

.filter('bbcode', function($sce) {
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
})

.factory('extensionProvider', function() {
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
})

.directive('adminDeleteThread', function(Ajax, $window, $rootScope) {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			element.bind('click', function() {
				let splitted = attrs.adminDeleteThread.toString().split(',');
				let threadId = parseInt(splitted[0]);

				if (confirm($rootScope.lang.Delete_Thread_Confirm)) {
					Ajax.deleteThread(threadId).then(function(result) {
						if (result.data.success) {
							$window.location.href = `#/board/${ splitted[1] }`;
						}
					});
				}
			});
		}
	}
})

.directive('fileModel', function($parse) {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			let model = $parse(attrs.fileModel);
			let modelSetter = model.assign;

			element.bind('change', function() {
				scope.$apply(function() {
					modelSetter(scope, element[0].files[0]);
				});
			});
		}
	}
});
