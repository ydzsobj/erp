<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrderExport implements FromCollection, WithHeadings ,WithEvents ,WithColumnFormatting
{
    protected $excel_data;
    function __construct($data)
    {
        $this->excel_data = $data;
        //$this->export_data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        foreach ($this->excel_data as $key=>$value){
            $data[$key]['yunlu_sn']=$value['yunlu_sn'];
            $data[$key]['order_sn']=$value['order_sn'];

        }

        return collect($data);
    }

    //导出标题头
    public function headings(): array
    {
        // TODO: Implement headings() method.
        return ['运单编号','订单编号',
//            '订单类型',
//            '下单日期',
//            '收件人',
//            '商品总件数',
//            '备注',
//            '商品中文名称',
//            'SKU编码',
//            '货架号',
//            '日期',
        ];
    }

    //按需格式化单元格
    public function registerEvents(): array
    {
        // TODO: Implement registerEvents() method.
        return [
            AfterSheet::class => function(AfterSheet $event){
                // 所有表头-设置字体为14
                //$cellRange = 'A1:K1';
                $num = count($this->excel_data) + 1;
                $cell_num = 'A1:D'.$num;
                $data_map = [
                    'A' => ['name' => '运单编号', 'key' => 'yunlu_sn', 'data_type' => DataType::TYPE_STRING ],
                    'B' => ['name' => '订单编号', 'key' => 'order_sn', 'data_type' => DataType::TYPE_STRING],

                ];

                foreach($data_map as $code=>$map){
                    $event->sheet->getDelegate()->setCellValue($code.'1', $map['name']);
                }


                /*$start_index = 2;
                foreach ($this->excel_data as $key=>$value){
                    $map = $data_map[$code];
                    $event->sheet->getDelegate()->setCellValueExplicit($code.$start_index,'AAAAAA', $map['data_type']);

                    $start_index ++;

                }*/


                // 将第一行行高设置为20
                //$event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);

                //格式化
                //$event->sheet->getDelegate()->getStyle($cell_num)->getAlignment()->setVertical('center');
                //$event->sheet->autoSize();
            }

        ];
    }

    //
    public function columnFormats(): array
    {
        // TODO: Implement columnFormats() method.
        return [
            //'C' => NumberFormat::FORMAT_NUMBER
        ];
    }


}
