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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/bundle.js":
/*!*****************************!*\
  !*** ./assets/js/bundle.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var js_base64__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! js-base64 */ "./node_modules/js-base64/base64.js");
/* harmony import */ var js_base64__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(js_base64__WEBPACK_IMPORTED_MODULE_0__);

var smp_container_id = '#social_media_popup';
var smp_cookie_name = 'social-media-popup';
var smp_eventFired = false;
var smp_firedEventDescription = '';
jQuery(document).ready(function ($) {
  smp_renderPopup();
  $(smp_container_id + ' ul.tabs, ' + smp_container_id + ' ul.smp-icons').on('click', 'li:not(.current)', function () {
    $(this).addClass('current').siblings().removeClass('current').parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
  });
  $(smp_container_id + ' ul.tabs li:first, ' + smp_container_id + ' ul.smp-icons li:first').addClass('current');
  $(smp_container_id + ' .section .box:first').addClass('visible');
});

function is_smp_cookie_present() {
  return smp_getCookie(smp_cookie_name) && smp_getCookie(smp_cookie_name) == 'true';
}

window.is_smp_cookie_present = is_smp_cookie_present;

function smp_destroyPlugin(after_n_days, container_id) {
  var date = new Date(new Date().getTime() + 1000 * 60 * 60 * 24 * after_n_days);
  smp_setCookie(smp_cookie_name, "true", {
    "expires": date,
    "path": "/"
  });
  smp_deleteCookie('smp-page-views');
  jQuery(container_id || smp_container_id).hide();
}

window.smp_destroyPlugin = smp_destroyPlugin;

function smp_renderPopup() {
  // jshint ignore:start
  eval(js_base64__WEBPACK_IMPORTED_MODULE_0__["Base64"].decode(smp.encodedContent)); // jshint ignore:end
}

function smp_getWindowHeight() {
  return window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight || 0;
}

function smp_getWindowYscroll() {
  return window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop || 0;
}

function smp_getDocHeight() {
  return Math.max(document.body.scrollHeight || 0, document.documentElement.scrollHeight || 0, document.body.offsetHeight || 0, document.documentElement.offsetHeight || 0, document.body.clientHeight || 0, document.documentElement.clientHeight || 0);
}

function smp_getScrollPercentage() {
  return parseInt(Math.abs((smp_getWindowYscroll() + smp_getWindowHeight()) / smp_getDocHeight() * 100));
}

window.smp_getScrollPercentage = smp_getScrollPercentage;

/***/ }),

/***/ "./node_modules/js-base64/base64.js":
/*!******************************************!*\
  !*** ./node_modules/js-base64/base64.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/*
 *  base64.js
 *
 *  Licensed under the BSD 3-Clause License.
 *    http://opensource.org/licenses/BSD-3-Clause
 *
 *  References:
 *    http://en.wikipedia.org/wiki/Base64
 */
;

