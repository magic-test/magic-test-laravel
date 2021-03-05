/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./js/Context.js":
/*!***********************!*\
  !*** ./js/Context.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "enableKeyboardShortcuts": () => (/* binding */ enableKeyboardShortcuts)
/* harmony export */ });
function enableKeyboardShortcuts() {
  function keydown(event) {
    if (event.ctrlKey && event.shiftKey && event.key === 'A') {
      event.preventDefault();
      generateAssertion();
    }
  }

  document.addEventListener('keydown', keydown, false);

  function generateAssertion() {
    var text = selectedText();

    if (text.trim().length > 0) {
      var action = "see";
      var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
      var target = "'".concat(text.replace("'", "\\\'"), "'");
      var options = "";
      MagicTest.addData({
        action: action,
        path: '',
        target: target,
        options: options,
        classList: [],
        tag: ''
      });
      alert("Generated an assertion for \"" + selectedText() + "\". Type `ok()` in the debugger console to add it to your test file.");
    }
  }

  function selectedText() {
    var text = "";

    if (window.getSelection) {
      text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
      text = document.selection.createRange().text;
    }

    return text;
  }
}

/***/ }),

/***/ "./js/Events/Click.js":
/*!****************************!*\
  !*** ./js/Events/Click.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ click)
/* harmony export */ });
/* harmony import */ var _Finders__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../Finders */ "./js/Finders.js");

function click(event) {
  console.log(event);
  var tagName = event.currentTarget.tagName;
  var classList = event.currentTarget.classList;
  var action = "";
  var target = "";
  var options = "";
  var path = '';
  var targetMeta = {
    type: event.target.type || null
  };

  if (tagName == "BUTTON" || tagName == "A" || tagName == "INPUT" && event.currentTarget.type == "submit") {
    action = "click";
    var target = event.currentTarget.value || event.currentTarget.text || event.currentTarget.innerText;

    if (!target) {
      return;
    }

    target = "'" + target.trim().replace("'", "\\'") + "'";
  } else if (tagName == 'SELECT') {
    action = "click";
    var target = event.currentTarget.name;
    target = "'" + target.trim().replace("'", "\\'") + "'";
  } else if (tagName == "INPUT") {
    var ignoreType = ["text", "password", "date", "email", "month", "number", "search"];

    if (ignoreType.includes(event.currentTarget.type)) {
      return;
    }

    var path = (0,_Finders__WEBPACK_IMPORTED_MODULE_0__.getPathTo)(event.currentTarget);
    target = event.currentTarget.name;
    action = "click";
  } else {
    return;
  }

  if (tagName === 'SELECT') {
    targetMeta.label = event.currentTarget.value;
    var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
    var lastAction = testingOutput[testingOutput.length - 1];

    if (lastAction && lastAction.tag == tagName.toLowerCase() && lastAction.target == target) {
      lastAction.targetMeta = targetMeta;
      sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
      return;
    }
  } else {
    var _event$target$labels;

    var label = (_event$target$labels = event.target.labels) === null || _event$target$labels === void 0 ? void 0 : _event$target$labels[0];

    if (label) {
      targetMeta.label = label.innerText;
    }
  }

  MagicTest.addData({
    action: action,
    path: path,
    target: target,
    options: options,
    classList: classList,
    tag: tagName.toLowerCase(),
    targetMeta: targetMeta
  });
}

/***/ }),

/***/ "./js/Events/Keypress.js":
/*!*******************************!*\
  !*** ./js/Events/Keypress.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ keypress)
/* harmony export */ });
function keypress(event) {
  event = event || window.event;
  var charCode = event.keyCode || event.which;
  var name = event.target.name;
  var tagName = '';
  var classList = '';
  var charStr = String.fromCharCode(charCode);

  if (!event.target.labels) {
    return;
  }

  var label = event.target.labels[0].textContent;
  var text = (event.target.value + charStr).trim().replace("'", "\\'");
  var target = event.target.labels[0].textContent;
  var options = {
    "text": "'".concat(text, "'")
  };
  var action = 'fill';
  var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
  var lastAction = testingOutput[testingOutput.length - 1];

  if (lastAction && lastAction.action == action && lastAction.target == "'" + name + "'") {
    lastAction.options = options;
  } else {
    testingOutput.push({
      action: action,
      path: '',
      target: "'".concat(name, "'"),
      options: options,
      classList: classList,
      tag: tagName.toLowerCase()
    });
  }

  sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
}

/***/ }),

/***/ "./js/Finders.js":
/*!***********************!*\
  !*** ./js/Finders.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getPathTo": () => (/* binding */ getPathTo),
/* harmony export */   "visibleFilter": () => (/* binding */ visibleFilter),
/* harmony export */   "finderForElement": () => (/* binding */ finderForElement)
/* harmony export */ });
function getPathTo(element) {
  if (element.tagName == 'HTML') {
    return '/HTML[1]';
  }

  if (element === document.body) {
    return '/HTML[1]/BODY[1]';
  }

  var ix = 0;
  var siblings = element.parentNode.childNodes;

  for (var i = 0; i < siblings.length; i++) {
    var sibling = siblings[i];

    if (sibling === element) {
      return getPathTo(element.parentNode) + '/' + element.tagName + '[' + (ix + 1) + ']';
    }

    if (sibling.nodeType === 1 && sibling.tagName === element.tagName) {
      ix++;
    }
  }
} // Chrome doesn't respond to the jQuery :visible selector properly so we have to do this:

