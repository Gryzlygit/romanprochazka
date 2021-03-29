<?php

namespace App\FincaModule\Presenters;

use Nette;

class HomepagePresenter extends Nette\Application\UI\Presenter {

    protected function startup(): void {
        parent::startup();
    }

    public function renderSage() {
        
    }

    public function renderSwager() {
        $sales = new \Swagger\Client\Api\SalesQuickEntriesApi();
        $sales->getSalesQuickEntries();
    }

    public function renderSageApi() {
        $url = 'https://api.accounting.sage.com/v3.1/sales_invoices?from_date=2021-01-29';
        $data = ['access_token' => 'eyJhbGciOiJSUzUxMiIsImtpZCI6InE3bnVHWVZRdWtjT0gwQnlEQjZ5UWFhdkx6S2tET0xZQml6TzZoUzVXVUE9In0.eyJqdGkiOiJlMDFmNWVkMC0zMDE5LTQ2OWItYWZlNC1lNWRmZDdhMjRjZmEiLCJpYXQiOjE2MTcwMjc5MTEsImV4cCI6MTYxNzAyODIxMSwiaXNzIjoib2F1dGguYXdzLnNiYy1hY2NvdW50aW5nLnNhZ2UuY29tIiwic3ViIjoiZGQ5YTNkNjgtMjY0Yi00NTY4LWIxMTMtMGU2YzM4MDM2OTMzIiwiYXVkIjoiYXBpLnNiYy1hY2NvdW50aW5nLnNhZ2UuY29tIiwiYXpwIjoiMTNjZTJhNTYtY2JkZi00ZTdiLWIwYmUtMmRlYzgyNGM4MzU1L2Q4M2ZhMTQ1LWNlY2MtNDRiYy1hNzM2LWNiMTUwZTU2MWVlZiIsImNvdW50cnkiOiJFUyIsInNjb3BlcyI6ImFjY291bnRpbmc6cncgY29yZTpydyIsInVzYWdlX3BsYW5fY29kZSI6ImV4dGVybmFsIn0.Pn84pCO8kzk5DJSN4tNfyRqJ3qK0nN_2U3h_hN50ERio3-hlnw_PnLl0lbF1kfC-grrH2gaIQ-TJjgquTMwfv-QbHjjXQkH5uyz55KNIz3xqEOUlIdHX5-a1TWIsDHUIRkps37UTLJQbHhl8SHhEwtq7yTa9INuH7jmmqRc69ZewtTFXTXZQTwtcoOAidciaw-lO_H5AaRNCJwMxrj51VbR9qYIxtH4fcXlmUzrd1H9Ftlo0kSy-bpRy-Cvk1dPb6FB2Fv-PNHPuoww7iqXfl3Pp-9qAFkOq7dxjonHzJTozUobHQFi0yWBHTt9ff5bIDVT7dQUsJsL0EqNvUoSf5rlIboO32lmAFInp2rfo5mg-ZJlivBYKdSQZddlLe852TC6Hg7OwVzeBo7EBiaWTTcji3PyunEHoMs-nXYCTadjGyeptDtedC6YPbbfFRkvM3c_LX8uGJMONl6R7hymo1JM28zTLi-uzlIFNh47RmLVPalz1bQDh4MR4j0ktrt90oAm2ubZfqq002bEcperBWhVJ4Dn-6fQ_PW2tGfSvZR4McfpuMTzECuhsLkzN1x1UGxXl4KhPc6myuNIKhn1MIeB6KHynhAeop7iSSU7vGdb0l179HgyDO51Ovv6EbloWCMP56AQINYQrOMBVdmrUPCI4a9vtjHkpwXkKZ8pqHBw'];

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $resultJson = file_get_contents($url, false, $context);
        if ($resultJson === FALSE) { /* Handle error */
        }

        $result = json_decode($resultJson);
        bdump($result);
        var_dump($result);
        die;
        echo $result->access_token;
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





        $url = 'https://api.accounting.sage.com/v3.1/sales_invoices?from_date=2021-01-29';
        $data = ['access_token' => $result->access_token];

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $resultJson = file_get_contents($url, false, $context);
        if ($resultJson === FALSE) {
        }

        $result = json_decode($resultJson);
        bdump($result);
        var_dump($result);
        die;
    }

}
