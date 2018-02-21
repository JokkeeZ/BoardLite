const app = angular.module('installer', []);

app.controller('InstallController', function($scope, $http, $window) {

	$scope.install = function(data) {
		if (data.aPass !== data.aPassConfirm) {
			alert('Passwords doesn\'t match!');
			data.aPass = '';
			data.aPassConfirm = '';
			return;
		}

		if (data.dbPass === undefined)
			data.dbPass = '';

		if (data.appLang === undefined)
			data.appLang = 'en_US';

		const formData = new FormData();
		formData.append('submit', 'true');
		formData.append('aName', data.aName);
		formData.append('aPass', data.aPass);
		formData.append('appLang', data.appLang);
		formData.append('appName', data.appName);
		formData.append('dbHost', data.dbHost);
		formData.append('dbPass', data.dbPass);
		formData.append('dbUser', data.dbUser);

		$http.post('install.php', formData, {
			transformRequest: angular.identity,
			headers: {
				'Content-Type': undefined,
				'Process-Data': false,
				'X-Requested-With': 'XMLHttpRequest'
			}
		})
		.success(function(result) {
			if (result.error === 0) {
				$window.location.href = '../';
			} else {
				console.log(result);
			}
		});
	};
});
