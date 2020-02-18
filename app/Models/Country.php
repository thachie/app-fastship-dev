<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $table = 'country'; // กำหนดชื่อของตารางที่ต้องการเรียกใช้

    protected $country = [
        'CNTRY_NAME', 'IS_ACTIVE',
    ];
}
