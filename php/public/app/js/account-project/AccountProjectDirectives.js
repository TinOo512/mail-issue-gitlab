'use strict';

/* Account Project Directives */

var accountProjectDirectives = angular.module('AccountProjectDirectives', ['ui.select2', 'AccountProjectFactories', 'AccountFactories', 'ProjectFactories']);

accountProjectDirectives.directive('miFormAccountProject', function() {
    return {
        restrict: 'A',
        require: '^miService',
        scope: {
            miService: '@'
        },
        templateUrl: '/app/partials/account-project/_form.html',
        controller: ['$scope', '$injector', 'AccountList', 'ProjectList', function($scope, $injector, AccountList, ProjectList) {
            var factory = $injector.get($scope.miService);

            $scope.submit = function(item){
                if ($scope.modal_form.$valid) {
                    // CREATE
                    var resource = factory.getResource();
                    resource.save(item, function (resp) {
                        if (resp.success) {
                            factory.fetchItems(function () {
                                $('#CUDModal').modal('hide');
                            });
                        }
                    });
                }
            }

            AccountList.fetchItems(function () {
                $scope.accounts = AccountList.items;
            });
            ProjectList.fetchItems(function () {
                $scope.projects = ProjectList.items;
            });
        }]
    }
});