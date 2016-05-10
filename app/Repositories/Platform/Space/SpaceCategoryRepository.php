<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 19/04/2016
 * Time: 9:21 AM
 */

namespace App\Repositories\Platform\Space;

use App\Repositories\BaseRepository;

class SpaceCategoryRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Entities\Platform\Space\SpaceCategory';
    }
    
    /**
     * @param string $column
     * @param string $id
     * @return mixed
     */
    public function categoriesWithSpaces($column = "nombre_cat_LI", $id = "bd_cat_espacios_ofrecidos_LIST.id_cat_LI")
    {
        return $this->model
            ->join('bd_subcat_espacios_ofrecidos_LIST', 'bd_subcat_espacios_ofrecidos_LIST.id_cat_LI', '=', 'bd_cat_espacios_ofrecidos_LIST.id_cat_LI')
            ->join('bd_espacios_ofrecidos_LIST', 'bd_espacios_ofrecidos_LIST.id_subcat_LI', '=', 'bd_subcat_espacios_ofrecidos_LIST.id_subcat_LI')
            ->groupBy('bd_cat_espacios_ofrecidos_LIST.id_cat_LI')
            ->lists($column, $id)
            ->all();
    }
    
}