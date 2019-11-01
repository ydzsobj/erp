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

class OrdersExport implements FromCollection,WithHeadings,withEvents,WithColumnFormatting
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

        return collect([]);
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
            '详细地址1',
            '详细地址2',
            // '公司',
            '代收货款',
            '币种',
            'SKUID',
            '备注',
            '中文品名',
            '英文品名',
            '件数',
            '物品描述',
            '审核状态',
            '国家',
            '客服备注',

        ];
    }

    public function registerEvents(): array
    {
        $num = count($this->export_data) + 1;
        $cell_num = 'A1:W'.$num;
        return [
            AfterSheet::class  => function(AfterSheet $event) use ($cell_num) {

                $columns_map = [
                    'A' => ['name' => '下单时间', 'key' => 'submit_order_at', 'data_type' => DataType::TYPE_STRING ],
                    'B' => ['name' => '审核时间', 'key' => 'last_audited_at' ,'data_type' => DataType::TYPE_STRING],
                    'C' => ['name' => '订单sn', 'key' => 'sn','data_type' => DataType::TYPE_STRING ],
                    'D' => ['name' => '收件人', 'key' => 'receiver_name' ,'data_type' => DataType::TYPE_STRING ],
                    'E' => ['name' => '收货地邮编', 'key' => 'postcode','data_type' => DataType::TYPE_STRING ],
                    'F' => ['name' => '收货人电话', 'key' => 'receiver_phone','data_type' => DataType::TYPE_STRING ],
                    'G' => ['name' => '收件省份', 'key' => 'province','data_type' => DataType::TYPE_STRING ],
                    'H' => ['name' => '收件城市', 'key' => 'city' ,'data_type' => DataType::TYPE_STRING],
                    'I' => ['name' => '收件地区', 'key' => 'area' ,'data_type' => DataType::TYPE_STRING],
                    'J' => ['name' => '详细地址', 'key' => 'address1','data_type' => DataType::TYPE_STRING ],
                    'K' => ['name' => '代收货款', 'key' => 'price','data_type' => DataType::TYPE_NUMERIC ],
                    'L' => ['name' => '币种', 'key' => 'currency_code','data_type' => DataType::TYPE_STRING ],

                    'M' => ['name' => 'SKU编码', 'key' => 'sku_id' ,'data_type' => DataType::TYPE_STRING],
                    'N' => ['name' => '件数', 'key' => 'sku_nums' ,'data_type' => DataType::TYPE_NUMERIC],
                    'O' => ['name' => '中文品名', 'key' => 'product_name_str' ,'data_type' => DataType::TYPE_STRING],
                    'P' => ['name' => '颜色', 'key' => 'color_english' ,'data_type' => DataType::TYPE_STRING],
                    'Q' => ['name' => '尺码', 'key' => 'size_english' ,'data_type' => DataType::TYPE_STRING],
                    'R' => ['name' => '备注', 'key' => 'sku_str','data_type' => DataType::TYPE_STRING ],
                    'S' => ['name' => '英文品名', 'key' => 'product_english_name_str' ,'data_type' => DataType::TYPE_STRING],
                    'T' => ['name' => '物品描述', 'key' => 'sku_desc_str' ,'data_type' => DataType::TYPE_STRING],

                    'U' => ['name' => '审核状态', 'key' => 'status_name','data_type' => DataType::TYPE_STRING ],
                    'V' => ['name' => '国家', 'key' => 'country_name' ,'data_type' => DataType::TYPE_STRING],
                    'W' => ['name' => '客服备注', 'key' => 'remark','data_type' => DataType::TYPE_STRING ]
                ];

                foreach($columns_map as $code=>$map){
                    $event->sheet->getDelegate()->setCellValue($code.'1', $map['name']);
                }
                $base_column_codes = array_merge(range('A','L'), range('U','W'));
                $other_column_codes = range('M','T');
                $start_index = 2;

                foreach ($this->export_data as $key=>$order){

                    $order_skus = $order->order_skus;

                    $sku_count = $order_skus->count();

                    $end_index = $start_index + $sku_count - 1;

                    //订单的基本信息
                    foreach($base_column_codes as $code){
                        $map = $columns_map[$code];
                        $event->sheet->getDelegate()
                            ->mergeCells($code.$start_index. ':'. $code.$end_index)
                            ->setCellValueExplicit($code.$start_index, $order->{$map['key']}, $map['data_type']);
                    }

                    $skus_info = $this->formart_sku_info($start_index, $order_skus);
                    //sku 多行显示
                    foreach($other_column_codes as $code){

                        for($i=$start_index;$i<=$end_index;$i++){
                            $map = $columns_map[$code];
                            $sku_info = Arr::get($skus_info, $i,null);
                            if($sku_info){
                                $event->sheet->getDelegate()
                                ->setCellValueExplicit($code.$i, $sku_info[$map['key']], $map['data_type']);
                            }
                        }
                    }
                    //下一行索引
                    $start_index = $end_index + 1;
                }

                //格式化
                $event->sheet->getDelegate()->getStyle($cell_num)->getAlignment()->setVertical('center');
                $event->sheet->autoSize();
            }

        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function formart_sku_info($start_index, $order_skus){

        $skus_info = collect([]);
        foreach ($order_skus as $key=>$order_sku){

            $index_key = ($start_index + $key);

            $sku = $order_sku->sku;

            if($sku->count() == 0){
                $skus_info->put($index_key, null);
                continue;
            }

            //skuid
            $sku_id = $sku->sku_code;

            //备注-sku-中文
            $sku_str = $sku->sku_name .' '. $sku->sku_attr_value_names. ' x'. $order_sku->sku_nums;

            //物品描述-sku-英文
            $sku_desc_str = '';
            //属性
            $color_english = '';
            $size_english = '';
            $sku_attr_values = $sku->sku_values;
            if($sku_attr_values->count() >0){
                $attr_values_str = $sku_attr_values->map(function($item){
                    return $item->attr_value->attr_value_english;
                });
                $sku_desc_str = $sku->sku_english. ' '.$attr_values_str->implode(',') .' x'. $order_sku->sku_nums;

                $color_english = $attr_values_str->first();
                $size_english = $attr_values_str->last();

                if($attr_values_str->count() == 1){
                    $size_english = '';
                }
            }
            //产品中文名称
            $product_name_str = $sku->sku_name;

            //产品英文名称
            $product_english_name_str = $sku->sku_english;

            //件数
            $sku_nums = $order_sku->sku_nums;

            $skus_info->put($index_key, compact(
                        'sku_id',
                        'sku_str',
                        'sku_desc_str',
                        'product_name_str',
                        'product_english_name_str',
                        'sku_nums',
                        'color_english',
                        'size_english'
                    )
            );
        }

        return $skus_info;

    }
}
