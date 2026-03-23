<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'site_carbon');
function site_carbon()
{
    Container::make('theme_options', 'Контакты')

        ->set_page_menu_position(2)
        ->set_icon('dashicons-megaphone')
        ->add_tab(__('Контакты'), array(

            Field::make('text', 'crb_tel_text', 'Номер телефона')
                ->set_width(33),
            Field::make('text', 'crb_tel_link', 'Ссылка телефона')
                ->set_width(33),

            Field::make('complex', 'crb_contacts', 'Мессенджеры')

                ->add_fields(array(
                    Field::make('image', 'crb_contact_image', 'Иконка')
                        ->set_width(33),
                    Field::make('text', 'crb_contact_name', 'Название')
                        ->set_width(33),
                    Field::make('text', 'crb_contact_link', 'Ссылка')
                        ->set_width(33),
                )),

            Field::make('text', 'crb_button_text', 'Кнопка')
                ->set_width(50),
            Field::make('text', 'crb_button_link', 'Ссылка кнопки')
                ->set_width(50)
                ->help_text('для вызова попап окна, необходимо поставить ссылку #main-form'),
        ));
}
