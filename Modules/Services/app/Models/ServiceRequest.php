<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;
use Modules\Payment\Models\PaymentRegistration;

class ServiceRequest extends Model
{
    protected $fillable = [
        'student_id',
        'term_id',
        'service_type_id',
        'notes',
        'payment_amount',
        'payment_status',
        'status',
        'shipping_required',
        'directed_to',
        'delivered_at',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'shipping_required' => 'boolean',
        'delivered_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function shipping(): HasOne
    {
        return $this->hasOne(ServiceRequestShipping::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PaymentRegistration::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }
}