(function (global, factory) {
  ( false ? undefined : _typeof(exports)) === 'object' && typeof module !== 'undefined' ? module.exports = factory(global) :  true ? !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : undefined;
})(typeof self !== 'undefined' ? self : typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : this, function (global) {
  'use strict'; // existing version for noConflict()

  var _Base64 = global.Base64;
  var version = "2.5.0"; // if node.js and NOT React Native, we use Buffer

  var buffer;

  if ( true && module.exports) {
    try {
      buffer = eval("require('buffer').Buffer");
    } catch (err) {
      buffer = undefined;
    }
  } // constants


  var b64chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

  var b64tab = function (bin) {
    var t = {};

    for (var i = 0, l = bin.length; i < l; i++) {
      t[bin.charAt(i)] = i;
    }

    return t;
  }(b64chars);

  var fromCharCode = String.fromCharCode; // encoder stuff

  var cb_utob = function cb_utob(c) {
    if (c.length < 2) {
      var cc = c.charCodeAt(0);
      return cc < 0x80 ? c : cc < 0x800 ? fromCharCode(0xc0 | cc >>> 6) + fromCharCode(0x80 | cc & 0x3f) : fromCharCode(0xe0 | cc >>> 12 & 0x0f) + fromCharCode(0x80 | cc >>> 6 & 0x3f) + fromCharCode(0x80 | cc & 0x3f);
    } else {
      var cc = 0x10000 + (c.charCodeAt(0) - 0xD800) * 0x400 + (c.charCodeAt(1) - 0xDC00);
      return fromCharCode(0xf0 | cc >>> 18 & 0x07) + fromCharCode(0x80 | cc >>> 12 & 0x3f) + fromCharCode(0x80 | cc >>> 6 & 0x3f) + fromCharCode(0x80 | cc & 0x3f);
    }
  };

  var re_utob = /[\uD800-\uDBFF][\uDC00-\uDFFFF]|[^\x00-\x7F]/g;

  var utob = function utob(u) {
    return u.replace(re_utob, cb_utob);
  };

  var cb_encode = function cb_encode(ccc) {
    var padlen = [0, 2, 1][ccc.length % 3],
        ord = ccc.charCodeAt(0) << 16 | (ccc.length > 1 ? ccc.charCodeAt(1) : 0) << 8 | (ccc.length > 2 ? ccc.charCodeAt(2) : 0),
        chars = [b64chars.charAt(ord >>> 18), b64chars.charAt(ord >>> 12 & 63), padlen >= 2 ? '=' : b64chars.charAt(ord >>> 6 & 63), padlen >= 1 ? '=' : b64chars.charAt(ord & 63)];
    return chars.join('');
  };

  var btoa = global.btoa ? function (b) {
    return global.btoa(b);
  } : function (b) {
    return b.replace(/[\s\S]{1,3}/g, cb_encode);
  };

  var _encode = buffer ? buffer.from && Uint8Array && buffer.from !== Uint8Array.from ? function (u) {
    return (u.constructor === buffer.constructor ? u : buffer.from(u)).toString('base64');
  } : function (u) {
    return (u.constructor === buffer.constructor ? u : new buffer(u)).toString('base64');
  } : function (u) {
    return btoa(utob(u));
  };

  var encode = function encode(u, urisafe) {
    return !urisafe ? _encode(String(u)) : _encode(String(u)).replace(/[+\/]/g, function (m0) {
      return m0 == '+' ? '-' : '_';
    }).replace(/=/g, '');
  };

  var encodeURI = function encodeURI(u) {
    return encode(u, true);
  }; // decoder stuff


  var re_btou = new RegExp(['[\xC0-\xDF][\x80-\xBF]', '[\xE0-\xEF][\x80-\xBF]{2}', '[\xF0-\xF7][\x80-\xBF]{3}'].join('|'), 'g');

  var cb_btou = function cb_btou(cccc) {
    switch (cccc.length) {
      case 4:
        var cp = (0x07 & cccc.charCodeAt(0)) << 18 | (0x3f & cccc.charCodeAt(1)) << 12 | (0x3f & cccc.charCodeAt(2)) << 6 | 0x3f & cccc.charCodeAt(3),
            offset = cp - 0x10000;
        return fromCharCode((offset >>> 10) + 0xD800) + fromCharCode((offset & 0x3FF) + 0xDC00);

      case 3:
        return fromCharCode((0x0f & cccc.charCodeAt(0)) << 12 | (0x3f & cccc.charCodeAt(1)) << 6 | 0x3f & cccc.charCodeAt(2));

      default:
        return fromCharCode((0x1f & cccc.charCodeAt(0)) << 6 | 0x3f & cccc.charCodeAt(1));
    }
  };

  var btou = function btou(b) {
    return b.replace(re_btou, cb_btou);
  };

  var cb_decode = function cb_decode(cccc) {
    var len = cccc.length,
        padlen = len % 4,
        n = (len > 0 ? b64tab[cccc.charAt(0)] << 18 : 0) | (len > 1 ? b64tab[cccc.charAt(1)] << 12 : 0) | (len > 2 ? b64tab[cccc.charAt(2)] << 6 : 0) | (len > 3 ? b64tab[cccc.charAt(3)] : 0),
        chars = [fromCharCode(n >>> 16), fromCharCode(n >>> 8 & 0xff), fromCharCode(n & 0xff)];
    chars.length -= [0, 0, 2, 1][padlen];
    return chars.join('');
  };

  var _atob = global.atob ? function (a) {
    return global.atob(a);
  } : function (a) {
    return a.replace(/\S{1,4}/g, cb_decode);
  };

  var atob = function atob(a) {
    return _atob(String(a).replace(/[^A-Za-z0-9\+\/]/g, ''));
  };

  var _decode = buffer ? buffer.from && Uint8Array && buffer.from !== Uint8Array.from ? function (a) {
    return (a.constructor === buffer.constructor ? a : buffer.from(a, 'base64')).toString();
  } : function (a) {
    return (a.constructor === buffer.constructor ? a : new buffer(a, 'base64')).toString();
  } : function (a) {
    return btou(_atob(a));
  };

  var decode = function decode(a) {
    return _decode(String(a).replace(/[-_]/g, function (m0) {
      return m0 == '-' ? '+' : '/';
    }).replace(/[^A-Za-z0-9\+\/]/g, ''));
  };

  var noConflict = function noConflict() {
    var Base64 = global.Base64;
    global.Base64 = _Base64;
    return Base64;
  }; // export Base64


  global.Base64 = {
    VERSION: version,
    atob: atob,
    btoa: btoa,
    fromBase64: decode,
    toBase64: encode,
    utob: utob,
    encode: encode,
    encodeURI: encodeURI,
    btou: btou,
    decode: decode,
    noConflict: noConflict,
    __buffer__: buffer
  }; // if ES5 is available, make Base64.extendString() available

  if (typeof Object.defineProperty === 'function') {
    var noEnum = function noEnum(v) {
      return {
        value: v,
        enumerable: false,
        writable: true,
        configurable: true
      };
    };

    global.Base64.extendString = function () {
      Object.defineProperty(String.prototype, 'fromBase64', noEnum(function () {
        return decode(this);
      }));
      Object.defineProperty(String.prototype, 'toBase64', noEnum(function (urisafe) {
        return encode(this, urisafe);
      }));
      Object.defineProperty(String.prototype, 'toBase64URI', noEnum(function () {
        return encode(this, true);
      }));
    };
  } //
  // export Base64 to the namespace
  //


  if (global['Meteor']) {
    // Meteor.js
    Base64 = global.Base64;
  } // module.exports and AMD are mutually exclusive.
  // module.exports has precedence.


  if ( true && module.exports) {
    module.exports.Base64 = global.Base64;
  } else if (true) {
    // AMD. Register as an anonymous module.
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
      return global.Base64;
    }).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } // that's it!


  return {
    Base64: global.Base64
  };
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

var g; // This works in non-strict mode

g = function () {
  return this;
}();

try {
  // This works if eval is allowed (see CSP)
  g = g || new Function("return this")();
} catch (e) {
  // This works if the window reference is available
  if ((typeof window === "undefined" ? "undefined" : _typeof(window)) === "object") g = window;
} // g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}


module.exports = g;

/***/ }),

