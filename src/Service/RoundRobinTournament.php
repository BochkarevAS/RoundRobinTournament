<?php

namespace App\Service;

class RoundRobinTournament
{
    public function schedule(array $teams): array
    {
        $teamCount = count($teams);

        // Если число команд нечетное то одна из команд будет отдыхать =)
        if ($teamCount % 2 != 0) {
            $teamCount++;
        }

        $rounds = [];

        for ($round = 0; $round < ($teamCount - 1); $round++) {
            $matches = [];

            for ($match = 0; $match < ($teamCount / 2); $match++) {
                $home = ($round + $match) % ($teamCount - 1);
                $away = ($teamCount - 1 - $match + $round) % ($teamCount - 1);

                if ($match === 0) {
                    $away = $teamCount - 1;
                }

                if (isset($teams[$away]) && $teams[$home] !== $teams[$away]) {
                    $matches[] = [$teams[$home], $teams[$away]];
                }
            }

            $rounds[] = $matches;
        }

        return $rounds;
    }
}