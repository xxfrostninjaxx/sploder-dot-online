<?php

class ChallengesService
{
    public static function formatChallengeMode($mode, $challenge): string
    {
        if ($mode) {
            $minutes = floor($challenge / 60);
            $seconds = $challenge % 60;
            $result = "Win in less than";

            if ($minutes > 0 && $seconds == 0) {
                $unit = $minutes == 1 ? "minute" : "minutes";
                $result .= " {$minutes} {$unit}";
            } elseif ($minutes > 0 && $seconds > 0) {
                $minUnit = $minutes == 1 ? "min" : "mins";
                $secUnit = $seconds == 1 ? "sec" : "secs";
                $result .= " {$minutes} {$minUnit} {$seconds} {$secUnit}";
            } elseif ($minutes == 0 && $seconds > 0) {
                $unit = $seconds == 1 ? "second" : "seconds";
                $result .= " {$seconds} {$unit}";
            }

            return trim($result);
        } else {
            return "Score at least {$challenge} points";
        }
    }
}
