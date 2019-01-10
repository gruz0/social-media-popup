jQuery(document).ready(function($){
	const SCP_PREFIX = 'scp-';

	// Клик по табу и открытие соответствующей вкладки
	$('#smp_welcome_screen ul.tabs').on('click', 'li:not(.current)', function() {
		$(this).addClass('current').siblings().removeClass('current')
		.parents('#smp_welcome_screen').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
	});

	// Add Color Picker
	const colorFields = [];
	colorFields.push('#scp-setting_overlay_color');
	colorFields.push('#scp-setting_vkontakte_color_background');
	colorFields.push('#scp-setting_vkontakte_color_text');
	colorFields.push('#scp-setting_vkontakte_color_button');
	colorFields.push('#scp-setting_twitter_link_color');

	$(colorFields.join(',')).wpColorPicker();

	$('#smp_upload_background_image').click(function() {
		tb_show('Upload a background image', 'media-upload.php?referer=social_media_popup&type=image&TB_iframe=true&post_id=0', false);

		window.smp_restore_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html) {
			$('.smp-background-image').html(html);
			$('#smp_background_image').val($('.smp-background-image img').attr('src'));
			tb_remove();

			window.send_to_editor = window.smp_restore_send_to_editor;
		};

		return false;
	});

	// Сортировка табов соц. сетей
	if ($('#smp-sortable').length) {
		$('#smp-sortable').sortable({
			revert: true,
			update: function(event, ui) {
				const networks = [];
				$('#smp-sortable li').each(function() {
					networks.push($(this).text());
				});
				$('#scp-setting_tabs_order').val(networks.join(','));
			}
		});
		$('ul, li').disableSelection();
	}

	// Тестирование анимации
	if ($('#smp_animation').length) {
		$.fn.extend({
			animateCss: function (animationName) {
				const animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
				this.addClass('animated ' + animationName).one(animationEnd, function() {
					$(this).removeClass('animated ' + animationName);
				});
				return this;
			}
		});

		$('#smp_play_animation').on('click', function() {
			$('#smp_animation').animateCss($('#smp_animation_style').val());
		});

		$('#smp_animation_style').on('change', function() {
			$('#smp_animation').animateCss($('#smp_animation_style').val());
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

		const $checkboxes = $('.' + SCP_PREFIX + checkboxSuffix);

		if ($checkboxes.length == 0) return;

		$checkboxes.each(function() {
			let $relatedObject = $('#' + SCP_PREFIX + relatedObjectPrefix + $(this).val());
			let checked = $(this).is(':checked');

			if (checked) {
				$relatedObject.removeAttr('disabled');
			} else {
				$relatedObject.attr('disabled', 'disabled');
			}

			switch($(this).val()) {
				case 'after_clicking_on_element': {
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
		let $result      = $('#' + SCP_PREFIX + targetObjectSuffix);
		let resultString = '';
		let className    = checkboxClassName;

		$(className).each(function() {
			if ($(this).is(':checked')) {
				resultString += $(this).val() + ',';
			}
		});

		$result.val(resultString);

		setRelatedObjectStateDependsOnCheckbox(targetObjectSuffix, relatedObjectPrefix);
	}

	$('.' + SCP_PREFIX + 'when_should_the_popup_appear').on('click', function() {
		let className = '.' + $(this).attr('class');
		prepareResultStringForEvents(className, 'when_should_the_popup_appear', 'popup_will_appear_');
	});

	$('.' + SCP_PREFIX + 'who_should_see_the_popup').on('click', function() {
		let className = '.' + $(this).attr('class');
		prepareResultStringForEvents(className, 'who_should_see_the_popup');
	});

	$('.' + SCP_PREFIX + 'setting_facebook_tabs').on('click', function() {
		let className = '.' + $(this).attr('class');
		prepareResultStringForEvents(className, 'setting_facebook_tabs');
	});

	$('.' + SCP_PREFIX + 'setting_twitter_chrome').on('click', function() {
		let className = '.' + $(this).attr('class');
		prepareResultStringForEvents(className, 'setting_twitter_chrome');
	});

	// Установим состояние текстовых полей и других объектов в зависимости от выбранных чекбоксов
	setRelatedObjectStateDependsOnCheckbox('when_should_the_popup_appear', 'popup_will_appear_');
	setRelatedObjectStateDependsOnCheckbox('who_should_see_the_popup');
});