/***/ 0:
/*!***********************************!*\
  !*** multi ./assets/js/bundle.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/gruz0/Projects/my-projects/social-media-popup/assets/js/bundle.js */"./assets/js/bundle.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2J1bmRsZS5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvanMtYmFzZTY0L2Jhc2U2NC5qcyIsIndlYnBhY2s6Ly8vKHdlYnBhY2spL2J1aWxkaW4vZ2xvYmFsLmpzIl0sIm5hbWVzIjpbInNtcF9jb250YWluZXJfaWQiLCJzbXBfY29va2llX25hbWUiLCJzbXBfZXZlbnRGaXJlZCIsInNtcF9maXJlZEV2ZW50RGVzY3JpcHRpb24iLCJqUXVlcnkiLCJkb2N1bWVudCIsInJlYWR5IiwiJCIsInNtcF9yZW5kZXJQb3B1cCIsIm9uIiwiYWRkQ2xhc3MiLCJzaWJsaW5ncyIsInJlbW92ZUNsYXNzIiwicGFyZW50cyIsImZpbmQiLCJlcSIsImluZGV4IiwiZmFkZUluIiwiaGlkZSIsImlzX3NtcF9jb29raWVfcHJlc2VudCIsInNtcF9nZXRDb29raWUiLCJ3aW5kb3ciLCJzbXBfZGVzdHJveVBsdWdpbiIsImFmdGVyX25fZGF5cyIsImNvbnRhaW5lcl9pZCIsImRhdGUiLCJEYXRlIiwiZ2V0VGltZSIsInNtcF9zZXRDb29raWUiLCJzbXBfZGVsZXRlQ29va2llIiwiZXZhbCIsIkJhc2U2NCIsImRlY29kZSIsInNtcCIsImVuY29kZWRDb250ZW50Iiwic21wX2dldFdpbmRvd0hlaWdodCIsImlubmVySGVpZ2h0IiwiZG9jdW1lbnRFbGVtZW50IiwiY2xpZW50SGVpZ2h0IiwiYm9keSIsInNtcF9nZXRXaW5kb3dZc2Nyb2xsIiwicGFnZVlPZmZzZXQiLCJzY3JvbGxUb3AiLCJzbXBfZ2V0RG9jSGVpZ2h0IiwiTWF0aCIsIm1heCIsInNjcm9sbEhlaWdodCIsIm9mZnNldEhlaWdodCIsInNtcF9nZXRTY3JvbGxQZXJjZW50YWdlIiwicGFyc2VJbnQiLCJhYnMiLCJnbG9iYWwiLCJmYWN0b3J5IiwiZXhwb3J0cyIsIm1vZHVsZSIsImRlZmluZSIsInNlbGYiLCJfQmFzZTY0IiwidmVyc2lvbiIsImJ1ZmZlciIsImVyciIsInVuZGVmaW5lZCIsImI2NGNoYXJzIiwiYjY0dGFiIiwiYmluIiwidCIsImkiLCJsIiwibGVuZ3RoIiwiY2hhckF0IiwiZnJvbUNoYXJDb2RlIiwiU3RyaW5nIiwiY2JfdXRvYiIsImMiLCJjYyIsImNoYXJDb2RlQXQiLCJyZV91dG9iIiwidXRvYiIsInUiLCJyZXBsYWNlIiwiY2JfZW5jb2RlIiwiY2NjIiwicGFkbGVuIiwib3JkIiwiY2hhcnMiLCJqb2luIiwiYnRvYSIsImIiLCJfZW5jb2RlIiwiZnJvbSIsIlVpbnQ4QXJyYXkiLCJjb25zdHJ1Y3RvciIsInRvU3RyaW5nIiwiZW5jb2RlIiwidXJpc2FmZSIsIm0wIiwiZW5jb2RlVVJJIiwicmVfYnRvdSIsIlJlZ0V4cCIsImNiX2J0b3UiLCJjY2NjIiwiY3AiLCJvZmZzZXQiLCJidG91IiwiY2JfZGVjb2RlIiwibGVuIiwibiIsIl9hdG9iIiwiYXRvYiIsImEiLCJfZGVjb2RlIiwibm9Db25mbGljdCIsIlZFUlNJT04iLCJmcm9tQmFzZTY0IiwidG9CYXNlNjQiLCJfX2J1ZmZlcl9fIiwiT2JqZWN0IiwiZGVmaW5lUHJvcGVydHkiLCJub0VudW0iLCJ2IiwidmFsdWUiLCJlbnVtZXJhYmxlIiwid3JpdGFibGUiLCJjb25maWd1cmFibGUiLCJleHRlbmRTdHJpbmciLCJwcm90b3R5cGUiLCJnIiwiRnVuY3Rpb24iLCJlIl0sIm1hcHBpbmdzIjoiO0FBQUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxrREFBMEMsZ0NBQWdDO0FBQzFFO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsZ0VBQXdELGtCQUFrQjtBQUMxRTtBQUNBLHlEQUFpRCxjQUFjO0FBQy9EOztBQUVB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QUFHb0IsTUFBTSxDQUFDcEIsTUFBaEI7QUFDSCxHQTFNZSxDQTJNaEI7QUFDQTs7O0FBQ0EsTUFBSSxTQUFpQ3VCLE1BQU0sQ0FBQ0QsT0FBNUMsRUFBcUQ7QUFDakRDLFVBQU0sQ0FBQ0QsT0FBUCxDQUFldEIsTUFBZixHQUF3Qm9CLE1BQU0sQ0FBQ3BCLE1BQS9CO0FBQ0gsR0FGRCxNQUdLLElBQUksSUFBSixFQUFnRDtBQUNqRDtBQUNBd0IscUNBQU8sRUFBRCxtQ0FBSyxZQUFVO0FBQUUsYUFBT0osTUFBTSxDQUFDcEIsTUFBZDtBQUFzQixLQUF2QztBQUFBLG9HQUFOO0FBQ0gsR0FuTmUsQ0FvTmhCOzs7QUFDQSxTQUFPO0FBQUNBLFVBQU0sRUFBRW9CLE1BQU0sQ0FBQ3BCO0FBQWhCLEdBQVA7QUFDSCxDQWhPQyxDQUFELEM7Ozs7Ozs7Ozs7Ozs7O0FDVEQsSUFBSStGLENBQUosQyxDQUVBOztBQUNBQSxDQUFDLEdBQUksWUFBVztBQUNmLFNBQU8sSUFBUDtBQUNBLENBRkcsRUFBSjs7QUFJQSxJQUFJO0FBQ0g7QUFDQUEsR0FBQyxHQUFHQSxDQUFDLElBQUksSUFBSUMsUUFBSixDQUFhLGFBQWIsR0FBVDtBQUNBLENBSEQsQ0FHRSxPQUFPQyxDQUFQLEVBQVU7QUFDWDtBQUNBLE1BQUksUUFBTzNHLE1BQVAseUNBQU9BLE1BQVAsT0FBa0IsUUFBdEIsRUFBZ0N5RyxDQUFDLEdBQUd6RyxNQUFKO0FBQ2hDLEMsQ0FFRDtBQUNBO0FBQ0E7OztBQUVBaUMsTUFBTSxDQUFDRCxPQUFQLEdBQWlCeUUsQ0FBakIsQyIsImZpbGUiOiJidW5kbGUuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMCk7XG4iLCJpbXBvcnQgeyBCYXNlNjQgfSBmcm9tICdqcy1iYXNlNjQnO1xuXG5sZXQgc21wX2NvbnRhaW5lcl9pZCA9ICcjc29jaWFsX21lZGlhX3BvcHVwJztcbmxldCBzbXBfY29va2llX25hbWUgPSAnc29jaWFsLW1lZGlhLXBvcHVwJztcblxubGV0IHNtcF9ldmVudEZpcmVkID0gZmFsc2U7XG5sZXQgc21wX2ZpcmVkRXZlbnREZXNjcmlwdGlvbiA9ICcnO1xuXG5qUXVlcnkoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCQpe1xuXHRzbXBfcmVuZGVyUG9wdXAoKTtcblxuXHQkKHNtcF9jb250YWluZXJfaWQgKyAnIHVsLnRhYnMsICcgKyBzbXBfY29udGFpbmVyX2lkICsgJyB1bC5zbXAtaWNvbnMnKS5vbignY2xpY2snLCAnbGk6bm90KC5jdXJyZW50KScsIGZ1bmN0aW9uKCkge1xuXHRcdCQodGhpcykuYWRkQ2xhc3MoJ2N1cnJlbnQnKS5zaWJsaW5ncygpLnJlbW92ZUNsYXNzKCdjdXJyZW50Jylcblx0XHRcdC5wYXJlbnRzKCdkaXYuc2VjdGlvbicpLmZpbmQoJ2Rpdi5ib3gnKS5lcSgkKHRoaXMpLmluZGV4KCkpLmZhZGVJbigxNTApLnNpYmxpbmdzKCdkaXYuYm94JykuaGlkZSgpO1xuXHR9KTtcblxuXHQkKHNtcF9jb250YWluZXJfaWQgKyAnIHVsLnRhYnMgbGk6Zmlyc3QsICcgKyBzbXBfY29udGFpbmVyX2lkICsgJyB1bC5zbXAtaWNvbnMgbGk6Zmlyc3QnKS5hZGRDbGFzcygnY3VycmVudCcpO1xuXHQkKHNtcF9jb250YWluZXJfaWQgKyAnIC5zZWN0aW9uIC5ib3g6Zmlyc3QnKS5hZGRDbGFzcygndmlzaWJsZScpO1xufSk7XG5cbmZ1bmN0aW9uIGlzX3NtcF9jb29raWVfcHJlc2VudCgpIHtcblx0cmV0dXJuIChzbXBfZ2V0Q29va2llKHNtcF9jb29raWVfbmFtZSkgJiYgc21wX2dldENvb2tpZShzbXBfY29va2llX25hbWUpID09ICd0cnVlJyk7XG59XG53aW5kb3cuaXNfc21wX2Nvb2tpZV9wcmVzZW50ID0gaXNfc21wX2Nvb2tpZV9wcmVzZW50O1xuXG5mdW5jdGlvbiBzbXBfZGVzdHJveVBsdWdpbihhZnRlcl9uX2RheXMsIGNvbnRhaW5lcl9pZCkge1xuXHRsZXQgZGF0ZSA9IG5ldyBEYXRlKCBuZXcgRGF0ZSgpLmdldFRpbWUoKSArICgxMDAwICogNjAgKiA2MCAqIDI0ICogYWZ0ZXJfbl9kYXlzKSApO1xuXHRzbXBfc2V0Q29va2llKHNtcF9jb29raWVfbmFtZSwgXCJ0cnVlXCIsIHsgXCJleHBpcmVzXCI6IGRhdGUsIFwicGF0aFwiOiBcIi9cIiB9ICk7XG5cdHNtcF9kZWxldGVDb29raWUoJ3NtcC1wYWdlLXZpZXdzJyk7XG5cdGpRdWVyeShjb250YWluZXJfaWQgfHwgc21wX2NvbnRhaW5lcl9pZCkuaGlkZSgpO1xufVxud2luZG93LnNtcF9kZXN0cm95UGx1Z2luID0gc21wX2Rlc3Ryb3lQbHVnaW47XG5cbmZ1bmN0aW9uIHNtcF9yZW5kZXJQb3B1cCgpIHtcblx0Ly8ganNoaW50IGlnbm9yZTpzdGFydFxuXHRldmFsKEJhc2U2NC5kZWNvZGUoc21wLmVuY29kZWRDb250ZW50KSk7XG5cdC8vIGpzaGludCBpZ25vcmU6ZW5kXG59XG5cbmZ1bmN0aW9uIHNtcF9nZXRXaW5kb3dIZWlnaHQoKSB7XG5cdHJldHVybiB3aW5kb3cuaW5uZXJIZWlnaHQgfHwgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudEhlaWdodCB8fCBkb2N1bWVudC5ib2R5LmNsaWVudEhlaWdodCB8fCAwO1xufVxuXG5mdW5jdGlvbiBzbXBfZ2V0V2luZG93WXNjcm9sbCgpIHtcblx0cmV0dXJuIHdpbmRvdy5wYWdlWU9mZnNldCB8fCBkb2N1bWVudC5ib2R5LnNjcm9sbFRvcCB8fCBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuc2Nyb2xsVG9wIHx8IDA7XG59XG5cbmZ1bmN0aW9uIHNtcF9nZXREb2NIZWlnaHQoKSB7XG5cdHJldHVybiBNYXRoLm1heChcblx0XHRkb2N1bWVudC5ib2R5LnNjcm9sbEhlaWdodCB8fCAwLFxuXHRcdGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5zY3JvbGxIZWlnaHQgfHwgMCxcblx0XHRkb2N1bWVudC5ib2R5Lm9mZnNldEhlaWdodCB8fCAwLFxuXHRcdGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5vZmZzZXRIZWlnaHQgfHwgMCxcblx0XHRkb2N1bWVudC5ib2R5LmNsaWVudEhlaWdodCB8fCAwLFxuXHRcdGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGllbnRIZWlnaHQgfHwgMFxuXHRcdCk7XG59XG5cbmZ1bmN0aW9uIHNtcF9nZXRTY3JvbGxQZXJjZW50YWdlKCkge1xuXHRyZXR1cm4gcGFyc2VJbnQoTWF0aC5hYnMoKChzbXBfZ2V0V2luZG93WXNjcm9sbCgpICsgc21wX2dldFdpbmRvd0hlaWdodCgpKSAvIHNtcF9nZXREb2NIZWlnaHQoKSkgKiAxMDApKTtcbn1cbndpbmRvdy5zbXBfZ2V0U2Nyb2xsUGVyY2VudGFnZSA9IHNtcF9nZXRTY3JvbGxQZXJjZW50YWdlO1xuIiwiLypcbiAqICBiYXNlNjQuanNcbiAqXG4gKiAgTGljZW5zZWQgdW5kZXIgdGhlIEJTRCAzLUNsYXVzZSBMaWNlbnNlLlxuICogICAgaHR0cDovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL0JTRC0zLUNsYXVzZVxuICpcbiAqICBSZWZlcmVuY2VzOlxuICogICAgaHR0cDovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9CYXNlNjRcbiAqL1xuOyhmdW5jdGlvbiAoZ2xvYmFsLCBmYWN0b3J5KSB7XG4gICAgdHlwZW9mIGV4cG9ydHMgPT09ICdvYmplY3QnICYmIHR5cGVvZiBtb2R1bGUgIT09ICd1bmRlZmluZWQnXG4gICAgICAgID8gbW9kdWxlLmV4cG9ydHMgPSBmYWN0b3J5KGdsb2JhbClcbiAgICAgICAgOiB0eXBlb2YgZGVmaW5lID09PSAnZnVuY3Rpb24nICYmIGRlZmluZS5hbWRcbiAgICAgICAgPyBkZWZpbmUoZmFjdG9yeSkgOiBmYWN0b3J5KGdsb2JhbClcbn0oKFxuICAgIHR5cGVvZiBzZWxmICE9PSAndW5kZWZpbmVkJyA/IHNlbGZcbiAgICAgICAgOiB0eXBlb2Ygd2luZG93ICE9PSAndW5kZWZpbmVkJyA/IHdpbmRvd1xuICAgICAgICA6IHR5cGVvZiBnbG9iYWwgIT09ICd1bmRlZmluZWQnID8gZ2xvYmFsXG46IHRoaXNcbiksIGZ1bmN0aW9uKGdsb2JhbCkge1xuICAgICd1c2Ugc3RyaWN0JztcbiAgICAvLyBleGlzdGluZyB2ZXJzaW9uIGZvciBub0NvbmZsaWN0KClcbiAgICB2YXIgX0Jhc2U2NCA9IGdsb2JhbC5CYXNlNjQ7XG4gICAgdmFyIHZlcnNpb24gPSBcIjIuNS4wXCI7XG4gICAgLy8gaWYgbm9kZS5qcyBhbmQgTk9UIFJlYWN0IE5hdGl2ZSwgd2UgdXNlIEJ1ZmZlclxuICAgIHZhciBidWZmZXI7XG4gICAgaWYgKHR5cGVvZiBtb2R1bGUgIT09ICd1bmRlZmluZWQnICYmIG1vZHVsZS5leHBvcnRzKSB7XG4gICAgICAgIHRyeSB7XG4gICAgICAgICAgICBidWZmZXIgPSBldmFsKFwicmVxdWlyZSgnYnVmZmVyJykuQnVmZmVyXCIpO1xuICAgICAgICB9IGNhdGNoIChlcnIpIHtcbiAgICAgICAgICAgIGJ1ZmZlciA9IHVuZGVmaW5lZDtcbiAgICAgICAgfVxuICAgIH1cbiAgICAvLyBjb25zdGFudHNcbiAgICB2YXIgYjY0Y2hhcnNcbiAgICAgICAgPSAnQUJDREVGR0hJSktMTU5PUFFSU1RVVldYWVphYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5ejAxMjM0NTY3ODkrLyc7XG4gICAgdmFyIGI2NHRhYiA9IGZ1bmN0aW9uKGJpbikge1xuICAgICAgICB2YXIgdCA9IHt9O1xuICAgICAgICBmb3IgKHZhciBpID0gMCwgbCA9IGJpbi5sZW5ndGg7IGkgPCBsOyBpKyspIHRbYmluLmNoYXJBdChpKV0gPSBpO1xuICAgICAgICByZXR1cm4gdDtcbiAgICB9KGI2NGNoYXJzKTtcbiAgICB2YXIgZnJvbUNoYXJDb2RlID0gU3RyaW5nLmZyb21DaGFyQ29kZTtcbiAgICAvLyBlbmNvZGVyIHN0dWZmXG4gICAgdmFyIGNiX3V0b2IgPSBmdW5jdGlvbihjKSB7XG4gICAgICAgIGlmIChjLmxlbmd0aCA8IDIpIHtcbiAgICAgICAgICAgIHZhciBjYyA9IGMuY2hhckNvZGVBdCgwKTtcbiAgICAgICAgICAgIHJldHVybiBjYyA8IDB4ODAgPyBjXG4gICAgICAgICAgICAgICAgOiBjYyA8IDB4ODAwID8gKGZyb21DaGFyQ29kZSgweGMwIHwgKGNjID4+PiA2KSlcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgKyBmcm9tQ2hhckNvZGUoMHg4MCB8IChjYyAmIDB4M2YpKSlcbiAgICAgICAgICAgICAgICA6IChmcm9tQ2hhckNvZGUoMHhlMCB8ICgoY2MgPj4+IDEyKSAmIDB4MGYpKVxuICAgICAgICAgICAgICAgICAgICsgZnJvbUNoYXJDb2RlKDB4ODAgfCAoKGNjID4+PiAgNikgJiAweDNmKSlcbiAgICAgICAgICAgICAgICAgICArIGZyb21DaGFyQ29kZSgweDgwIHwgKCBjYyAgICAgICAgICYgMHgzZikpKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHZhciBjYyA9IDB4MTAwMDBcbiAgICAgICAgICAgICAgICArIChjLmNoYXJDb2RlQXQoMCkgLSAweEQ4MDApICogMHg0MDBcbiAgICAgICAgICAgICAgICArIChjLmNoYXJDb2RlQXQoMSkgLSAweERDMDApO1xuICAgICAgICAgICAgcmV0dXJuIChmcm9tQ2hhckNvZGUoMHhmMCB8ICgoY2MgPj4+IDE4KSAmIDB4MDcpKVxuICAgICAgICAgICAgICAgICAgICArIGZyb21DaGFyQ29kZSgweDgwIHwgKChjYyA+Pj4gMTIpICYgMHgzZikpXG4gICAgICAgICAgICAgICAgICAgICsgZnJvbUNoYXJDb2RlKDB4ODAgfCAoKGNjID4+PiAgNikgJiAweDNmKSlcbiAgICAgICAgICAgICAgICAgICAgKyBmcm9tQ2hhckNvZGUoMHg4MCB8ICggY2MgICAgICAgICAmIDB4M2YpKSk7XG4gICAgICAgIH1cbiAgICB9O1xuICAgIHZhciByZV91dG9iID0gL1tcXHVEODAwLVxcdURCRkZdW1xcdURDMDAtXFx1REZGRkZdfFteXFx4MDAtXFx4N0ZdL2c7XG4gICAgdmFyIHV0b2IgPSBmdW5jdGlvbih1KSB7XG4gICAgICAgIHJldHVybiB1LnJlcGxhY2UocmVfdXRvYiwgY2JfdXRvYik7XG4gICAgfTtcbiAgICB2YXIgY2JfZW5jb2RlID0gZnVuY3Rpb24oY2NjKSB7XG4gICAgICAgIHZhciBwYWRsZW4gPSBbMCwgMiwgMV1bY2NjLmxlbmd0aCAlIDNdLFxuICAgICAgICBvcmQgPSBjY2MuY2hhckNvZGVBdCgwKSA8PCAxNlxuICAgICAgICAgICAgfCAoKGNjYy5sZW5ndGggPiAxID8gY2NjLmNoYXJDb2RlQXQoMSkgOiAwKSA8PCA4KVxuICAgICAgICAgICAgfCAoKGNjYy5sZW5ndGggPiAyID8gY2NjLmNoYXJDb2RlQXQoMikgOiAwKSksXG4gICAgICAgIGNoYXJzID0gW1xuICAgICAgICAgICAgYjY0Y2hhcnMuY2hhckF0KCBvcmQgPj4+IDE4KSxcbiAgICAgICAgICAgIGI2NGNoYXJzLmNoYXJBdCgob3JkID4+PiAxMikgJiA2MyksXG4gICAgICAgICAgICBwYWRsZW4gPj0gMiA/ICc9JyA6IGI2NGNoYXJzLmNoYXJBdCgob3JkID4+PiA2KSAmIDYzKSxcbiAgICAgICAgICAgIHBhZGxlbiA+PSAxID8gJz0nIDogYjY0Y2hhcnMuY2hhckF0KG9yZCAmIDYzKVxuICAgICAgICBdO1xuICAgICAgICByZXR1cm4gY2hhcnMuam9pbignJyk7XG4gICAgfTtcbiAgICB2YXIgYnRvYSA9IGdsb2JhbC5idG9hID8gZnVuY3Rpb24oYikge1xuICAgICAgICByZXR1cm4gZ2xvYmFsLmJ0b2EoYik7XG4gICAgfSA6IGZ1bmN0aW9uKGIpIHtcbiAgICAgICAgcmV0dXJuIGIucmVwbGFjZSgvW1xcc1xcU117MSwzfS9nLCBjYl9lbmNvZGUpO1xuICAgIH07XG4gICAgdmFyIF9lbmNvZGUgPSBidWZmZXIgP1xuICAgICAgICBidWZmZXIuZnJvbSAmJiBVaW50OEFycmF5ICYmIGJ1ZmZlci5mcm9tICE9PSBVaW50OEFycmF5LmZyb21cbiAgICAgICAgPyBmdW5jdGlvbiAodSkge1xuICAgICAgICAgICAgcmV0dXJuICh1LmNvbnN0cnVjdG9yID09PSBidWZmZXIuY29uc3RydWN0b3IgPyB1IDogYnVmZmVyLmZyb20odSkpXG4gICAgICAgICAgICAgICAgLnRvU3RyaW5nKCdiYXNlNjQnKVxuICAgICAgICB9XG4gICAgICAgIDogIGZ1bmN0aW9uICh1KSB7XG4gICAgICAgICAgICByZXR1cm4gKHUuY29uc3RydWN0b3IgPT09IGJ1ZmZlci5jb25zdHJ1Y3RvciA/IHUgOiBuZXcgIGJ1ZmZlcih1KSlcbiAgICAgICAgICAgICAgICAudG9TdHJpbmcoJ2Jhc2U2NCcpXG4gICAgICAgIH1cbiAgICAgICAgOiBmdW5jdGlvbiAodSkgeyByZXR1cm4gYnRvYSh1dG9iKHUpKSB9XG4gICAgO1xuICAgIHZhciBlbmNvZGUgPSBmdW5jdGlvbih1LCB1cmlzYWZlKSB7XG4gICAgICAgIHJldHVybiAhdXJpc2FmZVxuICAgICAgICAgICAgPyBfZW5jb2RlKFN0cmluZyh1KSlcbiAgICAgICAgICAgIDogX2VuY29kZShTdHJpbmcodSkpLnJlcGxhY2UoL1srXFwvXS9nLCBmdW5jdGlvbihtMCkge1xuICAgICAgICAgICAgICAgIHJldHVybiBtMCA9PSAnKycgPyAnLScgOiAnXyc7XG4gICAgICAgICAgICB9KS5yZXBsYWNlKC89L2csICcnKTtcbiAgICB9O1xuICAgIHZhciBlbmNvZGVVUkkgPSBmdW5jdGlvbih1KSB7IHJldHVybiBlbmNvZGUodSwgdHJ1ZSkgfTtcbiAgICAvLyBkZWNvZGVyIHN0dWZmXG4gICAgdmFyIHJlX2J0b3UgPSBuZXcgUmVnRXhwKFtcbiAgICAgICAgJ1tcXHhDMC1cXHhERl1bXFx4ODAtXFx4QkZdJyxcbiAgICAgICAgJ1tcXHhFMC1cXHhFRl1bXFx4ODAtXFx4QkZdezJ9JyxcbiAgICAgICAgJ1tcXHhGMC1cXHhGN11bXFx4ODAtXFx4QkZdezN9J1xuICAgIF0uam9pbignfCcpLCAnZycpO1xuICAgIHZhciBjYl9idG91ID0gZnVuY3Rpb24oY2NjYykge1xuICAgICAgICBzd2l0Y2goY2NjYy5sZW5ndGgpIHtcbiAgICAgICAgY2FzZSA0OlxuICAgICAgICAgICAgdmFyIGNwID0gKCgweDA3ICYgY2NjYy5jaGFyQ29kZUF0KDApKSA8PCAxOClcbiAgICAgICAgICAgICAgICB8ICAgICgoMHgzZiAmIGNjY2MuY2hhckNvZGVBdCgxKSkgPDwgMTIpXG4gICAgICAgICAgICAgICAgfCAgICAoKDB4M2YgJiBjY2NjLmNoYXJDb2RlQXQoMikpIDw8ICA2KVxuICAgICAgICAgICAgICAgIHwgICAgICgweDNmICYgY2NjYy5jaGFyQ29kZUF0KDMpKSxcbiAgICAgICAgICAgIG9mZnNldCA9IGNwIC0gMHgxMDAwMDtcbiAgICAgICAgICAgIHJldHVybiAoZnJvbUNoYXJDb2RlKChvZmZzZXQgID4+PiAxMCkgKyAweEQ4MDApXG4gICAgICAgICAgICAgICAgICAgICsgZnJvbUNoYXJDb2RlKChvZmZzZXQgJiAweDNGRikgKyAweERDMDApKTtcbiAgICAgICAgY2FzZSAzOlxuICAgICAgICAgICAgcmV0dXJuIGZyb21DaGFyQ29kZShcbiAgICAgICAgICAgICAgICAoKDB4MGYgJiBjY2NjLmNoYXJDb2RlQXQoMCkpIDw8IDEyKVxuICAgICAgICAgICAgICAgICAgICB8ICgoMHgzZiAmIGNjY2MuY2hhckNvZGVBdCgxKSkgPDwgNilcbiAgICAgICAgICAgICAgICAgICAgfCAgKDB4M2YgJiBjY2NjLmNoYXJDb2RlQXQoMikpXG4gICAgICAgICAgICApO1xuICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgICAgcmV0dXJuICBmcm9tQ2hhckNvZGUoXG4gICAgICAgICAgICAgICAgKCgweDFmICYgY2NjYy5jaGFyQ29kZUF0KDApKSA8PCA2KVxuICAgICAgICAgICAgICAgICAgICB8ICAoMHgzZiAmIGNjY2MuY2hhckNvZGVBdCgxKSlcbiAgICAgICAgICAgICk7XG4gICAgICAgIH1cbiAgICB9O1xuICAgIHZhciBidG91ID0gZnVuY3Rpb24oYikge1xuICAgICAgICByZXR1cm4gYi5yZXBsYWNlKHJlX2J0b3UsIGNiX2J0b3UpO1xuICAgIH07XG4gICAgdmFyIGNiX2RlY29kZSA9IGZ1bmN0aW9uKGNjY2MpIHtcbiAgICAgICAgdmFyIGxlbiA9IGNjY2MubGVuZ3RoLFxuICAgICAgICBwYWRsZW4gPSBsZW4gJSA0LFxuICAgICAgICBuID0gKGxlbiA+IDAgPyBiNjR0YWJbY2NjYy5jaGFyQXQoMCldIDw8IDE4IDogMClcbiAgICAgICAgICAgIHwgKGxlbiA+IDEgPyBiNjR0YWJbY2NjYy5jaGFyQXQoMSldIDw8IDEyIDogMClcbiAgICAgICAgICAgIHwgKGxlbiA+IDIgPyBiNjR0YWJbY2NjYy5jaGFyQXQoMildIDw8ICA2IDogMClcbiAgICAgICAgICAgIHwgKGxlbiA+IDMgPyBiNjR0YWJbY2NjYy5jaGFyQXQoMyldICAgICAgIDogMCksXG4gICAgICAgIGNoYXJzID0gW1xuICAgICAgICAgICAgZnJvbUNoYXJDb2RlKCBuID4+PiAxNiksXG4gICAgICAgICAgICBmcm9tQ2hhckNvZGUoKG4gPj4+ICA4KSAmIDB4ZmYpLFxuICAgICAgICAgICAgZnJvbUNoYXJDb2RlKCBuICAgICAgICAgJiAweGZmKVxuICAgICAgICBdO1xuICAgICAgICBjaGFycy5sZW5ndGggLT0gWzAsIDAsIDIsIDFdW3BhZGxlbl07XG4gICAgICAgIHJldHVybiBjaGFycy5qb2luKCcnKTtcbiAgICB9O1xuICAgIHZhciBfYXRvYiA9IGdsb2JhbC5hdG9iID8gZnVuY3Rpb24oYSkge1xuICAgICAgICByZXR1cm4gZ2xvYmFsLmF0b2IoYSk7XG4gICAgfSA6IGZ1bmN0aW9uKGEpe1xuICAgICAgICByZXR1cm4gYS5yZXBsYWNlKC9cXFN7MSw0fS9nLCBjYl9kZWNvZGUpO1xuICAgIH07XG4gICAgdmFyIGF0b2IgPSBmdW5jdGlvbihhKSB7XG4gICAgICAgIHJldHVybiBfYXRvYihTdHJpbmcoYSkucmVwbGFjZSgvW15BLVphLXowLTlcXCtcXC9dL2csICcnKSk7XG4gICAgfTtcbiAgICB2YXIgX2RlY29kZSA9IGJ1ZmZlciA/XG4gICAgICAgIGJ1ZmZlci5mcm9tICYmIFVpbnQ4QXJyYXkgJiYgYnVmZmVyLmZyb20gIT09IFVpbnQ4QXJyYXkuZnJvbVxuICAgICAgICA/IGZ1bmN0aW9uKGEpIHtcbiAgICAgICAgICAgIHJldHVybiAoYS5jb25zdHJ1Y3RvciA9PT0gYnVmZmVyLmNvbnN0cnVjdG9yXG4gICAgICAgICAgICAgICAgICAgID8gYSA6IGJ1ZmZlci5mcm9tKGEsICdiYXNlNjQnKSkudG9TdHJpbmcoKTtcbiAgICAgICAgfVxuICAgICAgICA6IGZ1bmN0aW9uKGEpIHtcbiAgICAgICAgICAgIHJldHVybiAoYS5jb25zdHJ1Y3RvciA9PT0gYnVmZmVyLmNvbnN0cnVjdG9yXG4gICAgICAgICAgICAgICAgICAgID8gYSA6IG5ldyBidWZmZXIoYSwgJ2Jhc2U2NCcpKS50b1N0cmluZygpO1xuICAgICAgICB9XG4gICAgICAgIDogZnVuY3Rpb24oYSkgeyByZXR1cm4gYnRvdShfYXRvYihhKSkgfTtcbiAgICB2YXIgZGVjb2RlID0gZnVuY3Rpb24oYSl7XG4gICAgICAgIHJldHVybiBfZGVjb2RlKFxuICAgICAgICAgICAgU3RyaW5nKGEpLnJlcGxhY2UoL1stX10vZywgZnVuY3Rpb24obTApIHsgcmV0dXJuIG0wID09ICctJyA/ICcrJyA6ICcvJyB9KVxuICAgICAgICAgICAgICAgIC5yZXBsYWNlKC9bXkEtWmEtejAtOVxcK1xcL10vZywgJycpXG4gICAgICAgICk7XG4gICAgfTtcbiAgICB2YXIgbm9Db25mbGljdCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgQmFzZTY0ID0gZ2xvYmFsLkJhc2U2NDtcbiAgICAgICAgZ2xvYmFsLkJhc2U2NCA9IF9CYXNlNjQ7XG4gICAgICAgIHJldHVybiBCYXNlNjQ7XG4gICAgfTtcbiAgICAvLyBleHBvcnQgQmFzZTY0XG4gICAgZ2xvYmFsLkJhc2U2NCA9IHtcbiAgICAgICAgVkVSU0lPTjogdmVyc2lvbixcbiAgICAgICAgYXRvYjogYXRvYixcbiAgICAgICAgYnRvYTogYnRvYSxcbiAgICAgICAgZnJvbUJhc2U2NDogZGVjb2RlLFxuICAgICAgICB0b0Jhc2U2NDogZW5jb2RlLFxuICAgICAgICB1dG9iOiB1dG9iLFxuICAgICAgICBlbmNvZGU6IGVuY29kZSxcbiAgICAgICAgZW5jb2RlVVJJOiBlbmNvZGVVUkksXG4gICAgICAgIGJ0b3U6IGJ0b3UsXG4gICAgICAgIGRlY29kZTogZGVjb2RlLFxuICAgICAgICBub0NvbmZsaWN0OiBub0NvbmZsaWN0LFxuICAgICAgICBfX2J1ZmZlcl9fOiBidWZmZXJcbiAgICB9O1xuICAgIC8vIGlmIEVTNSBpcyBhdmFpbGFibGUsIG1ha2UgQmFzZTY0LmV4dGVuZFN0cmluZygpIGF2YWlsYWJsZVxuICAgIGlmICh0eXBlb2YgT2JqZWN0LmRlZmluZVByb3BlcnR5ID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgIHZhciBub0VudW0gPSBmdW5jdGlvbih2KXtcbiAgICAgICAgICAgIHJldHVybiB7dmFsdWU6dixlbnVtZXJhYmxlOmZhbHNlLHdyaXRhYmxlOnRydWUsY29uZmlndXJhYmxlOnRydWV9O1xuICAgICAgICB9O1xuICAgICAgICBnbG9iYWwuQmFzZTY0LmV4dGVuZFN0cmluZyA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIE9iamVjdC5kZWZpbmVQcm9wZXJ0eShcbiAgICAgICAgICAgICAgICBTdHJpbmcucHJvdG90eXBlLCAnZnJvbUJhc2U2NCcsIG5vRW51bShmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBkZWNvZGUodGhpcylcbiAgICAgICAgICAgICAgICB9KSk7XG4gICAgICAgICAgICBPYmplY3QuZGVmaW5lUHJvcGVydHkoXG4gICAgICAgICAgICAgICAgU3RyaW5nLnByb3RvdHlwZSwgJ3RvQmFzZTY0Jywgbm9FbnVtKGZ1bmN0aW9uICh1cmlzYWZlKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBlbmNvZGUodGhpcywgdXJpc2FmZSlcbiAgICAgICAgICAgICAgICB9KSk7XG4gICAgICAgICAgICBPYmplY3QuZGVmaW5lUHJvcGVydHkoXG4gICAgICAgICAgICAgICAgU3RyaW5nLnByb3RvdHlwZSwgJ3RvQmFzZTY0VVJJJywgbm9FbnVtKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGVuY29kZSh0aGlzLCB0cnVlKVxuICAgICAgICAgICAgICAgIH0pKTtcbiAgICAgICAgfTtcbiAgICB9XG4gICAgLy9cbiAgICAvLyBleHBvcnQgQmFzZTY0IHRvIHRoZSBuYW1lc3BhY2VcbiAgICAvL1xuICAgIGlmIChnbG9iYWxbJ01ldGVvciddKSB7IC8vIE1ldGVvci5qc1xuICAgICAgICBCYXNlNjQgPSBnbG9iYWwuQmFzZTY0O1xuICAgIH1cbiAgICAvLyBtb2R1bGUuZXhwb3J0cyBhbmQgQU1EIGFyZSBtdXR1YWxseSBleGNsdXNpdmUuXG4gICAgLy8gbW9kdWxlLmV4cG9ydHMgaGFzIHByZWNlZGVuY2UuXG4gICAgaWYgKHR5cGVvZiBtb2R1bGUgIT09ICd1bmRlZmluZWQnICYmIG1vZHVsZS5leHBvcnRzKSB7XG4gICAgICAgIG1vZHVsZS5leHBvcnRzLkJhc2U2NCA9IGdsb2JhbC5CYXNlNjQ7XG4gICAgfVxuICAgIGVsc2UgaWYgKHR5cGVvZiBkZWZpbmUgPT09ICdmdW5jdGlvbicgJiYgZGVmaW5lLmFtZCkge1xuICAgICAgICAvLyBBTUQuIFJlZ2lzdGVyIGFzIGFuIGFub255bW91cyBtb2R1bGUuXG4gICAgICAgIGRlZmluZShbXSwgZnVuY3Rpb24oKXsgcmV0dXJuIGdsb2JhbC5CYXNlNjQgfSk7XG4gICAgfVxuICAgIC8vIHRoYXQncyBpdCFcbiAgICByZXR1cm4ge0Jhc2U2NDogZ2xvYmFsLkJhc2U2NH1cbn0pKTtcbiIsInZhciBnO1xuXG4vLyBUaGlzIHdvcmtzIGluIG5vbi1zdHJpY3QgbW9kZVxuZyA9IChmdW5jdGlvbigpIHtcblx0cmV0dXJuIHRoaXM7XG59KSgpO1xuXG50cnkge1xuXHQvLyBUaGlzIHdvcmtzIGlmIGV2YWwgaXMgYWxsb3dlZCAoc2VlIENTUClcblx0ZyA9IGcgfHwgbmV3IEZ1bmN0aW9uKFwicmV0dXJuIHRoaXNcIikoKTtcbn0gY2F0Y2ggKGUpIHtcblx0Ly8gVGhpcyB3b3JrcyBpZiB0aGUgd2luZG93IHJlZmVyZW5jZSBpcyBhdmFpbGFibGVcblx0aWYgKHR5cGVvZiB3aW5kb3cgPT09IFwib2JqZWN0XCIpIGcgPSB3aW5kb3c7XG59XG5cbi8vIGcgY2FuIHN0aWxsIGJlIHVuZGVmaW5lZCwgYnV0IG5vdGhpbmcgdG8gZG8gYWJvdXQgaXQuLi5cbi8vIFdlIHJldHVybiB1bmRlZmluZWQsIGluc3RlYWQgb2Ygbm90aGluZyBoZXJlLCBzbyBpdCdzXG4vLyBlYXNpZXIgdG8gaGFuZGxlIHRoaXMgY2FzZS4gaWYoIWdsb2JhbCkgeyAuLi59XG5cbm1vZHVsZS5leHBvcnRzID0gZztcbiJdLCJzb3VyY2VSb290IjoiIn0=