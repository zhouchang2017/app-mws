<?php

use Illuminate\Database\Seeder;

class LogisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('logistics')->truncate();

        DB::table('logistics')->insert([
            [
                "code" => "OTHER",
                "name" => "其他",
            ],
            [
                "code" => "POST",
                "name" => "中国邮政平邮",
            ],
            [
                "code" => "AIR",
                "name" => "亚风",
            ],
            [
                "code" => "CYEXP",
                "name" => "长宇",
            ],
            [
                "code" => "DTW",
                "name" => "大田",
            ],
            [
                "code" => "YUD",
                "name" => "长发",
            ],
            [
                "code" => "DFH",
                "name" => "东方汇",
            ],
            [
                "code" => "SY",
                "name" => "首业",
            ],
            [
                "code" => "YC",
                "name" => "远长",
            ],
            [
                "code" => "UNIPS",
                "name" => "发网",
            ],
            [
                "code" => "GZLT",
                "name" => "飞远配送 ",
            ],
            [
                "code" => "QFKD",
                "name" => "全峰快递",
            ],
            [
                "code" => "SCKJ",
                "name" => "成都东骏快捷",
            ],
            [
                "code" => "UAPEX",
                "name" => "全一快递",
            ],
            [
                "code" => "SCWL",
                "name" => "尚橙物流",
            ],
            [
                "code" => "GDEMS",
                "name" => "广东EMS",
            ],
            [
                "code" => "EYB",
                "name" => "EMS经济快递",
            ],
            [
                "code" => "HZABC",
                "name" => "杭州爱彼西",
            ],
            [
                "code" => "ZJS",
                "name" => "宅急送",
            ],
            [
                "code" => "FEDEX",
                "name" => "联邦快递",
            ],
            [
                "code" => "SF",
                "name" => "顺丰速运",
            ],
            [
                "code" => "LB",
                "name" => "龙邦速递",
            ],
            [
                "code" => "FAST",
                "name" => "快捷速递",
            ],
            [
                "code" => "YCT",
                "name" => "黑猫宅急便",
            ],
            [
                "code" => "NEDA",
                "name" => "港中能达",
            ],
            [
                "code" => "UC",
                "name" => "优速物流",
            ],
            [
                "code" => "LTS",
                "name" => "联昊通",
            ],
            [
                "code" => "BJCS",
                "name" => "城市100",
            ],
            [
                "code" => "ZHQKD",
                "name" => "汇强快递",
            ],
            [
                "code" => "SURE",
                "name" => "速尔",
            ],
            [
                "code" => "EMS",
                "name" => "EMS",
            ],
            [
                "code" => "YTO",
                "name" => "圆通速递",
            ],
            [
                "code" => "ZTO",
                "name" => "中通速递",
            ],
            [
                "code" => "YUNDA",
                "name" => "韵达快运",
            ],
            [
                "code" => "TTKDEX",
                "name" => "天天快递",
            ],
            [
                "code" => "BEST",
                "name" => "百世物流",
            ],
            [
                "code" => "DBL",
                "name" => "德邦物流",
            ],
            [
                "code" => "SHQ",
                "name" => "华强物流",
            ],
            [
                "code" => "HTKY",
                "name" => "汇通快运",
            ],
            [
                "code" => "CRE",
                "name" => "中铁快运",
            ],
            [
                "code" => "XFWL",
                "name" => "信丰物流",
            ],
            [
                "code" => "STO",
                "name" => "申通E物流",
            ],
            [
                "code" => "POSTB",
                "name" => "邮政国内小包",
            ],
            [
                "code" => "XB",
                "name" => "新邦物流",
            ],
            [
                "code" => "QRT",
                "name" => "全日通快递",
            ],
            [
                "code" => "GTO",
                "name" => "国通快递",
            ],
            [
                "code" => "ESB",
                "name" => "E速宝",
            ],
        ]);
    }
}
