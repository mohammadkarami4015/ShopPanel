<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountUsage extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $table="discount_usage";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public static function createNew($user_id,$discount_id,$discount_code)
    {
        $discount_usage =new DiscountUsage();
        $discount_usage->saveAs($user_id,$discount_id,$discount_code);
        return $discount_usage;
    }

    public function saveAs($user_id,$discount_id,$discount_code)
    {
        $this->user_id       = $user_id;
        $this->discount_id       = $discount_id;
        $this->code       = $discount_code;
        $this->save();

    }
}

