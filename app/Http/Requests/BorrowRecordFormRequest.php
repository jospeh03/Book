<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Models\BorrowRecord;

class BorrowRecordFormRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // Ensure the user is authenticated
    }

    public function rules()
    {
        return [
            'book_id' => 'required|exists:books,id',
            'returned_at' => 'nullable|date|after_or_equal:borrowed_at', // Validate returned_at if provided
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => 'معرف الكتاب مطلوب.',
            'book_id.exists' => 'الكتاب المحدد غير موجود.',
            'returned_at.after_or_equal' => 'تاريخ الإرجاع يجب أن يكون بعد أو مساويًا لتاريخ الاستعارة.',
        ];
    }
    public function failedValidation(Validator $validator){
        return response()->json([
            'status'=>'failed validation',
            'errors' => $validator->errors(),
    ], 422);}
    public function passedValidation(){
        // تنفيذ عملية بعد التحقق الناجح
    $this->recordAdditionalTime();
}
protected function recordAdditionalTime()
{
//Add extra time
    BorrowRecord::where('id', $this->route('record'))->update(['extra_time' => now()]);
}
}
