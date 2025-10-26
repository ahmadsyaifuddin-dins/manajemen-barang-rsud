<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Ruangan;
use Illuminate\Validation\Rule;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Password;
use MoonShine\Fields\Email;

class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'User Aplikasi';

    public string $titleField = 'nama_user';

    protected int $priority = 4;

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('No User', 'no_user')->sortable(),

                Text::make('Nama User', 'nama_user')
                    ->required()
                    ->sortable(),

                // Relasi ke Ruangan (Gunakan BelongsTo dari Relationships)
                BelongsTo::make('Ruangan', 'ruangan', fn(Ruangan $model) => $model->nama_ruangan)
                    ->searchable()
                    ->required(),

                Text::make('Username (Lama)', 'id_user')
                    ->hideOnIndex()
                    ->nullable(),

                Email::make('Email', 'email')
                    ->required()
                    ->sortable(),

                Password::make('Password', 'password')
                    ->hideOnIndex()
                    ->hideOnDetail(),

                Text::make('Role', 'role_user')
                    ->nullable()
                    ->hideOnIndex(),
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'nama_user' => ['required', 'string', 'max:50'],
            'no_ruangan' => ['required', 'exists:ruangans,no_ruangan'],
            'id_user' => ['nullable', 'string', 'max:25'],

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($item->getKey(), $item->getKeyName())
       
            ],
            'password' => [$item->exists ? 'nullable' : 'required', 'string', 'min:6'],
            'role_user' => ['nullable', 'string', 'max:10'],
        ];
    }

    public function search(): array
    {
        return ['no_user', 'nama_user', 'id_user', 'email'];
    }

    // filters() and actions() can be added here if needed
}