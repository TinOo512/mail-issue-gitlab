'use strict';

/* Account Services */

var accountServices = angular.module('AccountServices', ['ngResource']);

accountServices.factory('AccountResource', ['$resource',
    function($resource){
        return $resource('/mail/account/:id', {}, {
            get: {method:'GET'},
            save: {method:'POST'},
            update: {method:'PUT'},
            query: {method:'GET', isArray:true},
            remove: {method:'DELETE'},
            delete: {method:'DELETE'}
        });
    }]);