'use strict';

/* Crud Directives */

var crudDirectives = angular.module('CrudDirectives', []);

crudDirectives.directive('miOnlyDelete', function() {
    return {
        controller: function($scope) {}
    }
});

crudDirectives.directive('miService', function() {
    return {
        controller: function($scope) {}
    }
});

crudDirectives.directive('miId', function() {
    return {
        require: 'miService',
        controller: function($scope) {}
    }
});

crudDirectives.directive('miConfirm', function() {
    return {
        require: 'miService',
        scope: {
            miService: '@'
        },
        templateUrl: 'app/partials/_confirm.html',
        controller: ['$scope', '$injector', function($scope, $injector) {
            $scope.confirm = function (service) {
                var factory = $injector.get(service);
                var resource = factory.getResource();
                resource.delete({id: factory.getSelectedId()}, function (resp) {
                    if (resp.success) {
                        factory.delSelectedItem();
                        $('#confirmModal').modal('hide');
                    }
                });
            }
        }]
    }
});

crudDirectives.directive('miCreate', function() {
    return {
        restrict: 'A',
        require: 'miService',
        scope: {
            miService: '@'
        },
        template:
            '<button ng-click="onClick(miService)" style="float: right;" type="button" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> Ajouter</button>',
        controller: ['$scope', '$injector', function($scope, $injector) {
            $scope.onClick = function (service) {
                var factory = $injector.get(service);
                factory.selectedId = null;
                $('#CUDModal').modal('show');
            }
        }]
    }
});

crudDirectives.directive('miUpdateDelete', function() {
    return {
        restrict: 'A',
        require: 'miId',
        scope: {
            miId: '@',
            miService: '@',
            miOnlyDelete: '@'
        },
        template:
            '<div style="float: right;" class="btn-group btn-group-xs">' +
                '<button ng-show="!miOnlyDelete" ng-click="onClick(\'edit\', miId, miService)" type="button" class="btn btn-default"><i class="glyphicon glyphicon-edit"></i> Edit</button>' +
                '<button ng-click="onClick(\'remove\', miId, miService)" type="button" class="btn btn-default"><i class="glyphicon glyphicon-remove"></i> Remove</button>' +
            '</div>',
        controller: ['$scope', '$injector', function($scope, $injector) {
            $scope.onClick = function (type, id, service) {
                var factory = $injector.get(service);
                factory.setSelectedId(id);
                // EDIT
                if (type === 'edit') {
                    $('#CUDModal').modal('show');
                // REMOVE
                } else {
                    $('#confirmModal').modal('show');
                }
            }
        }]
    }
});