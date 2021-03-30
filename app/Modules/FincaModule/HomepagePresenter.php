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
        bdump($this->savePath);
    }

    public function renderSageApi() {
        $url = 'https://api.accounting.sage.com/v3.1/sales_quick_entries';
        $accessToken = 'eyJhbGciOiJSUzUxMiIsImtpZCI6InE3bnVHWVZRdWtjT0gwQnlEQjZ5UWFhdkx6S2tET0xZQml6TzZoUzVXVUE9In0.eyJqdGkiOiIzZmRhMTExMy1jOGM3LTQ1YWQtOGUyYS01MTRkYWJiODE4OWIiLCJpYXQiOjE2MTcxMzMyMjAsImV4cCI6MTYxNzEzMzUyMCwiaXNzIjoib2F1dGguYXdzLnNiYy1hY2NvdW50aW5nLnNhZ2UuY29tIiwic3ViIjoiZGQ5YTNkNjgtMjY0Yi00NTY4LWIxMTMtMGU2YzM4MDM2OTMzIiwiYXVkIjoiYXBpLnNiYy1hY2NvdW50aW5nLnNhZ2UuY29tIiwiYXpwIjoiMTNjZTJhNTYtY2JkZi00ZTdiLWIwYmUtMmRlYzgyNGM4MzU1L2Q4M2ZhMTQ1LWNlY2MtNDRiYy1hNzM2LWNiMTUwZTU2MWVlZiIsImNvdW50cnkiOiJFUyIsInNjb3BlcyI6ImFjY291bnRpbmc6cncgY29yZTpydyIsInVzYWdlX3BsYW5fY29kZSI6ImV4dGVybmFsIn0.QldoySy_0ZpTM0iu6QBSYTs7vo5Df4XMWqOwV0bAy-06luChetVSo03l7vpTwx-5_HsqWakjU5X5MfuBS5HznGKUbYhB-KVjrCI-5FPHNWeo_Ot6bnx-DG9RbLV7OL5DZ8Wz1tWNDtHyfLUOBzfLzCntCOgGRxhjftwf49AsasTQqcLv2zInHgQH6Gjdwd4BiDf1MZfQcYy98uxlVhBZkS83IfPmbvF5jh73sOAcTzog_tWIEannKsNb18EI_m8Vw9bNsHyJpEjVUyMGXkcpsqj-xfyAMszfWpNx5LWcVkomsjIGdawa6RsWJGvKc81nH7KKdVOikY5pB_dRFnTPtBRLtJ0U6ipG4AurOFoNqavo0qn_qk1G8Cg5EEEzLfKBf_RSYqcdKcowbgbHkZIGNL5I-PXP9ULtP-jRHvnHTjZE65URwg3KmI8HCVAlGT2wOcqhy52w9_Bslwzv3uFABLb3CKFbS18tWXmG_MBIXS0DODoHOXmRiDodqC_b7GtyVsgI4Xd63XBzb7EDza_Tm6yDrKJCOn9Ly5U5q1kg4PPOCPQk1ALzDpQE2JE-D1y8khbxeG8rgYbiwRbaq8quTQV-l1yBdxLptITBSNUDmiTS8vEpjpDbQRJGAOllHw2U8ZoLtvwNrTFx6U-MEr4MEJ9O03RcnmQqJa6yANWfoNA';

        $options = array(
            'http' => array(
                //'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'header' => "Authorization: Bearer " . $accessToken,
                'method' => 'GET',
            //'content' => http_build_query($data)
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
        bdump($result);
        //var_dump($result->access_token);die;
        file_put_contents($this->savePath . 'access_token_sage.txt', $result->access_token);
        file_put_contents($this->savePath . 'refresh_token.txt', $result->refresh_token);
        $this->flashMessage('tokeny ulozeny');
        $this->redirect('default');
    }

}
