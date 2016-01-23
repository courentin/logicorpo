var logicorpo = angular.module('logicorpo', ['ngCookies', 'chart.js']);

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

logicorpo.controller('CommandeCtrl',function($scope, $http) {

	$http.get('/app_dev.php/produit/list')
		.success(function(data, status, headers, config) {
			$scope.categories = data;
			console.log(data);
		})
		.error(function(data, status, headers, config) {
		});

	var produits = {
		list: [],
		add: function(id) {
			produit = this.exist(id);
			if(produit) {
				produit.quantite++;
			} else {
				angular.forEach($scope.categories, function(categorie) {
					angular.forEach(categorie.produits, function(prod) {
						if(prod.id == id)
							produit = prod;
					});
				});

				if(typeof produit == 'undefined') return null;		

				this.list.push({
					id: id,
					quantite: 1,
					libelle: produit.libelle,
					prix: produit.prixVente,
					stock: produit.stock
				});
			}
		},
		remove: function(id) {
			that = this;
			angular.forEach(that.list, function(produit, index) {
				if(produit.id == id)
					that.list.splice(index, 1);
			});
		},
		exist: function(id) {
			r = false;
			angular.forEach(this.list, function(produit) {
				if(produit.id == id)
					r = produit;
			});
			return r;
		},
		isEmpty: function() {
			return this.list.length == 0;
		},
		getTotal: function() {
			total = 0;
			angular.forEach(this.list, function(produit) {
				total += produit.quantite*produit.prix;
			});
			return total;
		}

	};

	$scope.produits = Object.create(produits);

	$scope.ajouter = function(id) {
		produit = getProduit(id);
		$scope.produits.push({
			produit: {
				label: produit.libelle,
				prixVente: produit.prixVente,
				id: id
			},
			quantite: 1
		});
	};

	$scope.retirer = function(id) {
		angular.forEach($scope.produits, function(produit) {
			if(produit.produit.id = id)
				$scope
		});
	};

	function getProduit(id) {
		r = null;
		angular.forEach($scope.categories, function(categorie) {
			angular.forEach(categorie.produits, function(produit) {
				if(produit.id == id)
					r = produit;
			});
		});

		return r;
	}

	$scope.getTotal = function() {
		total = 0;
		angular.forEach($scope.produits, function(produit) {
			total += produit.quantite*produit.produit.prixVente;
		});
		return total;
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
		};

	}).error(function(data, status, headers, config) {
	    console.log('failed');
  });;
		$scope.servir = function(commande, $event) {
			$event.stopPropagation();
			commande.servie = true;
			if(commande.paye)
				commande.etat = "vert";
		}
});

logicorpo.controller('StatistiquesCtrl',function($scope, $http) {
	$http.get('/app_dev.php/stats/2015-01-03/2016-01-03/jour/commandes').success(function(data, status, headers, config) {
		$scope.labels = data.labels;
		$scope.series = data.series;
		$scope.data = data.datas;
		console.log($scope.data);
	}).error(function(data, status, headers, config) {
		console.log('failed : '+status);
	});

	$scope.labels = ["January", "February", "March", "April", "May", "June", "July"];
	$scope.series = ['Nombre de commande', 'Montant'];
	$scope.data = [
		[65, 59, 80, 81, 56, 55, 40],
		[28, 48, 40, 19, 86, 27, 90]
	];

	$scope.onClick = function (points, evt) {
		console.log(points, evt);
	};
});