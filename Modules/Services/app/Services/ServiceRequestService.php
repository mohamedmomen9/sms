<?php

namespace Modules\Services\Services;

use Illuminate\Support\Collection;
use Modules\Services\Models\ServiceRequest;
use Modules\Services\Models\ServiceType;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class ServiceRequestService
{
    /**
     * Get available service types.
     */
    public function getAvailableServices(Term $term): Collection
    {
        return ServiceType::available()
            ->get()
            ->map(fn($type) => [
                'id' => $type->id,
                'name' => $type->name,
                'code' => $type->code,
                'price' => $type->price,
                'duration_days' => $type->duration_days,
                'requires_shipping' => $type->requires_shipping,
            ]);
    }

    /**
     * Get student's service requests.
     */
    public function getStudentRequests(
        Student $student,
        Term $term,
        ?Term $previousTerm = null
    ): Collection {
        return ServiceRequest::query()
            ->where('student_id', $student->student_id)
            ->when(
                $previousTerm,
                fn($q) => $q->whereIn('term_id', [$term->id, $previousTerm->id]),
                fn($q) => $q->where('term_id', $term->id)
            )
            ->with(['serviceType', 'shipping', 'payments'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($req) => [
                'id' => $req->id,
                'service_name' => $req->serviceType->name,
                'notes' => $req->notes,
                'payment_amount' => $req->payment_amount,
                'payment_status' => $req->payment_status,
                'status' => $req->status,
                'has_shipping' => $req->shipping_required,
                'created_at' => $req->created_at->toDateTimeString(),
                'delivered_at' => $req->delivered_at?->toDateTimeString(),
            ]);
    }

    /**
     * Submit a new service request.
     */
    public function submit(
        Student $student,
        Term $term,
        int $serviceTypeId,
        ?string $notes = null,
        bool $shippingRequired = false
    ): ServiceRequest {
        $serviceType = ServiceType::findOrFail($serviceTypeId);

        return ServiceRequest::create([
            'student_id' => $student->student_id,
            'term_id' => $term->id,
            'service_type_id' => $serviceTypeId,
            'notes' => $notes,
            'payment_amount' => $serviceType->price,
            'payment_status' => 'pending',
            'status' => 'pending',
            'shipping_required' => $shippingRequired,
        ]);
    }

    /**
     * Update request status.
     */
    public function updateStatus(ServiceRequest $request, string $status): bool
    {
        $data = ['status' => $status];
        if ($status === 'delivered') {
            $data['delivered_at'] = now();
        }
        return $request->update($data);
    }
}