function visibleFilter() {
  return $(this).css('display') != 'none' && $(this).css('visibility') != 'hidden';
}
function finderForElement(element) {
  // Try to find just using the element tagName
  var tagName = element.tagName.toLowerCase();

  if ($(tagName).length == 1) {
    return "find('".concat(tagName, "')");
  } // Try adding in the classes of the element


  var classList = [].slice.apply(element.classList);
  var classString = classList.length ? "." + classList.join('.') : "";

  if (classList.length && $(tagName + classList).length == 1) {
    return "find('".concat(tagName).concat(classString, "')");
  } // Try adding in the text of the element


  var text = element.textContent.trim();

  if (text && $(tagName + classString + ":contains(".concat(text, "):visible")).filter(visibleFilter).length == 1) {
    return "find('".concat(tagName).concat(classString, "', text: '").concat(text, "')");
  } // use the xpath to the element


  return "find(:xpath, '".concat(getPathTo(element), "')");
}

/***/ }),

/***/ "./js/Mutation.js":
/*!************************!*\
  !*** ./js/Mutation.js ***!
  \************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initializeMutationObserver": () => (/* binding */ initializeMutationObserver),
/* harmony export */   "mutationStart": () => (/* binding */ mutationStart),
/* harmony export */   "mutationEnd": () => (/* binding */ mutationEnd)
/* harmony export */ });
function initializeMutationObserver() {
  window.mutationObserver = new MutationObserver(function (mutations) {
    console.log("Mutation observed");

    if (!window.target) {
      console.log("There is no window.target element. Quitting the mutation callback function");
      return;
    }

    var options = "";
    var targetClass = window.target.classList[0] ? ".".concat(window.target.classList[0]) : "";
    var text = window.target.innerText ? "', text: '".concat(window.target.innerText) : "";
    var action = "".concat(finderForElement(window.target), ".hover"); // var action = `find('${window.target.localName}${targetClass}${text}').hover`;

    var target = "";
    var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
    testingOutput.push({
      action: action,
      target: target,
      options: options
    });
    sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
  });
}
function mutationStart(evt) {
  window.target = evt.target;
  var opts = {
    attributes: true,
    characterData: true,
    childList: true,
    subtree: true
  };
  window.mutationObserver.observe(document.documentElement, opts);
}
function mutationEnd() {
  window.mutationObserver.disconnect();
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		if(__webpack_module_cache__[moduleId]) {
/******/ 			return __webpack_module_cache__[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**************************!*\
  !*** ./js/magic_test.js ***!
  \**************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Events_Click__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Events/Click */ "./js/Events/Click.js");
/* harmony import */ var _Events_Keypress__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Events/Keypress */ "./js/Events/Keypress.js");
/* harmony import */ var _Context__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Context */ "./js/Context.js");
/* harmony import */ var _Mutation__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Mutation */ "./js/Mutation.js");





function initializeStorage() {
  if (sessionStorage.getItem("testingOutput") == null) {
    MagicTest.clear();
  }
}

$(function () {
  console.log("Magic Test started");
  initializeStorage();
});
window.MagicTest = {
  start: function start() {
    if (!this.running()) {
      return;
    }

    document.addEventListener("keypress", _Events_Keypress__WEBPACK_IMPORTED_MODULE_1__.default);
    document.addEventListener('mouseover', _Mutation__WEBPACK_IMPORTED_MODULE_3__.mutationStart, true);
    document.addEventListener('mouseover', _Mutation__WEBPACK_IMPORTED_MODULE_3__.mutationEnd, false);
    $(document).on("click", "*", _Events_Click__WEBPACK_IMPORTED_MODULE_0__.default);
    $('select').on('change', _Events_Click__WEBPACK_IMPORTED_MODULE_0__.default);
    (0,_Context__WEBPACK_IMPORTED_MODULE_2__.enableKeyboardShortcuts)();
    (0,_Mutation__WEBPACK_IMPORTED_MODULE_3__.initializeMutationObserver)();
  },
  run: function run() {
    if (sessionStorage.getItem('magicTestRunning') == null) {
      sessionStorage.setItem('magicTestRunning', true);
      this.start();
    }
  },
  running: function running() {
    return sessionStorage.getItem('magicTestRunning') != null;
  },
  getData: function getData() {
    return sessionStorage.getItem("testingOutput") || {};
  },
  formattedData: function formattedData() {
    return JSON.parse(this.getData());
  },
  addData: function addData(data) {
    var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
    testingOutput.push(data);
    sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
  },
  clear: function clear() {
    sessionStorage.setItem("testingOutput", JSON.stringify([]));
  }
};
$(function () {
  MagicTest.start();
});
})();

/******/ })()
;