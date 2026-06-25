<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Refugee Needs Report</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #1e293b; }

    .header { border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 18px; }
    .header h1 { font-size: 20px; color: #4f46e5; }
    .header p { font-size: 10px; color: #64748b; margin-top: 2px; }
    .meta { font-size: 9px; color: #94a3b8; margin-top: 4px; }

    .stats-grid { display: table; width: 100%; margin-bottom: 18px; border-collapse: separate; border-spacing: 4px; }
    .stat-cell { display: table-cell; width: 20%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px 10px; text-align: center; }
    .stat-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-value { font-size: 18px; font-weight: bold; margin-top: 2px; }
    .c-indigo { color: #4338ca; } .c-blue { color: #1d4ed8; } .c-amber { color: #d97706; }
    .c-red { color: #dc2626; } .c-emerald { color: #059669; }

    .section { margin-bottom: 20px; }
    .section-title { font-size: 12px; font-weight: bold; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 8px; }
    .section-sub { font-size: 9px; color: #94a3b8; font-weight: normal; }

    .two-col { display: table; width: 100%; border-collapse: separate; border-spacing: 8px; margin-bottom: 20px; }
    .col { display: table-cell; width: 50%; vertical-align: top; }

    table { width: 100%; border-collapse: collapse; }
    th { background: #f1f5f9; font-size: 9px; font-weight: 600; color: #64748b; text-transform: uppercase; padding: 5px 8px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    th.right, td.right { text-align: right; }
    th.center, td.center { text-align: center; }
    td { padding: 5px 8px; font-size: 10px; border-bottom: 1px solid #f1f5f9; }
    tr:last-child td { border-bottom: none; }

    .badge-pending { background: #fef3c7; color: #92400e; padding: 1px 5px; border-radius: 10px; font-size: 8px; }
    .badge-in_progress { background: #dbeafe; color: #1e40af; padding: 1px 5px; border-radius: 10px; font-size: 8px; }
    .badge-resolved { background: #d1fae5; color: #065f46; padding: 1px 5px; border-radius: 10px; font-size: 8px; }

    .urgency-high { background: #fee2e2; color: #b91c1c; padding: 1px 5px; border-radius: 10px; font-size: 9px; }
    .urgency-med { background: #fef3c7; color: #92400e; padding: 1px 5px; border-radius: 10px; font-size: 9px; }
    .urgency-low { background: #f1f5f9; color: #475569; padding: 1px 5px; border-radius: 10px; font-size: 9px; }

    .score-high { color: #dc2626; font-weight: bold; }
    .score-med  { color: #d97706; font-weight: bold; }
    .score-low  { color: #059669; font-weight: bold; }

    .footer { margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 6px; font-size: 8px; color: #94a3b8; }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <h1>Refugee Needs System &mdash; Report</h1>
    <p>Statistical overview of refugee needs assessment</p>
    <p class="meta">Generated: {{ now()->format('d M Y, H:i') }}</p>
</div>

{{-- Stats --}}
@php
$stats = [
    ['label'=>'Total Refugees',   'value'=>$totalRefugees,           'class'=>'c-indigo'],
    ['label'=>'Assessed',         'value'=>$refugeesWithNeeds,        'class'=>'c-blue'],
    ['label'=>'Not Yet Assessed', 'value'=>$refugeesWithoutNeeds,     'class'=>'c-amber'],
    ['label'=>'Pending Needs',    'value'=>$needsByStatus['pending'] ?? 0,  'class'=>'c-red'],
    ['label'=>'Resolved Needs',   'value'=>$needsByStatus['resolved'] ?? 0, 'class'=>'c-emerald'],
];
@endphp
<div class="stats-grid">
    @foreach($stats as $s)
    <div class="stat-cell">
        <div class="stat-label">{{ $s['label'] }}</div>
        <div class="stat-value {{ $s['class'] }}">{{ $s['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Category + Urgency side by side --}}
<div class="two-col">
    <div class="col">
        <div class="section-title">Needs by Category</div>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="right">Count</th>
                    <th class="right">Avg Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse($needsByCategory as $row)
                @php $avg = round($row->avg_score, 1); @endphp
                <tr>
                    <td style="text-transform:capitalize">{{ $row->category }}</td>
                    <td class="right">{{ $row->total }}</td>
                    <td class="right {{ $avg >= 200 ? 'score-high' : ($avg >= 100 ? 'score-med' : 'score-low') }}">{{ $avg }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="color:#94a3b8">No data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="col">
        <div class="section-title">Needs by Urgency Level</div>
        @php $urgencyLabels = [5=>'Critical',4=>'Very High',3=>'High',2=>'Moderate',1=>'Low']; @endphp
        <table>
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Label</th>
                    <th class="right">Count</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 5; $i >= 1; $i--)
                <tr>
                    <td>
                        <span class="{{ $i >= 4 ? 'urgency-high' : ($i >= 3 ? 'urgency-med' : 'urgency-low') }}">{{ $i }}</span>
                    </td>
                    <td>{{ $urgencyLabels[$i] }}</td>
                    <td class="right">{{ $needsByUrgency[$i] ?? 0 }}</td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

{{-- Top priority cases --}}
<div class="section">
    <div class="section-title">Top 20 Priority Cases <span class="section-sub">(unresolved)</span></div>
    <table>
        <thead>
            <tr>
                <th style="width:24px">#</th>
                <th>Refugee</th>
                <th>Category</th>
                <th class="center">Urgency</th>
                <th class="right">Score</th>
                <th>Status</th>
                <th>Recorded</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topPriorityCases as $i => $need)
            @php $s = $need->priority_score; @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $need->refugee->name ?? '—' }}</td>
                <td style="text-transform:capitalize">{{ $need->category }}</td>
                <td class="center">
                    <span class="{{ $need->urgency_level >= 4 ? 'urgency-high' : 'urgency-med' }}">{{ $need->urgency_level }}</span>
                </td>
                <td class="right {{ $s >= 200 ? 'score-high' : ($s >= 100 ? 'score-med' : 'score-low') }}">{{ $s }}</td>
                <td><span class="badge-{{ $need->status }}">{{ str_replace('_',' ',$need->status) }}</span></td>
                <td>{{ $need->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="color:#94a3b8">All needs resolved.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Recently recorded --}}
<div class="section">
    <div class="section-title">Recently Recorded <span class="section-sub">(last 10)</span></div>
    <table>
        <thead>
            <tr>
                <th>Refugee</th>
                <th>Category</th>
                <th>Recorded By</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentNeeds as $need)
            <tr>
                <td>{{ $need->refugee->name ?? '—' }}</td>
                <td style="text-transform:capitalize">{{ $need->category }}</td>
                <td>{{ $need->recorder->name ?? '—' }}</td>
                <td>{{ $need->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="footer">Refugee Needs System &bull; Confidential &bull; Page 1</div>

</body>
</html>
