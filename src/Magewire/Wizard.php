<?php declare(strict_types=1);
/**
 * Copyright © Willem Poortman 2021-present. All rights reserved.
 *
 * Please read the README and LICENSE files for more
 * details on copyrights and license information.
 */

namespace Magewirephp\MagewireExamples\Magewire;

use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Model\Element\FlashMessage;

/**
 * @method int getStep()
 * @method bool hasStep()
 */
class Wizard extends Component
{
    public $step;
    public array $success = [];

    public function mount($properties)
    {
        if (isset($properties['step'])) {
            $this->step = $properties['step'];
            $this->unlock($properties['step']);
        }
    }

    /**
     * @param int|null $step
     * @return FlashMessage|void
     */
    public function navigate(int $step = null)
    {
        $step = $step ?? $this->step + 1;

        if ($step > $this->step && $this->canMove($step) === false) {
            return $this->dispatchNoticeMessage(__('Can not navigate into step %1', $step));
        }

        $this->step = $step;
        $this->success[$this->step] = true;

        if ($this->step === $this->getTotalSteps()) {
            $this->dispatchSuccessMessage('You\'ve finished the wizard');
        }
    }

    public function restart()
    {
        $this->reset();

        $this->step = 1;
        $this->unlock(1);
    }

    public function canMove(int $to): bool
    {
        $children = $this->getParent()->getChildNames();
        return isset($this->success[$to - 1]) && in_array($this->getStepBlockNameInLayout($to), $children);
    }

    public function getStepBlockNameInLayout(int $step = null): string
    {
        return sprintf('hc.example.wizard.step-%s', $step ?? $this->getStep());
    }

    public function getTotalSteps(): int
    {
        return count($this->getParent()->getChildNames());
    }

    public function unlock($step)
    {
        $this->success[$step] = true;
    }
}
