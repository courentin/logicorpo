logicorpo.factory('Commandes', function($http, Commandes) {

function Commandes (data) {

	angular.extend(this, {
			title: "",
			description: "",

			getShortDescription: function() {
				return this.description.substring(0, 100);
			}
	}, data);
}

	return Commandes;
});