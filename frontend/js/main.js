var app = angular.module("chatApp",["ng"]);
app.controller("chatCtrl",["$scope", "$timeout", "$http",  function($scope,$timeout,$http){
    $scope.xs=false;
    var date=new Date();
    var H = date.getHours();
    var M = date.getMinutes();
    $http.get("http://119.29.133.42/api/public/?s=Friends.Lists").success(function(data){
        $scope.arrList = data.data
    })
    $scope.contentUpdate = function(){
        $http.get("http://119.29.133.42/api/public/?s=Dialog.Lists&nickname="+$scope.nickname+"http://119.29.133.42/api/public/?s=Dialog.Lists&nickname="+$scope.nickname).success(function(data){
 /*           data = {
                "ret": 200,
                "data": [
                    {
                        "id": "201",
                        "Type": "1",
                        "FromUserName": "@4e6ba1a4d15c16fd05d88172ed13ad58",
                        "FromNickName": "\u65e5\u843d",
                        "ToUserName": "@07aa0e8b340b5240bcf4ea13a5634627",
                        "ToNickName": "\u8001\u9b4f",
                        "Content": "\u5927\u65b9\u516c\u5f00",
                        "CreateTime": "2017-10-13 22:03:22"
                    },
                    {
                        "id": "200",
                        "Type": "1",
                        "FromUserName": "@4e6ba1a4d15c16fd05d88172ed13ad58",
                        "FromNickName": "\u65e5\u843d",
                        "ToUserName": "@07aa0e8b340b5240bcf4ea13a5634627",
                        "ToNickName": "\u8001\u9b4f",
                        "Content": "\u4e70\u90a3\u4e2a\u6d77\u6d0b",
                        "CreateTime": "2017-10-13 22:03:17"
                    },
                    {
                        "id": "199",
                        "Type": "1",
                        "FromUserName": "@4e6ba1a4d15c16fd05d88172ed13ad58",
                        "FromNickName": "\u65e5\u843d",
                        "ToUserName": "@07aa0e8b340b5240bcf4ea13a5634627",
                        "ToNickName": "\u8001\u9b4f",
                        "Content": "\u7684",
                        "CreateTime": "2017-10-13 22:03:08"
                    },
                    {
                        "id": "198",
                        "Type": "1",
                        "FromUserName": "@4e6ba1a4d15c16fd05d88172ed13ad58",
                        "FromNickName": "\u65e5\u843d",
                        "ToUserName": "@07aa0e8b340b5240bcf4ea13a5634627",
                        "ToNickName": "\u8001\u9b4f",
                        "Content": "\u89c4\u8303\u5316",
                        "CreateTime": "2017-10-13 22:01:03"
                    },
                    {
                        "id": "197",
                        "Type": "1",
                        "FromUserName": "@4e6ba1a4d15c16fd05d88172ed13ad58",
                        "FromNickName": "\u65e5\u843d",
                        "ToUserName": "@07aa0e8b340b5240bcf4ea13a5634627",
                        "ToNickName": "\u8001\u9b4f",
                        "Content": "\u53d1\u8fc7\u706b",
                        "CreateTime": "2017-10-13 21:53:53"
                    },
                    {
                        "id": "196",
                        "Type": "1",
                        "FromUserName": "@4e6ba1a4d15c16fd05d88172ed13ad58",
                        "FromNickName": "\u65e5\u843d",
                        "ToUserName": "@07aa0e8b340b5240bcf4ea13a5634627",
                        "ToNickName": "\u8001\u9b4f",
                        "Content": "\u7535\u996d\u9505",
                        "CreateTime": "2017-10-13 21:53:44"
                    },
                    {
                        "id": "189",
                        "Type": "1",
                        "FromUserName": "@4e6ba1a4d15c16fd05d88172ed13ad58",
                        "FromNickName": "\u65e5\u843d",
                        "ToUserName": "@07aa0e8b340b5240bcf4ea13a5634627",
                        "ToNickName": "\u8001\u9b4f",
                        "Content": "\u5927\u795e",
                        "CreateTime": "2017-10-13 21:47:20"
                    },
                    {
                        "id": "198",
                        "Type": "2",
                        "FromUserName": "@4e6ba1a4d15c16fd05d88172ed13ad58",
                        "FromNickName": "\u65e5\u843d",
                        "ToUserName": "@07aa0e8b340b5240bcf4ea13a5634627",
                        "ToNickName": "\u8001\u9b4f",
                        "Content": "\u5927\u795e",
                        "CreateTime": "2017-10-13 21:47:20"
                    }

                ],
                "msg": ""
            };*/
            if(data.data.length!==0){
                $scope.chatContent=data.data.reverse();
                console.log($scope.chatContent)
            }

        })
    }
    $scope.goChat=function(currentUserName,nickname){
        $scope.xs=true;
        console.log(nickname)
        $scope.nickname=nickname;
        $scope.currentUserName=currentUserName;
        $scope.chatContent=[];
        $scope.contentUpdate();
    }
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

