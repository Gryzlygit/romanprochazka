<?php

namespace App\FincaModule\Presenters;

use Nette;

class SagePresenter extends HomepagePresenter
{

    public function renderDefault()
    {
        $this->template->codeUrl = 'https://www.sageone.com/oauth2/auth/central?filter=apiv3.1&response_type=code&client_id=13ce2a56-cbdf-4e7b-b0be-2dec824c8355/d83fa145-cecc-44bc-a736-cb150e561eef&redirect_uri=http://localhost/romanprochazka/www/finca.homepage/callback&scope=full_access';
    }

    public function actionSageApi()
    {
        $url = 'https://api.accounting.sage.com/v3.1/sales_quick_entries';
        $accessToken = file_get_contents($this->savePath . 'access_token_sage.txt');

        $options = array(
            'http' => array(
                'header' => "Authorization: Bearer " . $accessToken,
                'method' => 'GET',
            ),
        );
        $context = stream_context_create($options);
        $resultJson = file_get_contents($url, false, $context);
        if ($resultJson === FALSE) {
            
        }

        bdump($resultJson);
        $result = json_decode($resultJson);
        bdump($result);
        dump($result);
        die;
    }

    public function actionApiRefresh()
    {
        $url = 'https://oauth.accounting.sage.com/token';
        /* 'client_id=13ce2a56-cbdf-4e7b-b0be-2dec824c8355/d83fa145-cecc-44bc-a736-cb150e561eef
          &client_secret=O]JcL2pi.>to+B95[:Kj
          &grant_type=refresh_token
          &refresh_token=eyJxxxxxxxxxxYLk'; */
        $refreshToken = file_get_contents($this->savePath . 'refresh_token_sage.txt');
        $data = [
            'client_id' => '13ce2a56-cbdf-4e7b-b0be-2dec824c8355/d83fa145-cecc-44bc-a736-cb150e561eef',
            'client_secret' => 'O]JcL2pi.>to+B95[:Kj',
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded",
                'method' => 'POST',
                'content' => http_build_query($data),
            )
        );
        $context = stream_context_create($options);
        $resultJson = file_get_contents($url, false, $context);
        if ($resultJson === FALSE) {
            
        }

        /* bdump($resultJson);
          $result = json_decode($resultJson);
          bdump($result);
          dump($result);
          die; */

        $result = json_decode($resultJson);
        file_put_contents($this->savePath . 'access_token_sage.txt', $result->access_token);
        file_put_contents($this->savePath . 'access_token_sage_info.txt', 'date: ' . date("Y.m.d H:i:s") . ' expires_in: ' . $result->expires_in . ' seconds');
        file_put_contents($this->savePath . 'refresh_token_sage.txt', $result->refresh_token);
        file_put_contents($this->savePath . 'refresh_token_sage_info.txt', 'date: ' . date("Y.m.d H:i:s") . ' expires_in: ' . $result->refresh_token_expires_in . ' seconds');
        $this->flashMessage('tokens refreshed and saved');
        $this->redirect('sage');
    }

    public function actionSageApiCreate()
    {
        $url = 'https://api.accounting.sage.com/v3.1/sales_quick_entries';
        $accessToken = file_get_contents($this->savePath . 'access_token_sage.txt');
        $data = [
            'quick_entry_type_id' => 'Cargo',
            'date' => '31/03/2021',
            'contact_id' => 'f_epos',
            'reference' => 'Epos Now',
            'ledger_account_id' => '',
        ];

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                "Authorization: Bearer " . $accessToken,
                'method' => 'POST',
                'content' => http_build_query($data),
            //'sales_quick_entry' => http_build_query($data),
            )
        );
        $context = stream_context_create($options);
        $resultJson = file_get_contents($url, false, $context);
        if ($resultJson === FALSE) {
            
        }

        bdump($resultJson);
        $result = json_decode($resultJson);
        bdump($result);
        dump($result);
        die;
    }

    public function actionCallback()
    {
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
        file_put_contents($this->savePath . 'access_token_sage.txt', $result->access_token);
        file_put_contents($this->savePath . 'access_token_sage_info.txt', 'date: ' . date("Y.m.d H:i:s") . ' expires_in: ' . $result->expires_in . ' seconds');
        file_put_contents($this->savePath . 'refresh_token_sage.txt', $result->refresh_token);
        file_put_contents($this->savePath . 'refresh_token_sage_info.txt', 'date: ' . date("Y.m.d H:i:s") . ' expires_in: ' . $result->refresh_token_expires_in . ' seconds');
        $this->flashMessage('tokens saved');
        $this->redirect('sage');
    }
}
