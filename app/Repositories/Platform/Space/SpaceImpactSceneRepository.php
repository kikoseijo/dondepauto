<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 19/04/2016
 * Time: 9:21 AM
 */

namespace App\Repositories\Platform\Space;

use App\Repositories\BaseRepository;

class SpaceImpactSceneRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Entities\Platform\Space\SpaceImpactScene';
    }
    
    /**
     * @param string $column
     * @param string $id
     * @return mixed
     */
    public function scenesWithSpaces($column = "nombre_tipo_lugar_LI", $id = "tipos_lugares_ubicacion_espacios_LIST.id_tipo_lugar_LI")
    {
        return $this->model
            ->join('espacios_ofrecidos_LIST', 'tipos_lugares_ubicacion_espacios_LIST.id_tipo_lugar_LI', '=', 'espacios_ofrecidos_LIST.id_tipo_lugar_ubicacion_LI')
            ->groupBy('tipos_lugares_ubicacion_espacios_LIST.id_tipo_lugar_LI')
            ->orderBy($column, 'asc')
            ->lists($column, $id)
            ->all();
    }
    
}