<?php

/**
 * sysPass
 * 
 * @author nuxsmin
 * @link http://syspass.org
 * @copyright 2012 Rubén Domínguez nuxsmin@syspass.org
 *  
 * This file is part of sysPass.
 *
 * sysPass is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sysPass is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with sysPass.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined('APP_ROOT') || die(_('No es posible acceder directamente a este archivo'));

/**
 * Esta clase es la encargada de realizar las operaciones sobre las categorías de sysPass.
 */
class SP_Category {

    /**
     * @brief Obtener el id de una categoría por el nombre
     * @param string $categoryName con el nombre de la categoría
     * @return bool|int si la consulta es errónea devuelve bool. Si no hay registros o se obtiene el id, devuelve int 
     */ 
    public static function getCategoryIdByName($categoryName) {
        $strQuery = "SELECT category_id "
                . "FROM categories "
                . "WHERE category_name = '" . DB::escape($categoryName) . "' LIMIT 1";
        $resQuery = DB::getResults($strQuery, __FUNCTION__);

        if (!$resQuery && !is_array($resQuery)) {
            return FALSE;
        }

        if (count(DB::$last_result) == 0) {
            return 0;
        } else {
            return $resQuery[0]->intCategoryId;
        }
    }

    /**
     * @brief Crear una nueva categoría en la BBDD
     * @param string $categoryName con el nombre de la categoría
     * @return bool
     */ 
    public static function categoryAdd($categoryName) {
        $strQuery = "INSERT INTO categories SET "
                . "category_name = '" . DB::escape($categoryName) . "'";

        if (DB::doQuery($strQuery, __FUNCTION__) === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * @brief Comprobar si una categoría está en uso por alguna cuenta
     * @param int $categoryId con el id de la categoría
     * @return bool
     */ 
    public static function isCategoryInUse($categoryId) {
        $strQuery = "SELECT account_categoryId "
                . "FROM accounts "
                . "WHERE account_categoryId = " . (int) $categoryId;

        if (DB::doQuery($strQuery, __FUNCTION__) === FALSE) {
            return FALSE;
        }

        return ( count(DB::$last_result) > 0 ) ? TRUE : FALSE;
    }

    /**
     * @brief Eliminar una categoría de la BBDD
     * @param int $categoryId con el id de la categoría
     * @return bool
     */
    public static function categoryDel($categoryId) {
        $strQuery = "DELETE FROM categories "
                . "WHERE category_id = $categoryId LIMIT 1";

        if (DB::doQuery($strQuery, __FUNCTION__) === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * @brief Actualizar una categoría en la BBDD con el id
     * @param int $categoryId con el id de la categoría
     * @param int $categoryNameNew con el nombre nuevo de la categoría
     * @return bool
     */
    public static function editCategoryById($categoryId, $categoryNameNew) {
        $strQuery = "UPDATE categories SET "
                . "category_name = '" . DB::escape($categoryNameNew) . "' "
                . "WHERE category_id = " . (int) $categoryId . " LIMIT 1";

        if (DB::doQuery($strQuery, __FUNCTION__) === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * @brief Obtiene el listado de categorías
     * @return array con en id de categorioa como clave y en nombre como valor
     */ 
    public static function getCategories(){
        $strQuery = "SELECT category_id, category_name FROM categories ORDER BY category_name";
        $resQuery = DB::getResults($strQuery, __FUNCTION__);

        if ( $resQuery === FALSE || ! is_array($resQuery) ){
            return FALSE;
        }
        
        $resCategories = array();
        
        foreach ( $resQuery as $category ){
            $resCategories[$category->category_id] = $category->category_name;
        }

        return $resCategories;
    }
}