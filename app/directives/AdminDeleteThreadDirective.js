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
