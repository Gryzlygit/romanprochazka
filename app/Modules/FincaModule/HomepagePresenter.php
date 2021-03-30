<?php

namespace App\FincaModule\Presenters;

use Nette;

class HomepagePresenter extends Nette\Application\UI\Presenter {

    public $savePath;

    protected function startup(): void {
        parent::startup();
        $this->savePath = dirname($_SERVER['SCRIPT_FILENAME']) . '/../files/tokens/';
    }

    public function renderSage() {
        $this->template->apiUrl = 'https://www.sageone.com/oauth2/auth/central?filter=apiv3.1&response_type=code&client_id=13ce2a56-cbdf-4e7b-b0be-2dec824c8355/d83fa145-cecc-44bc-a736-cb150e561eef&redirect_uri=http://localhost/romanprochazka/www/finca.homepage/callback&scope=full_access';
    }

    public function actionSageApi() {
        $url = 'https://api.accounting.sage.com/v3.1/sales_quick_entries';
        $accessToken = file_get_contents($this->savePath . 'access_token_sage.txt');

        $options = array(
            'http' => array(
                'header' => "Authorization: Bearer " . $accessToken,
                'method' => 'GET',
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

    public function actionSageApiCreate() {
        $url = 'https://api.accounting.sage.com/v3.1/sales_quick_entries';
        $accessToken = file_get_contents($this->savePath . 'access_token_sage.txt');
        $data = [
            'quick_entry_type_id' => 'Cargo',
            'date' => '25/02/2021 21:17:46',
            'contact_id' => 'f_epos',
            'reference' => 'Epos Now',
            'ledger_account_id' => '',
        ];

        $options = array(
            'http' => array(
                'header' => [
                    "Authorization: Bearer " . $accessToken,
                    "Content-type: application/x-www-form-urlencoded",
                ],
                'method' => 'POST',
                'content' => http_build_query($data)
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

    public function actionCallback() {
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
        file_put_contents($this->savePath . 'refresh_token.txt', $result->refresh_token);
        $this->flashMessage('tokeny ulozeny');
        $this->redirect('sage');
    }

}
