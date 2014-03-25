'use strict';

/* Account Project Controllers */

var accountProjectCtrl = angular.module('AccountProjectCtrl', ['AccountProjectFactories', 'AccountProjectDirectives', 'CrudDirectives']);

accountProjectCtrl.controller('AccountProjectCtrl', ['$scope', '$rootScope', 'AccountProjectList',
    function($scope, $rootScope, AccountProjectList) {
        console.log('AccountProjectCtrl');
        $rootScope.active = 'account-controller';

        AccountProjectList.fetchItems(function () {
            $scope.activeTab = AccountProjectList.orderBy;
            $scope.accountsProjects = AccountProjectList.items;
        });

        $scope.onTabClick = function (tab) {
            AccountProjectList.orderBy = tab;
            // on vide tous les items precedemment telecharge
            AccountProjectList.items = {};
            AccountProjectList.fetchItems(function () {
                $scope.activeTab = AccountProjectList.orderBy;
                $scope.accountsProjects = AccountProjectList.items;
            });
        }
    }]);