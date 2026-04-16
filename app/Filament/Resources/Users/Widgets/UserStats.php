<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\ListUsers;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'User Overview';

    protected ?string $description = 'Summary of users and account status from the table below.';

    protected function getTablePage(): string
    {
        return ListUsers::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $userCount = (clone $query)
            ->count();

        $verifiedUserCount = (clone $query)
            ->whereNotNull('email_verified_at')
            ->count();

        $adminUserCount = (clone $query)
            ->where('role', UserRole::Admin)
            ->count();

        return [
            Stat::make('User', $userCount),
            Stat::make('Verified user', $verifiedUserCount),
            Stat::make('Admin', $adminUserCount),
        ];
    }
}
