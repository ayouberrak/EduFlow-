<?php

namespace App\Repository\Implement;

use App\Repository\interfaces\PaymentRepositoryInterface;
use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface {
    public function all() {
        return Payment::all();
    }

    public function create(array $data) {
        return Payment::create($data);
    }

    public function find($id) {
        return Payment::find($id);
    }

    public function update($id, array $data) {
        $payment = Payment::find($id);
        $payment->update($data);
        return $payment;
    }

    public function delete($id) {
        $payment = Payment::find($id);
        $payment->delete();
        return $payment;
    }

    public function getTotalRevenueByTeacher($teacherId) {
        return Payment::whereHas('course', function($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->sum('amount');
    }
}
