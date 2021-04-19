<?php

namespace App\FincaModule\Presenters;

use Nette;

class HomepagePresenter extends Nette\Application\UI\Presenter
{

    public $savePath;

    protected function startup(): void
    {
        parent::startup();
        $this->savePath = dirname($_SERVER['SCRIPT_FILENAME']) . '/../files/tokens/';
    }
}
