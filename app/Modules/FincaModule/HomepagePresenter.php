<?php

namespace App\FincaModule\Presenters;

use Nette;

class HomepagePresenter extends Nette\Application\UI\Presenter {

    protected function startup(): void {
        parent::startup();
    }

    public function rendersage() {
        
    }

    public function renderCallback() {
        bdump($this->params);

        $url = 'https://oauth.accounting.sage.com/token';
        $data = [
            'client_id' => '13ce2a56-cbdf-4e7b-b0be-2dec824c8355/d83fa145-cecc-44bc-a736-cb150e561eef',
            'client_secret' => 'O]JcL2pi.>to+B95[:Kj',
            'code' => $this->params['code'],
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'http://localhost/romanprochazka/www/finca.homepage/callback',
        ];

// use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $resultJson = file_get_contents($url, false, $context);
        if ($resultJson === FALSE) { /* Handle error */
        }

        $result = json_decode($resultJson);
        bdump($result);
        var_dump($result->access_token);
        die;
        echo $result->access_token;
    }

}
