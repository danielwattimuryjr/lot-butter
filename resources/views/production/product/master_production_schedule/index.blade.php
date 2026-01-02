@extends('layouts.dashboard')

@section('title', "$product->name - Master Production Schedule")

@section('content')
<div class="space-y-6">
  <!-- Page Title -->
  <div>
    <h1 class="text-xl font-bold text-gray-900">{{ $product->name }} ({{ $product->pack }}) - Master Production Schedule</h1>
    <div class="mt-2 border-b border-gray-200"></div>
  </div>

  @if (auth()->user()->team->name == 'Production')  
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div class="flex flex-wrap gap-3">
        <!-- Export CSV Button -->
        <a href="{{ route('export', ['resource' => 'products', 'format' => 'csv']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-400 hover:bg-orange-500 text-white text-sm font-medium rounded-lg transition-colors">
            <x-heroicon-o-document class="w-4 h-4"/>
            Export CSV
        </a>

        <!-- Print PDF Button -->
        <a href="{{ route('export', ['resource' => 'products', 'format' => 'pdf']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
            <x-heroicon-o-printer class="w-4 h-4"/>
            Print PDF
        </a>
      </div>
    </div>
  @endif

  <!-- Table Card -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full border-collapse border border-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 border border-gray-200">Information</th>
            @foreach ($monthlyData as $week => $data)
              <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 border border-gray-200">
                @if ($week === 0)
                  First Stock
                @else
                  {{ \Carbon\Carbon::now()->format('F') }}
                @endif
              </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          {{-- Week --}}
          <tr class="bg-butter-100">
            <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
              Week
            </td>
            @foreach($monthlyData as $week => $data)
              <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
                {{ $week }}
              </td>
            @endforeach
          </tr>

          {{-- Forecasting --}}
          <tr>
            <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
              Forecasting
            </td>
            @foreach($monthlyData as $data)
              <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
                {{ $data['forecasting'] }}
              </td>
            @endforeach
          </tr>

          {{-- MPS --}}
          <tr>
            <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
              MPS
            </td>
            @foreach($monthlyData as $data)
              <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
                {{ $data['mps'] }}
              </td>
            @endforeach
          </tr>

          {{-- Available --}}
          <tr>
            <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
              Available
            </td>
            @foreach($monthlyData as $data)
              <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
                {{ $data['available'] }}
              </td>
            @endforeach
          </tr>
          
          {{-- Projected on Hand  --}}
          <tr>
            <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
              Projected On Hand
            </td>
            @foreach($monthlyData as $data)
              <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
                {{ $data['projected_on_hand'] ?? 0 }}
              </td>
            @endforeach
          </tr>

          {{-- Actions --}}
          @if (auth()->user()->team->name == 'Production')  
            <tr>
              <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
                Actions
              </td>
              @foreach($monthlyData as $week => $data)
                <td class="py-4 px-4 text-sm text-gray-700 border border-gray-200">
                  <a href="{{ route('employee.production.products.master-production-schedule.edit', [$product, $data['mps_id']]) }}" class="text-orange-400 hover:text-orange-600 transition-colors">
                    Edit
                  </a>
                </td>
              @endforeach
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection