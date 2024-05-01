<?php

namespace App\Livewire;

use App\Models\Pool\StateLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class DeviceStats extends BaseWidget
{

    public string $device;

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        // limit to 1 days
        $stateLogs = StateLog::where('device', $this->device)->orderBy('created_at', 'desc')->limit(1 * 24 * 1)->get()->toArray();

        $sensorsData = []; // [sensor => [state1, state2, state3, ...]]
        if (empty($stateLogs)) {
            return [];
        }
        foreach ($stateLogs as $stateLog) {

            foreach ($stateLog['formatted_sensors'] as $sensor => $state) {
                if (!isset($sensorsData[$sensor])) {
                    $sensorsData[$sensor] = [];
                }
                $sensorsData[$sensor][] = $state['value'];
            }
        }
        $stats = [];
        $firstState = $stateLogs[0];
        $lastState = $stateLogs[count($stateLogs) - 1];

        foreach ($stateLogs[0]['formatted_sensors'] as $sensor => $state) {
            if (!isset($sensorsData[$sensor])) {
                continue;
            }
            $diff = floatval($lastState['formatted_sensors'][$sensor]['value']) - floatval($firstState['formatted_sensors'][$sensor]['value']);
            $diffInPercent = 0;
            try {
                $diffInPercent = $diff / floatval($firstState['formatted_sensors'][$sensor]['value']) * 100;
            } catch (\DivisionByZeroError $e) {
                $diffInPercent = 0;
            }

            $diffInPercent = round($diffInPercent, 2);
            $stats[] = Stat::make($state['label'], $state['value'] . ' ' . $state['unit'])
                ->description(($diff > 0 ? 'increase' : 'decrease') . ' by ' . abs($diffInPercent) . '%')
                ->descriptionIcon('heroicon-m-arrow-trending-' . ($diff > 0 ? 'up' : 'down'))
                ->chart($sensorsData[$sensor])
                ->color('neutral');
        }
        return $stats;

    }
}
