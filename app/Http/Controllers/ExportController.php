<?php

namespace App\Http\Controllers;

use App\Exports\AccountsExport;
use App\Exports\ComponentsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use App\Exports\IncomesExport;
use App\Exports\JournalsExport;
use App\Exports\LogisticsExport;
use App\Exports\ProcurementsExport;
use App\Exports\ProductsExport;
use App\Exports\TeamsExport;

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

        $filename = date('U') . '-' . $resource . '.' . $format;

        $excelFormat = match ($format) {
            'csv' => \Maatwebsite\Excel\Excel::CSV,
            'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
            default => \Maatwebsite\Excel\Excel::XLSX
        };

        return Excel::download(new $exportClass, $filename, $excelFormat);
    }
}
