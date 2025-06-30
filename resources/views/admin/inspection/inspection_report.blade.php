<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>Property Inspection Report</title>
    <style>
        /* global resets and body */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding-top: 60px;    /* space for fixed header */
            padding-bottom: 40px; /* space for fixed footer */
            padding-left: 30px;
            padding-right: 30px;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
    /* fixed header */
    .header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 40px;
        padding: 10px 30px;
        background-color: #fff;
        z-index: 1000;
    }
    .header-content {
        font-size: 11px;
        color: #555;
        margin: 0;
    }

    /* fixed footer */
    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 20px;
        padding: 8px 30px;
        background-color: #fff;
        z-index: 1000;
        text-align: right;
        font-size: 9px;
        color: #777;
    }
    .footer-content {
        margin: 0;
    }

    /* page breaking */
    .page-break {
        page-break-before: always;
    }

    /* first page (logo + titles) */
    .first-page {
        text-align: center;
        margin-top: 100px;
    }
    .logo {
        max-height: 200px;
        width: auto;
        margin-bottom: 15px;
    }
    .report-title {
        font-size: 20px;
        font-weight: bold;
        color: #000;
        margin: 10px 0;
    }
    .report-subtitle {
        font-size: 14px;
        font-weight: normal;
        margin: 5px 0;
        color: #000;
    }

    /* table of contents */
    .toc .section-title {
        margin-bottom: 5px;
    }
    .toc ol {
        margin: 0 0 20px 20px;
        padding: 0;
        font-size: 10px;
    }
    .toc ol li {
        margin-bottom: 6px;
    }

    /* section headings */
    .section-title {
        background-color: #f0f0f0;
        color: #333;
        padding: 8px 10px;
        margin-top: 20px;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 12px;
        border-bottom: 1px solid #ddd;
    }

    /* tables */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    table, th, td {
        border: 1px solid #e0e0e0;
    }
    th, td {
        padding: 7px 6px;
        text-align: left;
        vertical-align: top;
        font-size: 10px;
    }
    th {
        background-color: #f5f5f5;
        font-weight: bold;
        color: #555;
    }
    .comments-cell {
        min-width: 150px;
    }
    .dummy-image {
        max-width: 100px;
        height: 75px;
        display: block;
        margin-top: 3px;
    }

    /* declaration & signatures */
    .declaration-text {
        font-size: 10px;
        line-height: 1.4;
        margin-bottom: 15px;
    }
    .signature-block {
        border: 1px solid #e0e0e0;
        padding: 10px;
        margin-bottom: 20px;
        font-size: 10px;
    }
    .signature-row {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    .sig-label {
        flex: 0 0 90px;
        color: #333;
    }
    .sig-line {
        flex: 1;
        border-bottom: 1px dotted #999;
        display: inline-block;
        height: 1px;
        vertical-align: middle;
        margin-left: 5px;
    }
    .date-dots {
        font-family: monospace;
        letter-spacing: 1px;
        display: inline-block;
    }
    
</style>

</head>
<body>
    <div class="header">
        <p class="header-content">{{ $inspection->property->line1 }}, {{ $inspection->property->city }}, {{ $inspection->property->county }}, {{ $inspection->property->postcode }}</p>
    </div>
<div class="container">

    <!-- Page 1: Logo + Titles -->
    <div class="first-page">
        <img src="{{ public_path('storage/logo/logo.png') }}" alt="Company Logo" class="logo">
        <h1 class="report-title">Property Inspection Report</h1>
        <h2 class="report-subtitle">Carried Out: {{ \Carbon\Carbon::parse($inspection->date)->format('d/m/Y') }}</h2>
        <h2 class="report-subtitle">Inspected By: {{ $inspection->assignedTo->name }}</h2>
    </div>

    <div class="page-break"></div>

    <!-- Page 2: Table of Contents -->
    <div class="toc">
        <div class="section-title">Contents</div>
        <ul>
            @foreach($inspection->template->inspectionQuestionSections as $sec)
                <li>{{ $sec->section_name }}</li>
            @endforeach
            <li>Declaration</li>
        </ul>
    </div>

    <div class="page-break"></div>

    <!-- Page 3+: Sections and Tables -->
    @foreach($sections as $sectionName => $respList)
    <div class="section-title">{{ $loop->iteration }}. {{ $sectionName }}</div>
    <table>
        <thead>
        <tr>
            <th style="width:5%;">Ref</th>
            <th style="width:30%;">Name</th>
            <th style="width:15%;">Condition</th>
            <th style="50%" class="comments-cell">Comments</th>
        </tr>
        </thead>
        <tbody>
        @foreach($respList as $resp)
            @php
            // take all photos and chunk into groups of 4
            $chunks = $resp->photos->chunk(4);
            @endphp

            {{-- Main question row, 4 cols only --}}
            <tr>
            <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
            <td>{{ $resp->question }}</td>
            <td>{{ ucfirst($resp->answer) }}</td>
            <td class="comments-cell">{{ $resp->comment }}</td>
            </tr>

            {{-- Now render each 4-up chunk in one border-free row --}}
            @foreach($chunks as $chunk)
            <tr>
                {{-- merge all 4 cols so no inner lines --}}
                <td colspan="4" style="border:none; padding:10px 0;">
                <table
                    style="width:100%; border:none; border-collapse:collapse;">
                    <tr>
                    @foreach($chunk as $photo)
                        <td style="border:none; text-align:left; padding:20px;">
                        <img
                            src="{{ public_path('storage/'.$photo->path) }}"
                            class="dummy-image"
                            style="display:block; margin:0 auto;"
                        >
                        <div style="font-size:8px; color:#666; margin-top:4px;">
                            {{ \Carbon\Carbon::parse($photo->created_at)
                                ->format('d M Y H:i') }}
                        </div>
                        </td>
                    @endforeach

                    {{-- pad out to always get 4 cells --}}
                    @for($i = $chunk->count(); $i < 4; $i++)
                        <td style="border:none; padding:4px;"></td>
                    @endfor
                    </tr>
                </table>
                </td>
            </tr>
            @endforeach

        @endforeach
        </tbody>
    </table>
@endforeach




    <!-- Declaration -->
    <div class="section-title">Declaration</div>
    <div class="declaration-text">
        I/We the undersigned, affirm that if I/we do not comment on the Inventory in writing within seven days of receipt of this Inventory then I/we accept the Inventory as being an accurate record of the contents and condition of the property.
    </div>

    @for($i=0; $i<2; $i++)
        <div class="signature-block">
            <div class="signature-row">
                <span class="sig-label">Signed by the</span>
                <span class="sig-line"></span>
            </div>
            <div class="signature-row">
                <span class="sig-label">Signature</span>
                <span class="sig-line"></span>
            </div>
            <div class="signature-row">
                <span class="sig-label">Print Name</span>
                <span class="sig-line"></span>
            </div>
            <div class="signature-row">
                <span class="sig-label">Date</span>
                <span class="date-dots">...........</span> /
                <span class="date-dots">...........</span> /
                <span class="date-dots">..................</span>
            </div>
        </div>
    @endfor

</div><!-- /.container -->
</body>
</html>
