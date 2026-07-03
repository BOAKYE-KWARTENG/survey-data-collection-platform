<?php

namespace App\Filament\Resources\SurveySubmissions\Pages;

use App\Filament\Resources\SurveySubmissions\SurveySubmissionResource;
use App\Models\DigitalAccess;
use App\Models\EmploymentInformation;
use App\Models\FinancialInclusion;
use App\Models\Household;
use App\Models\HouseholdInformation;
use App\Models\RespondentProfile;
use App\Models\SurveySubmission;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Schema;



use App\Models\SurveyCampaign;



class CreateSurveySubmission extends CreateRecord
{
    use HasWizard;

    protected static string $resource = SurveySubmissionResource::class;

    public function getSteps(): array
    {
        return [
            Step::make('Respondent Information')
                ->icon('heroicon-o-user')
                ->schema([
                    Grid::make(2)->schema([


                        Select::make('campaign_id')
                            ->label('Campaign')
                            ->options(SurveyCampaign::where('status', 'active')->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Select::make('household_id')
                            ->label('Household')
                            ->options(Household::all()->pluck('household_code', 'id'))
                            ->searchable()
                            ->required(),

                        TextInput::make('respondent_id')
                            ->label('Respondent ID')
                            ->default(fn () => RespondentProfile::generateRespondentId())
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        DatePicker::make('interview_date')
                            ->label('Interview Date')
                            ->default(now())
                            ->required(),

                        TimePicker::make('interview_start_time')
                            ->label('Interview Start Time')
                            ->default(now())
                            ->required(),

                        TextInput::make('full_name')
                            ->label('Full Name (Optional)')
                            ->nullable(),

                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'male'   => 'Male',
                                'female' => 'Female',
                            ])
                            ->required(),

                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->nullable()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $age = \Carbon\Carbon::parse($state)->age;
                                    $set('age', $age);
                                }
                            }),

                        TextInput::make('age')
                            ->label('Age')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(120)
                            ->required()
                            ->readOnly()
                            ->dehydrated()
                            ->placeholder('Auto-calculated from Date of Birth'),

                        Select::make('marital_status')
                            ->label('Marital Status')
                            ->options([
                                'single'    => 'Single',
                                'married'   => 'Married',
                                'divorced'  => 'Divorced',
                                'widowed'   => 'Widowed',
                                'separated' => 'Separated',
                            ])
                            ->required(),
                        Select::make('education_level')
                            ->label('Highest Level of Education')
                            ->options([
                                'none'       => 'No Formal Education',
                                'primary'    => 'Primary',
                                'jhs'        => 'JHS',
                                'shs'        => 'SHS',
                                'vocational' => 'Vocational/Technical',
                                'diploma'    => 'Diploma',
                                'bachelors'  => "Bachelor's Degree",
                                'masters'    => "Master's Degree",
                                'phd'        => 'PhD',
                            ])
                            ->required(),
                        Select::make('religion')
                            ->label('Religion')
                            ->options([
                                'christianity'   => 'Christianity',
                                'islam'          => 'Islam',
                                'traditionalist' => 'Traditionalist',
                                'other'          => 'Other',
                            ])
                            ->nullable(),
                        Select::make('has_disability')
                            ->label('Disability Status')
                            ->options([
                                true  => 'Yes',
                                false => 'No',
                            ])
                            ->default(false)
                            ->reactive()
                            ->live()
                            ->required(),
                        TextInput::make('disability_type')
                            ->label('Type of Disability')
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('has_disability') === true ||
                                $get('has_disability') === 'true' ||
                                $get('has_disability') === 1 ||
                                $get('has_disability') === '1'
                            ),
                        TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->tel()
                            ->nullable(),
                        TextInput::make('alternative_phone')
                            ->label('Alternative Phone Number')
                            ->tel()
                            ->nullable(),
                    ]),
                ]),

            Step::make('Household Information')
                ->icon('heroicon-o-home')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('household_size')
                            ->label('Household Size')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $adults   = (int) $get('number_of_adults');
                                $children = (int) $get('number_of_children');
                                if ($adults + $children > (int) $state) {
                                    $set('number_of_adults', 0);
                                    $set('number_of_children', 0);
                                }
                            }),

                        TextInput::make('number_of_adults')
                            ->label('Number of Adults (18+)')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $children      = (int) $get('number_of_children');
                                $householdSize = (int) $get('household_size');
                                $total         = (int) $state + $children;
                                if ($householdSize > 0 && $total > $householdSize) {
                                    $set('number_of_adults', $householdSize - $children);
                                }
                            })
                            ->rule(function (callable $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $adults        = (int) $value;
                                    $children      = (int) $get('number_of_children');
                                    $householdSize = (int) $get('household_size');
                                    if ($householdSize > 0 && ($adults + $children) !== $householdSize) {
                                        $fail("Adults + Children must equal Household Size ({$householdSize}).");
                                    }
                                };
                            }),

                        TextInput::make('number_of_children')
                            ->label('Number of Children (<18)')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $adults        = (int) $get('number_of_adults');
                                $householdSize = (int) $get('household_size');
                                $total         = $adults + (int) $state;
                                if ($householdSize > 0 && $total > $householdSize) {
                                    $set('number_of_children', $householdSize - $adults);
                                }
                            })
                            ->rule(function (callable $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $children      = (int) $value;
                                    $adults        = (int) $get('number_of_adults');
                                    $householdSize = (int) $get('household_size');
                                    if ($householdSize > 0 && ($adults + $children) !== $householdSize) {
                                        $fail("Adults + Children must equal Household Size ({$householdSize}).");
                                    }
                                };
                            }),
                            
                        Select::make('household_head_gender')
                            ->label('Household Head Gender')
                            ->options([
                                'male'   => 'Male',
                                'female' => 'Female',
                            ])
                            ->required(),
                        Select::make('respondent_relationship')
                            ->label('Relationship to Household Head')
                            ->options([
                                'head'           => 'Head',
                                'spouse'         => 'Spouse',
                                'child'          => 'Child',
                                'parent'         => 'Parent',
                                'sibling'        => 'Sibling',
                                'other_relative' => 'Other Relative',
                                'non_relative'   => 'Non-Relative',
                            ])
                            ->required(),
                        Select::make('residence_type')
                            ->label('Type of Residence')
                            ->options([
                                'owned'        => 'Owned',
                                'rented'       => 'Rented',
                                'family_house' => 'Family House',
                                'other'        => 'Other',
                            ])
                            ->required(),
                        TextInput::make('drinking_water_source')
                            ->label('Main Source of Drinking Water')
                            ->required(),
                        TextInput::make('electricity_source')
                            ->label('Main Source of Electricity')
                            ->required(),
                        Select::make('has_internet_at_home')
                            ->label('Access to Internet at Home')
                            ->options([
                                true  => 'Yes',
                                false => 'No',
                            ])
                            ->default(false)
                            ->reactive()
                            ->live()
                            ->required(),
                    ]),
                ]),

            Step::make('Financial Inclusion')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('has_bank_account')
                            ->label('Do you have a bank account?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        TextInput::make('bank_institution')
                            ->label('If Yes, Which Institution?')
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('has_bank_account') === true || $get('has_bank_account') === 'true' ||
                                $get('has_bank_account') === 1 || $get('has_bank_account') === '1'
                            ),
                        Select::make('bank_account_duration')
                            ->label('How long have you had the account?')
                            ->options([
                                'less_than_1_year' => 'Less than 1 year',
                                '1_to_3_years'     => '1 to 3 years',
                                '3_to_5_years'     => '3 to 5 years',
                                'over_5_years'     => 'Over 5 years',
                            ])
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('has_bank_account') === true || $get('has_bank_account') === 'true' ||
                                $get('has_bank_account') === 1 || $get('has_bank_account') === '1'
                            ),
                        Select::make('has_mobile_money')
                            ->label('Do you have a mobile money account?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        Select::make('mobile_money_provider')
                            ->label('Mobile Money Provider')
                            ->options([
                                'mtn_momo'     => 'MTN MoMo',
                                'telecel_cash' => 'Telecel Cash',
                                'airteltigo'   => 'AirtelTigo Money',
                            ])
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('has_mobile_money') === true || $get('has_mobile_money') === 'true' ||
                                $get('has_mobile_money') === 1 || $get('has_mobile_money') === '1'
                            ),
                        Select::make('mobile_money_frequency')
                            ->label('Frequency of Mobile Money Use')
                            ->options([
                                'daily'   => 'Daily',
                                'weekly'  => 'Weekly',
                                'monthly' => 'Monthly',
                                'rarely'  => 'Rarely',
                            ])
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('has_mobile_money') === true || $get('has_mobile_money') === 'true' ||
                                $get('has_mobile_money') === 1 || $get('has_mobile_money') === '1'
                            ),
                        Select::make('saves_money')
                            ->label('Do you save money?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        CheckboxList::make('savings_location')
                            ->label('Where do you save?')
                            ->options([
                                'bank'         => 'Bank',
                                'mobile_money' => 'Mobile Money',
                                'susu'         => 'Susu',
                                'home'         => 'Home',
                                'sacco'        => 'SACCO',
                                'other'        => 'Other',
                            ])
                            ->nullable()->columnSpanFull()
                            ->visible(fn (Get $get) =>
                                $get('saves_money') === true || $get('saves_money') === 'true' ||
                                $get('saves_money') === 1 || $get('saves_money') === '1'
                            ),
                        Select::make('borrowed_last_12_months')
                            ->label('Have you borrowed money in the last 12 months?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        Select::make('loan_source')
                            ->label('Source of Loan')
                            ->options([
                                'bank'          => 'Bank',
                                'family'        => 'Family',
                                'friends'       => 'Friends',
                                'mobile_loan'   => 'Mobile Loan',
                                'microfinance'  => 'Microfinance',
                                'savings_group' => 'Savings Group',
                            ])
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('borrowed_last_12_months') === true || $get('borrowed_last_12_months') === 'true' ||
                                $get('borrowed_last_12_months') === 1 || $get('borrowed_last_12_months') === '1'
                            ),
                        Select::make('has_insurance')
                            ->label('Do you have any insurance policy?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        CheckboxList::make('insurance_types')
                            ->label('Types of Insurance')
                            ->options([
                                'health'   => 'Health',
                                'life'     => 'Life',
                                'motor'    => 'Motor',
                                'property' => 'Property',
                            ])
                            ->nullable()->columnSpanFull()
                            ->visible(fn (Get $get) =>
                                $get('has_insurance') === true || $get('has_insurance') === 'true' ||
                                $get('has_insurance') === 1 || $get('has_insurance') === '1'
                            ),
                    ]),
                ]),

            Step::make('Digital Access')
                ->icon('heroicon-o-device-phone-mobile')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('owns_mobile_phone')
                            ->label('Do you own a mobile phone?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        Select::make('mobile_phone_type')
                            ->label('Mobile Phone Type')
                            ->options([
                                'basic_phone' => 'Basic Phone',
                                'smartphone'  => 'Smartphone',
                            ])
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('owns_mobile_phone') === true || $get('owns_mobile_phone') === 'true' ||
                                $get('owns_mobile_phone') === 1 || $get('owns_mobile_phone') === '1'
                            ),
                        Select::make('owns_computer')
                            ->label('Do you own a computer?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        Select::make('used_internet_last_3_months')
                            ->label('Have you used the internet in the last 3 months?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        Select::make('internet_access_method')
                            ->label('Main Method of Accessing the Internet')
                            ->options([
                                'mobile_data'   => 'Mobile Data',
                                'wifi'          => 'Wi-Fi',
                                'internet_cafe' => 'Internet Café',
                                'other'         => 'Other',
                            ])
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('used_internet_last_3_months') === true || $get('used_internet_last_3_months') === 'true' ||
                                $get('used_internet_last_3_months') === 1 || $get('used_internet_last_3_months') === '1'
                            ),
                        Select::make('internet_frequency')
                            ->label('Frequency of Internet Use')
                            ->options([
                                'daily'   => 'Daily',
                                'weekly'  => 'Weekly',
                                'monthly' => 'Monthly',
                                'rarely'  => 'Rarely',
                            ])
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('used_internet_last_3_months') === true || $get('used_internet_last_3_months') === 'true' ||
                                $get('used_internet_last_3_months') === 1 || $get('used_internet_last_3_months') === '1'
                            ),
                        CheckboxList::make('digital_skills')
                            ->label('Digital Skills — Select all that apply')
                            ->options([
                                'send_sms'        => 'Send SMS',
                                'use_whatsapp'    => 'Use WhatsApp',
                                'send_email'      => 'Send Email',
                                'download_apps'   => 'Download Apps',
                                'online_payments' => 'Make Online Payments',
                            ])
                            ->nullable()->columnSpanFull(),
                        Select::make('used_mobile_banking')
                            ->label('Have you used mobile banking?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        Select::make('made_online_payment_last_12_months')
                            ->label('Have you made an online payment in the last 12 months?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        Select::make('received_money_digitally')
                            ->label('Have you received money digitally?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                    ]),
                ]),

            Step::make('Employment & Income')
                ->icon('heroicon-o-briefcase')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('employment_status')
                            ->label('Current Employment Status')
                            ->options([
                                'employed'      => 'Employed',
                                'self_employed' => 'Self-Employed',
                                'unemployed'    => 'Unemployed',
                                'student'       => 'Student',
                                'retired'       => 'Retired',
                            ])
                            ->live()->reactive()->required(),
                        TextInput::make('main_occupation')
                            ->label('Main Occupation')
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                in_array($get('employment_status'), ['employed', 'self_employed', 'retired'])
                            ),
                        Select::make('employment_sector')
                            ->label('Sector of Employment')
                            ->options([
                                'agriculture'   => 'Agriculture',
                                'trade'         => 'Trade',
                                'manufacturing' => 'Manufacturing',
                                'education'     => 'Education',
                                'health'        => 'Health',
                                'ict'           => 'ICT',
                                'other'         => 'Other',
                            ])
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                in_array($get('employment_status'), ['employed', 'self_employed'])
                            ),
                        Select::make('owns_business')
                            ->label('Do you own a business?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required(),
                        Select::make('business_registered')
                            ->label('Is the business registered?')
                            ->options([true => 'Yes', false => 'No'])
                            ->nullable()
                            ->visible(fn (Get $get) =>
                                $get('owns_business') === true || $get('owns_business') === 'true' ||
                                $get('owns_business') === 1 || $get('owns_business') === '1'
                            ),
                        TextInput::make('number_of_employees')
                            ->label('Number of Employees')
                            ->numeric()->minValue(0)->nullable()
                            ->visible(fn (Get $get) =>
                                $get('owns_business') === true || $get('owns_business') === 'true' ||
                                $get('owns_business') === 1 || $get('owns_business') === '1'
                            ),
                        TextInput::make('main_income_source')
                            ->label('Main Source of Income')
                            ->nullable(),
                        Select::make('monthly_income_range')
                            ->label('Monthly Income Range')
                            ->options([
                                'less_than_500' => 'Less than GHS 500',
                                '500_to_999'    => 'GHS 500 – 999',
                                '1000_to_1999'  => 'GHS 1,000 – 1,999',
                                '2000_to_4999'  => 'GHS 2,000 – 4,999',
                                '5000_plus'     => 'GHS 5,000+',
                            ])
                            ->required(),
                        Select::make('household_monthly_income_range')
                            ->label('Household Monthly Income Range')
                            ->options([
                                'less_than_500' => 'Less than GHS 500',
                                '500_to_999'    => 'GHS 500 – 999',
                                '1000_to_1999'  => 'GHS 1,000 – 1,999',
                                '2000_to_4999'  => 'GHS 2,000 – 4,999',
                                '5000_plus'     => 'GHS 5,000+',
                            ])
                            ->required(),
                        Select::make('can_meet_emergency_expense')
                            ->label('Could your household meet an emergency expense of GHS 1,000 within one month?')
                            ->options([true => 'Yes', false => 'No'])
                            ->default(false)->reactive()->live()->required()->columnSpanFull(),
                        Select::make('financial_confidence')
                            ->label('How confident are you in your financial future?')
                            ->options([
                                'very_confident' => 'Very Confident',
                                'confident'      => 'Confident',
                                'neutral'        => 'Neutral',
                                'not_confident'  => 'Not Confident',
                            ])
                            ->required()->columnSpanFull(),
                    ]),
                ]),

            Step::make('Review & Submit')
                ->icon('heroicon-o-check-circle')
                ->schema([
                    Grid::make(1)->schema([
                        Placeholder::make('respondent_summary')
                            ->label('Respondent Information')
                            ->content(function (Get $get): string {
                                return implode(' | ', array_filter([
                                    $get('respondent_id'),
                                    $get('full_name') ? 'Name: ' . $get('full_name') : null,
                                    $get('gender') ? 'Gender: ' . ucfirst($get('gender')) : null,
                                    $get('age') ? 'Age: ' . $get('age') : null,
                                    $get('marital_status') ? 'Marital: ' . ucfirst($get('marital_status')) : null,
                                    $get('education_level') ? 'Education: ' . ucfirst($get('education_level')) : null,
                                ]));
                            }),
                        Placeholder::make('household_summary')
                            ->label('Household Information')
                            ->content(function (Get $get): string {
                                return implode(' | ', array_filter([
                                    $get('household_size') ? 'Size: ' . $get('household_size') : null,
                                    $get('number_of_adults') ? 'Adults: ' . $get('number_of_adults') : null,
                                    $get('number_of_children') ? 'Children: ' . $get('number_of_children') : null,
                                    $get('residence_type') ? 'Residence: ' . ucfirst($get('residence_type')) : null,
                                    $get('has_internet_at_home') ? 'Internet at Home: Yes' : 'Internet at Home: No',
                                ]));
                            }),
                        Placeholder::make('financial_summary')
                            ->label('Financial Inclusion')
                            ->content(function (Get $get): string {
                                return implode(' | ', array_filter([
                                    $get('has_bank_account') ? 'Bank Account: Yes' : 'Bank Account: No',
                                    $get('has_mobile_money') ? 'Mobile Money: Yes' : 'Mobile Money: No',
                                    $get('saves_money') ? 'Saves: Yes' : 'Saves: No',
                                    $get('borrowed_last_12_months') ? 'Borrowed: Yes' : 'Borrowed: No',
                                    $get('has_insurance') ? 'Insurance: Yes' : 'Insurance: No',
                                ]));
                            }),
                        Placeholder::make('digital_summary')
                            ->label('Digital Access')
                            ->content(function (Get $get): string {
                                return implode(' | ', array_filter([
                                    $get('owns_mobile_phone') ? 'Mobile Phone: Yes' : 'Mobile Phone: No',
                                    $get('mobile_phone_type') ? 'Phone Type: ' . ucfirst($get('mobile_phone_type')) : null,
                                    $get('owns_computer') ? 'Computer: Yes' : 'Computer: No',
                                    $get('used_internet_last_3_months') ? 'Internet: Yes' : 'Internet: No',
                                    $get('used_mobile_banking') ? 'Mobile Banking: Yes' : 'Mobile Banking: No',
                                ]));
                            }),
                        Placeholder::make('employment_summary')
                            ->label('Employment & Income')
                            ->content(function (Get $get): string {
                                return implode(' | ', array_filter([
                                    $get('employment_status') ? 'Status: ' . ucfirst($get('employment_status')) : null,
                                    $get('employment_sector') ? 'Sector: ' . ucfirst($get('employment_sector')) : null,
                                    $get('owns_business') ? 'Business: Yes' : 'Business: No',
                                    $get('monthly_income_range') ? 'Income: ' . $get('monthly_income_range') : null,
                                    $get('financial_confidence') ? 'Confidence: ' . ucfirst($get('financial_confidence')) : null,
                                ]));
                            }),
                        Fieldset::make('Enumerator Verification')
                            ->schema([
                                Select::make('interview_completed')
                                    ->label('Interview Completed?')
                                    ->options([true => 'Yes', false => 'No'])
                                    ->default(true)->reactive()->live()->required(),
                                Select::make('respondent_consented')
                                    ->label('Respondent Consented to Participate?')
                                    ->options([true => 'Yes', false => 'No'])
                                    ->default(true)->reactive()->live()->required(),
                                Textarea::make('enumerator_remarks')
                                    ->label('Enumerator Remarks')
                                    ->nullable()->columnSpanFull(),
                                TimePicker::make('interview_end_time')
                                    ->label('Interview End Time')
                                    ->default(now()->format('H:i:s'))
                                    ->readOnly()
                                    ->dehydrated()
                                    ->placeholder('Auto-populated at submission time'),
                            ]),
                    ]),
                ]),
        ];
    }



    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['enumerator_id'] = auth()->id();
        $data['status']        = 'submitted';
        $data['submitted_at']  = now();
        $data['interview_end_time']  = now()->format('H:i:s');

        return $data;
    }



    protected function afterCreate(): void
    {
        $submission = $this->record;
        $data       = $this->data;

        // Respondent Profile
        RespondentProfile::create([
            'submission_id'        => $submission->id,
            'respondent_id'        => $data['respondent_id'],
            'interview_date'       => $data['interview_date'],
            'interview_start_time' => $data['interview_start_time'],
            'full_name'            => $data['full_name'] ?? null,
            'gender'               => $data['gender'],
            'age'                  => $data['age'],
            'date_of_birth'        => $data['date_of_birth'] ?? null,
            'marital_status'       => $data['marital_status'],
            'education_level'      => $data['education_level'],
            'religion'             => $data['religion'] ?? null,
            'has_disability'       => $data['has_disability'] ?? false,
            'disability_type'      => $data['disability_type'] ?? null,
            'phone_number'         => $data['phone_number'] ?? null,
            'alternative_phone'    => $data['alternative_phone'] ?? null,
        ]);

        // Household Information
        HouseholdInformation::create([
            'submission_id'           => $submission->id,
            'household_size'          => $data['household_size'],
            'number_of_adults'        => $data['number_of_adults'],
            'number_of_children'      => $data['number_of_children'],
            'household_head_gender'   => $data['household_head_gender'],
            'respondent_relationship' => $data['respondent_relationship'],
            'residence_type'          => $data['residence_type'],
            'drinking_water_source'   => $data['drinking_water_source'],
            'electricity_source'      => $data['electricity_source'],
            'has_internet_at_home'    => $data['has_internet_at_home'] ?? false,
        ]);

        // Financial Inclusion
        FinancialInclusion::create([
            'submission_id'           => $submission->id,
            'has_bank_account'        => $data['has_bank_account'] ?? false,
            'bank_institution'        => $data['bank_institution'] ?? null,
            'bank_account_duration'   => $data['bank_account_duration'] ?? null,
            'has_mobile_money'        => $data['has_mobile_money'] ?? false,
            'mobile_money_provider'   => $data['mobile_money_provider'] ?? null,
            'mobile_money_frequency'  => $data['mobile_money_frequency'] ?? null,
            'saves_money'             => $data['saves_money'] ?? false,
            'savings_location'        => $data['savings_location'] ?? null,
            'borrowed_last_12_months' => $data['borrowed_last_12_months'] ?? false,
            'loan_source'             => $data['loan_source'] ?? null,
            'has_insurance'           => $data['has_insurance'] ?? false,
            'insurance_types'         => $data['insurance_types'] ?? null,
        ]);

        // Digital Access
        DigitalAccess::create([
            'submission_id'                      => $submission->id,
            'owns_mobile_phone'                  => $data['owns_mobile_phone'] ?? false,
            'mobile_phone_type'                  => $data['mobile_phone_type'] ?? null,
            'owns_computer'                      => $data['owns_computer'] ?? false,
            'used_internet_last_3_months'        => $data['used_internet_last_3_months'] ?? false,
            'internet_access_method'             => $data['internet_access_method'] ?? null,
            'internet_frequency'                 => $data['internet_frequency'] ?? null,
            'digital_skills'                     => $data['digital_skills'] ?? null,
            'used_mobile_banking'                => $data['used_mobile_banking'] ?? false,
            'made_online_payment_last_12_months' => $data['made_online_payment_last_12_months'] ?? false,
            'received_money_digitally'           => $data['received_money_digitally'] ?? false,
        ]);

        // Employment Information
        EmploymentInformation::create([
            'submission_id'                  => $submission->id,
            'employment_status'              => $data['employment_status'],
            'main_occupation'                => $data['main_occupation'] ?? null,
            'employment_sector'              => $data['employment_sector'] ?? null,
            'owns_business'                  => $data['owns_business'] ?? false,
            'business_registered'            => $data['business_registered'] ?? null,
            'number_of_employees'            => $data['number_of_employees'] ?? null,
            'main_income_source'             => $data['main_income_source'] ?? null,
            'monthly_income_range'           => $data['monthly_income_range'],
            'household_monthly_income_range' => $data['household_monthly_income_range'],
            'can_meet_emergency_expense'     => $data['can_meet_emergency_expense'] ?? false,
            'financial_confidence'           => $data['financial_confidence'],
        ]);
    }
}