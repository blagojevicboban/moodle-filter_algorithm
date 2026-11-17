<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'filter_algorithm', language 'cs' (Czech).
 *
 * @package    filter_algorithm
 * @copyright  2026 Boban Blagojević
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['algorithm:sharework'] = 'Uchovávat vlastní kopii cvičení na serveru (pohled třídy, další zařízení)';
$string['algorithm:viewclasswork'] = 'Zobrazit kopie cvičení, které studenti změnili (pohled třídy)';
$string['codeforce'] = 'Použít tento jazyk pro každý algoritmus, i pro ty, které si zvolily jiný';
$string['codelang'] = 'Jazyk kódu';
$string['codelang_help'] = 'Programovací jazyk generovaného kódu v tomto kurzu (nebo kategorii, nebo činnosti). Algoritmus, který si zvolil vlastní jazyk, si ho ponechá, pokud není zaškrtnuta další volba. Student může jazyk změnit v panelu kódu, pokud není uzamčen; volba se v tomto kurzu pamatuje.';
$string['codelang_inherit'] = 'Jak je nastaveno výše (kategorie nebo stránky)';
$string['codelock'] = 'Studenti nemohou jazyk změnit';
$string['filtername'] = 'Prohlížeč algoritmů (Spustit / Krok)';
$string['pluginname'] = 'Prohlížeč algoritmů (Spustit / Krok)';
$string['privacy:metadata'] = 'Filtr Prohlížeč algoritmů ukládá na serveru kopie cvičení změněné studenty. Prohlížeč uchovává ve svém místním úložišti několik nastavení zobrazení a u upravitelných diagramů studentovu vlastní kopii algoritmu.';
$string['privacy:metadata:work'] = 'Poslední kopie cvičení, kterou každý student změnil, aby učitel mohl vidět práci třídy během hodiny a student mohl pokračovat na jiném zařízení.';
$string['privacy:metadata:work:contextid'] = 'Stránka se cvičením.';
$string['privacy:metadata:work:program'] = 'Studentův algoritmus.';
$string['privacy:metadata:work:timemodified'] = 'Kdy byla kopie uložena.';
$string['privacy:metadata:work:userid'] = 'Student.';
$string['privacy:path'] = 'Cvičení s algoritmy';
