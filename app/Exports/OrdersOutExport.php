<?php

namespace App\Exports;

use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet ;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class OrdersOutExport implements FromCollection,WithHeadings,WithColumnFormatting,WithEvents
{
    protected $export_data;

    function __construct($data)
    {

        $this->export_data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        // dd($this->export_data);

        return $this->export_data;
    }

    public function headings(): array
    {
        return [
            '下单时间',
            '审核时间',
            '订单sn',
            '收件人',
            '收货地邮编',
            '收货人电话',
            '收件省份',
            '收件城市',
            '收件地区',
            '详细地址',
            '代收货款',
            '币种',
            'SKUID',
            '件数',
            '中文品名',
            '英文品名',
            // '属性',
            '属性值'

        ];
    }


    public function columnFormats(): array
    {
        return [
            // 'F' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function registerEvents(): array
    {
        $num = count($this->export_data) + 1;
        $cell_num = 'A1:R'.$num;
        return [
            AfterSheet::class  => function(AfterSheet $event) use ($cell_num) {
                //格式化
                $event->sheet->getDelegate()->getStyle($cell_num)->getAlignment()->setVertical('center');
                $event->sheet->autoSize();
            }

        ];
    }


}
