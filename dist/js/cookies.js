/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/cookies.js":
/*!******************************!*\
  !*** ./assets/js/cookies.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

var smp_cookie_name = 'social-media-popup';

function smp_setCookie(name, value, options) {
  options = options || {};
  var expires = options.expires;

  if (typeof expires == "number" && expires) {
    var d = new Date();
    d.setTime(d.getTime() + expires * 1000);
    expires = options.expires = d;
  }

  if (expires && expires.toUTCString) {
    options.expires = expires.toUTCString();
  }

  value = encodeURIComponent(value);
  var updatedCookie = name + "=" + value;

  for (var propName in options) {
    updatedCookie += "; " + propName;
    var propValue = options[propName];

    if (propValue !== true) {
      updatedCookie += "=" + propValue;
    }
  }

  document.cookie = updatedCookie;
}

window.smp_setCookie = smp_setCookie;

function smp_getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');

  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];

    while (c.charAt(0) == ' ') {
      c = c.substring(1, c.length);
    }

    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }

  return null;
}

window.smp_getCookie = smp_getCookie;

function smp_deleteCookie(name) {
  var date = new Date().getTime();
  smp_setCookie(name, '', {
    expires: date - 3600,
    path: '/'
  });
}

window.smp_deleteCookie = smp_deleteCookie;

function smp_clearAllPluginCookies() {
  if (window.confirm(window.smp_cookies.clearCookiesMessage)) {
    smp_deleteCookie(smp_cookie_name);
    smp_deleteCookie('smp-page-views');
    document.location.reload(true);
  }
}

window.smp_clearAllPluginCookies = smp_clearAllPluginCookies;

/***/ }),

