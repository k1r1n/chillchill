var app = angular.module('app.controllers',[])

app.controller('index',Mainctrl)
	function Mainctrl($http,$scope,$rootScope){
		var vm = this
		vm.init = function(){
			vm.requestLogin()
			vm.clearLogout()
			vm.getCart()
		}
		vm.requestLogin = function(){
			$http.get('./controller/login.php')
			.then(function(response){
				vm.userinfo = response.data[0]
			})
		}
		vm.logout = function(){
			$http.get('./controller/logout.php')
			.then(function(response){
				vm.userinfo = false
				location.assign("#!/")
				location.reload()
			})
		}
		vm.getCart = function(){
			$http.get('./controller/cart.php')
			.then(function(response){
				vm.cartBadge = response.data.length
				$rootScope.$on("countMycart", function(event, data){
		    	vm.cartBadge = data
		    })
			})
		}
		vm.clearLogout = function(){
			// $http.get('./controller/clearLogout.php')
			// .then(function(response){
			// 	console.log(response)
			// })
		}
		$("body").css({"background":"rgb(239, 239, 239)","transition":"all linear 0.5s"})
		return vm
	}

app.controller('home',HomeCtrl)
	function HomeCtrl($http,$state,$stateParams){
		var vm = this
		vm.init = function(){
			vm.getCategory()
			vm.getProduct()
		}
		vm.getCategory = function(){
			$http.get('./controller/category.php')
			.then(function(response){
				vm.categorys = response.data
			})
		}
		vm.getProduct = function(){
			$http.get('./controller/product.php')
			.then(function(response){
				vm.products = response.data
			})
		}
		vm.goView = function(pid){
			$state.go("view",{"pid":pid})
		}

		$("body").css({"background":"rgb(239, 239, 239)","transition":"all linear 0.5s"})
		return vm
	}

app.controller('view',ViewCtrl)
	function ViewCtrl($http,$state,$stateParams,auth,$rootScope){
		var vm = this
		vm.init = function(){
			vm.getCategory()
			vm.getProductPerItem()
			vm.getProduct()
		}
		vm.getProduct = function(){
			$http.get('./controller/product.php')
			.then(function(response){
				vm.products = response.data
			})
		}
		vm.getCategory = function(){
			$http.get('./controller/category.php')
			.then(function(response){
				vm.categorys = response.data
			})
		}
		vm.getProductPerItem = function(){
			$http.get('./controller/product.php?id='+$stateParams.pid)
			.then(function(response){
				vm.item = response.data[0]
				$('.ui.embed').embed({
			    url:vm.item.video,
			    placeholder : vm.item.img
			  })
			  $("body").css({
			  	"background":"url("+vm.item.background+") center",
		      "background-attachment":"fixed",
		      "background-size":"cover",
		      "transition":"all linear 0.5s"
			  })
			  $("#description").html(vm.item.description)
			})
		}
		vm.addToCart = function(pid){
			vm.loading = true
			$('#cart_'+pid).transition('bounce')
			auth.async().then(function(response) {
				if(response){ // have logined
					$http.post('./controller/cart.php',{"pid":pid})
					.then(function(response){
						//console.log(response.data)
						vm.loading = false
						vm.getCart()
					})
				}
				else{
					vm.loading = false
					$state.go("login")
				}
  		})
		}
		vm.getCart = function(){
			$http.get('./controller/cart.php')
			.then(function(response){
				vm.cartBadge = response.data.length
				$rootScope.$emit("countMycart", vm.cartBadge)
			})
		}
		vm.favorite = function(pid){
			$('#Favorite').transition('bounce')
		}
		vm.goView = function(pid){
			$state.go("view",{"pid":pid})
		}
	}

app.controller('login',LoginCtrl)
  // https://github.com/AlmogBaku/ngFacebook
	function LoginCtrl($http,$state,$stateParams,$facebook,getIP,$scope){
		var vm = this
		vm.init = function(){
			vm.refresh()
		}
		vm.loginBtn = function(data){
			getIP.async().then(function(response) {
    		data.ip = response;
  		}).then(function(){
				$http.post('./controller/login.php',data)
				.then(function(response){
					if(response.data == "success"){
						location.assign("#!/")
	          location.reload()
					}
					else{
						vm.error = true
						vm.message = response.data
					}
				})
			})
		}
		vm.fbBtn = function(){
			$facebook.login().then(function() {
      	vm.refresh();
    	});
		}
		vm.refresh = function() {
	    $facebook.api("/me?fields=id,name,email,cover")
	    .then(function(response) {
	    	//console.log(response)
        //$scope.welcomeMsg = "Welcome " + response.name;
        //$scope.isLoggedIn = true;
      },function(err) {
        //$scope.welcomeMsg = "Please log in";
      });
  	}
	}

app.controller('register',RegisterCtrl)
	function RegisterCtrl($http,getIP,$timeout,$state){
		var vm = this
		vm.init = function(){

		}
		vm.regisBtn = function(data){
			console.log(data)
			if(!data.facebook_id)
				data.facebook_id = ""
			if(data.password == data.repassword){
				getIP.async().then(function(response) {
	    		data.ip = response;
	  		}).then(function(){
					$http.post('./controller/register.php',data)
					.then(function(response){
						vm.message = response.data
						$timeout(function(){
							$state.go("login")
						},3000)
					})
				})
			}
			else
				vm.error = true
		}
		vm.forgetBtn = function(){

		}

	}

app.controller('myCart',CartCtrl)
	function CartCtrl($http,$rootScope){
		var vm = this
		vm.listCount = []
		vm.listPrice = []
		vm.init = function(){
			vm.myCart()
		}
		vm.myCart = function(){
			$http.get('./controller/cart.php')
			.then(function(response){
				vm.myItems = response.data
				$rootScope.$emit("countMycart", vm.myItems.length)
				vm.sumFn()
			})
		}
		vm.remove = function(name,cart_id){
			var sure = confirm("คุณต้องการจะลบ " + name + " ใช่ไหม?")
			if(sure){
				$http.delete('./controller/cart.php?cart_id='+cart_id)
				.then(function(response){
					vm.myCart()
				})
			}
		}
		vm.sumPeritem = function(index,count,price){

			if(count <=1)
				vm.listPrice[index] =  parseFloat(price)
			else if(count > 1)
				vm.listPrice[index] +=  parseFloat(price)
			//console.log(typeof(count),typeof(price),parseFloat(price))
		}
		vm.sumFn = function(){
			vm.balance = 0
			for(var i = 0;i < vm.myItems.length ; i++){
				if(vm.myItems[i].status_payment == "ยังไม่จ่าย")
					vm.balance += parseFloat(vm.myItems[i].price)
			}
		}
	}

app.controller('topup',topupCtrl)
	function topupCtrl($http,$rootScope){
		var vm = this
		vm.init = function(){
			vm.getRate()
		}

		vm.getRate = function(){
			$http.get('./controller/rateTrue.php')
			.then(function(response){
				vm.rate = response.data
				console.log(response.data)
			})
			$http.get('./controller/login.php')
			.then(function(response){
				vm.userinfo = response.data[0]
			})
		}
		vm.topupBtn = function(data){
			$http.post('./controller/tmtopup/common/AES.php?ref1=admin&passkey=s52MMDNAK149')
			.then(function(response){
				console.log(response.data)
			})
			console.log(data)
		}
	}
