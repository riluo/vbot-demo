var app = angular.module("chatApp",["ng"]);
app.controller("chatCtrl",["$scope", "$timeout", "$http", "$interval",  function($scope,$timeout,$http,$interval){
    $scope.xs=false;
    $http.get("http://119.29.133.42/api/public/?s=Friends.Self").success(function(data){
      $scope.personalImage=data.data.icon;
      $scope.PersonalName=data.data.name
    })

  /*********************************************会话列表页数据*******************************************/
    $http.get("http://119.29.133.42/api/public/?s=Friends.Lists").success(function(data){
        $scope.arrList = data.data;
        console.log($scope.arrList)
    });
  /**********************************************更新会话框数据******************************************/
    $scope.contentUpdate = function(){
      $interval(function(){
        $http.get("http://119.29.133.42/api/public/?s=Dialog.Lists&nickname="+$scope.nickname).success(function(data){
          if(data.data.length!==0){
            $scope.chatContent=data.data.reverse();

          }
        })
      },5000)
        $http.get("http://119.29.133.42/api/public/?s=Dialog.Lists&nickname="+$scope.nickname).success(function(data){
          if(data.data.length!==0){
            $scope.chatContent=data.data.reverse();
            $scope.FromNickName=data.data[0].FromNickName
            console.log($scope.FromNickName)

            console.log($scope.chatContent)
          }
        })
    }
  /**********************************搜索************************************/
  $scope.searchName = function(searchKey){
    $http.get("http://119.29.133.42/api/public/?s=Friends.Search&username="+searchKey).success(function(data){
      $scope.arrList= data.data;
    });
  }
    $scope.goChat=function(currentUserName,nickname,rnickname){
      $scope.rnickname=rnickname
        $scope.xs=true;
        $scope.nickname=nickname;
        $scope.currentUserName=currentUserName;
        $scope.chatContent=[];
        $scope.contentUpdate();
    };
  /*********************************导航条************************/
  $scope.clickChat = function(){

  }
  /**********************************发送事件*********************/
    $scope.sendTextMessage = function(){
        $timeout(function(){
            document.getElementById("editArea").innerHTML="";
        },10)
      if($scope.editAreaCtn!==undefined&&$scope.editAreaCtn!==""){
            var content = $scope.editAreaCtn;
          console.log(content)
        $http({
          method:'post',
          url:'http://119.29.133.42/api/public/?s=Dialog.add',
          data:$.param({nickname :  $scope.nickname, username : $scope.currentUserName,content : content}),
          headers:{'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(req){
                $scope.contentUpdate()
        })
        }
    }
    $scope.editAreaKeydown = function(e){
        var keycode = window.event?e.keyCode:e.which;
        if(keycode==13){
          $scope.sendTextMessage();
        }
    }
}]);
/*******************************发送框***************************/
app.directive('contenteditable', function() {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ctrl) {
            element.bind('keyup', function () {
                scope.$apply(function () {
                    var html = element.html();
                    ctrl.$setViewValue(html);
                });
            });
        }
    }
});
 app .directive('scrollToBottom', ['$parse', '$window', '$timeout', function ($parse, $window, $timeout) {
    function createActivationState($parse, attr, scope) {
      function unboundState(initValue) {
        var activated = initValue;
        return {
          getValue: function () {
            return activated;
          },
          setValue: function (value) {
            activated = value;
          }
        };
      }

      function oneWayBindingState(getter, scope) {
        return {
          getValue: function () {
            return getter(scope);
          },
          setValue: function () {
          }
        };
      }

      function twoWayBindingState(getter, setter, scope) {
        return {
          getValue: function () {
            return getter(scope);
          },
          setValue: function (value) {
            if (value !== getter(scope)) {
              scope.$apply(function () {
                setter(scope, value);
              });
            }
          }
        };
      }

      if (attr !== '') {
        var getter = $parse(attr);
        if (getter.assign !== undefined) {
          return twoWayBindingState(getter, getter.assign, scope);
        } else {
          return oneWayBindingState(getter, scope);
        }
      } else {
        return unboundState(true);
      }
    }

    return {
      priority: 1,
      restrict: 'A',
      link: function (scope, $el, attrs) {
        var el = $el[0],
          activationState = createActivationState($parse, attrs.scrollToBottom, scope);

        var bottom = {
          isAttached: function (el,isAttached) {
            return isAttached;
          },
          scroll: function (el) {
            el.scrollTop = el.scrollHeight;
          }
        };

        function scrollIfGlued() {
          if (activationState.getValue() && !bottom.isAttached(el, scope.isAttached)) {
            bottom.scroll(el);
          }
        }

        scope.$watch(scrollIfGlued);

        $timeout(scrollIfGlued, 0, false);

        $window.addEventListener('resize', scrollIfGlued, false);

        $el.bind('scroll', function () {
          activationState.setValue(bottom.isAttached(el,scope.isAttached));
        });
      }
    };
  }]);

