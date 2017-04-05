<?php

namespace App\Http\Controllers\Ap;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Payments\Cardinity;
use App\Payments\Plan;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PartnerReportingController extends Controller {

    const PDF = 'pdf';
    const CSV = 'csv';
    const XLSX = 'xlsx';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('ap.Report', [
            'title'    => 'Create report',
            'partners' => User::partners()->get(),
        ]);
    }

    public function generate(Request $request, Cardinity $cardinity){
        $extension = $request->input('extension');
        $from = Carbon::createFromTimestamp(strtotime($request->input('from')));
        $to = Carbon::createFromTimestamp(strtotime($request->input('to')));

        $title = 'Report ' . $from->format(trans('app.php_date_format')) . ' - ' . $to->format(trans('app.php_date_format'));

        /** @var Builder $builder */
        $builder = Payment::whereBetween('created_at', [
            $from->format('Y-m-d'),
            $to->addDay()->format('Y-m-d'),
        ]);

        if(Auth::user()->isAdmin()){

            $filter = $request->input('filter', 'all');

            if($filter === 'all_partners'){
                $builder->whereNotIn('partner_sum', [0]);
            }elseif($filter === 'partner'){

                $partner_aids = $request->input('partner_aids', [0]);

                $builder->whereIn('payer_id', function($q) use($partner_aids){
                    $q->select('id')
                        ->from('users')
                        ->whereIn('partner_aid', $partner_aids)
                        ->whereIn('aid', $partner_aids, 'or');
                });
            }

            $sql = $builder->toSql();

            $bindigs = $builder->getBindings();

            /** @var Payment[] $payments */
            $payments = $builder->get();

            $partnerSum = 0;
            $newSum = 0;
            $priceSum = 0;

            if($payments) foreach($payments as $payment){

                $payer = $payment->payer();

                if($payer){

                    $net = $payment->price - $payment->partner_sum;

                    $body[] = [
                        $payment->start_access_date->format(trans('app.php_date_format')),
                        $payer->id,
                        $payer->name,
                        $payer->surname,
                        $payer->email,
                        $payer->country()->first()->name,
                        Plan::getFormatString($payment->price / 100, $extension !== self::PDF),
                        Plan::getFormatString($payment->partner_sum / 100, $extension !== self::PDF) . ' (' . ((int)$payment->partner_percent) . '%)',
                        Plan::getFormatString(($net) / 100, $extension !== self::PDF) . ' (' . ((int)(100 - $payment->partner_percent)) . '%)',
                    ];

                    $partnerSum += $payment->partner_sum;
                    $newSum += $net;
                    $priceSum += $payment->price;
                }
            }

            $body[] = [ 'Totals', null, null, null, null, null,
                        Plan::getFormatString($priceSum / 100, $extension !== self::PDF),
                        Plan::getFormatString($partnerSum / 100, $extension !== self::PDF),
                        Plan::getFormatString($newSum / 100, $extension !== self::PDF),
            ];

            $table = [
                'header' => [ 'Date of subscription', 'UserId', 'UserName', 'User Surname', 'eMail', 'Country', 'Billed (100%)', 'Partner (XX%)', 'Net' ],
                'body'   => $body,
                'width'  => [ 9.1, 6.1, 11.1, 11.1, 20.1, 11.1, 10.1, 10.1, 11.1 ],
            ];

            $fileName = 'AdminReport';

        } elseif(Auth::user()->isPartner()) {

            /** @var Payment[] $payments */
            $payments = $builder->whereIn('payer_id', function($q){
                $q->select('id')
                    ->from('users')
                    ->where('partner_aid', Auth::user()->aid)
                    ->orWhere('aid', Auth::user()->aid);
            })->get();

            $partnerSum = 0;

            foreach($payments as $payment){

                $partnerSum += $payment->partner_sum;

                $body[] = [
                    $payment->start_access_date->format(trans('app.php_date_format')),
                    $payment->payer_id,
                    $payment->payer()->country()->first()->name,
                    Plan::getFormatString($payment->partner_sum / 100, $extension !== self::PDF) . ' (' . ((int)$payment->partner_percent) . '%)',
                ];
            }

            $body[] = [ 'Totals', null, null, Plan::getFormatString($partnerSum / 100, $extension !== self::PDF) ];

            $table = [
                'header' => [ 'Date of subscription', 'UserId', 'Country', 'Partner (XX%)' ],
                'body'   => $body,
                'width'  => [ 25, 25, 25, 25 ],
            ];

            $fileName = 'PartnerReport';
        }

        $fileName .= $from->format(trans('app.php_date_format')) . '-' . $to->format(trans('app.php_date_format'));

        if(isset($table)) switch($extension){
            case self::PDF:
                return $this->generatePdfTable($table, $title, $fileName);
            case self::CSV:
                return $this->generateCsvTable($table, $fileName);
            case self::XLSX:
                return $this->generateXlsxTable($table, $title, $fileName);
        }
    }

    public function generatePdfTable($table, $title = null, $fileName = null){

        $filename = ( $fileName ?: 'Report' ) . '.pdf';

        $html2pdf = new \HTML2PDF(count($table['header']) > 5 ? 'L' : 'P', 'A4', \Config::get('app.locale'));
        $html2pdf->writeHTML(view('ap.reports.partners_pdf', [
            'title' => $title,
            'table' => $table,
        ]));
        $pdf = $html2pdf->Output($filename, 'S');

        return Response::make($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function generateCsvTable($table, $fileName = null){

        $filename = ( $fileName ?: 'Report' ) . '.csv';

        $fp = fopen('php://memory', 'w');

        fclose($fp);

        $data = array_merge(array( $table['header'] ), $table['body']);

        $csv = '';

        foreach($data as $row){
            $csv .= implode(';', $row) . "\n";
        }

        return Response::make($csv, 200, [
            'Content-Type'        => 'application/csv',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function generateXlsxTable($table, $title = null, $fileName = null){

        $all = array_merge(array( $table['header'] ), $table['body']);

        $filename = ( $fileName ?: 'Report' ) . '.xlsx';

        $objPHPExcel = new \PHPExcel();

        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        if($title) $sheet->setTitle($title);

        foreach($all as $rowIndex => $row){
            foreach($row as $columnIndex => $data){

                $style = $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex + 1, $data, true)->getStyle();

                $style->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $style->applyFromArray([ 'borders' => [
                    'allborders' => [
                        'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ] ]);

                if($rowIndex == 0){
                    $sheet->getColumnDimensionByColumn($columnIndex)->setWidth(15);

                    $style->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('6C6C6C');

                    $style->applyFromArray([ 'font' => [
                        'color' => [ 'rgb' => 'FFFFFF' ],
                    ] ]);
                }
            }
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');

        return;
    }
}
