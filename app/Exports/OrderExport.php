<?php

namespace App\Exports;

use App\Imports\OrderImport;
use App\Models\Order;
use App\Models\OrderInfo;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrderExport implements FromCollection, WithHeadings ,WithEvents
{
    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        foreach ($this->data as $key => $value) {
            $data[$key]['yunlu_sn'] = $value['yunlu_sn'];
            $data[$key]['order_sn'] = $value['order_sn'];
            $data[$key]['order_type']=$value['order_type']??'普通订单';
            $data[$key]['ordered_at'] = $value['ordered_at'];
            $data[$key]['order_num']='1';
            $data[$key]['order_name']=$value['order_name'];
            $data[$key]['order_text']=$value['order_text']??'';

            foreach ($value['order_info'] as $k=>$v){
                $data[$key]['goods_name']=$v['goods_name'];
                $data[$key]['goods_sku']=$v['goods_sku'];
            }
            foreach ($value['inventory'] as $kk=>$vv){
                $data[$key]['goods_position']=$vv['goods_position'];
            }
            $data[$key]['picked_at']=Carbon::now()->toDateTimeString();
        }

        return collect($data);
    }

    //导出标题头
    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            '运单编号',
            '订单编号',
            '订单类型',
            '下单日期',
            '商品总件数',
            '收件人',
            '备注',
            '商品中文名称',
            'SKU编码',
            '货架号',
            '日期',
        ];
    }

//按需格式化单元格
    public function registerEvents(): array
    {
        // TODO: Implement registerEvents() method.
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $num = count($this->data) + 1;
                $cell_num = 'A1:K'.$num;
                $data_map = [
                    'A' => ['name' => '运单编号', 'key' => 'yunlu_sn', 'data_type' => DataType::TYPE_STRING ],
                    'B' => ['name' => '订单编号', 'key' => 'order_sn', 'data_type' => DataType::TYPE_STRING],
                    'C' => ['name' => '订单类型', 'key' => 'order_type', 'data_type' => DataType::TYPE_STRING],
                    'D' => ['name' => '下单时间', 'key' => 'ordered_at', 'data_type' => DataType::TYPE_STRING],
                    'E' => ['name' => '订单总件数', 'key' => 'order_num', 'data_type' => DataType::TYPE_STRING],
                    'F' => ['name' => '收件人', 'key' => 'order_name', 'data_type' => DataType::TYPE_STRING],
                    'G' => ['name' => '备注', 'key' => 'order_text', 'data_type' => DataType::TYPE_STRING],
                    'H' => ['name' => '商品名', 'key' => 'goods_name', 'data_type' => DataType::TYPE_STRING],
                    'I' => ['name' => 'SKU编码', 'key' => 'goods_sku', 'data_type' => DataType::TYPE_STRING],
                    'J' => ['name' => '货架号', 'key' => 'order_position', 'data_type' => DataType::TYPE_STRING],
                    'K' => ['name' => '出库日期', 'key' => 'picked_at', 'data_type' => DataType::TYPE_STRING],

                ];

                foreach($data_map as $code=>$map){
                    $event->sheet->getDelegate()->setCellValue($code.'1', $map['name']);
                }

                $start_num = 2;
                foreach ($this->data as $key=>$value){
                    $map = $data_map[$code];

                    $goods_num = count($value['order_info']);



                    $end_num = $start_num + $goods_num - 1;
                    $event->sheet->getDelegate()->mergeCells($code.$start_num. ':'. $code.$end_num)
                        ->setCellValueExplicit($code.$start_num,$value->{$map['key']}, $map['data_type']);

                    $start_num ++;

                }

                // 将第一行行高设置为20
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);

                //格式化
                //$event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
                $event->sheet->getDelegate()->getStyle($cell_num)->getAlignment()->setVertical('center');
                //$event->sheet->autoSize();


            }
        ];


    }


}
