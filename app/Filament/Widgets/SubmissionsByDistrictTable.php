<?php

namespace App\Filament\Widgets;

use App\Models\District;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;


class SubmissionsByDistrictTable extends TableWidget
{

    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                District::query()
                    ->withCount([
                        'households',
                        'households as submissions_count' => function (Builder $query) {
                            $query->join(
                                'survey_submissions',
                                'survey_submissions.household_id',
                                '=',
                                'households.id'
                            );
                        },
                    ])
                    // ->having('households_count', '>', 0)
                    ->orderByDesc('submissions_count')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('District')
                    ->searchable(),
                TextColumn::make('region.name')
                    ->label('Region'),
                TextColumn::make('households_count')
                    ->label('Households')
                    ->sortable(),
                TextColumn::make('submissions_count')
                    ->label('Submissions')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('region')
                    ->relationship('region', 'name')
                    ->label('Region')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('id')
                    ->label('District')
                    ->options(District::all()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload(),


            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
