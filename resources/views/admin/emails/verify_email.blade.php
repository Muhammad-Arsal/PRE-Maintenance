<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .email-container {
            max-width: 1200px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-custom th, .table-custom td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table-custom th {
            background-color: #f4f4f4;
        }
        .section-title {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Weekly Sales Report</h2>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">

            <!-- Column 1: Best Ever General and Clean (Current Month) -->
            <div style="width: 33%;">
                <p class="section-title">Current Month: {{ $currentMonth }}</p>
                <table class="table-custom" style="width: 100%;">
                    <tr>
                        <th>Best Ever General {{ $currentMonth }}</th>
                        <td>
                            £{{ !empty($bestEverCurrentMonthGeneral)
                                ? number_format($bestEverCurrentMonthGeneral->total_gen_figure, 0) . ' - ' . $bestEverCurrentMonthGeneral->year
                                : '0' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Best Ever Clean {{ $currentMonth }}</th>
                        <td>
                            £{{ !empty($bestEverCurrentMonthClean)
                                ? number_format($bestEverCurrentMonthClean->total_clean_figure, 0) . ' - ' . $bestEverCurrentMonthClean->year
                                : '0' }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Column 2: Best Ever Gen Figure -->
            <div style="width: 33%;">
                <p class="section-title">Best Ever Gen Figure</p>
                <table class="table-custom" style="width: 100%;">
                    <tr>
                        <th>Figure</th>
                        <td>£{{ !empty($bestEverGenFigure) ? number_format($bestEverGenFigure->total_gen_figure, 0) : '0' }}</td>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <td>{{ !empty($bestEverGenFigure) && !empty($bestEverGenFigure->office)
                            ? ucfirst($bestEverGenFigure->office->name)
                            : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ !empty($bestEverGenFigure)
                            ? DateTime::createFromFormat('!m', $bestEverGenFigure->month)->format('M') . '-' . substr($bestEverGenFigure->year, -2)
                            : '' }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Column 3: Best Ever Clean Figure -->
            <div style="width: 33%;">
                <p class="section-title">Best Ever Clean Figure</p>
                <table class="table-custom" style="width: 100%;">
                    <tr>
                        <th>Figure</th>
                        <td>£{{ !empty($bestEverCleanFigure) ? number_format($bestEverCleanFigure->total_clean_figure, 0) : '0' }}</td>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <td>{{ !empty($bestEverCleanFigure) && !empty($bestEverCleanFigure->office)
                            ? ucfirst($bestEverCleanFigure->office->name)
                            : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ !empty($bestEverCleanFigure)
                            ? DateTime::createFromFormat('!m', $bestEverCleanFigure->month)->format('M') . '-' . substr($bestEverCleanFigure->year, -2)
                            : '' }}
                        </td>
                    </tr>
                </table>
            </div>

        </div>

        <!-- Insert Last 3 Years Data Below -->
        <div style="margin-top: 30px;">
            <h4>Last 3 Years Overview</h4>
            <div style="display: flex; justify-content: space-between; gap: 20px;">

                <!-- Last 3 Years Deals -->
                <div style="width: 33%;">
                    <p class="section-title">Last 3 Years: Deals</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Deals</th>
                                <th>Difference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $difference = 0; $index = 1; @endphp
                            @foreach ($last3YearDeals as $item)
                                @php
                                    $difference = $item->count - $difference;
                                    $formattedDate = \Carbon\Carbon::createFromDate($item->year, $currentMonthNumber, 1)->format('M-y');
                                @endphp
                                <tr>
                                    <td>{{ $formattedDate }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>
                                        @php
                                            if ($index > 1) {
                                                if ($difference < 0) {
                                                    echo "<span style='color:red'>▼ {$difference}</span>";
                                                } else {
                                                    echo "<span style='color:green'>▲ {$difference}</span>";
                                                }
                                            }
                                        @endphp
                                    </td>
                                </tr>
                                @php $difference = $item->count; $index++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Last 3 Years General Figure -->
                <div style="width: 33%;">
                    <p class="section-title">Last 3 Years: General Figure</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>General Fig.</th>
                                <th>Y-O-Y (£)</th>
                                <th>Y-O-Y (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $previousValue = 0; $index = 1; @endphp
                            @foreach ($last3YearsGeneralFigure as $item)
                                @php
                                    $differenceInMoney = $index > 1 ? $item->total_gen_figure - $previousValue : 0;
                                    $differenceInPercentage = $previousValue != 0
                                        ? ($differenceInMoney / $previousValue) * 100
                                        : ($item->total_gen_figure > 0 ? 100 : 0);
                                    $formattedDate = \Carbon\Carbon::createFromDate($item->year, $currentMonthNumber, 1)->format('M-y');
                                @endphp
                                <tr>
                                    <td>{{ $formattedDate }}</td>
                                    <td>£{{ number_format($item->total_gen_figure, 0) }}</td>
                                    <td>
                                        @if ($index > 1)
                                            @if ($differenceInMoney < 0)
                                                <span style="color:red">£{{ number_format($differenceInMoney, 0) }}</span>
                                            @else
                                                <span style="color:green">£{{ number_format($differenceInMoney, 0) }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($index > 1)
                                            @if ($differenceInPercentage < 0)
                                                <span style="color:red">{{ number_format($differenceInPercentage, 2, ".", "") }}%</span>
                                            @else
                                                <span style="color:green">{{ number_format($differenceInPercentage, 2, ".", "") }}%</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @php $previousValue = $item->total_gen_figure; $index++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Last 3 Years Clean Figure -->
                <div style="width: 33%;">
                    <p class="section-title">Last 3 Years: Clean Figure</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Clean Fig.</th>
                                <th>Y-O-Y (£)</th>
                                <th>Y-O-Y (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $previousValue = 0; $index = 1; @endphp
                            @foreach ($last3YearsCleanFigure as $item)
                                @php
                                    $differenceInMoney = $index > 1 ? $item->total_clean_figure - $previousValue : 0;
                                    $differenceInPercentage = $previousValue != 0
                                        ? ($differenceInMoney / $previousValue) * 100
                                        : ($item->total_clean_figure > 0 ? 100 : 0);
                                    $formattedDate = \Carbon\Carbon::createFromDate($item->year, $currentMonthNumber, 1)->format('M-y');
                                @endphp
                                <tr>
                                    <td>{{ $formattedDate }}</td>
                                    <td>£{{ number_format($item->total_clean_figure, 0) }}</td>
                                    <td>
                                        @if ($index > 1)
                                            @if ($differenceInMoney < 0)
                                                <span style="color:red">£{{ number_format($differenceInMoney, 0) }}</span>
                                            @else
                                                <span style="color:green">£{{ number_format($differenceInMoney, 0) }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($index > 1)
                                            @if ($differenceInPercentage < 0)
                                                <span style="color:red">{{ number_format($differenceInPercentage, 2, ".", "") }}%</span>
                                            @else
                                                <span style="color:green">{{ number_format($differenceInPercentage, 2, ".", "") }}%</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @php $previousValue = $item->total_clean_figure; $index++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Insert Year-to-Date Data Below -->
        <div style="margin-top: 30px;">
            <h4>Year to Date Overview</h4>
            <div style="display: flex; justify-content: space-between; gap: 20px;">

                <!-- Year to Date Deals -->
                <div style="width: 33%;">
                    <p class="section-title">Year to Date: Deals</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Deals</th>
                                <th>Difference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $difference = 0; $index = 1; @endphp
                            @foreach ($last3YearsSumDeals as $item)
                                @php
                                    $difference = $item->total_deals - $difference;
                                    $formattedDate = \Carbon\Carbon::createFromDate($item->year, $currentMonthNumber, 1)->format('M-y');
                                @endphp
                                <tr>
                                    <td>{{ $formattedDate }}</td>
                                    <td>{{ $item->total_deals }}</td>
                                    <td>
                                        @php
                                            if ($index > 1) {
                                                if ($difference < 0) {
                                                    echo "<span style='color:red'>▼ ".$difference."</span>";
                                                } else {
                                                    echo "<span style='color:green'>▲ ".$difference."</span>";
                                                }
                                            }
                                        @endphp
                                    </td>
                                </tr>
                                @php $difference = $item->total_deals; $index++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Year to Date General Figure -->
                <div style="width: 33%;">
                    <p class="section-title">Year to Date: General Figure</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>General Fig.</th>
                                <th>Y-O-Y (£)</th>
                                <th>Y-O-Y (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $previousValue = 0; $index = 1; @endphp
                            @foreach ($yearToDateGeneralFigure as $item)
                                @php
                                    $differenceInMoney = $index > 1 ? $item->total_gen_figure - $previousValue : 0;
                                    $differenceInPercentage = $previousValue != 0
                                        ? ($differenceInMoney / $previousValue) * 100
                                        : ($item->total_gen_figure > 0 ? 100 : 0);
                                    $formattedDate = \Carbon\Carbon::createFromDate($item->year, $currentMonthNumber, 1)->format('M-y');
                                @endphp
                                <tr>
                                    <td>{{ $formattedDate }}</td>
                                    <td>£{{ number_format($item->total_gen_figure, 0) }}</td>
                                    <td>
                                        @if ($index > 1)
                                            @if ($differenceInMoney < 0)
                                                <span style="color:red">£{{ number_format($differenceInMoney, 0) }}</span>
                                            @else
                                                <span style="color:green">£{{ number_format($differenceInMoney, 0) }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($index > 1)
                                            @if ($differenceInPercentage < 0)
                                                <span style="color:red">{{ number_format($differenceInPercentage, 2, ".", "") }}%</span>
                                            @else
                                                <span style="color:green">{{ number_format($differenceInPercentage, 2, ".", "") }}%</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @php $previousValue = $item->total_gen_figure; $index++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Year to Date Clean Figure -->
                <div style="width: 33%;">
                    <p class="section-title">Year to Date: Clean Figure</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Clean Fig.</th>
                                <th>Y-O-Y (£)</th>
                                <th>Y-O-Y (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $previousValue = 0; $index = 1; @endphp
                            @foreach ($yearToDateCleanFigure as $item)
                                @php
                                    $differenceInMoney = $index > 1 ? $item->total_clean_figure - $previousValue : 0;
                                    $differenceInPercentage = $previousValue != 0
                                        ? ($differenceInMoney / $previousValue) * 100
                                        : ($item->total_clean_figure > 0 ? 100 : 0);
                                    $formattedDate = \Carbon\Carbon::createFromDate($item->year, $currentMonthNumber, 1)->format('M-y');
                                @endphp
                                <tr>
                                    <td>{{ $formattedDate }}</td>
                                    <td>£{{ number_format($item->total_clean_figure, 0) }}</td>
                                    <td>
                                        @if ($index > 1)
                                            @if ($differenceInMoney < 0)
                                                <span style="color:red">£{{ number_format($differenceInMoney, 0) }}</span>
                                            @else
                                                <span style="color:green">£{{ number_format($differenceInMoney, 0) }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($index > 1)
                                            @if ($differenceInPercentage < 0)
                                                <span style="color:red">{{ number_format($differenceInPercentage, 2, ".", "") }}%</span>
                                            @else
                                                <span style="color:green">{{ number_format($differenceInPercentage, 2, ".", "") }}%</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @php $previousValue = $item->total_clean_figure; $index++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Insert Gen to Clean Fig. Variance Section Below -->
        <div style="margin-top: 30px;">
            <h4>Gen to Clean Fig. Variance</h4>
            <div style="display: flex; justify-content: space-between; gap: 20px;">

                <!-- Gen to Clean Fig. Variance -->
                <div style="width: 33%;">
                    <p class="section-title">Gen to Clean Fig. Variance</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Divergence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($divergence as $item)
                                <tr>
                                    <td>{{ $item['year'] }}</td>
                                    <td>{{ "Divergence of " . number_format($item['divergence'], 2, ".", "") . '%' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Avg. Office Comms (Gen Fig / # Offices) -->
                <div style="width: 33%;">
                    <p class="section-title">Avg. Office Comms (Gen Fig / # Offices)</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Value</th>
                                <th>% Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $previousValue = 0;
                                $index = 1;
                            @endphp

                            @foreach ($last3YearsAvgOfficeComm as $item)
                                @php
                                    $avgCommPerBranch = $item->branch_count > 0
                                        ? $item->total_gen_figure / $item->branch_count
                                        : 0;

                                    // Calculate the difference from the previous year
                                    $difference = $index > 1 ? $avgCommPerBranch - $previousValue : 0;

                                    // Calculate the percentage difference
                                    $differenceInPercentage = $previousValue != 0
                                        ? ($difference / $previousValue) * 100
                                        : ($avgCommPerBranch > 0 ? 100 : 0);

                                    // Format the year and month for display
                                    $formattedDate = \Carbon\Carbon::createFromDate($item->year, $currentMonthNumber, 1)->format('M-y');
                                @endphp

                                <tr>
                                    <td>{{ $formattedDate }}</td>
                                    <td>£{{ number_format($avgCommPerBranch, 0) }}</td>
                                    <td>
                                        @if ($index > 1)
                                            @if ($differenceInPercentage < 0)
                                                <span style="color:red">{{ number_format($differenceInPercentage, 2) }}%</span>
                                            @else
                                                <span style="color:green">{{ number_format($differenceInPercentage, 2) }}%</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>

                                @php
                                    $previousValue = $avgCommPerBranch;
                                    $index++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Avg. Deal Value (Gen Fig / Deals) -->
                <div style="width: 33%;">
                    <p class="section-title">Avg. Deal Value (Gen Fig / Deals)</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Value</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $previousValue = 0;
                                $index = 1;
                            @endphp
                            @foreach ($avgDealDataMerged as $item)
                                @php
                                    $formattedDate = \Carbon\Carbon::createFromDate($item['year'], $currentMonthNumber, 1)->format('M-y');

                                    $change = $previousValue != 0
                                            ? round(( ( $item['avg_deal_value'] - $previousValue ) / $previousValue ) * 100, 2)
                                            : ( $item['avg_deal_value'] > 0 ? 100 : 0 )
                                @endphp
                                <tr>
                                    <td>{{ $formattedDate }}</td>
                                    <td>£{{ isset($item['avg_deal_value']) ? number_format($item['avg_deal_value'], 0) : '0' }}</td>
                                    <td>
                                        @php
                                            if($index > 1) {
                                                if($change < 0) {
                                                    echo "<span style='color:red'>▼ ".$change."%</span>";
                                                } else {
                                                    echo "<span style='color:green'>▲ ".$change."%</span>";
                                                }
                                            }
                                        @endphp
                                    </td>
                                </tr>
                                @php $previousValue = $item['avg_deal_value']; $index++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <h4>Office Performance Overview</h4>
            <div style="display: flex; justify-content: space-between; gap: 20px;">

                <!-- Negs With Zero Deals -->
                <div style="width: 33%;">
                    <p class="section-title">Negs With Zero Deals</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dealNegsOrOfficeMan as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Top Performing Office -->
                <div style="width: 33%;">
                    <p class="section-title">Top Performing Office</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Office</th>
                                <th>Gen Fig</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $positions = ['Top', '2nd', '3rd'] @endphp
                            @foreach ($topPerformingOffices as $index => $item)
                                <tr>
                                    <td>{{ $positions[$index] }}</td>
                                    <td>{{ !empty($item->office) ? $item->office->name : '' }}</td>
                                    <td>£{{ number_format($item->total_gen_figure, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Lowest Performing Office -->
                <div style="width: 33%;">
                    <p class="section-title">Lowest Performing Office</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Office</th>
                                <th>Gen Fig</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowestPerformingOffices as $index => $item)
                                <tr>
                                    <td>
                                        @if ($index == 0)
                                            Last
                                        @elseif ($index == 1)
                                            2nd
                                        @elseif ($index == 2)
                                            3rd
                                        @else
                                            {{ $index + 1 }}th
                                        @endif
                                    </td>
                                    <td>{{ $item->office->name ?? '' }}</td>
                                    <td>£{{ number_format($item->total_gen_figure, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div style="margin-top: 30px;">
            <h4>Sales Performance Overview</h4>
            <div style="display: flex; justify-content: space-between; gap: 20px;">

                <!-- Office Sales Data -->
                <div style="width: 100%;">
                    <p class="section-title">Office Sales Data</p>
                    <table class="table-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Office</th>
                                @foreach($tuesdaysArray as $weekRange)
                                    <th>{{ $weekRange }}</th>
                                @endforeach
                                <th>Total Last Year</th>
                                <th>% Difference</th>
                                <th>Weighed Performance</th>
                                <th>Best Ever</th>
                                <th>Best Ever Year</th>
                                <th>% Above or Below</th>
                                <th>Clean Figure</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCleanFigure = 0;
                                $weightedPerformance = 0;
                                $totalLastYearGen = 0;
                                $weeklyTotals = array_fill(0, count($tuesdaysArray), 0); // Initialize array for weekly totals
                            @endphp
                            @foreach($offices as $office)
                                <tr>
                                    <td>{{ $office->name }}</td>
                                    @foreach($tuesdaysArray as $index => $weekRange)
                                        @php
                                            // Calculate weekly total for this column
                                            $weeklyFigure = $weeklySales[$office->id][$weekRange] ?? 0;
                                            $weeklyTotals[$index] += $weeklyFigure;
                                        @endphp
                                        <td>
                                            <a target="_blank" href="{{ route('admin.weeklySales.figureDeals', ['id' => $weeklySales[$office->id]['sale_id'], 'date' => str_replace("/", "-", $weekRange)]) }}">£{{ number_format($weeklyFigure, 0) }}</a>
                                        </td>
                                    @endforeach
                                    <td>{{ !empty($weeklySales[$office->id]['total_last_year_gen']) ? "£".number_format($weeklySales[$office->id]['total_last_year_gen'], 0) : "£0" }}</td>
                                    <td>{{ number_format($weeklySales[$office->id]['gen_figure_percentage_difference'], 2, ".", '') }}%</td>
                                    @php
                                        $totalCleanFigure += $weeklySales[$office->id]['clean_figure'] ?? 0;
                                        $weightedPerformance += $weeklySales[$office->id]['weightedPerformance'] ?? 0;
                                        $totalLastYearGen += $weeklySales[$office->id]['total_last_year_gen'] ?? 0;
                                    @endphp
                                    <td>{{ !empty($weeklySales[$office->id]['weightedPerformance']) ? "£".number_format($weeklySales[$office->id]['weightedPerformance'], 0) : "£0" }}</td>
                                    <td>{{ !empty($weeklySales[$office->id]['best_ever_genFigure']) ? "£".number_format($weeklySales[$office->id]['best_ever_genFigure'], 0) : "£0" }}</td>
                                    <td>{{ !empty($weeklySales[$office->id]['best_ever_genFigure_year']) ? $weeklySales[$office->id]['best_ever_genFigure_year'] : '' }}</td>
                                    <td>{{ !empty($weeklySales[$office->id]['above_or_below']) ? number_format($weeklySales[$office->id]['above_or_below'], 2, ".", "") . '%' : '0.00%' }}</td>
                                    <td>
                                        <a target="_blank" href="{{ route('admin.weeklySales.figureDeals', ['id' => $weeklySales[$office->id]['sale_id'], 'date' => str_replace("/", "-", $weeklySales[$office->id]['last_data_date'])]) }}">£{{ number_format($weeklySales[$office->id]['clean_figure'], 0) }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @php $lastValue = 0; @endphp
                            <tr>
                                <th>Total</th>
                                @foreach($weeklyTotals as $weeklyTotal)
                                    <th>£{{ number_format($weeklyTotal, 0) }}</th>

                                    @php $lastValue = $weeklyTotal != 0 ? $weeklyTotal : $lastValue; @endphp
                                @endforeach

                                @php
                                    $totalPercentageDiff = $totalLastYearGen != 0 ? (($lastValue - $totalLastYearGen) / $totalLastYearGen) * 100 : ($lastValue > 0 ? 100 : 0);
                                @endphp
                                <th>£{{ number_format($totalLastYearGen, 0) }}</th> <!-- Total Last Year (if needed) -->
                                <th>{{ number_format($totalPercentageDiff, 2, ".", "") }}%</th> <!-- % Different (if needed) -->
                                <th>£{{ number_format($weightedPerformance, 0) }}</th>
                                <th></th> <!-- Best Ever (if needed) -->
                                <th></th> <!-- % Above or Below (if needed) -->
                                <th></th>
                                <th>£{{ number_format($totalCleanFigure, 0) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
