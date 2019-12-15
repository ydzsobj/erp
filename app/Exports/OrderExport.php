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
            $data[$key]['ordered_at'] = $value['ordered_at'];
            $data[$key]['order_checked_at'] = $value['order_checked_at'];
            $data[$key]['order_sn'] = $value['order_sn'];
            //$data[$key]['order_type']=$value['order_type']??'普通订单';
            $data[$key]['order_name']=$value['order_name'];
            $data[$key]['order_name']=$value['order_code'];
            $data[$key]['order_phone']=$value['order_phone'];
            $data[$key]['order_province']=$value['order_province'];
            $data[$key]['order_city']=$value['order_city'];
            $data[$key]['order_area']=$value['order_area'];
            $data[$key]['order_address']=$value['order_address'];
            $data[$key]['order_money']=$value['order_money'];
            $data[$key]['order_currency']=$value['order_currency'];

            foreach ($value['order_info'] as $k=>$v){
                $data[$key]['goods_sku']=$v['goods_sku'];
                $data[$key]['goods_num']=$v['goods_num'];
                $data[$key]['goods_name']=$v['goods_name'];
                $data[$key]['goods_color']=$v['goods_name'];
                $data[$key]['goods_size']='xl';
                $data[$key]['goods_text']=$data[$key]['goods_name']=$v['goods_name'].'x'.$v['goods_num'];
                $data[$key]['goods_english']=$v['goods_english'];
            }
            $data[$key]['order_text']=$value['order_text']??'';
            $data[$key]['check_status']='已审核';
            $data[$key]['customer_text']=$value['customer_text'];
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
            '下单时间',
            '审核时间',
            '订单编号',
            '客户姓名',
            '邮编',
            '客户电话',
            '省',
            '城市',
            '地区',
            '详细地址',
            '总金额',
            '币种',
            'SKU码',
            '件数',
            '产品名称',
            '颜色',
            '尺码',
            '备注',
            '产品英文名称',
            '物品描述',
            '审核状态',
            '客服备注',
            '库位码',
            '出库时间',
        ];
    }

//按需格式化单元格
    public function registerEvents(): array
    {
        // TODO: Implement registerEvents() method.
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $num = count($this->data) + 1;
                $cell_num = 'A1:W'.$num;
                $data_map = [
                    'A' => ['name' => '下单时间', 'key' => 'ordered_at', 'data_type' => DataType::TYPE_STRING],
                    'B' => ['name' => '审核时间', 'key' => 'order_checked_at', 'data_type' => DataType::TYPE_STRING],
                    'C' => ['name' => '订单编号', 'key' => 'order_sn', 'data_type' => DataType::TYPE_STRING],
                    'D' => ['name' => '客户姓名', 'key' => 'order_name', 'data_type' => DataType::TYPE_STRING],
                    'E' => ['name' => '邮编', 'key' => 'order_code', 'data_type' => DataType::TYPE_STRING],
                    'F' => ['name' => '客户电话', 'key' => 'order_phone', 'data_type' => DataType::TYPE_STRING],
                    'G' => ['name' => '省', 'key' => 'order_province', 'data_type' => DataType::TYPE_STRING],
                    'H' => ['name' => '城市', 'key' => 'order_city', 'data_type' => DataType::TYPE_STRING],
                    'I' => ['name' => '地区', 'key' => 'order_area', 'data_type' => DataType::TYPE_STRING],
                    'J' => ['name' => '总金额', 'key' => 'order_money', 'data_type' => DataType::TYPE_STRING],
                    'K' => ['name' => '币种', 'key' => 'order_currency', 'data_type' => DataType::TYPE_STRING],
                    'L' => ['name' => 'SKU码', 'key' => 'goods_sku', 'data_type' => DataType::TYPE_STRING],
                    'M' => ['name' => '件数', 'key' => 'goods_num', 'data_type' => DataType::TYPE_STRING],
                    'N' => ['name' => '产品名称', 'key' => 'goods_name', 'data_type' => DataType::TYPE_STRING],
                    'O' => ['name' => '颜色', 'key' => 'goods_color', 'data_type' => DataType::TYPE_STRING],
                    'P' => ['name' => '尺码', 'key' => 'goods_size', 'data_type' => DataType::TYPE_STRING],
                    'Q' => ['name' => '备注', 'key' => 'goods_text', 'data_type' => DataType::TYPE_STRING],
                    'R' => ['name' => '产品英文名称', 'key' => 'goods_english', 'data_type' => DataType::TYPE_STRING],
                    'S' => ['name' => '物品描述', 'key' => 'order_text', 'data_type' => DataType::TYPE_STRING],
                    'T' => ['name' => '审核状态', 'key' => 'check_status', 'data_type' => DataType::TYPE_STRING],
                    'U' => ['name' => '客服备注', 'key' => 'customer_text', 'data_type' => DataType::TYPE_STRING],
                    'V' => ['name' => '货架号', 'key' => 'order_position', 'data_type' => DataType::TYPE_STRING],
                    'W' => ['name' => '出库日期', 'key' => 'picked_at', 'data_type' => DataType::TYPE_STRING],

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
                $event->sheet->autoSize();


            }
        ];


    }


}
