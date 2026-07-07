<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;


class Category extends Model
{
    protected $fillable =[
        'name',
        'parent_id',
        'image',
        
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

   public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
                    ->with('children');
    }

     // 🔥 Recursive children (IMPORTANT)
    public function childrenRecursive()
    {
        return $this->hasMany(Category::class, 'parent_id')
                    ->with('childrenRecursive');
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getDescendantIds()
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getDescendantIds());
        }

        return $ids;
    }

    public function getAncestorIds()
    {
        $ids = [];
        $parent = $this->parent;

        while ($parent) {
            $ids[] = $parent->id;
            $parent = $parent->parent;
        }

        return $ids;
    }

    public function getRootCategory()
    {
        $category = $this;

        while ($category->parent) {
            $category = $category->parent;
        }

        return $category;
    }

    public function pruneTree($selectedId)
    {
        $clone = clone $this;

        $clone->setRelation(
            'childrenRecursive',
            $this->childrenRecursive
                ->map(function ($child) use ($selectedId) {
                    return $child->pruneTree($selectedId);
                })
                ->filter(function ($child) use ($selectedId) {
                    return $child->id == $selectedId
                        || $child->containsCategory($selectedId)
                        || $child->parent_id == $selectedId;
                })
                ->values()
        );

        return $clone;
    }

    public function containsCategory($selectedId)
    {
        if ($this->id == $selectedId) {
            return true;
        }

        foreach ($this->childrenRecursive as $child) {
            if ($child->containsCategory($selectedId)) {
                return true;
            }
        }

        return false;
    }
}
