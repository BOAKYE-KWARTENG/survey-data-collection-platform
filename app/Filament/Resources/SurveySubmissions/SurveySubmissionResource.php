<?php

namespace App\Filament\Resources\SurveySubmissions;

use App\Filament\Resources\SurveySubmissions\Pages\CreateSurveySubmission;
use App\Filament\Resources\SurveySubmissions\Pages\EditSurveySubmission;
use App\Filament\Resources\SurveySubmissions\Pages\ListSurveySubmissions;
use App\Filament\Resources\SurveySubmissions\Schemas\SurveySubmissionForm;
use App\Filament\Resources\SurveySubmissions\Tables\SurveySubmissionsTable;
use App\Models\SurveySubmission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

use App\Filament\Resources\SurveySubmissions\Pages\ViewSurveySubmission;

class SurveySubmissionResource extends Resource
{
    protected static ?string $model = SurveySubmission::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    // protected static ?string $recordTitleAttribute = 'SurveySubmission';


    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Field Operations';
    }



    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return SurveySubmissionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSurveySubmissions::route('/'),
            'create' => CreateSurveySubmission::route('/create'),
            'view'   => ViewSurveySubmission::route('/{record}'),
            'edit'   => EditSurveySubmission::route('/{record}/edit'),
        ];
    }





    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()
            ->with([
                'household.district.region',
                'campaign',
                'enumerator',
                'workflowStatus',
                'latestQaAssignment.qaOfficer',
            ]);

        $user = auth()->user();

        // Enumerator sees only their own submissions
        if ($user->hasRole('enumerator')) {
            return $query->where('enumerator_id', $user->id);
        }

        // QA Officer sees only submissions assigned to them
        if ($user->hasRole('qa_officer')) {
            return $query->whereHas('latestQaAssignment', function ($q) use ($user) {
                $q->where('qa_officer_id', $user->id)
                ->where('status', 'pending');
            });
        }

        // Admin and Supervisor see everything
        return $query;
    }


    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Submission Details')
                    ->schema([
                        TextEntry::make('household.household_code')->label('Household Code'),
                        TextEntry::make('campaign.name')->label('Campaign'),
                        TextEntry::make('household.district.name')->label('District'),
                        TextEntry::make('household.district.region.name')->label('Region'),
                        TextEntry::make('enumerator.name')->label('Enumerator'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft'     => 'gray',
                                'submitted' => 'success',
                                default     => 'gray',
                            }),
                        TextEntry::make('submitted_at')->label('Submitted At')->dateTime(),
                    ])->columns(2),

                Section::make('Respondent Information')
                    ->schema([
                        TextEntry::make('respondentProfile.respondent_id')->label('Respondent ID'),
                        TextEntry::make('respondentProfile.full_name')->label('Full Name'),
                        TextEntry::make('respondentProfile.gender')->label('Gender'),
                        TextEntry::make('respondentProfile.age')->label('Age'),
                        TextEntry::make('respondentProfile.marital_status')->label('Marital Status'),
                        TextEntry::make('respondentProfile.education_level')->label('Education Level'),
                        TextEntry::make('respondentProfile.phone_number')->label('Phone Number'),
                    ])->columns(2),

                Section::make('Household Information')
                    ->schema([
                        TextEntry::make('householdInformation.household_size')->label('Household Size'),
                        TextEntry::make('householdInformation.number_of_adults')->label('Number of Adults'),
                        TextEntry::make('householdInformation.number_of_children')->label('Number of Children'),
                        TextEntry::make('householdInformation.residence_type')->label('Residence Type'),
                        TextEntry::make('householdInformation.drinking_water_source')->label('Drinking Water Source'),
                        TextEntry::make('householdInformation.electricity_source')->label('Electricity Source'),
                    ])->columns(2),

                Section::make('Financial Inclusion')
                    ->schema([
                        TextEntry::make('financialInclusion.has_bank_account')->label('Has Bank Account')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        TextEntry::make('financialInclusion.bank_institution')->label('Bank Institution'),
                        TextEntry::make('financialInclusion.has_mobile_money')->label('Has Mobile Money')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        TextEntry::make('financialInclusion.mobile_money_provider')->label('Mobile Money Provider'),
                        TextEntry::make('financialInclusion.saves_money')->label('Saves Money')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        TextEntry::make('financialInclusion.has_insurance')->label('Has Insurance')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                    ])->columns(2),

                Section::make('Digital Access')
                    ->schema([
                        TextEntry::make('digitalAccess.owns_mobile_phone')->label('Owns Mobile Phone')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        TextEntry::make('digitalAccess.mobile_phone_type')->label('Phone Type'),
                        TextEntry::make('digitalAccess.used_internet_last_3_months')->label('Used Internet (3 months)')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        TextEntry::make('digitalAccess.used_mobile_banking')->label('Used Mobile Banking')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                    ])->columns(2),

                Section::make('Employment & Income')
                    ->schema([
                        TextEntry::make('employmentInformation.employment_status')->label('Employment Status'),
                        TextEntry::make('employmentInformation.employment_sector')->label('Employment Sector'),
                        TextEntry::make('employmentInformation.monthly_income_range')->label('Monthly Income Range'),
                        TextEntry::make('employmentInformation.financial_confidence')->label('Financial Confidence'),
                    ])->columns(2),


                Section::make('QA History')
                    ->schema([
                        TextEntry::make('latestQaAssignment.qaOfficer.name')
                            ->label('Assigned QA Officer'),
                        TextEntry::make('latestQaAssignment.assigned_at')
                            ->label('Assigned At')
                            ->dateTime(),
                        TextEntry::make('latestQaReview.decision')
                            ->label('Last Decision')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'approved'            => 'success',
                                'rejected'            => 'danger',
                                'needs_clarification' => 'warning',
                                default               => 'gray',
                            }),
                        TextEntry::make('latestQaReview.comments')
                            ->label('QA Comments'),
                        TextEntry::make('latestQaReview.reviewed_at')
                            ->label('Reviewed At')
                            ->dateTime(),
                    ])->columns(2),
            ]);
    }

}
