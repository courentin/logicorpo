var logicorpo = angular.module('logicorpo', ['ngCookies']);

logicorpo.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

/**
 * Main Controller
 */
logicorpo.controller('MainCtrl',function($scope) {
	$scope.flash=true;
});

/**
 * Menu Controller
 */
logicorpo.controller('MenuCtrl',function($scope, $http, $cookieStore) {



/*
	console.log("1 : "+$cookies.menu);
	console.log("1 : "+$scope.menu);

	if($cookies.menu === "true") {
		$cookies.menu = true;
		$scope.menu = true;
	} else if($cookies.menu === "false") {
		$cookies.menu = false;
		$scope.menu = false;
	} else {
		$scope.menu = true; $cookies.menu=true; console.log("last")
	}

	console.log("2 : "+$cookies.menu);
	console.log("2 : "+$scope.menu);
	*/
/*
*/
	if($cookieStore.get('menu') === undefined) {$scope.menu = true; $cookieStore.put('menu',true); console.log("oui")}
	else {$scope.menu = $cookieStore.get('menu'); console.log("none")}
	
	$scope.$watch(function() { return $scope.menu; }, function(newValue, oldValue) {
		$cookieStore.put('menu', newValue);
	})

});

logicorpo.controller('CommandeCtrl',function($scope) {
	$scope.commande = {
		produits: [
			{
				produit: 0,
				quantite: 1,
				delete: function() {
					if(this.produit !=0) {
						delete this;
						//$scope.commande.produits.splice($scope.commande.produits.indexOf(this), 1);
					}
				}
			}
		],
		addProduit: function(id, qtt) {
			if(this.produits[this.produits.length-1].produit != 0 && this.produits[this.produits.length-1].quantite > 0) {	
				this.produits.push({
					produit: id,
					quantite: qtt
				});
			}
		}
	};
/*
	$scope.addProduit = function(id, qtt) {
		if(lastInsert().produit != 0 && lastInsert().quantite > 0) {	
			$scope.commande.produits.push({
				produit: id,
				quantite: qtt
			});
		}
	}

	$scope.delProduit = function(id) {
		if($scope.commande.produits[id].produit !=0)
		$scope.commande.produits.splice(id, 1);
	}

	lastInsert = function() {
		return $scope.commande.produits[$scope.commande.produits.length-1];
	}
	*/
});