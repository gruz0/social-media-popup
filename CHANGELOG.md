# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [0.7.2] – 2016.01.24
### Added
– Added event "Show the popup while trying to get away from the page"
– Added event "Show the popup when scrolling the window is larger than X percent"
– Added event "Show the popup when you click on the specified CSS-selector"
– Added option "Use small header" to Facebook widget
– Added option "Adapt container width" to Facebook Widget
– Added option "Show events in a widget from the following tabs" to Facebook Widget

### Changed
– Changed the "Debug Mode". The widget is shown only to the site administrator
– Updated version of the Facebook API to 2.5
– Fields grouped by type of events: when to show, who to show

### Removed
– Removed option "Show publications from the wall of page" in Facebook Widget

### Fixed
– Fixed clearing cookies after closing windows in Opera and Mozilla Firefox

## [0.7.0] – 2015.10.20
### Added
– All options in frontend escaped

### Fixed
– 

Исправлена ошибка JS при использовании пустых значений некоторых полей виджета ВКонтакте.
Настройки появления виджета вынесены во вкладку "События".
Удалена функция rand() при создании уникального некеширующего параметра в scp.php.

## [0.6.9] – 2015.09.19

Добавлена возможность загрузки фонового изображения для виджета.
Добавлена возможность задать цвет и прозрачность фоновой заливки полупрозрачного контейнера.
Добавлена возможность выбрать местоположение кнопки закрытия окна в заголовке виджета.
Добавлена возможность задать задержку перед показом кнопки закрытия виджета в подвале виджета.
Добавлена возможность отцентрировать заголовки табов социальных сетей.
Удалён тег <b> в описании описания виджета из-за невалидного HTML.
Удалены отступы у изображений в описании виджета. Были проблемы с некоторыми темами.
Исправлены стили CSS для кнопок закрытия окна (в заголовке и подвале виджета).
Восстановлена работоспособность кнопки "Добавить медиафайл" в записях и страницах.

## [0.6.8] – 2015.08.02
### Added Pinterest Profile widget

## [0.6.7] – 2015.08.01

Добавлена возможность задания заголовка окна с виджетами.
Добавлена возможность сокрытия табов с заголовками виджетов, если активна только одна соц. сеть.
Поле описания виджетов изменено на текстовый редактор WordPress. Разрешены HTML-теги в нём.
Добавлена кнопка "Спасибо, я уже с вами" с тремя вариантами оформления.
Исправлена некорректная работа виджета Google+ в Mozilla Firefox.

## [0.6.6] – 2015.06.20

Исправлена некорректная работа виджета Facebook Page Plugin.
Добавлена опция закрытия окна плагина нажатием на кнопку ESC.
Кнопка "Закрыть" заменена на HTML-сущность и перемещена в один ряд с закладками социальных сетей.
Обновлён внешний вид главного окна плагина, удалены нежелательные отступы.

## [0.6.5] – 2015.05.11
Добавлена поддержка виджетом Google+ персональных профилей. Раньше работали только страницы.

## [0.6.4] – 2015.05.06

Исправлена проблема с отображением виджета сообществ ВКонтакте в Mozilla Firefox. Во всех остальных браузерах проблем не
было.
Проблема была связана с особенностями скрипта виджета ВКонтакте.
Он попросту не хотел отрисовываться в табе, если таб был не первым по счёту и размещался в скрытом контейнере.
Обновлены настройки виджета Facebook в связи с изменением API до версии 2.3
Виджет Like Box помечен как deprecated, вместо него теперь используется Page Plugin

## [0.6.2] – 2015.02.09

Добавлена опция отображения плагина на мобильных устройствах (экспериментальная!)
Добавлена задержка 500 мс. для виджета ВКонтакте, некорректно работал в Mozilla Firefox
Добавлена опция закрытия окна при клике на любую область экрана по просьбе клиента
Исправлена работа плагина с кеширующими плагинами (благодарю за багрепорт)
Исправлено отображение полос прокрутки в виджете Twitter Timeline (благодарю за багрепорт)
Теперь код плагина не интегрируется в HTML, а загружается через Javascript

## [0.6.1] – 2015.02.05

Исправлена работа виджета Одноклассников, некорректно отображался
Добавлена опция скругления углов главного окна плагина по просьбе клиента

## [0.6] – 2015.01.19
Добавлен виджет Twitter Timeline
Добавлены расширенные настройки виджета Google+
Добавлен "Режим отладки", позволяющий администратору удобно отладить нужные ему настройки
Исправлены мелкие ошибки в работе плагина
Отказался от группы во ВКонтакте, поддержка только по электронной почте

## [0.5] – 2014.09.15
### Added
– Google+ Community widget

### Fixed

Исправлены ошибки, не позволяющие корректно обновиться до последней версии
При первой установке плагина добавил первичные настройки, чтобы легче было по примеру настраивать

## [0.3] – 2014.03.23
### Added
Добавлена настройка порядка отображения виджетов (сортировка закладок)

### Fixed
– Few bugs fixed. Code cleanup.

## [0.2] – 2014.02.06
### Added
– Odnoklassniki (ok.ru) widget

Для каждой социальной сети настройки вынесены в отдельные страницы
Добавлена мультиязычность плагина, все настройки переведены на русский язык
Добавлена возможность полного удаления настроек плагина при его деинсталляции

## [0.1] – 2014.02.01
Первая версия плагина
Добавлены виджеты Facebook и ВКонтакте