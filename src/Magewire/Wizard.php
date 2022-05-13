<?php

namespace Magewirephp\MagewireExamples\Magewire;

use Magewirephp\Magewire\Component;

class Wizard extends Component
{
    public string $currentStep = 'one';
    
    public string $first = '';
    public string $second = '';

    public function previous()
    {
        if ($this->currentStep === 'three') {
            $this->currentStep = 'two';
            return;
        }
        
        if ($this->currentStep === 'two') {
            $this->currentStep = 'one';
        }
    }

    public function next()
    {
        if ($this->currentStep === 'one') {
            $this->currentStep = 'two';
            return;
        }
        
        if ($this->currentStep === 'two') {
            $this->currentStep = 'three';
            $this->dispatchSuccessMessage('Last Step!');
        }
    }
}
