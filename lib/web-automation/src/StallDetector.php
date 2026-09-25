<?php
declare(strict_types=1);

namespace Monorepo\WebAutomation;

/** Port of openbrowser StallDetector — hash page tree/text to detect stall */
class StallDetector
{
    private array $hashes = [];
    public function isStalled(string $html, int $threshold = 3): bool
    {
        $h = md5(strip_tags($html));
        $this->hashes[] = $h;
        $last = array_slice($this->hashes, -$threshold);
        return count($last) === $threshold && count(array_unique($last)) === 1;
    }
}
