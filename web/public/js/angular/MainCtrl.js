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
	else {$scope.menu = $cookieStore.get('menu');}
	
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
						//delete this;
						$scope.commande.produits.splice($scope.commande.produits.indexOf(this), 1);
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
});

logicorpo.controller('ServiceCtrl',function($scope, $http) {
	$http.get('/app_dev.php/service/1/commande/0').success(function(data, status, headers, config) {
		$scope.commandes = data.datas;

		$scope.commandes.getNext = function() {
			for (commande in $scope.commandes) {
				if(commande.etat != "vert")
					return commande;
			};
			$scope.commandes
		};
	}).error(function(data, status, headers, config) {
	    console.log('failed');
  });;
});