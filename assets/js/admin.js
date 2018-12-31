$j = jQuery.noConflict();

$j(document).ready(function() {
	var SCP_PREFIX = 'scp-';

	// Клик по табу и открытие соответствующей вкладки
	$j('#smp_welcome_screen ul.tabs').on('click', 'li:not(.current)', function() {
		$j(this).addClass('current').siblings().removeClass('current')
		.parents('#smp_welcome_screen').find('div.box').eq($j(this).index()).fadeIn(150).siblings('div.box').hide();
	});

	// Add Color Picker
	colorFields = [];
	colorFields.push('#scp-setting_overlay_color');
	colorFields.push('#scp-setting_vkontakte_color_background');
	colorFields.push('#scp-setting_vkontakte_color_text');
	colorFields.push('#scp-setting_vkontakte_color_button');
	colorFields.push('#scp-setting_twitter_link_color');

	$j(colorFields.join(',')).wpColorPicker();

	$j('#smp_upload_background_image').click(function() {
		tb_show('Upload a background image', 'media-upload.php?referer=social_media_popup&type=image&TB_iframe=true&post_id=0', false);

		window.smp_restore_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html) {
			$j('.smp-background-image').html(html);
			var image_src = $j('.smp-background-image img').attr('src');
			$j('#smp_background_image').val(image_src);
			tb_remove();

			window.send_to_editor = window.smp_restore_send_to_editor;
		};

		return false;
	});

	// Сортировка табов соц. сетей
	if ($j('#smp-sortable').length) {
		$j('#smp-sortable').sortable({
			revert: true,
			update: function(event, ui) {
				var networks = [];
				$j('#smp-sortable li').each(function() {
					networks.push($j(this).text());
				});
				$j('#scp-setting_tabs_order').val(networks.join(','));
			}
		});
		$j('ul, li').disableSelection();
	}

	// Тестирование анимации
	if ($j('#smp_animation').length) {
		$j.fn.extend({
			animateCss: function (animationName) {
				var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
				this.addClass('animated ' + animationName).one(animationEnd, function() {
					$j(this).removeClass('animated ' + animationName);
				});
				return this;
			}
		});

		$j('#smp_play_animation').on('click', function() {
			var animation = $j('#smp_animation_style').val();
			$j('#smp_animation').animateCss(animation);
		});

		$j('#smp_animation_style').on('change', function() {
			var animation = $j('#smp_animation_style').val();
			$j('#smp_animation').animateCss(animation);
		});
	}

	/**
	 * Блокируем или разблокируем поля для ввода значений в зависимости от состояния чекбоксов
	 * "При наступлении каких событий показывать окно плагина".
	 */
	function setRelatedObjectStateDependsOnCheckbox(checkboxSuffix, relatedObjectPrefix) {
		if (typeof(relatedObjectPrefix) == 'undefined') {
			relatedObjectPrefix = '';
		}

		var $checkboxes = $j('.' + SCP_PREFIX + checkboxSuffix);

		if ($checkboxes.length == 0) return;

		$checkboxes.each(function() {
			var $relatedObject = $j('#' + SCP_PREFIX + relatedObjectPrefix + $j(this).val());
			checked = $j(this).is(':checked');

			if (checked) {
				$relatedObject.removeAttr('disabled');
			} else {
				$relatedObject.attr('disabled', 'disabled');
			}

			switch($j(this).val()) {
				case 'after_clicking_on_element': {
					$j('#' + SCP_PREFIX + 'event_hide_element_after_click_on_it').prop('disabled', !checked);
					$j('#' + SCP_PREFIX + 'do_not_use_cookies_after_click_on_element').prop('disabled', !checked);
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
		var $result      = $j('#' + SCP_PREFIX + targetObjectSuffix);
		var resultString = '';
		var className    = checkboxClassName;

		$j(className).each(function() {
			if ($j(this).is(':checked')) {
				resultString += $j(this).val() + ',';
			}
		});

		$result.val(resultString);

		setRelatedObjectStateDependsOnCheckbox(targetObjectSuffix, relatedObjectPrefix);
	}

	$j('.' + SCP_PREFIX + 'when_should_the_popup_appear').on('click', function() {
		var className = '.' + $j(this).attr('class');
		prepareResultStringForEvents(className, 'when_should_the_popup_appear', 'popup_will_appear_');
	});

	$j('.' + SCP_PREFIX + 'who_should_see_the_popup').on('click', function() {
		var className = '.' + $j(this).attr('class');
		prepareResultStringForEvents(className, 'who_should_see_the_popup');
	});

	$j('.' + SCP_PREFIX + 'setting_facebook_tabs').on('click', function() {
		var className = '.' + $j(this).attr('class');
		prepareResultStringForEvents(className, 'setting_facebook_tabs');
	});

	$j('.' + SCP_PREFIX + 'setting_twitter_chrome').on('click', function() {
		var className = '.' + $j(this).attr('class');
		prepareResultStringForEvents(className, 'setting_twitter_chrome');
	});

	// Установим состояние текстовых полей и других объектов в зависимости от выбранных чекбоксов
	setRelatedObjectStateDependsOnCheckbox('when_should_the_popup_appear', 'popup_will_appear_');
	setRelatedObjectStateDependsOnCheckbox('who_should_see_the_popup');
});
