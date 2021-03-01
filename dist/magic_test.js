/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************!*\
  !*** ./js/magic_test.js ***!
  \**************************/
function initializeStorage() {
  if (sessionStorage.getItem("testingOutput") == null) {
    sessionStorage.setItem("testingOutput", JSON.stringify([]));
  }
}

$(function () {
  console.log('Magic Test started');
  initializeStorage();
});

function clickFunction(event) {
  console.log('clicked');
  var tagName = event.currentTarget.tagName;
  var action = "";
  var target = "";
  var options = "";

  if (tagName == "BUTTON" || tagName == "A" || tagName == "INPUT" && event.currentTarget.type == 'submit') {
    action = "click";
    var target = event.currentTarget.value || event.currentTarget.text;

    if (!target) {
      return;
    }

    target = "\'" + target.trim().replace("'", "\\\'") + "\'";
  } else if (tagName == "INPUT") {
    var ignoreType = ['text', 'password', 'date', 'email', 'month', 'number', 'search'];

    if (ignoreType.includes(event.currentTarget.type)) {
      return;
    }

    var path = getPathTo(event.currentTarget);
    action = "click(".concat(path);
  } else {
    return;
  }

  var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
  testingOutput.push({
    action: action,
    target: target,
    options: options
  });
  sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
}

;
$(document).on('click', '*', function (event) {
  clickFunction(event);
});
window.MagicTest = {
  getData: function getData() {
    return sessionStorage.getItem("testingOutput") || {};
  }
};
/******/ })()
;