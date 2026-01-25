<?php

namespace Modules\System\Tests\Unit;

use Modules\System\Models\LookupItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LookupItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_by_type_ordered()
    {
        LookupItem::create(['type' => 'status', 'code' => 'b', 'name' => 'B', 'sort_order' => 2, 'is_active' => true]);
        LookupItem::create(['type' => 'status', 'code' => 'a', 'name' => 'A', 'sort_order' => 1, 'is_active' => true]);
        LookupItem::create(['type' => 'other', 'code' => 'c', 'name' => 'C', 'sort_order' => 1, 'is_active' => true]);

        $items = LookupItem::getByType('status');

        $this->assertCount(2, $items);
        $this->assertEquals('A', $items->first()->name);
        $this->assertEquals('B', $items->last()->name);
    }
}
