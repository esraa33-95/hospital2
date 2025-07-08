<?php

namespace App\Traits;

trait Response
{

    public function successCode(): array
    {
        return [200, 201, 202];
    }

    public  function responseApi($message = '', $dataOrErrors = null, $code = 200, $meta = []): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $array = [
            'status' => in_array($code, $this->successCode()) ? true : false,
            'message' => ($message == null) ? '' : $message,
            in_array($code, $this->successCode()) ? 'data' : 'errors'  => $dataOrErrors,
        ];
        if (!empty($meta))
            foreach ($meta as $key => $value) {
                $array[$key] = $value;
            }

        return response($array, $code);
    }


}