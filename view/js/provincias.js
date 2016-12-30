/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2015-2016  Carlos Garcia Gomez  neorazorx@gmail.com
 *Copyright (C) 2016-2017  Joe Nilson  joenilson@gmail.com
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

var provincia_list = [
    {value: 'Artibonite - Dessalines'},
    {value: 'Artibonite - Gonaïves'},
    {value: 'Artibonite - Gros Morne'},
    {value: 'Artibonite - Marmelade'},
    {value: 'Artibonite - Saint Marc'},
    {value: 'Centre - Cerca la Source'},
    {value: 'Centre - Hinche'},
    {value: 'Centre - Lascahobas'},
    {value: 'Centre - Mirebalais'},
    {value: 'Grand\'Anse - Anse à Veau'},
    {value: 'Grand\'Anse - Anse d\'Ainault'},
    {value: 'Grand\'Anse - Corail'},
    {value: 'Grand\'Anse - Jérémie'},
    {value: 'Grand\'Anse - Miragoâne'},
    {value: 'Nippes - Miragoâne'},
    {value: 'Nippes - Anse-à-Veau'},
    {value: 'Nippes - Barradères'},
    {value: 'Nord - Acul du Nord'},
    {value: 'Nord - Borgne'},
    {value: 'Nord - Cap-Haïtien'},
    {value: 'Nord - Grande-Rivière-du-Nord'},
    {value: 'Nord - Limbé'},
    {value: 'Nord - Plaisance'},
    {value: 'Nord - Saint-Raphaël'},
    {value: 'Nord-Est - Fort-Liberté'},
    {value: 'Nord-Est - Ouanaminthe'},
    {value: 'Nord-Est - Au trou'},
    {value: 'Nord-Ouest - Môle-Saint-Nicolas'},
    {value: 'Nord-Ouest - Port-de-Paix'},
    {value: 'Nord-Ouest - Saint-Louis-du-Nord'},
    {value: 'Ouest - Arcahaie'},
    {value: 'Ouest - Croix-des-Bouquets'},
    {value: 'Ouest - La Gonâve'},
    {value: 'Ouest - Léogâne'},
    {value: 'Ouest - Port-au-Prince'},
    {value: 'Sud - Aquin'},
    {value: 'Sud - Cayes'},
    {value: 'Sud - Les Chardonnières'},
    {value: 'Sud - Les Côteaux'},
    {value: 'Sud - Port-Salut'},
    {value: 'Sud-Est - Bainet'},
    {value: 'Sud-Est - Belle-Anse'},
    {value: 'Sud-Est - Jacmel'}
];

$(document).ready(function() {
   $("#ac_provincia, #ac_provincia2").autocomplete({
      lookup: provincia_list,
   });
});
