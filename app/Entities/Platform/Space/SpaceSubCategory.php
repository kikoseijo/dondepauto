<?php
/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 19/04/2016
 * Time: 9:14 AM
 */

namespace App\Entities\Platform\Space;

use App\Entities\Platform\Entity;
use Illuminate\Database\Eloquent\Builder;

class SpaceSubCategory extends Entity
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_subcat_LI';

    protected $databaseTranslate = ['name' => 'nombre_subcat_LI', 'description' => 'description_subcat_LI', 'category_id' => 'id_cat_LI'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subcat_espacios_ofrecidos_LIST';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(SpaceCategory::class, 'id_cat_LI', 'id_cat_LI');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function formats()
    {
        return $this->hasMany(SpaceFormat::class, 'id_subcat_LI', 'id_subcat_LI');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spaces()
    {
        return $this->hasMany(Space::class, 'id_subcat_LI', 'id_subcat_LI');
    }

    /**
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }

    /**
     * @return string
     */
    public function getNameCategoryNameAttribute()
    {
        return $this->category_name . ' - ' . $this->name;
    }

    /**
     * @param Builder $query
     * @param null $publisher_id
     * @return mixed
     */
    public function scopeJoinSpaces(Builder $query, $publisher_id = null)
    {
        if(! is_null($publisher_id)  && ! empty($publisher_id)) {
            return $query->join('espacios_ofrecidos_LIST', function ($join) use($publisher_id) {
                $join->on('subcat_espacios_ofrecidos_LIST.id_subcat_LI', '=', 'espacios_ofrecidos_LIST.id_subcat_LI')
                    ->where('espacios_ofrecidos_LIST.id_us_reg_LI', '=', $publisher_id);
            });
        }

        return $query->join('espacios_ofrecidos_LIST', 'espacios_ofrecidos_LIST.id_subcat_LI', '=', 'subcat_espacios_ofrecidos_LIST.id_subcat_LI');
    }

    /**
     * @param Builder $query
     * @param $scene_id
     * @return mixed
     */
    public function scopeJoinScenes(Builder $query, $scene_id)
    {
        return $query->join('impact_scene_space', function ($join) use($scene_id) {
            $join->on('impact_scene_space.space_id', '=', 'espacios_ofrecidos_LIST.id_espacio_LI')
                ->where('impact_scene_space.impact_scene_id', '=', $scene_id);
        });
    }

    /**
     * @param Builder $query
     * @param $city_id
     * @return mixed
     */
    public function scopeJoinCities(Builder $query, $city_id)
    {
        return $query->join('city_space', function ($join) use($city_id) {
            $join->on('city_space.space_id', '=', 'espacios_ofrecidos_LIST.id_espacio_LI')
                ->where('city_space.city_id', '=', $city_id);
        });
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeGroupById(Builder $query)
    {
        return $query->groupBy('subcat_espacios_ofrecidos_LIST.id_subcat_LI');
    }

    /**
     * @param Builder $query
     * @param $category_id
     * @return $this
     */
    public function scopeOfCategory(Builder $query, $category_id)
    {
        return $query->where('subcat_espacios_ofrecidos_LIST.id_cat_LI', $category_id);
    }
}