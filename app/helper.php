<?php
if (!function_exists('getSql')) {
    function getSql ()
    {
        DB::listen(function ($sql) {
//            dump($sql);
            $singleSql = $sql->sql;
            if ($sql->bindings) {
                foreach ($sql->bindings as $replace) {
                    $value = is_numeric($replace) ? $replace : "'" . $replace . "'";
                    $singleSql = preg_replace('/\?/', $value, $singleSql, 1);
                }
                dump($singleSql);
            } else {
                dump($singleSql);
            }
        });

    }
}

/**
 * @api返回json
 */
if(!function_exists('returned')){
    function returned($success, $msg,$data=[]){
        return response()->json(['success' => $success, 'msg' => $msg,'data' => $data ]);
    }
}



?>

