<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

// Heading
$_['heading_title']          = 'Яндекс.Маркет';

// Text
$_['text_extension']   	     = 'Расширения';
$_['text_feed']              = 'Каналы продвижения';
$_['text_success']           = 'Настройки успешно изменены!';
$_['text_edit']        	     = 'Редактирование';
$_['text_width']             = 'Ширина';
$_['text_height']            = 'Высота';
$_['text_not_unload']        = 'Не выгружать';
$_['text_product_id']        = 'ID товара - product_id';
$_['text_name']        	     = 'Название товара - name';
$_['text_meta_h1']        	 = 'HTML-тег H1 - meta_h1';
$_['text_meta_title']        = 'HTML-тег Title - meta_title';
$_['text_meta_keyword']      = 'Мета-тег Keywords - meta_keyword';
$_['text_meta_description']  = 'Мета-тег "Описание" - meta_description';
$_['text_model']        	 = 'Код товара - model';
$_['text_sku']        	     = 'Артикул (SKU, код производителя) - sku';
$_['text_upc']        	     = 'UPC - upc';
$_['text_ean']        	     = 'EAN - ean';
$_['text_jan']        	     = 'JAN - jan';
$_['text_isbn']        	     = 'ISBN - isbn';
$_['text_mpn']        	     = 'MPN - mpn';
$_['text_location']        	 = 'Расположение';
$_['text_simplified']        = 'Упрощённый тип описания';
$_['text_vendor_model']      = 'Произвольный тип описания  - vendor.model';
$_['text_medicine']          = 'Лекарства - medicine';
$_['text_books']             = 'Книги - books';
$_['text_audiobooks']        = 'Аудиокниги - audiobooks';
$_['text_artist_title']      = 'Музыкальная и видеопродукция - artist.title';
$_['text_event_ticket']      = 'Билеты на мероприятия - event-ticket';
$_['text_tour']              = 'Туры - tour';

// Entry
$_['entry_status']           = 'Статус';
$_['entry_secret_key']       = 'Секретный ключ';
$_['entry_data_feed']        = 'Адрес';
$_['entry_shopname']         = 'Название магазина';
$_['entry_company']          = 'Компания';
$_['entry_id']               = 'Идентификатор предложений';
$_['entry_type']             = 'Тип предложений';
$_['entry_name']             = 'Брать тэг name из поля';
$_['entry_model']        	 = 'Брать тэг model из поля';
$_['entry_vendorcode']       = 'Брать тэг vendorCode из поля';
$_['entry_image']            = 'Товар с изображениями';
$_['entry_image_resize']     = 'Разрешение изображений';
$_['entry_image_quantity']   = 'Кол-во картинок товара';
$_['entry_main_category']    = 'Товар с главной категорией';
$_['entry_category']         = 'Категории';
$_['entry_manufacturer']     = 'Производители';
$_['entry_currency']         = 'Валюта предложений';
$_['entry_in_stock']         = 'Статус &quot;В наличии&quot;';
$_['entry_out_of_stock']     = 'Статус &quot;Нет в наличии&quot;';
$_['entry_quantity_status']  = 'Выгружать при кол-ве 0';

//Help
$_['help_secret_key']        = 'Ключ доступа к YML файлу, для защиты от DDoS атак и скачивания базы товара.';
$_['help_shopname']          = 'Короткое название магазина (название, которое выводится в списке найденных на Яндекс.Маркете товаров. Не должно содержать более 20 символов).';
$_['help_company']           = 'Полное наименование компании, владеющей магазином. Не публикуется, используется для внутренней идентификации.';
$_['help_id']                = 'Идентификатор может содержать только цифры и латинские буквы. Максимальная длина id — 20 символов. По-умолчанию ID товара.';
$_['help_type']              = 'Тип структуры YML файла под определённую тематику товара.';
$_['help_name']        	     = 'По-умолчанию название товара.';
$_['help_model']        	 = 'По-умолчанию код товара.';
$_['help_vendorcode']        = 'По-умолчанию артикул (SKU, код производителя).';
$_['help_image']             = 'Если да, то товары не имеющие изображений выгружаться не будут.';
$_['help_image_resize']      = 'Яндекс требует разрешение изображений не менее 250х250px. Рекомендуется 600х600px. Максимальный размер 3500х3500px.';
$_['help_image_quantity']    = 'Сколько фотографий товара экспортировать. Яндекс принимает не более 10.';
$_['help_main_category']     = 'Если да, то товары не имеющие главной категории выгружаться не будут.';
$_['help_category']          = 'Отметьте категории из которых надо экспортировать предложения для Яндекс.Маркета. Если не отмечено, то выгружается весь товар.';
$_['help_manufacturer']      = 'Отметьте производители из которых надо экспортировать предложения для Яндекс.Маркета. Если не отмечено, то выгружается весь товар.';
$_['help_currency']          = 'Яндекс.Маркет принимает предложения в валюте RUR, RUB или UAH. Выберите валюту в которой будут передаваться предложения.';
$_['help_in_stock']          = 'При наличии товара на складе.';
$_['help_out_of_stock']      = 'При остатке на складе 0.';
$_['help_quantity_status']   = 'Если да, то товары с нулевым количеством будут выгружаться в YML файле.';
$_['help_yandex_market']     = 'Тема на <a target="_blank" href="//forum.opencart.pro/topic/1059-yandexmarket-20/">Форуме</a>';

// Error
$_['error_image_width']      = 'Укажите ширину!';
$_['error_image_height']     = 'Укажите высоту!';
$_['error_image_width_min']  = 'Ширина изображения не должна быть меньше 250 точек!';
$_['error_image_height_min'] = 'Высота изображения не должна быть меньше 250 точек!';
$_['error_image_width_max']  = 'Ширина изображения не должна быть больше 3500 точек!';
$_['error_image_height_max'] = 'Высота изображения не должна быть больше 3500 точек!';
$_['error_permission']       = 'У вас недостаточно прав для внесения изменений!';