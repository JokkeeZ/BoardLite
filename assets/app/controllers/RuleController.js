app.controller('RuleController', function($scope, ajaxRequest) {
	ajaxRequest.getAppRules().success(function(data) {
		$scope.rules = data;
	});
});
