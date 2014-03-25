'use strict';

/* Account Project Services */

var accountProjectServices = angular.module('AccountProjectServices', ['ngResource']);

accountProjectServices.factory('AccountProjectResource', ['$resource',
    function($resource){
        return $resource('/mail/account-project/:id', {}, {
            save: {method:'POST'},
            query: {method:'GET', isArray:true},
            queryByAccount: {method:'GET', isArray:true, params:{mod:'account'}},
            queryByProject: {method:'GET', isArray:true, params:{mod:'project'}},
            remove: {method:'DELETE'},
            delete: {method:'DELETE'}
        });
    }]);