<?PHP
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Book;

class BookFormRequest extends FormRequest
{
    //here is the authorize is set for the book like here the user had to be authenticated to be authorize to the book area
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'book_id' => 'required|exists:books,id',
            'title'=> 'required|string|min:3|max:255',
            'author'=> 'required|string|min:5|max:50',
            'describtion'=>'nullable|text|min:10|max:1000'
        ];
    }
    public function attributes()
{
    return [
        'title' => 'اسم الكتاب',
        'author' => 'اسم المؤلف',
        'describtion'=>'الوصف'
    ];
}


    public function messages()
    {
        return [
            'book_id.required' => 'The book ID is required.',
            'book_id.exists' => 'The selected book does not exist.',
            'title.required'=>'The title has to be filled it is reqired',
            'title.string'=> 'the title has to be a string value ',
            'title.min'=>'the title has to be at least 3 characters',
            'title.max'=>'the title has to be at least 255 characters',
            'author.required'=>'The author has to be filled it is reqired',
            'author.string'=> 'the author has to be a string value ',
            'author.min'=>'the author has to be at least 5 characters',
            'author.max'=>'the author has to be at least 50 charactauthor',
            'describtion.text'=>'the describtion had to be text ',
            'describtion.min'=>'the describtion had to be at least 10 characters',
            'describtion.max'=>'the describtion had to be at least 1000 characters'
        ];
    }
    
    public function failedValidation(Validator $validator){
        return response()->json([
            'status'=>'failed validation',
            'errors' => $validator->errors(),
    ], 422);
    // throw new HttpResponseException($response);

}
    public function passedValidation(){
        // تنفيذ عملية بعد التحقق الناجح
    $this->recordAdditionalTime();
}

protected function recordAdditionalTime()
{
//Add extra time
    Book::where('id', $this->route('record'))->update(['extra_time' => now()]);
}

}
