<?php 
namespace App\Lib\Translate;

use Illuminate\Support\Facades\DB;

class FastshipTranslate{

    public static function translate($search){

        if(session('lang') != null){
            $lang = session('lang');
        }else{
            $lang = "th";
        }
        $translate = DB::table('webtranslate')->where('tran_code',$search)
        ->select('tran_text_'.$lang.' as text','is_html as html')->first();
        
        $translateStr = "";
        if(isset($translate)){
            if($translate->html){
                $translateStr = html_entity_decode($translate->text);
            }else{
                $translateStr = $translate->text;
            }
            
        }
        /*
        $lines_array = file(app_path('Lib/Translate/en.txt'));

        $translateStr = $search;
        foreach($lines_array as $line) {
            if(strpos($line, $search_string) !== false) {
                list(, $new_str) = explode("=", $line);
                $translateStr = $new_str;
            }
        }

        
        */
        
        return $translateStr;
    }
}
?>