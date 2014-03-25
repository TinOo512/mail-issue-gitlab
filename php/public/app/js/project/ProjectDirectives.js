'use strict';

/* Project Directives */

var projectDirectives = angular.module('ProjectDirectives', ['ProjectFactories', 'CrudDirectives']);

projectDirectives.directive('miFormProject', function() {
    return {
        restrict: 'A',
        require: '^miService',
        scope: {
            miService: '@'
        },
        templateUrl: '/app/partials/project/_form.html',
        controller: ['$scope', '$injector', function($scope, $injector) {
            var factory = $injector.get($scope.miService);

            $scope.submit = function(item){
                if ($scope.modal_form.$valid) {
                    // UPDATE
                    if (item.hasOwnProperty('id')) {
                        item.$update({id: item.id}, function (resp) {
                            if (resp.success) {
                                factory.editSelectedItem(item);
                                $('#CUDModal').modal('hide');
                            }
                        });
                        // CREATE
                    } else {
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
            }

            $scope.setSelectedProject = function () {
                $scope.project = angular.copy(factory.getSelectedItem());
            }
        }],
        link: function(scope, iElement, iAttrs, ctrl) {
            iElement.find('.modal').on('show.bs.modal', function (e) {
                scope.setSelectedProject();
            });
        }
    }
});