'use strict';

/* Account Project Factories */

var accountProjectFactories = angular.module('AccountProjectFactories', ['AccountProjectServices']);

accountProjectFactories.factory('AccountProject', function() {
    function AccountProject() {
        this.idAccount = null;
        this.idProject = null;
    }

    return AccountProject;
});

accountProjectFactories.factory('AccountProjectList', ['AccountProjectResource', function(AccountProjectResource) {
    return {
        orderBy: 'account',
        selectedId: null,
        items: {},
        getSelectedId: function () {
            if (this.orderBy === 'account') {
                return this.selectedId;
            } else /* this.orderBy === 'project' */ {
                // on reverse l'array d'ids !
                // parce-que le webservice necessite l'id account a l'offset 0 puis l'id project a l'offset 1
                // on copy le tableau pour garder le sens d'origine pour les autres methodes du factories
                return angular.copy(this.selectedId).reverse();
            }
        },
        setSelectedId: function (id) {
            this.selectedId = id.split(',');
        },
        getSelectedItem: function () {
            // not implemented
            return undefined;
        },
        delSelectedItem: function () {
            if (this.orderBy === 'account') {
                this.delSelectedItemByAccount();
            } else /* this.orderBy === 'project' */ {
                this.delSelectedItemByProject();
            }
        },
        delSelectedItemByAccount: function () {
            for (var i=0 ; i<this.items[this.selectedId[0]].projects.length ; i++) {
                // si l' id du projet correspond a l' id du projet selectionne
                if (this.items[this.selectedId[0]].projects[i].id == this.selectedId[1]) {
                    // si il y a seulement 1 projet a supprimer on supprime tout l' item
                    if (this.items[this.selectedId[0]].projects.length === 1) {
                        delete this.items[this.selectedId[0]];
                        // sinon seulement le projet correspondant
                    } else {
                        this.items[this.selectedId[0]].projects.splice(i, 1);
                    }

                    break;
                }
            }
            this.selectedId = null;
        },
        delSelectedItemByProject: function () {
            for (var i=0 ; i<this.items[this.selectedId[0]].accounts.length ; i++) {
                // si l' id du compte correspond a l' id du compte selectionne
                if (this.items[this.selectedId[0]].accounts[i].id == this.selectedId[1]) {
                    // si il y a seulement 1 compte a supprimer on supprime tout l' item
                    if (this.items[this.selectedId[0]].accounts.length === 1) {
                        delete this.items[this.selectedId[0]];
                        // sinon seulement le compte correspondant
                    } else {
                        this.items[this.selectedId[0]].accounts.splice(i, 1);
                    }

                    break;
                }
            }
            this.selectedId = null;
        },
        getResource: function () {
            return AccountProjectResource;
        },
        fetchItems: function (callback) {
            if (this.orderBy === 'account') {
                return this.fetchItemsByAccount(callback);
            } else /* this.orderBy === 'project' */ {
                return this.fetchItemsByProject(callback);
            }
        },
        fetchItemsByAccount: function (callback) {
            var self = this;

            AccountProjectResource.queryByAccount(function(data) {
                for (var i=0 ; i<data.length ; i++) {
                    self.items[data[i].account.id] = data[i];
                }
                return callback();
            });
        },
        fetchItemsByProject: function (callback) {
            var self = this;

            AccountProjectResource.queryByProject(function(data) {
                for (var i=0 ; i<data.length ; i++) {
                    self.items[data[i].project.id] = data[i];
                }
                return callback();
            });
        }
    }
}]);