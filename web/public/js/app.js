var logicorpo = angular.module('logicorpo', ['mouse.utils']);

logicorpo.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

logicorpo.controller('MainCtrl', function ($scope) {
	$scope.flash=true;

$scope.commandes = [
	{
		user : {
			nom : 'aaa',
			prenom : 'bbb',
			solde : 3.01,
		},
		prix : 3,
		prete : false,
		servie: false,
		paye  : false,
		produits : [
			{
				label : 'produit 1',
				qtt : 2,
				supplements : [
					'suppl 1',
					'suppl 2'
				]
			},
			{
				label : 'produit 2',
				qtt : 2,
				supplements : [
					'suppl 1',
					'suppl 2'
				]
			}
		]
	},
	{
		user : {
			nom : 'ccc',
			prenom : 'dd',
			solde : 3.01
		},
		prix : 3,
		prete : false,
		servie: false,
		paye  : false,
		produits : [
			{
				label : 'produit 1',
				qtt : 2,
				supplements : [
					'suppl 1',
					'suppl 2'
				]
			},
			{
				label : 'produit 2',
				qtt : 2,
				supplements : [
					'suppl 1',
					'suppl 2'
				]
			}
		]
	},
	{
		user : {
			nom : 'ccc',
			prenom : 'dd',
			solde : 3.01
		},
		prix : 3,
		prete : false,
		servie: false,
		paye  : false,	
		produits : [
			{
				label : 'produit 1',
				qtt : 2,
				supplements : [
					'suppl 1',
					'suppl 2'
				]
			},
			{
				label : 'produit 3',
				qtt : 2,
				supplements : [
					'suppl 1',
					'suppl 2'
				]
			}
		]
	}
];
console.log($scope.commandes);

angular.forEach($scope.commandes, function(commande) {
	commande.changeState = function(state) {
		switch(state) {
			case 'P': this.prete = true;
			break;
			case 'S': this.servie = true;
			break;
		}
	};
});

$scope.products = function () {
	products = {};
	angular.forEach($scope.commandes,function(commande) {
		if(!commande.paye || !commande.servie) {			
			angular.forEach(commande.produits,function(produit) {

					if(products[produit.label]!=undefined) {
						products[produit.label]	+= produit.qtt;
					} else products[produit.label]=produit.qtt;

			});
		}
	});
	return products;
}

$scope.close = function() {
	console.log('ok');
}

});

logicorpo.directive('flash', function() {
	return {
		restrict:'E',
		scope: {type: '@'},
		transclude:true,
		replace:true,
		template: '<div ng-show="flash" ng-init="flash=true" class="flash [[type]]">'+
				  '<div ng-transclude></div>'+
				  '<button ng-click="flash=false" class="flash-close"></button>'+
				  '</div>'
	};
});

logicorpo.directive('solde', function() {
	return {
		restrict:'C',
		scope: {solde: '@'},
		template:'<span class="user-solde" ng-show="solde!=0" ng-class="{negative:solde<0}">[[solde | currency ]]</span>'
	};
});