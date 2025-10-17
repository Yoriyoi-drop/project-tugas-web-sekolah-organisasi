<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Value;

class ValueSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            ['icon' => 'bi-people-fill', 'title' => 'Tawassuth', 'description' => 'Sikap moderat dan seimbang dalam beragama dan bermasyarakat', 'color' => 'primary', 'order' => 1],
            ['icon' => 'bi-balance-scale', 'title' => 'Itidal', 'description' => 'Tegak lurus dan adil dalam segala aspek kehidupan', 'color' => 'success', 'order' => 2],
            ['icon' => 'bi-heart-fill', 'title' => 'Tasamuh', 'description' => 'Toleransi dan menghargai perbedaan dalam keberagaman', 'color' => 'warning', 'order' => 3],
            ['icon' => 'bi-star-fill', 'title' => 'Tawazun', 'description' => 'Keseimbangan antara kehidupan dunia dan akhirat', 'color' => 'info', 'order' => 4]
        ];

        foreach ($values as $value) {
            Value::create($value);
        }
    }
}