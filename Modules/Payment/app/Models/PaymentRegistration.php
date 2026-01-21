<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Students\Models\Student;
use Modules\Services\Models\ServiceRequest;

class PaymentRegistration extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Modules\Payment\Database\Factories\PaymentRegistrationFactory::new();
    }

    protected $fillable = [
        'student_id',
        'service_request_id',
        'amount',
        'payment_method',
        'transaction_id',
        'callback_status',
        'callback_data',
        'payment_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'callback_data' => 'array',
        'payment_date' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('callback_status', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('callback_status', 'pending');
    }
}
