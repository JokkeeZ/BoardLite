app.controller('TitleController', function($rootScope, Ajax, $window) {
	Ajax.getAppConfig().success(function (result) {
		if (result.error !== undefined && result.error === 'install') {
			$window.location.href = 'install/index.html';
		}

		$rootScope.appName = result.app_name;
	});
});
