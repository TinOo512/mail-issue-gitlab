'use strict';

/* Project Controllers */

var projectCtrl = angular.module('ProjectCtrl', ['ProjectFactories', 'ProjectDirectives', 'CrudDirectives']);

projectCtrl.controller('ProjectCtrl', ['$scope', '$rootScope', 'ProjectList',
    function($scope, $rootScope, ProjectList) {
        console.log('ProjectCtrl');
        $rootScope.active = 'project';

        ProjectList.fetchItems(function () {
            $scope.projects = ProjectList.items;
        });
    }]);