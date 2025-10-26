<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ruangan;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

class RuanganResource extends ModelResource
{
    protected string $model = Ruangan::class;

    protected string $title = 'Ruangan';

    public string $titleField = 'nama_ruangan';

    protected int $priority = 1;

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),

                Text::make('Nama Ruangan', 'nama_ruangan')
                    ->required()
                    ->sortable(),

                Text::make('Kepala Ruangan', 'kepala_ruangan')
                    ->required()
                    ->sortable(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'nama_ruangan' => ['required', 'string', 'max:25'],
            'kepala_ruangan' => ['required', 'string', 'max:50']
        ];
    }

    public function search(): array
    {
        return ['no_ruangan', 'nama_ruangan', 'kepala_ruangan'];
    }

    // filters() and actions() can be added here if needed
}