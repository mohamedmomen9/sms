<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Campus\Models\Campus;
use Modules\Campus\Models\Building;
use Modules\Campus\Models\Room;
use Modules\Campus\Models\Facility;

class DemoStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Campuses
        $campuses = [
            Campus::firstOrCreate(['code' => 'CAI'], [
                'name' => ['en' => 'Cairo Campus', 'ar' => 'فرع القاهرة'],
                'location' => 'Cairo, Egypt',
                'status' => 'active',
            ]),
            Campus::firstOrCreate(['code' => 'ALX'], [
                'name' => ['en' => 'Alexandria Campus', 'ar' => 'فرع الإسكندرية'],
                'location' => 'Alexandria, Egypt',
                'status' => 'active',
            ]),
            Campus::firstOrCreate(['code' => 'ASW'], [
                'name' => ['en' => 'Aswan Campus', 'ar' => 'فرع أسوان'],
                'location' => 'Aswan, Egypt',
                'status' => 'active',
            ]),
        ];

        // Facilities
        $facilities = [
            Facility::firstOrCreate(['name' => 'Projector']),
            Facility::firstOrCreate(['name' => 'Whiteboard']),
            Facility::firstOrCreate(['name' => 'Air Conditioning']),
            Facility::firstOrCreate(['name' => 'Computer Lab Equipment']),
        ];

        // Buildings per campus
        foreach ($campuses as $campus) {
            for ($b = 1; $b <= 2; $b++) {
                $building = Building::firstOrCreate(
                    ['code' => $campus->code . "-BLD{$b}"],
                    ['name' => "{$campus->code} Building {$b}", 'campus_id' => $campus->id]
                );

                // 8 rooms per building
                for ($r = 1; $r <= 8; $r++) {
                    $roomTypes = ['classroom', 'lab', 'auditorium', 'classroom'];
                    $room = Room::firstOrCreate(
                        ['room_code' => "{$building->code}-{$b}0{$r}"],
                        [
                            'building_id' => $building->id,
                            'number' => "{$b}0{$r}",
                            'name' => "Room {$b}0{$r}",
                            'floor_number' => $b,
                            'type' => $roomTypes[($r - 1) % count($roomTypes)],
                            'capacity' => 30 + (($r * 5) % 31),
                            'status' => 'active',
                        ]
                    );
                    $room->facilities()->syncWithoutDetaching(
                        collect($facilities)->take(2 + ($r % 3))->pluck('id')->toArray()
                    );
                }
            }
        }
    }
}
