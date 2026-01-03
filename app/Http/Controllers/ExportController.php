<?php

namespace App\Http\Controllers;

use App\Exports\AccountsExport;
use App\Exports\ComponentsExport;
use App\Exports\EmployeesExport;
use App\Exports\IncomesExport;
use App\Exports\JournalsExport;
use App\Exports\LogisticsExport;
use App\Exports\MasterProductionScheduleExport;
use App\Exports\MaterialRequirementsPlanningExport;
use App\Exports\ProcurementsExport;
use App\Exports\ProductsExport;
use App\Exports\TeamsExport;
use App\Models\Component;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __invoke(Request $request)
    {
        $resource = $request->input('resource');
        $format = $request->input('format', 'xlsx');

        $exportClass = match ($resource) {
            'employees' => EmployeesExport::class,
            'teams' => TeamsExport::class,
            'accounts' => AccountsExport::class,
            'products' => ProductsExport::class,
            'components' => ComponentsExport::class,
            'incomes' => IncomesExport::class,
            'journals' => JournalsExport::class,
            'procurements' => ProcurementsExport::class,
            'logistics' => LogisticsExport::class,
            default => abort(404, 'Resource not found')
        };

        $filename = date('U').'-'.$resource.'.'.$format;

        $excelFormat = match ($format) {
            'csv' => \Maatwebsite\Excel\Excel::CSV,
            'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
            default => \Maatwebsite\Excel\Excel::XLSX
        };

        return Excel::download(new $exportClass, $filename, $excelFormat);
    }

    public function exportMPS(Request $request, Product $product)
    {
        $format = $request->input('format', 'xlsx');
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $filename = date('U').'-mps-'.$product->name.'-'.$month.'-'.$year.'.'.$format;

        $excelFormat = match ($format) {
            'csv' => \Maatwebsite\Excel\Excel::CSV,
            'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
            default => \Maatwebsite\Excel\Excel::XLSX
        };

        return Excel::download(
            new MasterProductionScheduleExport($product->id, $month, $year),
            $filename,
            $excelFormat
        );
    }

    public function exportMRP(Request $request, Component $component)
    {
        $format = $request->input('format', 'xlsx');
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $filename = date('U').'-mrp-'.$component->name.'-'.$month.'-'.$year.'.'.$format;

        $excelFormat = match ($format) {
            'csv' => \Maatwebsite\Excel\Excel::CSV,
            'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
            default => \Maatwebsite\Excel\Excel::XLSX
        };

        return Excel::download(
            new MaterialRequirementsPlanningExport($component->id, $month, $year),
            $filename,
            $excelFormat
        );
    }
}
