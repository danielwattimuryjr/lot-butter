@extends('layouts.dashboard')

@section('content')
    <!-- Header -->
    @include('partials.header', [
        'userName' => auth()->user()->name ?? 'Adin',
        'userRole' => 'Finance'
    ])

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mb-6">
        <!-- Gross Revenue Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <p class="text-gray-500 text-sm mb-2">Gross revenue</p>
            <p class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">
                ${{ number_format($grossRevenue ?? 14509) }}
            </p>
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1 text-butter-500 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    {{ $revenueChange ?? '21' }}%
                </span>
                <span class="text-gray-400 text-sm">From last week</span>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <p class="text-gray-500 text-sm mb-2">Customers</p>
            <p class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">
                {{ number_format($customers ?? 306) }}
            </p>
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1 text-butter-500 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    {{ $customerChange ?? '114' }}
                </span>
                <span class="text-gray-400 text-sm">From last week</span>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
            <h3 class="font-semibold text-gray-900">Revenue</h3>
            <span class="text-gray-500 text-sm">March 15 - March 21</span>
        </div>
        
        <div class="relative h-64 lg:h-80">
            <!-- Y-Axis Labels -->
            <div class="absolute left-0 top-0 bottom-8 flex flex-col justify-between text-xs text-gray-400 w-8">
                <span>10K</span>
                <span>8K</span>
                <span>6K</span>
                <span>4K</span>
                <span>2K</span>
                <span>0</span>
            </div>
            
            <!-- Chart Area -->
            <div class="ml-10 h-full pb-8 relative">
                <!-- Grid Lines -->
                <div class="absolute inset-0 flex flex-col justify-between">
                    @for($i = 0; $i < 6; $i++)
                        <div class="border-t border-gray-100 w-full"></div>
                    @endfor
                </div>
                
                <!-- SVG Line Chart -->
                <svg class="w-full h-full" viewBox="0 0 700 200" preserveAspectRatio="none">
                    <polyline
                        fill="none"
                        stroke="#f59e0b"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        points="0,180 50,175 100,170 150,165 200,160 250,155 300,150 350,140 400,120 450,100 500,80 550,60 600,50 650,40 700,30"
                    />
                </svg>
                
                <!-- X-Axis Labels -->
                <div class="absolute bottom-0 left-0 right-0 flex justify-between text-xs text-gray-400 pt-2">
                    <div class="text-center">
                        <div>Mon</div>
                        <div>15</div>
                    </div>
                    <div class="text-center">
                        <div>Tue</div>
                        <div>16</div>
                    </div>
                    <div class="text-center">
                        <div>Wed</div>
                        <div>17</div>
                    </div>
                    <div class="text-center">
                        <div>Thu</div>
                        <div>18</div>
                    </div>
                    <div class="text-center">
                        <div>Fri</div>
                        <div>19</div>
                    </div>
                    <div class="text-center">
                        <div>Sat</div>
                        <div>20</div>
                    </div>
                    <div class="text-center">
                        <div>Sun</div>
                        <div>21</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
