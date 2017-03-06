angular.module('app',['app.controllers', 'app.services','ui.router'])
.config(function($stateProvider, $urlRouterProvider){
  $urlRouterProvider.otherwise("/")
  // member zone
  $stateProvider
    .state('login', {
      url: "/login",
      templateUrl: "./views/login.html",
      controller : "login as vm"
    })
  $stateProvider
    .state('register', {
      url: "/register",
      templateUrl: "./views/register.html",
      controller : "register as vm"
    })
  $stateProvider
    .state('logout', {
      url: "/logout",
    })
  // web controller zone
  $stateProvider
    .state('/', {
      url: "/",
      templateUrl: "./views/home.html",
      controller : "home as vm"
    })
  $stateProvider
    .state('view', {
      url: "/view/:pid",
      templateUrl: "./views/view.html",
      controller : "view as vm"
    })
  $stateProvider
    .state('mycart', {
      url: "/mycart",
      templateUrl: "./views/mycart.html",
      controller : "myCart as vm",
    })
  // menu zone
  $stateProvider
    .state('topup', {
      url: "/topup",
      templateUrl: "./views/topup.html",
      controller : "topup as vm",
    })
  // administrator zone
})
