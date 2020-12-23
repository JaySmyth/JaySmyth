<?php

namespace App\Console\Commands\RateIncrease;

use App\Models\Company;
use App\Models\DomesticRate;
use App\Models\DomesticRateDiscount;

use Illuminate\Console\Command;

class RecalcNiRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:recalc-ni-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $companies;
    protected $domesticRate;
    protected $domesticRateDiscount;
    protected $rateDetail;
    protected $rateDiscount;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->companies = [989,426,459,865,878,223,281,117,682,803,876,87,758,620,429,668,580,591,484,7,547,546,303,390,388,203,316,425,986,1024,496,503,190,160,885,917,472,850,675,200,92,789,907,622,855,827,818,582,923,961,492,313,209,518,928,934,759,978,777,94,272,436,150,951,956,453,99,98,801,1049,985,744,314,399,325,158,963,703,1035,861,721,512,815,971,358,482,1025,84,324,470,922,962,863,678,558,130,361,882,1001,184,1045,912,110,881,554,424,1066,59,738,737,1036,906,133,187,237,145,283,949,1044,750,893,873,760,766,259,304,709,947,775,1046,114,832,924,838,944,1060,448,78,931,836,843,959,562,416,1026,1029,1074,842,500,1000,1013,699,520,1002,1021,583,689,396,1010,65,847,708,277,802,976,109,918,178,359,581,1038,529,162,785,191,905,860,323,794,243,55,55,550,837,814,556,909,883,1068,764,749,393,569,507,176,715,138,674,278,410,857,952,904,483,224,935,302,264,166,479,66,733,594,305,86,697,711,1040,183,509,68,598,611,795,889,701,295,830,742,992,736,144,564,82,423,354,890,774,746,896,940,607,998,103,948,318,446,747,208,141,1056,751,1011,967,382,1027,1012,1034,960,413,972,140,821,579,1039,945,927,463,979,151,903,291,621,293,804,493,735,1030,251,292,1031,364,250,812,201,916,877,994,522,880,867,249,467,306,499,853,623,461,225,207,90,957,171,921,525,172,405,122,724,1032,430,1070,975,950,1005,116,219,977,988,1072,1006,206,763,1069,422,232,521,458,202,920,728,695,152,964,1054,528,589,270,891,266,845,894,127,127,958,136,308,427,969,285,101,60,687,833,601,809,441,937,953,919,932,443,366,456,126,320,287,772,987,755,1016,134,228,897,1055,434,1058,690,603,791,465,435,142,888,321,165,868,887,748,683,826,829,954,121,471,1067,980,572,681,700,702,984,936,125,817,602,846,604,486,22,487,95,481,839,167,970,58,73,1073,792,139,391,1050,870,248,807,968,1043,1008,497,397,526,864,1047,939,107,1004,840,480,1003,1009,501,999,743,1051,80,996,1007,333,118,914,89,816,510,543,327,680,13,67,288,995,474,312,1071,395,475,757,451,262,725,309,723];

        $this->domesticRate = new DomesticRate();
        $this->domesticRateDiscount = new DomesticRateDiscount();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->companies as $companyId) {
            $company = Company::find($companyId);
            $services = $company->services->whereIn('id', [1,2,66]);
            foreach ($services as $service) {
                $this->check($company, $service);
            }
        }
    }

    protected function check($company, $service)
    {
        $date = date('2021-01-30');
        $rate = $company->salesRateForService($service->id);
        $rateDetail = $this->domesticRate::where('rate_id', $rate['id'])
                ->where('service', $service->code)
                ->where('packaging_code', 'Package')
                ->where('area', 'ni')
                ->where('from_date', '<=', $date)
                ->where('to_date', '>=', $date)
                ->first();
        $rateDiscount = $this->domesticRateDiscount::where('company_id', $company->id)
                ->where('rate_id', $rate['id'])
                ->where('service', $service->code)
                ->where('packaging_code', 'Package')
                ->where('area', 'ni')
                ->where('from_date', '<=', $date)
                ->where('to_date', '>=', $date)
                ->first();

        if (empty($rateDetail)) {
            echo $company->company_name,' - '.$service->code." Failed\n";
        } else {
            $value = $this->calcValue($rateDetail, $rateDiscount);
            if ($value < 5.65) {
                $rateDiscount = $this->adjustMin($company->id, $rateDetail, $rateDiscount);
                echo $company->company_name,' - '.$service->code." Updated\n";
            }
        }
    }

    protected function calcValue($rateDetail, $rateDiscount)
    {
        $value = $rateDetail->first;
        $discount = $rateDiscount->first_discount ?? 0;
        return round($value - ($value * $discount)/100, 2);
    }

    protected function adjustMin($companyId, $rateDetail, $rateDiscount)
    {
        $discount = round((($rateDetail->first - 5.65)/$rateDetail->first)*100, 5);

        if (empty($rateDiscount)) {
            $rateDiscount = DomesticRateDiscount::Create([
                'company_id' =>  $companyId,
                'rate_id' =>  $rateDetail->rate_id,
                'service' =>  $rateDetail->service,
                'packaging_code' =>  $rateDetail->packaging_code,
                'area' =>  $rateDetail->area,
                'first_discount' =>  $discount,
                'others_discount' =>  0,
                'notional_discount' =>  0,
                'from_date' =>  '2021-01-01',
                'to_date' =>  '2099-12-31',
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' =>  date('Y-m-d H:i:s'),
            ]);
        } else {
            $rateDiscount->first_discount = $discount;
            $rateDiscount->save();
        }

        return $rateDiscount;
    }
}
