<?php

namespace App\Filament\Reports;

use App\Models\User;
use Carbon\Carbon;
use EightyNine\Reports\Components\Body\TextColumn;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use EightyNine\Reports\Report;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Header\Layout\HeaderRow;
use EightyNine\Reports\Components\Header\Layout\HeaderColumn;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Body\Layout\BodyColumn;
use EightyNine\Reports\Components\Body\Table;
use EightyNine\Reports\Components\VerticalSpace;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Footer\Layout\FooterRow;
use EightyNine\Reports\Components\Footer\Layout\FooterColumn;
use EightyNine\Reports\Components\Text;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class MonthlyRegistrationsReport extends Report
{
    public ?string $heading    = 'Monthly Registrations Report';
    public ?string $subHeading = 'User registrations grouped by month';

    public function header(Header $header): Header
    {
        return $header
            ->schema([
                HeaderRow::make()->schema([
                    HeaderColumn::make()->schema([
                        Text::make($this->heading)->title()->primary(),
                        Text::make($this->subHeading)->subtitle(),
                    ]),
                    HeaderColumn::make()->schema([
                        Text::make('Generated on: ' . now()->format('Y-m-d H:i:s')),
                    ])->alignRight(),
                ]),
            ]);
    }

    public function body(Body $body): Body
    {
        return $body
            ->schema([
                BodyColumn::make()->schema([
                    Text::make('Monthly Registration Summary')->fontXl()->fontBold(),
    
                    Table::make()
                        ->columns([
                            TextColumn::make('month')->label('Month'),
                            TextColumn::make('year')->label('Year'),
                            TextColumn::make('count')->label('Number of Registrations'),
                        ])
                        ->data(fn (?array $filters) => $this->getMonthlyRegistrationData($filters)),
    
                    VerticalSpace::make(),
    
                    Text::make('Last 5 Registered Users')->fontXl()->fontBold(),
    
                    Table::make()
                        ->columns([
                            TextColumn::make('name')->label('Name'),
                            TextColumn::make('email')->label('Email'),
                            TextColumn::make('registered_at')->label('Registration Date'),
                        ])
                        ->data(fn (?array $filters) => $this->getLatestUsersData($filters)),
                ]),
            ]);
    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                FooterRow::make()->schema([
                    FooterColumn::make()->schema([
                        Text::make($this->heading),
                    ]),
                    FooterColumn::make()->schema([
                        Text::make('Page 1 of 1'),
                    ])->alignRight(),
                ]),
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('year')
                    ->label('Year')
                    ->options($this->getYearOptions())
                    ->placeholder('All Years'),

                DatePicker::make('from_date')->label('From Date'),
                DatePicker::make('to_date')->label('To Date'),
            ]);
    }

    // Now returns a Collection, not array
    private function getMonthlyRegistrationData(?array $filters = []): Collection
    {
        $query = User::query()
            ->select([
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count'),
            ])
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc');

        if (!empty($filters['year'] ?? null)) {
            $query->whereYear('created_at', $filters['year']);
        }
        if (!empty($filters['from_date'] ?? null)) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'] ?? null)) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query
            ->get()
            ->map(fn ($item) => [
                Carbon::createFromDate(null, $item->month, 1)->format('F'),
                $item->year,
                $item->count,
            ]);
    }

    // Also returns a Collection
    private function getLatestUsersData(?array $filters = []): Collection
    {
        $query = User::query();

        if (!empty($filters['year'] ?? null)) {
            $query->whereYear('created_at', $filters['year']);
        }
        if (!empty($filters['from_date'] ?? null)) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'] ?? null)) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(fn ($user) => [
                $user->name,
                $user->email,
                $user->created_at->format('Y-m-d H:i:s'),
            ]);
    }

    private function getYearOptions(): array
    {
        $years = User::query()
            ->select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return array_combine($years, $years);
    }
}
