app.controller('RuleController', function($scope, Ajax) {
	Ajax.getAppRules().success(function(result) {
		$scope.rules = result;
	});
});
