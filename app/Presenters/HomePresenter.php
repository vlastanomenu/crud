<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Presenters\entities\CraEntity;
use App\Presenters\forms\CraForm;
use App\Presenters\service\CraService;
use Contributte\Translation\Translator;
use Nette\Forms\Form;
use Nette;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    private $id = 0;

    /** @var CraEntity */
    public $craEntity = null;

    /** @var CraService @inject */
    public $craService;

    /** @var CraForm @inject */
    public $craForm;

    /** @var Translator @inject */
    public Translator $translator;

    public function actionDefault()
    {
        $this->template->dataList = $this->craService->getDataList();
    }

    public function actionDetail(?int $id = null)
    {
       $this->template->id = $this->id = $id;
       $this->craEntity = $this->craService->get($id);
    }

    public function handleDelete(?int $id = null)
    {
        if ($this->craService->delete($id))
        {
            $this->flashMessage($this->translator->translate('messages.back.cra.flash.delete'), 'success');
        }
        else
        {
            $this->flashMessage($this->translator->translate('messages.back.cra.flash.errorDel'), 'danger');
        }
        $this->redirect('Home:default');
    }

    protected function createComponentCraForm(): Form
    {
        $form = $this->craForm->create($this->id);
        if ($this->id)
        {
            $form->setDefaults(
                [
                    CraForm::NAME => $this->craEntity->getName(),
                    CraForm::DESCRIPTION => $this->craEntity->getDescription(),
                    CraForm::PUBLISH => $this->craEntity->getPublish()
                ]
            );
        }
        return $form;
    }


}
