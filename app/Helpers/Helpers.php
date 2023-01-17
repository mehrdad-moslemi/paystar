<?php

if(! function_exists('convertPaystarStatusCode')){
    function convertPaystarStatusCode($status){
        $status_message = [
            'موفق',
            'درخواست نامعتبر (خطا در پارامترهای ورودی)',
            'درگاه فعال نیست',
            'توکن تکراری است',
            'مبلغ بیشتر از سقف مجاز درگاه است',
            'شناسه ref_num معتبر نیست',
            'تراکنش قبلا وریفای شده است',
            'پارامترهای ارسال شده نامعتبر است',
            'تراکنش را نمیتوان وریفای کرد',
            'تراکنش وریفای نشد',
            'تراکنش ناموفق',
            'خطای سامانه'
        ];
        
        $status_code = ['1' , '-1' , '-2' , '-3' , '-4' , '-5' , '-6' , '-7' , '-8' , '-9' , '-98' , '-99'];

        $message_index = array_search($status , $status_code);

        return $status_message[$message_index];
    }
}