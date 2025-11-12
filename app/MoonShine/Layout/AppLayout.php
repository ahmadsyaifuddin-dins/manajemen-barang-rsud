<?php

declare(strict_types=1);

namespace App\MoonShine\Layout;

use MoonShine\Components\Layout\Content;
use MoonShine\Components\Layout\Flash;
use MoonShine\Components\Layout\Footer;
use MoonShine\Components\Layout\Header;
use MoonShine\Components\Layout\LayoutBlock;
use MoonShine\Components\Layout\LayoutBuilder;
use MoonShine\Components\Layout\Menu;
use MoonShine\Components\Layout\Profile;
use MoonShine\Components\Layout\Sidebar;
use MoonShine\Contracts\MoonShineLayoutContract;

final class AppLayout implements MoonShineLayoutContract
{
    public static function build(): LayoutBuilder
    {
        return LayoutBuilder::make([
            Sidebar::make([
                Menu::make()->customAttributes(['class' => 'mt-2']),
                Profile::make(),
            ]),
            LayoutBlock::make([
                Flash::make(),
                Header::make(),
                Content::make(),

                Footer::make()->copyright(fn (): string => sprintf(
                    <<<HTML
                    <div class="text-center">
                        &copy; %d RSUD H. Badaruddin Kasim.
                        <br/>
                        <span class="text-sm opacity-50">dibuat oleh anak magang.</span>
                    </div>
                    HTML,
                    date('Y')
                ))->menu([]),

            ])->customAttributes(['class' => 'layout-page']),
        ]);
    }
}