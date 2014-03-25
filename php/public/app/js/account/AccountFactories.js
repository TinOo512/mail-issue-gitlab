'use strict';

/* Account Factories */

var accountFactories = angular.module('AccountFactories', ['AccountServices']);

accountFactories.factory('Account', function() {
    function Account() {
        this.id = null;
        this.email = null;
    }

    return Account;
});

accountFactories.factory('AccountList', ['AccountResource', function(AccountResource) {
    return {
        selectedId: null,
        items: {},
        getSelectedId: function () {
            return this.selectedId;
        },
        setSelectedId: function (id) {
            this.selectedId = id;
        },
        getSelectedItem: function () {
            return this.items[this.selectedId];
        },
        editSelectedItem: function (item) {
            this.items[this.selectedId] = item;
        },
        delSelectedItem: function () {
            delete this.items[this.selectedId];
            this.selectedId = null;
        },
        getResource: function () {
            return AccountResource;
        },
        fetchItems: function (callback) {
            var self = this;
            AccountResource.query(function(data) {
                for (var i=0 ; i<data.length ; i++) {
                    self.items[data[i].id] = data[i];
                }
                return callback();
            });
        }
    }
}]);