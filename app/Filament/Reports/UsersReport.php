<?php

namespace App\Filament\Reports;

use App\Models\User;
use Illuminate\Support\Collection;
use EightyNine\Reports\Report;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Header\Layout\HeaderRow;
use EightyNine\Reports\Components\Header\Layout\HeaderColumn;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Body\Layout\BodyColumn;
use EightyNine\Reports\Components\Body\Table;
use EightyNine\Reports\Components\Body\TextColumn;
use EightyNine\Reports\Components\VerticalSpace;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Footer\Layout\FooterRow;
use EightyNine\Reports\Components\Footer\Layout\FooterColumn;
use EightyNine\Reports\Components\Text;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class UsersReport extends Report
{
    public ?string $heading    = 'User Registration Report';
    public ?string $subHeading = 'Overview of registered users in the system';

    public function header(Header $header): Header
    {
        return $header->schema([
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
        return $body->schema([
            BodyColumn::make()->schema([
                Text::make('User Registrations')->fontXl()->fontBold(),

                Table::make()
                    ->columns([
                        TextColumn::make('id')->label('ID'),
                        TextColumn::make('name')->label('Name'),
                        TextColumn::make('email')->label('Email'),
                        TextColumn::make('registered_at')->label('Registration Date'),
                    ])
                    ->data(fn (?array $filters) => $this->getUserData($filters)),

                VerticalSpace::make(),
            ]),
        ]);
    }

    public function footer(Footer $footer): Footer
    {
        return $footer->schema([
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
        return $form->schema([
            TextInput::make('search')
                ->label('Search')
                ->placeholder('Name or email'),

            DatePicker::make('from_date')->label('From Date'),
            DatePicker::make('to_date')->label('To Date'),
        ]);
    }

    private function getUserData(?array $filters = []): Collection
    {
        $query = User::query();

        if (!empty($filters['search'] ?? null)) {
            $query->where(fn($q) =>
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%")
            );
        }
        if (!empty($filters['from_date'] ?? null)) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'] ?? null)) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query->get()->map(fn($user) => [
            'id'            => $user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'registered_at' => $user->created_at->format('Y-m-d H:i:s'),
        ]);
    }
}