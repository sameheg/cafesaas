<?php

namespace App\Support;

use App\Models\SearchIndex;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Searchable
{
    protected static function bootSearchable(): void
    {
        static::saved(function (Model $model) {
            $model->indexSearch();
        });

        static::deleted(function (Model $model) {
            $model->removeFromSearch();
        });
    }

    public function indexSearch(): void
    {
        SearchIndex::updateOrCreate(
            [
                'module' => $this->searchModule(),
                'entity_id' => $this->getKey(),
            ],
            [
                'content' => implode(' ', $this->toSearchableArray()),
            ]
        );
    }

    public function removeFromSearch(): void
    {
        SearchIndex::where('module', $this->searchModule())
            ->where('entity_id', $this->getKey())
            ->delete();
    }

    protected function toSearchableArray(): array
    {
        return [];
    }

    protected function searchModule(): string
    {
        return Str::snake(class_basename($this));
    }
}
