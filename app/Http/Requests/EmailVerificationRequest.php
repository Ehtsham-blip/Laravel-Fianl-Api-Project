<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Models\VerifyToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class EmailVerificationRequest extends FormRequest
{  
    
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        
        return $this->expireToken();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
    public function expireToken()
    {
       
       $token=VerifyToken::where('token',$this->route('hash'))->first();
       
       if((!is_null($token)) &&Carbon::parse($token->created_at)->addMinutes(50)->gte(Carbon::now())){
           return true;
       }
       
       return false;
       
    }

    
   
}
