<?php

namespace Modules\System\Services;

use Modules\System\Models\LookupItem;
use Illuminate\Support\Collection;

class LookupService
{
    public function getByType(string $type): Collection
    {
        return LookupItem::getByType($type);
    }

    public function getOptions(string $type): array
    {
        return LookupItem::getOptions($type);
    }
}
