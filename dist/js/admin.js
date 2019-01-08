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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/admin.js":
/*!****************************!*\
  !*** ./assets/js/admin.js ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(document).ready(function ($) {
  var SCP_PREFIX = 'scp-'; // Клик по табу и открытие соответствующей вкладки

  $('#smp_welcome_screen ul.tabs').on('click', 'li:not(.current)', function () {
    $(this).addClass('current').siblings().removeClass('current').parents('#smp_welcome_screen').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
  }); // Add Color Picker

  var colorFields = [];
  colorFields.push('#scp-setting_overlay_color');
  colorFields.push('#scp-setting_vkontakte_color_background');
  colorFields.push('#scp-setting_vkontakte_color_text');
  colorFields.push('#scp-setting_vkontakte_color_button');
  colorFields.push('#scp-setting_twitter_link_color');
  $(colorFields.join(',')).wpColorPicker();
  $('#smp_upload_background_image').click(function () {
    tb_show('Upload a background image', 'media-upload.php?referer=social_media_popup&type=image&TB_iframe=true&post_id=0', false);
    window.smp_restore_send_to_editor = window.send_to_editor;

    window.send_to_editor = function (html) {
      $('.smp-background-image').html(html);
      $('#smp_background_image').val($('.smp-background-image img').attr('src'));
      tb_remove();
      window.send_to_editor = window.smp_restore_send_to_editor;
    };

    return false;
  }); // Сортировка табов соц. сетей

  if ($('#smp-sortable').length) {
    $('#smp-sortable').sortable({
      revert: true,
      update: function update(event, ui) {
        var networks = [];
        $('#smp-sortable li').each(function () {
          networks.push($(this).text());
        });
        $('#scp-setting_tabs_order').val(networks.join(','));
      }
    });
    $('ul, li').disableSelection();
  } // Тестирование анимации


  if ($('#smp_animation').length) {
    $.fn.extend({
      animateCss: function animateCss(animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass('animated ' + animationName).one(animationEnd, function () {
          $(this).removeClass('animated ' + animationName);
        });
        return this;
      }
    });
    $('#smp_play_animation').on('click', function () {
      $('#smp_animation').animateCss($('#smp_animation_style').val());
    });
    $('#smp_animation_style').on('change', function () {
      $('#smp_animation').animateCss($('#smp_animation_style').val());
    });
  }
  /**
   * Блокируем или разблокируем поля для ввода значений в зависимости от состояния чекбоксов
   * "При наступлении каких событий показывать окно плагина".
   */


  function setRelatedObjectStateDependsOnCheckbox(checkboxSuffix, relatedObjectPrefix) {
    if (typeof relatedObjectPrefix == 'undefined') {
      relatedObjectPrefix = '';
    }

    var $checkboxes = $('.' + SCP_PREFIX + checkboxSuffix);
    if ($checkboxes.length == 0) return;
    $checkboxes.each(function () {
      var $relatedObject = $('#' + SCP_PREFIX + relatedObjectPrefix + $(this).val());
      var checked = $(this).is(':checked');

      if (checked) {
        $relatedObject.removeAttr('disabled');
      } else {
        $relatedObject.attr('disabled', 'disabled');
      }

      switch ($(this).val()) {
        case 'after_clicking_on_element':
          {
            $('#' + SCP_PREFIX + 'event_hide_element_after_click_on_it').prop('disabled', !checked);
            $('#' + SCP_PREFIX + 'do_not_use_cookies_after_click_on_element').prop('disabled', !checked);
            break;
          }
      }
    });
  }
  /**
   * Формирует строку со значениями выбранных опций событий отображения окна.
   * Конечное значение формируется из value-атрибута каждого выбранного чекбокса.
   *
   * @param {string} checkboxClassName
   * @param {string} targetObjectSuffix
   * @param {string} relatedObjectPrefix
   */


  function prepareResultStringForEvents(checkboxClassName, targetObjectSuffix, relatedObjectPrefix) {
    var $result = $('#' + SCP_PREFIX + targetObjectSuffix);
    var resultString = '';
    var className = checkboxClassName;
    $(className).each(function () {
      if ($(this).is(':checked')) {
        resultString += $(this).val() + ',';
      }
    });
    $result.val(resultString);
    setRelatedObjectStateDependsOnCheckbox(targetObjectSuffix, relatedObjectPrefix);
  }

  $('.' + SCP_PREFIX + 'when_should_the_popup_appear').on('click', function () {
    var className = '.' + $(this).attr('class');
    prepareResultStringForEvents(className, 'when_should_the_popup_appear', 'popup_will_appear_');
  });
  $('.' + SCP_PREFIX + 'who_should_see_the_popup').on('click', function () {
    var className = '.' + $(this).attr('class');
    prepareResultStringForEvents(className, 'who_should_see_the_popup');
  });
  $('.' + SCP_PREFIX + 'setting_facebook_tabs').on('click', function () {
    var className = '.' + $(this).attr('class');
    prepareResultStringForEvents(className, 'setting_facebook_tabs');
  });
  $('.' + SCP_PREFIX + 'setting_twitter_chrome').on('click', function () {
    var className = '.' + $(this).attr('class');
    prepareResultStringForEvents(className, 'setting_twitter_chrome');
  }); // Установим состояние текстовых полей и других объектов в зависимости от выбранных чекбоксов

  setRelatedObjectStateDependsOnCheckbox('when_should_the_popup_appear', 'popup_will_appear_');
  setRelatedObjectStateDependsOnCheckbox('who_should_see_the_popup');
});

