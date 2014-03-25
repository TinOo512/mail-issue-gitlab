'use strict';

/* Account Controllers */

var accountCtrl = angular.module('AccountCtrl', ['AccountFactories', 'AccountDirectives', 'CrudDirectives']);

accountCtrl.controller('AccountCtrl', ['$scope', '$rootScope', 'AccountList',
    function($scope, $rootScope, AccountList) {
        console.log('AccountCtrl');
        $rootScope.active = 'account';

        AccountList.fetchItems(function () {
            $scope.accounts = AccountList.items;
        });
    }]);