/***/ 2:
/*!************************************!*\
  !*** multi ./assets/js/cookies.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/gruz0/Projects/my-projects/social-media-popup/assets/js/cookies.js */"./assets/js/cookies.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2Nvb2tpZXMuanMiXSwibmFtZXMiOlsic21wX2Nvb2tpZV9uYW1lIiwic21wX3NldENvb2tpZSIsIm5hbWUiLCJ2YWx1ZSIsIm9wdGlvbnMiLCJleHBpcmVzIiwiZCIsIkRhdGUiLCJzZXRUaW1lIiwiZ2V0VGltZSIsInRvVVRDU3RyaW5nIiwiZW5jb2RlVVJJQ29tcG9uZW50IiwidXBkYXRlZENvb2tpZSIsInByb3BOYW1lIiwicHJvcFZhbHVlIiwiZG9jdW1lbnQiLCJjb29raWUiLCJ3aW5kb3ciLCJzbXBfZ2V0Q29va2llIiwibmFtZUVRIiwiY2EiLCJzcGxpdCIsImkiLCJsZW5ndGgiLCJjIiwiY2hhckF0Iiwic3Vic3RyaW5nIiwiaW5kZXhPZiIsInNtcF9kZWxldGVDb29raWUiLCJkYXRlIiwicGF0aCIsInNtcF9jbGVhckFsbFBsdWdpbkNvb2tpZXMiLCJjb25maXJtIiwic21wX2Nvb2tpZXMiLCJjbGVhckNvb2tpZXNNZXNzYWdlIiwibG9jYXRpb24iLCJyZWxvYWQiXSwibWFwcGluZ3MiOiI7QUFBQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGtEQUEwQyxnQ0FBZ0M7QUFDMUU7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxnRUFBd0Qsa0JBQWtCO0FBQzFFO0FBQ0EseURBQWlELGNBQWM7QUFDL0Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlEQUF5QyxpQ0FBaUM7QUFDMUUsd0hBQWdILG1CQUFtQixFQUFFO0FBQ3JJO0FBQ0E7O0FBRUE7QUFDQTtBQUNB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U0cseUJBQVQsR0FBcUM7QUFDcEMsTUFBSWQsTUFBTSxDQUFDZSxPQUFQLENBQWVmLE1BQU0sQ0FBQ2dCLFdBQVAsQ0FBbUJDLG1CQUFsQyxDQUFKLEVBQTREO0FBQzNETixvQkFBZ0IsQ0FBQzVCLGVBQUQsQ0FBaEI7QUFDQTRCLG9CQUFnQixDQUFDLGdCQUFELENBQWhCO0FBQ0FiLFlBQVEsQ0FBQ29CLFFBQVQsQ0FBa0JDLE1BQWxCLENBQXlCLElBQXpCO0FBQ0E7QUFDRDs7QUFDRG5CLE1BQU0sQ0FBQ2MseUJBQVAsR0FBbUNBLHlCQUFuQyxDIiwiZmlsZSI6ImNvb2tpZXMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMik7XG4iLCJ2YXIgc21wX2Nvb2tpZV9uYW1lID0gJ3NvY2lhbC1tZWRpYS1wb3B1cCc7XG5cbmZ1bmN0aW9uIHNtcF9zZXRDb29raWUobmFtZSwgdmFsdWUsIG9wdGlvbnMpIHtcblx0b3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG5cdGxldCBleHBpcmVzID0gb3B0aW9ucy5leHBpcmVzO1xuXG5cdGlmICh0eXBlb2YgZXhwaXJlcyA9PSBcIm51bWJlclwiICYmIGV4cGlyZXMpIHtcblx0XHRsZXQgZCA9IG5ldyBEYXRlKCk7XG5cdFx0ZC5zZXRUaW1lKGQuZ2V0VGltZSgpICsgZXhwaXJlcyoxMDAwKTtcblx0XHRleHBpcmVzID0gb3B0aW9ucy5leHBpcmVzID0gZDtcblx0fVxuXG5cdGlmIChleHBpcmVzICYmIGV4cGlyZXMudG9VVENTdHJpbmcpIHtcblx0XHRvcHRpb25zLmV4cGlyZXMgPSBleHBpcmVzLnRvVVRDU3RyaW5nKCk7XG5cdH1cblxuXHR2YWx1ZSA9IGVuY29kZVVSSUNvbXBvbmVudCh2YWx1ZSk7XG5cdGxldCB1cGRhdGVkQ29va2llID0gbmFtZSArIFwiPVwiICsgdmFsdWU7XG5cblx0Zm9yKGxldCBwcm9wTmFtZSBpbiBvcHRpb25zKSB7XG5cdFx0dXBkYXRlZENvb2tpZSArPSBcIjsgXCIgKyBwcm9wTmFtZTtcblx0XHRsZXQgcHJvcFZhbHVlID0gb3B0aW9uc1twcm9wTmFtZV07XG5cdFx0aWYgKHByb3BWYWx1ZSAhPT0gdHJ1ZSkge1xuXHRcdFx0dXBkYXRlZENvb2tpZSArPSBcIj1cIiArIHByb3BWYWx1ZTtcblx0XHR9XG5cdH1cblxuXHRkb2N1bWVudC5jb29raWUgPSB1cGRhdGVkQ29va2llO1xufVxud2luZG93LnNtcF9zZXRDb29raWUgPSBzbXBfc2V0Q29va2llO1xuXG5mdW5jdGlvbiBzbXBfZ2V0Q29va2llKG5hbWUpIHtcblx0bGV0IG5hbWVFUSA9IG5hbWUgKyBcIj1cIjtcblx0bGV0IGNhID0gZG9jdW1lbnQuY29va2llLnNwbGl0KCc7Jyk7XG5cblx0Zm9yKGxldCBpPTA7aSA8IGNhLmxlbmd0aDtpKyspIHtcblx0XHRsZXQgYyA9IGNhW2ldO1xuXHRcdHdoaWxlIChjLmNoYXJBdCgwKT09JyAnKSBjID0gYy5zdWJzdHJpbmcoMSxjLmxlbmd0aCk7XG5cdFx0aWYgKGMuaW5kZXhPZihuYW1lRVEpID09IDApIHJldHVybiBjLnN1YnN0cmluZyhuYW1lRVEubGVuZ3RoLGMubGVuZ3RoKTtcblx0fVxuXG5cdHJldHVybiBudWxsO1xufVxud2luZG93LnNtcF9nZXRDb29raWUgPSBzbXBfZ2V0Q29va2llO1xuXG5mdW5jdGlvbiBzbXBfZGVsZXRlQ29va2llKG5hbWUpIHtcblx0bGV0IGRhdGUgPSBuZXcgRGF0ZSgpLmdldFRpbWUoKTtcblx0c21wX3NldENvb2tpZShuYW1lLCAnJywgeyBleHBpcmVzOiBkYXRlIC0gMzYwMCwgcGF0aDogJy8nIH0pO1xufVxud2luZG93LnNtcF9kZWxldGVDb29raWUgPSBzbXBfZGVsZXRlQ29va2llO1xuXG5mdW5jdGlvbiBzbXBfY2xlYXJBbGxQbHVnaW5Db29raWVzKCkge1xuXHRpZiAod2luZG93LmNvbmZpcm0od2luZG93LnNtcF9jb29raWVzLmNsZWFyQ29va2llc01lc3NhZ2UpKSB7XG5cdFx0c21wX2RlbGV0ZUNvb2tpZShzbXBfY29va2llX25hbWUpO1xuXHRcdHNtcF9kZWxldGVDb29raWUoJ3NtcC1wYWdlLXZpZXdzJyk7XG5cdFx0ZG9jdW1lbnQubG9jYXRpb24ucmVsb2FkKHRydWUpO1xuXHR9XG59XG53aW5kb3cuc21wX2NsZWFyQWxsUGx1Z2luQ29va2llcyA9IHNtcF9jbGVhckFsbFBsdWdpbkNvb2tpZXM7XG4iXSwic291cmNlUm9vdCI6IiJ9