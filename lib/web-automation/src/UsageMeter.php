<?php
declare(strict_types=1);

namespace Monorepo\WebAutomation;

/** Port of openbrowser UsageMeter/PricingTable */
class UsageMeter
{
    private int $steps = 0;
    private float $cost = 0.0;
    public function step(float $stepCost = 0.01): void { $this->steps++; $this->cost += $stepCost; }
    public function isOverBudget(float $budget): bool { return $this->cost >= $budget; }
    public function summary(): array { return ['steps' => $this->steps, 'cost' => $this->cost]; }
}