/***/ }),

/***/ 1:
/*!**********************************!*\
  !*** multi ./assets/js/admin.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/gruz0/Projects/my-projects/social-media-popup/assets/js/admin.js */"./assets/js/admin.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2FkbWluLmpzIl0sIm5hbWVzIjpbImpRdWVyeSIsImRvY3VtZW50IiwicmVhZHkiLCIkIiwiU0NQX1BSRUZJWCIsIm9uIiwiYWRkQ2xhc3MiLCJzaWJsaW5ncyIsInJlbW92ZUNsYXNzIiwicGFyZW50cyIsImZpbmQiLCJlcSIsImluZGV4IiwiZmFkZUluIiwiaGlkZSIsImNvbG9yRmllbGRzIiwicHVzaCIsImpvaW4iLCJ3cENvbG9yUGlja2VyIiwiY2xpY2siLCJ0Yl9zaG93Iiwid2luZG93Iiwic21wX3Jlc3RvcmVfc2VuZF90b19lZGl0b3IiLCJzZW5kX3RvX2VkaXRvciIsImh0bWwiLCJ2YWwiLCJhdHRyIiwidGJfcmVtb3ZlIiwibGVuZ3RoIiwic29ydGFibGUiLCJyZXZlcnQiLCJ1cGRhdGUiLCJldmVudCIsInVpIiwibmV0d29ya3MiLCJlYWNoIiwidGV4dCIsImRpc2FibGVTZWxlY3Rpb24iLCJmbiIsImV4dGVuZCIsImFuaW1hdGVDc3MiLCJhbmltYXRpb25OYW1lIiwiYW5pbWF0aW9uRW5kIiwib25lIiwic2V0UmVsYXRlZE9iamVjdFN0YXRlRGVwZW5kc09uQ2hlY2tib3giLCJjaGVja2JveFN1ZmZpeCIsInJlbGF0ZWRPYmplY3RQcmVmaXgiLCIkY2hlY2tib3hlcyIsIiRyZWxhdGVkT2JqZWN0IiwiY2hlY2tlZCIsImlzIiwicmVtb3ZlQXR0ciIsInByb3AiLCJwcmVwYXJlUmVzdWx0U3RyaW5nRm9yRXZlbnRzIiwiY2hlY2tib3hDbGFzc05hbWUiLCJ0YXJnZXRPYmplY3RTdWZmaXgiLCIkcmVzdWx0IiwicmVzdWx0U3RyaW5nIiwiY2xhc3NOYW1lIl0sIm1hcHBpbmdzIjoiO0FBQUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFD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SyxTQUFELEVBQVksMEJBQVosQ0FBNUI7QUFDQSxHQUhEO0FBS0F2RCxHQUFDLENBQUMsTUFBTUMsVUFBTixHQUFtQix1QkFBcEIsQ0FBRCxDQUE4Q0MsRUFBOUMsQ0FBaUQsT0FBakQsRUFBMEQsWUFBVztBQUNwRSxRQUFJcUQsU0FBUyxHQUFHLE1BQU12RCxDQUFDLENBQUMsSUFBRCxDQUFELENBQVF1QixJQUFSLENBQWEsT0FBYixDQUF0QjtBQUNBMkIsZ0NBQTRCLENBQUNLLFNBQUQsRUFBWSx1QkFBWixDQUE1QjtBQUNBLEdBSEQ7QUFLQXZELEdBQUMsQ0FBQyxNQUFNQyxVQUFOLEdBQW1CLHdCQUFwQixDQUFELENBQStDQyxFQUEvQyxDQUFrRCxPQUFsRCxFQUEyRCxZQUFXO0FBQ3JFLFFBQUlxRCxTQUFTLEdBQUcsTUFBTXZELENBQUMsQ0FBQyxJQUFELENBQUQsQ0FBUXVCLElBQVIsQ0FBYSxPQUFiLENBQXRCO0FBQ0EyQixnQ0FBNEIsQ0FBQ0ssU0FBRCxFQUFZLHdCQUFaLENBQTVCO0FBQ0EsR0FIRCxFQTlJaUMsQ0FtSmpDOztBQUNBZCx3Q0FBc0MsQ0FBQyw4QkFBRCxFQUFpQyxvQkFBakMsQ0FBdEM7QUFDQUEsd0NBQXNDLENBQUMsMEJBQUQsQ0FBdEM7QUFDQSxDQXRKRCxFIiwiZmlsZSI6ImFkbWluLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDEpO1xuIiwialF1ZXJ5KGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigkKXtcblx0Y29uc3QgU0NQX1BSRUZJWCA9ICdzY3AtJztcblxuXHQvLyDQmtC70LjQuiDQv9C+INGC0LDQsdGDINC4INC+0YLQutGA0YvRgtC40LUg0YHQvtC+0YLQstC10YLRgdGC0LLRg9GO0YnQtdC5INCy0LrQu9Cw0LTQutC4XG5cdCQoJyNzbXBfd2VsY29tZV9zY3JlZW4gdWwudGFicycpLm9uKCdjbGljaycsICdsaTpub3QoLmN1cnJlbnQpJywgZnVuY3Rpb24oKSB7XG5cdFx0JCh0aGlzKS5hZGRDbGFzcygnY3VycmVudCcpLnNpYmxpbmdzKCkucmVtb3ZlQ2xhc3MoJ2N1cnJlbnQnKVxuXHRcdC5wYXJlbnRzKCcjc21wX3dlbGNvbWVfc2NyZWVuJykuZmluZCgnZGl2LmJveCcpLmVxKCQodGhpcykuaW5kZXgoKSkuZmFkZUluKDE1MCkuc2libGluZ3MoJ2Rpdi5ib3gnKS5oaWRlKCk7XG5cdH0pO1xuXG5cdC8vIEFkZCBDb2xvciBQaWNrZXJcblx0Y29uc3QgY29sb3JGaWVsZHMgPSBbXTtcblx0Y29sb3JGaWVsZHMucHVzaCgnI3NjcC1zZXR0aW5nX292ZXJsYXlfY29sb3InKTtcblx0Y29sb3JGaWVsZHMucHVzaCgnI3NjcC1zZXR0aW5nX3Zrb250YWt0ZV9jb2xvcl9iYWNrZ3JvdW5kJyk7XG5cdGNvbG9yRmllbGRzLnB1c2goJyNzY3Atc2V0dGluZ192a29udGFrdGVfY29sb3JfdGV4dCcpO1xuXHRjb2xvckZpZWxkcy5wdXNoKCcjc2NwLXNldHRpbmdfdmtvbnRha3RlX2NvbG9yX2J1dHRvbicpO1xuXHRjb2xvckZpZWxkcy5wdXNoKCcjc2NwLXNldHRpbmdfdHdpdHRlcl9saW5rX2NvbG9yJyk7XG5cblx0JChjb2xvckZpZWxkcy5qb2luKCcsJykpLndwQ29sb3JQaWNrZXIoKTtcblxuXHQkKCcjc21wX3VwbG9hZF9iYWNrZ3JvdW5kX2ltYWdlJykuY2xpY2soZnVuY3Rpb24oKSB7XG5cdFx0dGJfc2hvdygnVXBsb2FkIGEgYmFja2dyb3VuZCBpbWFnZScsICdtZWRpYS11cGxvYWQucGhwP3JlZmVyZXI9c29jaWFsX21lZGlhX3BvcHVwJnR5cGU9aW1hZ2UmVEJfaWZyYW1lPXRydWUmcG9zdF9pZD0wJywgZmFsc2UpO1xuXG5cdFx0d2luZG93LnNtcF9yZXN0b3JlX3NlbmRfdG9fZWRpdG9yID0gd2luZG93LnNlbmRfdG9fZWRpdG9yO1xuXHRcdHdpbmRvdy5zZW5kX3RvX2VkaXRvciA9IGZ1bmN0aW9uKGh0bWwpIHtcblx0XHRcdCQoJy5zbXAtYmFja2dyb3VuZC1pbWFnZScpLmh0bWwoaHRtbCk7XG5cdFx0XHQkKCcjc21wX2JhY2tncm91bmRfaW1hZ2UnKS52YWwoJCgnLnNtcC1iYWNrZ3JvdW5kLWltYWdlIGltZycpLmF0dHIoJ3NyYycpKTtcblx0XHRcdHRiX3JlbW92ZSgpO1xuXG5cdFx0XHR3aW5kb3cuc2VuZF90b19lZGl0b3IgPSB3aW5kb3cuc21wX3Jlc3RvcmVfc2VuZF90b19lZGl0b3I7XG5cdFx0fTtcblxuXHRcdHJldHVybiBmYWxzZTtcblx0fSk7XG5cblx0Ly8g0KHQvtGA0YLQuNGA0L7QstC60LAg0YLQsNCx0L7QsiDRgdC+0YYuINGB0LXRgtC10Llcblx0aWYgKCQoJyNzbXAtc29ydGFibGUnKS5sZW5ndGgpIHtcblx0XHQkKCcjc21wLXNvcnRhYmxlJykuc29ydGFibGUoe1xuXHRcdFx0cmV2ZXJ0OiB0cnVlLFxuXHRcdFx0dXBkYXRlOiBmdW5jdGlvbihldmVudCwgdWkpIHtcblx0XHRcdFx0Y29uc3QgbmV0d29ya3MgPSBbXTtcblx0XHRcdFx0JCgnI3NtcC1zb3J0YWJsZSBsaScpLmVhY2goZnVuY3Rpb24oKSB7XG5cdFx0XHRcdFx0bmV0d29ya3MucHVzaCgkKHRoaXMpLnRleHQoKSk7XG5cdFx0XHRcdH0pO1xuXHRcdFx0XHQkKCcjc2NwLXNldHRpbmdfdGFic19vcmRlcicpLnZhbChuZXR3b3Jrcy5qb2luKCcsJykpO1xuXHRcdFx0fVxuXHRcdH0pO1xuXHRcdCQoJ3VsLCBsaScpLmRpc2FibGVTZWxlY3Rpb24oKTtcblx0fVxuXG5cdC8vINCi0LXRgdGC0LjRgNC+0LLQsNC90LjQtSDQsNC90LjQvNCw0YbQuNC4XG5cdGlmICgkKCcjc21wX2FuaW1hdGlvbicpLmxlbmd0aCkge1xuXHRcdCQuZm4uZXh0ZW5kKHtcblx0XHRcdGFuaW1hdGVDc3M6IGZ1bmN0aW9uIChhbmltYXRpb25OYW1lKSB7XG5cdFx0XHRcdGNvbnN0IGFuaW1hdGlvbkVuZCA9ICd3ZWJraXRBbmltYXRpb25FbmQgbW96QW5pbWF0aW9uRW5kIE1TQW5pbWF0aW9uRW5kIG9hbmltYXRpb25lbmQgYW5pbWF0aW9uZW5kJztcblx0XHRcdFx0dGhpcy5hZGRDbGFzcygnYW5pbWF0ZWQgJyArIGFuaW1hdGlvbk5hbWUpLm9uZShhbmltYXRpb25FbmQsIGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRcdCQodGhpcykucmVtb3ZlQ2xhc3MoJ2FuaW1hdGVkICcgKyBhbmltYXRpb25OYW1lKTtcblx0XHRcdFx0fSk7XG5cdFx0XHRcdHJldHVybiB0aGlzO1xuXHRcdFx0fVxuXHRcdH0pO1xuXG5cdFx0JCgnI3NtcF9wbGF5X2FuaW1hdGlvbicpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuXHRcdFx0JCgnI3NtcF9hbmltYXRpb24nKS5hbmltYXRlQ3NzKCQoJyNzbXBfYW5pbWF0aW9uX3N0eWxlJykudmFsKCkpO1xuXHRcdH0pO1xuXG5cdFx0JCgnI3NtcF9hbmltYXRpb25fc3R5bGUnKS5vbignY2hhbmdlJywgZnVuY3Rpb24oKSB7XG5cdFx0XHQkKCcjc21wX2FuaW1hdGlvbicpLmFuaW1hdGVDc3MoJCgnI3NtcF9hbmltYXRpb25fc3R5bGUnKS52YWwoKSk7XG5cdFx0fSk7XG5cdH1cblxuXHQvKipcblx0ICog0JHQu9C+0LrQuNGA0YPQtdC8INC40LvQuCDRgNCw0LfQsdC70L7QutC40YDRg9C10Lwg0L/QvtC70Y8g0LTQu9GPINCy0LLQvtC00LAg0LfQvdCw0YfQtdC90LjQuSDQsiDQt9Cw0LLQuNGB0LjQvNC+0YHRgtC4INC+0YIg0YHQvtGB0YLQvtGP0L3QuNGPINGH0LXQutCx0L7QutGB0L7QslxuXHQgKiBcItCf0YDQuCDQvdCw0YHRgtGD0L/Qu9C10L3QuNC4INC60LDQutC40YUg0YHQvtCx0YvRgtC40Lkg0L/QvtC60LDQt9GL0LLQsNGC0Ywg0L7QutC90L4g0L/Qu9Cw0LPQuNC90LBcIi5cblx0ICovXG5cdGZ1bmN0aW9uIHNldFJlbGF0ZWRPYmplY3RTdGF0ZURlcGVuZHNPbkNoZWNrYm94KGNoZWNrYm94U3VmZml4LCByZWxhdGVkT2JqZWN0UHJlZml4KSB7XG5cdFx0aWYgKHR5cGVvZihyZWxhdGVkT2JqZWN0UHJlZml4KSA9PSAndW5kZWZpbmVkJykge1xuXHRcdFx0cmVsYXRlZE9iamVjdFByZWZpeCA9ICcnO1xuXHRcdH1cblxuXHRcdGNvbnN0ICRjaGVja2JveGVzID0gJCgnLicgKyBTQ1BfUFJFRklYICsgY2hlY2tib3hTdWZmaXgpO1xuXG5cdFx0aWYgKCRjaGVja2JveGVzLmxlbmd0aCA9PSAwKSByZXR1cm47XG5cblx0XHQkY2hlY2tib3hlcy5lYWNoKGZ1bmN0aW9uKCkge1xuXHRcdFx0bGV0ICRyZWxhdGVkT2JqZWN0ID0gJCgnIycgKyBTQ1BfUFJFRklYICsgcmVsYXRlZE9iamVjdFByZWZpeCArICQodGhpcykudmFsKCkpO1xuXHRcdFx0bGV0IGNoZWNrZWQgPSAkKHRoaXMpLmlzKCc6Y2hlY2tlZCcpO1xuXG5cdFx0XHRpZiAoY2hlY2tlZCkge1xuXHRcdFx0XHQkcmVsYXRlZE9iamVjdC5yZW1vdmVBdHRyKCdkaXNhYmxlZCcpO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0JHJlbGF0ZWRPYmplY3QuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcblx0XHRcdH1cblxuXHRcdFx0c3dpdGNoKCQodGhpcykudmFsKCkpIHtcblx0XHRcdFx0Y2FzZSAnYWZ0ZXJfY2xpY2tpbmdfb25fZWxlbWVudCc6IHtcblx0XHRcdFx0XHQkKCcjJyArIFNDUF9QUkVGSVggKyAnZXZlbnRfaGlkZV9lbGVtZW50X2FmdGVyX2NsaWNrX29uX2l0JykucHJvcCgnZGlzYWJsZWQnLCAhY2hlY2tlZCk7XG5cdFx0XHRcdFx0JCgnIycgKyBTQ1BfUFJFRklYICsgJ2RvX25vdF91c2VfY29va2llc19hZnRlcl9jbGlja19vbl9lbGVtZW50JykucHJvcCgnZGlzYWJsZWQnLCAhY2hlY2tlZCk7XG5cdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9KTtcblx0fVxuXG5cdC8qKlxuXHQgKiDQpNC+0YDQvNC40YDRg9C10YIg0YHRgtGA0L7QutGDINGB0L4g0LfQvdCw0YfQtdC90LjRj9C80Lgg0LLRi9Cx0YDQsNC90L3Ri9GFINC+0L/RhtC40Lkg0YHQvtCx0YvRgtC40Lkg0L7RgtC+0LHRgNCw0LbQtdC90LjRjyDQvtC60L3QsC5cblx0ICog0JrQvtC90LXRh9C90L7QtSDQt9C90LDRh9C10L3QuNC1INGE0L7RgNC80LjRgNGD0LXRgtGB0Y8g0LjQtyB2YWx1ZS3QsNGC0YDQuNCx0YPRgtCwINC60LDQttC00L7Qs9C+INCy0YvQsdGA0LDQvdC90L7Qs9C+INGH0LXQutCx0L7QutGB0LAuXG5cdCAqXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBjaGVja2JveENsYXNzTmFtZVxuXHQgKiBAcGFyYW0ge3N0cmluZ30gdGFyZ2V0T2JqZWN0U3VmZml4XG5cdCAqIEBwYXJhbSB7c3RyaW5nfSByZWxhdGVkT2JqZWN0UHJlZml4XG5cdCAqL1xuXHRmdW5jdGlvbiBwcmVwYXJlUmVzdWx0U3RyaW5nRm9yRXZlbnRzKGNoZWNrYm94Q2xhc3NOYW1lLCB0YXJnZXRPYmplY3RTdWZmaXgsIHJlbGF0ZWRPYmplY3RQcmVmaXgpIHtcblx0XHRsZXQgJHJlc3VsdCAgICAgID0gJCgnIycgKyBTQ1BfUFJFRklYICsgdGFyZ2V0T2JqZWN0U3VmZml4KTtcblx0XHRsZXQgcmVzdWx0U3RyaW5nID0gJyc7XG5cdFx0bGV0IGNsYXNzTmFtZSAgICA9IGNoZWNrYm94Q2xhc3NOYW1lO1xuXG5cdFx0JChjbGFzc05hbWUpLmVhY2goZnVuY3Rpb24oKSB7XG5cdFx0XHRpZiAoJCh0aGlzKS5pcygnOmNoZWNrZWQnKSkge1xuXHRcdFx0XHRyZXN1bHRTdHJpbmcgKz0gJCh0aGlzKS52YWwoKSArICcsJztcblx0XHRcdH1cblx0XHR9KTtcblxuXHRcdCRyZXN1bHQudmFsKHJlc3VsdFN0cmluZyk7XG5cblx0XHRzZXRSZWxhdGVkT2JqZWN0U3RhdGVEZXBlbmRzT25DaGVja2JveCh0YXJnZXRPYmplY3RTdWZmaXgsIHJlbGF0ZWRPYmplY3RQcmVmaXgpO1xuXHR9XG5cblx0JCgnLicgKyBTQ1BfUFJFRklYICsgJ3doZW5fc2hvdWxkX3RoZV9wb3B1cF9hcHBlYXInKS5vbignY2xpY2snLCBmdW5jdGlvbigpIHtcblx0XHRsZXQgY2xhc3NOYW1lID0gJy4nICsgJCh0aGlzKS5hdHRyKCdjbGFzcycpO1xuXHRcdHByZXBhcmVSZXN1bHRTdHJpbmdGb3JFdmVudHMoY2xhc3NOYW1lLCAnd2hlbl9zaG91bGRfdGhlX3BvcHVwX2FwcGVhcicsICdwb3B1cF93aWxsX2FwcGVhcl8nKTtcblx0fSk7XG5cblx0JCgnLicgKyBTQ1BfUFJFRklYICsgJ3dob19zaG91bGRfc2VlX3RoZV9wb3B1cCcpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuXHRcdGxldCBjbGFzc05hbWUgPSAnLicgKyAkKHRoaXMpLmF0dHIoJ2NsYXNzJyk7XG5cdFx0cHJlcGFyZVJlc3VsdFN0cmluZ0ZvckV2ZW50cyhjbGFzc05hbWUsICd3aG9fc2hvdWxkX3NlZV90aGVfcG9wdXAnKTtcblx0fSk7XG5cblx0JCgnLicgKyBTQ1BfUFJFRklYICsgJ3NldHRpbmdfZmFjZWJvb2tfdGFicycpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuXHRcdGxldCBjbGFzc05hbWUgPSAnLicgKyAkKHRoaXMpLmF0dHIoJ2NsYXNzJyk7XG5cdFx0cHJlcGFyZVJlc3VsdFN0cmluZ0ZvckV2ZW50cyhjbGFzc05hbWUsICdzZXR0aW5nX2ZhY2Vib29rX3RhYnMnKTtcblx0fSk7XG5cblx0JCgnLicgKyBTQ1BfUFJFRklYICsgJ3NldHRpbmdfdHdpdHRlcl9jaHJvbWUnKS5vbignY2xpY2snLCBmdW5jdGlvbigpIHtcblx0XHRsZXQgY2xhc3NOYW1lID0gJy4nICsgJCh0aGlzKS5hdHRyKCdjbGFzcycpO1xuXHRcdHByZXBhcmVSZXN1bHRTdHJpbmdGb3JFdmVudHMoY2xhc3NOYW1lLCAnc2V0dGluZ190d2l0dGVyX2Nocm9tZScpO1xuXHR9KTtcblxuXHQvLyDQo9GB0YLQsNC90L7QstC40Lwg0YHQvtGB0YLQvtGP0L3QuNC1INGC0LXQutGB0YLQvtCy0YvRhSDQv9C+0LvQtdC5INC4INC00YDRg9Cz0LjRhSDQvtCx0YrQtdC60YLQvtCyINCyINC30LDQstC40YHQuNC80L7RgdGC0Lgg0L7RgiDQstGL0LHRgNCw0L3QvdGL0YUg0YfQtdC60LHQvtC60YHQvtCyXG5cdHNldFJlbGF0ZWRPYmplY3RTdGF0ZURlcGVuZHNPbkNoZWNrYm94KCd3aGVuX3Nob3VsZF90aGVfcG9wdXBfYXBwZWFyJywgJ3BvcHVwX3dpbGxfYXBwZWFyXycpO1xuXHRzZXRSZWxhdGVkT2JqZWN0U3RhdGVEZXBlbmRzT25DaGVja2JveCgnd2hvX3Nob3VsZF9zZWVfdGhlX3BvcHVwJyk7XG59KTtcbiJdLCJzb3VyY2VSb290IjoiIn0=