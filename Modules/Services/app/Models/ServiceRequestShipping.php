<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequestShipping extends Model
{
    protected $table = 'service_request_shipping';

    protected $fillable = [
        'service_request_id',
        'recipient_name',
        'address',
        'city',
        'country',
        'phone',
        'shipping_fee',
        'status',
    ];

    protected $casts = [
        'shipping_fee' => 'decimal:2',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }
}
