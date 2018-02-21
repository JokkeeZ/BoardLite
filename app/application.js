const app = angular.module('boardLite', ['ngRoute', 'ngCookies']);

app.constant('PATH', 'static/global.php');

app.run(function($rootScope, Ajax, User, $window) {
	$rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
		if (current.$$route !== undefined && current.$$route.adminOnly && (!User.isLoggedIn() || !User.isAdmin())) {
			$window.location.href = '#/';
			console.log('Redirecting non-admin user back..');
		}

		let element = angular.element(document.querySelector('#blNav'))
		if (element !== null && element !== undefined) {
			element.collapse('hide');
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
                let rank = this.get().rank;
				return rank === 1 || rank === 2;
			}
			return false;
		},
		isLoggedIn: function() {
			let usr = this.get();
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

.factory('Ajax', function($http, PATH) {
	return {
		getAppConfig: function() {
			return this.get('get_config', null);
		},
		getAppRules: function() {
			return this.get('get_rules', null);
		},
		getBoards: function() {
			return this.get('get_boards', null);
		},
		getThreads: function(prefix) {
			return this.get('get_threads', `&prefix=${ prefix }`);
		},
		getThreadReplies: function(threadId) {
			return this.get('get_thread_replies', `&id=${ threadId }`);
		},
		getThreadStartPost: function(threadId) {
			return this.get('get_thread_start_post', `&id=${threadId}`);
		},
		getLanguage: function() {
			return this.get('get_lang', null);
		},
		deleteBoard: function(id) {
			const formData = new FormData();
			formData.append('id', id);
			formData.append('request', 'delete_board');

			return this.post(formData);
		},
		updateBoard: function(id, name, desc, prefix, tag) {
            const formData = new FormData();
			formData.append('id', id);
			formData.append('name', name);
			formData.append('desc', desc);
			formData.append('prefix', prefix);
			formData.append('tag', tag);
			formData.append('request', 'update_board');

			return this.post(formData);
		},
		createUser: function(name, pass) {
            const formData = new FormData();
			formData.append('name', name);
			formData.append('pass', pass);
			formData.append('request', 'create_user');

			return this.post(formData);
		},
		loginUser: function(name, pass) {
            const formData = new FormData();
			formData.append('name', name);
			formData.append('pass', pass);
			formData.append('request', 'login_user');

			return this.post(formData);
		},
		logoutUser: function() {
            const formData = new FormData();
			formData.append('request', 'logout_user');

			return this.post(formData);
		},
		createThread: function(file, title, message, prefix) {
            const formData = new FormData();
			formData.append('file', file);
			formData.append('title', title);
			formData.append('message', message);
			formData.append('prefix', prefix);
			formData.append('request', 'create_thread');
			return this.post(formData);
		},
		deleteThread: function(id) {
            const formData = new FormData();
			formData.append('id', id);
			formData.append('request', 'delete_thread');

			return this.post(formData);
		},
		createBoard: function(name, desc, prefix, tag) {
            const formData = new FormData();
			formData.append('name', name);
			formData.append('desc', desc);
			formData.append('prefix', prefix);
			formData.append('tag', tag);
			formData.append('request', 'create_board');

			return this.post(formData);
		},
		addReply: function(file, message, threadId) {
            const formData = new FormData();
			formData.append('file', file);
			formData.append('message', message);
			formData.append("thread_id", threadId);
			formData.append('request', 'add_reply');

			return this.post(formData);
		},
		setThreadLockState: function(id, state) {
            const formData = new FormData();
			formData.append('id', id);
			formData.append('state', state);
			formData.append('request', 'lock_thread');

			return this.post(formData);
		},
		post: function(formData) {
			return $http.post(PATH, formData, {
				transformRequest: angular.identity,
				headers: {
					'Content-Type': undefined,
					'Process-Data': false,
					'X-Requested-With': 'XMLHttpRequest'
				}
			});
		},

		get: function(request, args) {
			return $http.get(PATH + `?request=${request}` + (args !== null ? args : ''), {
                transformRequest: angular.identity,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
			});
		}
	}
})

.filter('nl2br', function($sce) {
	return function(message) {
		let msg = message.toString().replace(/(?:\\[rn]|[\r\n]+)+/g, '<br>');
		return $sce.trustAsHtml(msg);
	}
})

.filter('bbcode', function($sce) {
	return function(message) {
        let msg = message.toString()
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
			let splitted = fileUrl.toString().split('.');
            let ext = splitted[splitted.length - 1];
			
			if (ext === 'mp4' || ext === 'webm' || ext === 'ogg' || ext === 'mp3') {
				return { type: 'video', extension: ext };
			}
			else if (ext === 'jpg' || ext === 'jpeg' || ext === 'png' || ext === 'bmp' || ext === 'gif') {
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
})

.directive('fallbackSrc', function() {
	return {
		link: function(scope, element, attrs) {
			element.bind('error', function() {
				angular.element(this).attr('src', 'assets/img/empty.png');
			});
		}
	}
});
