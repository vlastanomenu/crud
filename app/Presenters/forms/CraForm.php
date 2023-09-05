<?php

namespace App\Presenters\forms;

use App\Presenters\entities\CraEntity;
use App\Presenters\service\CraService;
use Contributte\Translation\Translator;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;

class CraForm extends Form
{
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const PUBLISH = 'publish';

    private $id;

    /** @var Translator  */
    private Translator $translator;

    /** @var CraService  */
    private CraService $craService;

    /**
     * @param CraService $craService
     * @param Translator $translator
     */
    public function __construct(
        CraService $craService,
        Translator $translator
    )
    {
        $this->craService = $craService;
        $this->translator = $translator;

        $this->onSuccess[] = [$this, 'processForm'];
        $this->onValidate[] = [$this, 'validateForm'];
    }

    public function create(?int $id)
    {
        $this->id = $id;

        $this->addText(self::NAME,  $this->translator->translate('messages.back.cra.form.name'))
            ->setRequired($this->translator->translate('messages.back.cra.form.nameRequired'));
        $this->addTextArea(self::DESCRIPTION,  $this->translator->translate('messages.back.cra.form.description'));
        $this->addCheckbox(self::PUBLISH,  $this->translator->translate('messages.back.cra.form.publish'));

        $this->addSubmit('submitNew',  $this->translator->translate('messages.back.cra.form.send'));
        return $this;
    }

    public function processForm()
    {
        if (!$this->isSuccess())
        {
            $this->getPresenter()->flashMessage($this->translator->translate('messages.back.cra.flash.error'), 'danger');
            $this->getPresenter()->redirect('Home:default');
        }

        $formData = $this->getValues();

        $craEntity = new CraEntity();
        $craEntity->setName($formData->offsetGet(self::NAME));
        $craEntity->setDescription($formData->offsetGet(self::DESCRIPTION));
        $craEntity->setPublish($formData->offsetGet(self::PUBLISH));

        if ($this->id)
        {
            $craEntity->setDateUpdate(new DateTime());
            $craEntity->setId($this->id);
            $this->craService->update($craEntity);

            $flashMessage = 'messages.back.cra.flash.update';
        }
        else {
            $craEntity->setDateInsert(new DateTime());
            $this->craService->insert($craEntity);

            $flashMessage = 'messages.back.cra.flash.insert';
        }

        $this->getPresenter()->flashMessage($this->translator->translate($flashMessage), 'success');
        $this->getPresenter()->redirect('Home:default');
    }

    public function validateForm()
    {
        if (!$this->isValid())
        {
            $this->addError($this->translator->translate('messages.back.cra.flash.error'));
            $this->getPresenter()
                ->flashMessage(
                    $this->translator->translate('messages.back.cra.flash.error'),
                    'danger'
                )
            ;
            return false;
        }

        $formData = $this->getValues();
        $this->cleanErrors();

        if (!$formData->offsetGet(self::NAME))
        {
            $this->addError($this->translator->translate('messages.back.cra.form.nameRequired'));
            $this->getPresenter()
                ->flashMessage(
                    $this->translator->translate('messages.back.cra.form.nameRequired'),
                    'danger'
                )
            ;
        }

    }
}