<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{

    protected $guarded = [];

    public static function createNew($v_code, $phone_number)
    {
        $code = new Code;
      
        $code->saveAs($v_code, $phone_number);
        
        return $code;
    }

    public function saveAs($v_code, $phone_number)
    {
        $this->code = $v_code;
        $this->phone_number = $phone_number;
        $this->status = 'Active';
        $this->save();
      
    }
}
