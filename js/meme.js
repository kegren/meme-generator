$(function () {
    $(".fancybox").fancybox();
});

angular.module('service.upload', []).factory('service.upload', [
    '$http', function ($http) {
        return {
            upload: function(file) {
                return $http({
                    url: '/api/v1/images',
                    method: 'POST',
                    transformRequest: function(data) { return data; },
                    headers: {
                        'Content-Type': undefined
                    },
                    data: file
                });
            },
        };
}]);

angular.module('service.image', []).factory('service.image', [
    '$resource', function($resource) {
        return $resource("/api/v1/images");
    }
]);

angular.module('index.listCtrl', []).controller('index.listCtrl', [
    '$scope', 'service.image', 'service.upload', function ($scope, ImageSvc, UploadSvc) {
        $scope.images = [];
        $scope.isUploading = false;
        $scope.file = undefined;
        $scope.showAll = true;
        $scope.text = 'Hello World!';

        $scope.init = function() {
            ImageSvc.query(function (res) {
                $scope.images = res;

                if (res.length <= 0) {
                    $scope.images = undefined;
                }

            }, function (err) {
                $scope.images = false;
            });
        };

        $scope.setFile = function(file) {
            $scope.file = file;
        };

        $scope.delete = function() {
            if ($scope.images) {
                if (confirm('Are you sure?')) {
                    ImageSvc.delete({}, function (res) {
                        $scope.images = undefined;
                        $scope.alert = undefined;
                    });
                }
            }
        };

        $scope.upload = function() {
            if ($scope.file) {
                var file = $scope.file;

                if (confirm('Are you sure?')) {
                    $scope.isUploading = true;

                    var fd = new FormData();
                    var image = file.files;

                    angular.forEach(image, function(file) {
                        fd.append('file', file);
                        fd.append('text', $scope.text);
                    });

                    UploadSvc.upload(fd, $scope.text).then(function (res) {
                        $scope.images = res.data;

                        $scope.isUploading = false;
                        $scope.alert = {
                            type: 'success',
                            msg: 'Your image was uploaded'
                        };
                        $scope.showUploadForm = false;
                    }, function (err) {
                        $scope.isUploading = false;
                        $scope.showUploadForm = false;
                        $scope.alert = {
                            type: 'alert',
                            msg: angular.fromJson(err.data)
                        };
                    });
                }
            }
        };

        $scope.init();
}]);
// app name
var name = 'kegren';

// modules
var mods = [
    'ngResource',
    'service.image',
    'service.upload',
    'index.listCtrl'
];

// main module
var app = angular.module(name, mods);

// as we're using twig we'll change this
app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%').endSymbol('%>');
});

angular.element(document).ready(function() {
    return angular.bootstrap(document, [name]);
});
