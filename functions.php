<?php

/*
 * Copyright (C) 2017 Joe Nilson <joenilson at gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once 'plugins/haiti/vendor/php-i18n/i18n.class.php';
function i18n($lang=false){
    $lang = substr(\filter_input(INPUT_SERVER,'HTTP_ACCEPT_LANGUAGE'), 0, 2);
    $language = ($lang and file_exists('plugins/haiti/lang/lang_'.$lang.'.ini'))?$lang:'es';
    $i18n = new i18n('plugins/haiti/lang/lang_'.$language.'.ini', 'plugins/haiti/langcache/');
    $i18n->setForcedLang($language);
    $i18n->init();
}

if(!function_exists('fs_tipos_id_fiscal'))
{
   /**
    * Devuelve la lista de identificadores fiscales.
    * @return type
    */
   function fs_tipos_id_fiscal()
   {
      return array('Carte d identité','NIF');
   }
}
