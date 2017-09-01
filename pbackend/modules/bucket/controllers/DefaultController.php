<?php

namespace app\modules\bucket\controllers;

//use yii\web\Controller;
use Yii;
use yii\rest\Controller;

/**
 * Default controller for the `bucket` module
 */
class DefaultController extends Controller
{    
    public function behaviors()
    {
    $behaviors = parent::behaviors();
//
//        $behaviors['authenticator'] = [
//            'class' => CompositeAuth::className(),
//            'authMethods' => [
//                HttpBearerAuth::className(),
//            ],
//        ];
//        
//       $behaviors['verbs'] = [
//              'class' => \yii\filters\VerbFilter::className(),
//              'actions' => [
//                  //'index'  => ['post'],
//                  'indexme'  => ['post'],
//                  'view'   => ['get'],
//                  'create' => ['post'],
//                  'update' => ['put'],
//                  'delete' => ['delete'],
//                  'login'  => ['post'],
//                  'userlogin'  => ['post'],
//                  'me'    =>  ['get', 'post'],
//              ],
//          ];
//        
//        $auth = $behaviors['authenticator'];
//        unset($behaviors['authenticator']);
//
        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

//        // re-add authentication filter
//        $behaviors['authenticator'] = $auth;
//        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
//        $behaviors['authenticator']['except'] = ['Indexme','userlogin','options', 'login', 
//            'signup', 'confirm', 'password-reset-request', 'password-reset-token-verification', 'password-reset'];
//
//
//        // setup access
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//            'only' => ['view', 'create', 'update', 'delete'], //only be applied to
//            'rules' => [
//                [
//                    'allow' => true,
//                    'actions' => ['view', 'create', 'update', 'delete'],
//                    'roles' => ['admin', 'manageUsers'],
//                ],
//                [
//                    'allow' => true,
//                    'actions'   => ['me'],
//                    'roles' => ['user'],
//                    //'usertype'
//                ]
//            ],
//        ];
//
        return $behaviors;
    }
    
    
     public static function showOverdueBucket($dtParam, $timezone='ind')
    {
        if ($dtParam == "" || $dtParam == "0000-00-00 00:00:00") {
            return "";
            //Date param not pass so it consider current time
            //$dtParam = date('Y-m-d H:i:s');
        }

        $todayCurrentDate = new \DateTime();
        //if($timezone=="ind") {
            $todayCurrentDate->setTimezone(new \DateTimeZone('Asia/Kolkata'));
        //}
        //date_default_timezone_set('Asia/Kolkata');
        // To stop counter after 4.30 pm india time
        //if( (($todayCurrentDate->format("H")==16 && $todayCurrentDate->format("i") > 30) || $todayCurrentDate->format("H")>16) || (($todayCurrentDate->format("H")==7 && $todayCurrentDate->format("i") < 30) || $todayCurrentDate->format("H")<7) ) {
//            return '<a href="javascript:;"> '.date("Y-m-d 16:30:00").' </a>';
//            return '<a href="javascript:;" data-original-title="Day over" data-placement="top" class="sr-tooltip"><span class="glyphicon glyphicon-hourglass"></span>&nbsp;</a>';
//            return "N/A";
        //}

        $currentTimeStamp = $todayCurrentDate->getTimestamp();

        $dtParamDate = new \DateTime();
        //if($timezone=="ind") {
            $dtParamDate->setTimezone(new \DateTimeZone('Asia/Kolkata'));
        //}
        //$dtParamDate->modify(date("Y-m-d H:i:s",strtotime(self::excludeDays($dtParam,$timezone))));
        $holidays = self::checkNoOfHolidays($dtParam, $timezone);
        if($holidays > 0) {
            $dtParamDate->modify(date("Y-m-d H:i:s",strtotime($dtParam)));

            if($dtParamDate->getTimestamp() > $currentTimeStamp)
                $dtParamDate->modify("-".$holidays." day"); // If param date is greather than curent date then decrease
            else
                $dtParamDate->modify("+".$holidays." day");
        } else {
            $dtParamDate->modify(date("Y-m-d H:i:s",strtotime($dtParam)));
        }
        $dtParamTimeStamp = $dtParamDate->getTimestamp();

        // Check if Overdue
        if($currentTimeStamp > $dtParamTimeStamp) {
            $isOverdue = 1;
        } else {
            $isOverdue = 0;
            //$diff34 = $todayCurrentDate->diff($dtParamDate);
        }

        //$date1 = $dtParamDate;
        $date2 = new \DateTime(date('Y-m-d H:i:s'));
        //if($timezone=="ind") {
            $date2->setTimezone(new \DateTimeZone('Asia/Kolkata'));
        //}

        if($isOverdue==1) {
            $startDate = $dtParamDate->format("Y-m-d H:i:s");
            $endDate = $date2->format('Y-m-d H:i:s');

            // To stop counter after 4.30 pm india time
            if( (($todayCurrentDate->format("H")==16 && $todayCurrentDate->format("i") > 30) || $todayCurrentDate->format("H")>16) || (($todayCurrentDate->format("H")==7 && $todayCurrentDate->format("i") < 30) || $todayCurrentDate->format("H")<7) ) {
                $endDate = date("Y-m-d 16:30:00");
            }

            return self::business_hours($startDate, $endDate, $dtParam, $isOverdue);
        } else {
            $startDate = $date2->format('Y-m-d H:i:s');
            $endDate = $dtParamDate->format("Y-m-d H:i:s");

            // To stop counter after 4.30 pm india time
            if( (($todayCurrentDate->format("H")==16 && $todayCurrentDate->format("i") > 30) || $todayCurrentDate->format("H")>16) || (($todayCurrentDate->format("H")==7 && $todayCurrentDate->format("i") < 30) || $todayCurrentDate->format("H")<7) ) {
                //$startDate = date("Y-m-d 16:30:00");

                if( (($todayCurrentDate->format("H")==16 && $todayCurrentDate->format("i") > 30) || $todayCurrentDate->format("H")>16) ) {
                    $startDate = $date2->format('Y-m-d 16:30:00');
                } else {
                    if($timezone=='ind')
                        $date2->modify("-1 day"); // If before 7.30 then minus one day to display proper count from last days 4.30 pm
                    $startDate = $date2->format('Y-m-d 16:30:00');
                }
            }
            else {
                $holidays = self::checkNoOfHolidays($startDate,$timezone);
                if($holidays>0) {
                    $date2->modify("-".$holidays." day");
                    $startDate = $date2->format('Y-m-d 16:30:00');
                }
            }

            return self::business_hours($startDate, $endDate, $dtParam, $isOverdue);
        }

        //determine what interval should be used - can change to weeks, months, etc
//        $interval = new \DateInterval('PT1M');
//
//        //create periods every hour between the two dates
//        $periods = new \DatePeriod($date1, $interval, $date2);
//
//        //count the number of objects within the periods
//        $hours = iterator_count($periods);
//        return $hours . ' mins';


        //accesing days
        $days = $diff34->d;
        //accesing months
        $months = $diff34->m;
        //accesing years
        $years = $diff34->y;
        //accesing hours
        $hours=$diff34->h;
        //accesing minutes
        $minutes=$diff34->i;
        //accesing seconds
        $seconds=$diff34->s;

        $overdueString = "";
        $overdueArray  = array();
        if($months > 0) {
            if($months>1)
                $overdueArray[] = $months . ' months';
            else
                $overdueArray[] = $months . ' month';
        }
        if($days > 0) {
            if($days>1)
                $overdueArray[] = $days . ' days';
            else
                $overdueArray[] = $days . ' day';
        }
        if($hours > 0) {
            if($hours>1)
                $overdueArray[] = $hours . ' hours';
            else
                $overdueArray[] = $hours . ' hour';
        }
        if($minutes > 0) {
            if($minutes>1)
                $overdueArray[] = $minutes . ' mins';
            else
                $overdueArray[] = $minutes . ' min';
        }

        $overdueString = implode(", ",$overdueArray);

        if($isOverdue == 1) {
            $t = $overdueString;
            /*$t = '<a href="javascript:;">';
            $t .= '<p>Overdue by: '. $overdueString .'</p>';
            $t.= '<span data-original-title="Overdue by '.$overdueString.'<br/>'.$dtParamDate->format("Y-m-d H:i:s").'" title="" data-placement="top" style="width:100%" class="pull-left sr-tooltip">
                <div style="background:#E9544D;height:15px;" class="progress">
                    <div style="width:100%;background:#E9544D" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-warning progress-bar-striped"> <span class="sr-only">60% Complete (warning)</span> </div>
            </div>
            </span>';
            $t .= '</a>';*/
        }
        else
        {
            $t = $overdueString;
            /*$t = '<a href="javascript:;">';
            $t .= '<p>'. $overdueString .' left</p>';
            $t.= '<span data-original-title="'. $overdueString .' left<br/>'.$dtParamDate->format("Y-m-d H:i:s").'" title="" data-placement="top" style="width:100%" class="pull-left sr-tooltip">
                <div style="background:#359210;height:15px;" class="progress">
                  <div style="width:100%;background:#E5AF0A" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-warning progress-bar-striped"> <span class="sr-only">60% Complete (warning)</span> </div>
                </div>
                </span>';
            $t .= '</a>';*/
        }

        /*$t= '<center>';
            $t.= '<br /><div style="background-color:green;color:#fff;padding:10px;width:600px;font-size:16px">
            <b>The difference between '.$todayCurrentDate->format("Y-m-d H:i:s").' and '.$dtParamDate->format("Y-m-d H:i:s").'.
            <br />is: ' . $days . ' day(s), ' . $months . ' month(s), ' . $years . ' year(s), '.$hours.' hour(s),
            '.$minutes.' minute(s), '.$seconds.' second(s) </b>
            </div><br />';
            $t.= '</center>';
            return $t;
             */

        return $t;
    }
    
    
    public static function checkNoOfHolidays($dtParam,$timezone='ind')
    {
        if($timezone == 'ind') {
            $holidayArray = self::fh("ind");
        } else {
            $holidayArray = self::fh("aus");
        }

        $start = new \DateTime();
        //$start->setTimezone(new \DateTimeZone('Asia/Kolkata'));
        $start->modify(date("Y-m-d",strtotime($dtParam)));

        $end   = new \DateTime(date('Y-m-d'));
        //$interval = \DateInterval::createFromDateString('1 day');
        $interval = new \DateInterval( "P1D" );

        $increaseDescrease = '+';

        $period = new \DatePeriod($start, $interval, $end);
        // Check if date is greater them todays date then need to decrease otherwise increase
        if(date("Y-m-d",strtotime($dtParam))>date("Y-m-d")) {
            $period = new \DatePeriod($end, $interval, $start);
            $increaseDescrease = '-';
        }

        $no = 0;
        if($timezone == 'ind') {
            foreach ($period as $dt)
            {
                if ($dt->format('N') == 7)
                {
                    $no++;
                }
                //else if ($dt->format('N') == 6 && $dt->format("Y-m-d") == date("Y-m-d",strtotime("last sat of ".  date("F Y", strtotime($dtParam)))) ) {
                else if ($dt->format('N') == 6 && $dt->format("Y-m-d") == date("Y-m-d",strtotime("last sat of ".  date("F Y", strtotime($dt->format('Y-m-d'))))) && $dt->format("Y-m-d") == $end->format("Y-m-d")) {
                    // Added below condition 28/04/2017 as it was showing wrong
                    //$dt->format("Y-m-d") == $end->format("Y-m-d")
                    $no++;
                }
                //if (in_array(date("Y-m-d", strtotime($dtParam)), $holidayArray)) {
                if (in_array($dt->format("Y-m-d"), $holidayArray)) {
                // Changed if on 11-10-2016 as task is displayed overdue if tomorrow is holiday and task is added after 16:30
                //if (in_array($dt->format("Y-m-d"), $holidayArray) && (date("H")<16 || (date("H")<=16 && date("i")<31) )) {
                    $no++;
                }
            }
        } else {
            foreach ($period as $dt)
            {
                if ($dt->format('N') == 7 || $dt->format('N') == 6)
                {
                    $no++;
                }
                if (in_array(date("Y-m-d", strtotime($dtParam)), $holidayArray)) {
                    $no++;
                }
            }
        }

        if($no == 0) {
            if ($start->format('N') == 7)
            {
                $no++;
            }
            else if ($start->format('N') == 6 && $start->format("Y-m-d") == date("Y-m-d",strtotime("last sat of ".  date("F Y", strtotime($dtParam)))) ) {
                $no++;
            }
            if (in_array(date("Y-m-d", strtotime($dtParam)), $holidayArray)) {
                $no++;
            }
        }

        return $no;
    }
    
    public static function fh($country = "ind")
            {
        $holidaysArray = array();
        // Added by MB - 11 Oct, 2016
        // Data is added in session to improve page performance, as in every time calculation its running query
        $sessionName = 'COMPANYHOLIDAYLIST'. strtoupper($country);
//        if(Yii::app()->session->contains($sessionName)){
//            $holidaysArray = Yii::app()->session->get($sessionName);
//        } else {
//            // Change by MB - 11 Nov, 2016
//            // Holiday should consider only Indian Holiday's
//            $cId = 76;
//            /*if($country == "ind") {
//                $cId = 76;
//            } else {
//                $cId = 9;
//            }*/
//
//            $criteria = new CDbCriteria();
//            $criteria->select = 'id,holiday_date';
//            $criteria->compare('holiday_country',$cId);
//            $criteria->compare('is_active', 1);
//            //$criteria->addCondition('DATE_FORMAT(holiday_date,"%Y") = ' . date('Y'));
//            $holidays = CompanyHolidays::model()->findAll($criteria);
//            $holidaysArray = CHtml::listData($holidays, 'id','holiday_date');
//            Yii::app()->session->add($sessionName,$holidaysArray);
//        }
        return $holidaysArray;
    }

    /**
     * Function excludeHolidayIndia
     * sort function exhi
     * Add $operator parameter and set value whene days calculated
     */
    public static function exhi($dtParam,$operator = '+') {
        $holidayArray = self::fh("ind");
//        echo "<pre>";
//        print_r($holidayArray);
//        exit;
        $addDay = 0;
        //Exclude Sunday
        if (date("w", strtotime($dtParam)) == 0) {
            $addDay = 1;
        //Exclude Holiday From Database
        } else if (in_array(date("Y-m-d", strtotime($dtParam)), $holidayArray)) {
            $addDay = 1;
        //Exclude Last Saturday
        } else if(date("d-m-Y",strtotime("last sat of ".  date("F Y", strtotime($dtParam)))) == date("d-m-Y",  strtotime($dtParam))) {
            $addDay = 1;
        }
        if($addDay == 1 ) {
            //$dtParam = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($dtParam)));
            $dtParam = date('Y-m-d H:i:s', strtotime($operator ." 1 day", strtotime($dtParam)));
            $dtParam = self::exhi($dtParam);
        }
        return $dtParam;
    }

    /**
     * Function excludeHolidayAus
     * sort exha
     */
    public static function exha($dtParam) {
        $holidayArray = self::fh("aus");
        //Exclude Saturday Sunday
        if (date("w", strtotime($dtParam)) == 0 || date("w", strtotime($dtParam)) == 6) {
            $dtParam = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($dtParam)));
            $dtParam = self::exha($dtParam);
        //Exclude Holiday
        } else if (in_array(date("Y-m-d", strtotime($dtParam)), $holidayArray)) {
            $dtParam = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($dtParam)));
            $dtParam = self::exha($dtParam);
        }
        return $dtParam;
    }

    /**
     * function excludeHolidayCount
     * sortfunction exhc
     */

    public static function exhc($dTOa,$dtParam,$country = "ind") {
        for($i=1;$i<=$dTOa;$i++) {
            if($country == "ind"){
                $dtParam = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($dtParam)));
                $dtParam = self::exhi($dtParam);
            } else {
                $dtParam = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($dtParam)));
                $dtParam = self::exha($dtParam);
            }
        }
        return $dtParam;
    }

/**
 *
 * @param type $dTOa == Days To Add
 * @param type $dORh == Days Or Hours
 * @param type $dtParam == Date Param
 * @param type $tBase = aus / ind
 * @return type date
 */
    public static function getAusToIndiaDateTime($dTOa, $dORh = 'days', $dtParam = "", $tBase = "aus") {

        //echo "AUS ". $dtParam."&nbsp;&nbsp;&nbsp;&nbsp;";
        if ($tBase == "ind") {
            $todayStart = date('Y-m-d 7:30:00');
            $todayEnd = date('Y-m-d 16:30:00');
            $orgDtParam = $dtParam;
            if ($dtParam == "") {
                //Date param not pass so it consider current time
                //$dtParam = date('Y-m-d H:i:s');

                // Added on 10 Mar, 2017
                $todayDate = new \DateTime();
                $todayDate->setTimezone(new \DateTimeZone('Asia/Kolkata'));

                // Added on 27 June, 2017
                // If today is holiday then start from next business day 07:30 AM.
                $afterHolidays = self::exhi($todayDate->format("Y-m-d H:i:s"));
                if($afterHolidays != $todayDate->format("Y-m-d H:i:s")) { 
                    // If today is holiday then start from 7.30 am of next business day
                    $todayDate->modify(date("Y-m-d 07:30:00",strtotime($afterHolidays)));
                }

                if($todayDate->format('Y-m-d') != date('Y-m-d')) {
                    $dtParam = $todayDate->format('Y-m-d H:i:s');

                    $todayStart = $todayDate->format('Y-m-d 7:30:00');
                    $todayEnd = $todayDate->format('Y-m-d 16:30:00');
                } else {
                    $orgDtParam=1;
                    //$dtParam = date('Y-m-d H:i:s');
                    $dtParam = $todayDate->format('Y-m-d H:i:s');
                }
            } else {
                $convertDate = new \DateTime();
                $convertDate->modify($dtParam);
                $convertDate->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                //$dtParam = $convertDate->format('Y-m-d H:i:s');
                $dtParam = self::exhi($convertDate->format('Y-m-d H:i:s'));

                $todayStart = $convertDate->format('Y-m-d 7:30:00');
                $todayEnd = $convertDate->format('Y-m-d 16:30:00');
            }
            //echo "IND ". $dtParam."&nbsp;&nbsp;&nbsp;&nbsp;";
            //print_r($dtParam);exit;

            //$todaydateTimeFixedStartHours = self::exhi(date('Y-m-d 7:30:00'));
            //$todaydateTimeFixedEndHours = self::exhi(date('Y-m-d 16:30:00'));

            $todaydateTimeFixedStartHours = self::exhi($todayStart);
            $todaydateTimeFixedEndHours = self::exhi($todayEnd);

            //echo "Start ". $todaydateTimeFixedStartHours."&nbsp;&nbsp;&nbsp;&nbsp;";
            //echo "End ". $todaydateTimeFixedEndHours."&nbsp;&nbsp;&nbsp;&nbsp;";

            if ($orgDtParam == "") {
                $dT = $dT2 = clone $todayDate;
            } else {
                // Already converted to India timezone(line #8898) so no need to change again.
                $dT = new \DateTime($dtParam);
                //$dT->setTimezone(new \DateTimeZone('Asia/Kolkata'));
                $dT2 = new \DateTime($dtParam);
                //$dT2->setTimezone(new \DateTimeZone('Asia/Kolkata'));
            }

            if($dTOa == 0){
                return $dT->format('Y-m-d H:i:s');
            }
            //use for practice milestone
            if ($dORh == "days") {
                //$dT->modify('+' . $dTOa . ' day');
                //$dT->modify(self::exhi($dT->format('Y-m-d H:i:s')));
                $rDt = self::exhc($dTOa,$dT->format('Y-m-d 16:30:00'),$tBase);
                $dT->modify(date($rDt));
            } else {
                /**
                 * Time trigger after working hours
                 * India Time After 4
                 */
                if ($dT->format("H") >= 16) {
                    /*
                    *  India Time Before 4.30
                    */
                    if ($dT->format("H") == 16 && $dT->format("i") <= 30) {
                        //Nothing to Do
                    } else {
                        $dT->modify(
                            self::exhi(date('Y-m-d 7:30:00',strtotime("+1 day", strtotime($dtParam)))));
                        //$dT->modify(self::exhi($dT->format('Y-m-d H:i:s')));
                        //echo $dT->format('Y-m-d H:i:s');exit;
                    }
                }

                //Time trigger before working hors
                //if ($dT->format("H") <= 7 && $dT->format("i") <= 30) {
                if ($dT->format("H") <= 7) {

                    if ($dT->format("H") == 7 && $dT->format("i") >= 30) {

                    } else {
                        $dT->modify(date($dT->format('Y-m-d 7:30:00')));
                    }
                    $dT->modify(self::exhi($dT->format('Y-m-d H:i:s')));
                    //staff due time
                    //$dT->format('Y-m-d 7:30:00');

                    //Count Days based on working hours
                    if (is_float($dTOa / 9) && floor($dTOa / 9) > 0) {
                        //$dT->modify('+' . floor($dTOa) . 'day');
                        //Add days
                        $rDt = self::exhc(floor($dTOa/9),$dT->format('Y-m-d H:i:s'),$tBase);
                        $dT->modify(date($rDt));
                        //Add hours
                        $dT->modify('+' . ($dTOa % 9) . 'hour');
                        $dT->modify(self::exhi($dT->format('Y-m-d H:i:s')));
                    } else if(($dTOa % 9) == 0) {
                        $dT->modify('+' . 9 . 'hour');
                    } else {
                        $dT->modify('+' . ($dTOa % 9) . 'hour');
                        //echo $dT->format('Y-m-d H:i:s');exit("hi");
                    }
                } else if ($dT->format("H") <= 16) {
                    //End of day
                    $dT2->modify($todaydateTimeFixedEndHours);

                    //today diffrance with adding hours
                    $interval = date_diff($dT, $dT2);
                    $tmpDateDiff = $interval->format("%h,%i");
                    $tmpDateDiff = explode(",", $tmpDateDiff);

                    //get remaining hors today
                    $hoursAdd = $tmpDateDiff[0] - $dTOa;
                    //$minutesAdd = $tmpDateDiff[1] - 30;
                    $minutesAdd = $tmpDateDiff[1];

                    if ($hoursAdd >= 0) {
                        $dT->modify('+' . $dTOa . 'hour');
                        /**
                         * Only hours request come for this function so no need to add minuts
                         */
                        //$dT->modify('+' . $minutesAdd . 'minutes');
                    } else if ($minutesAdd >= 0 && $hoursAdd == 0) {
                        //in case minutes pass as args howeer below is not in use
                        $dT->modify('+' . $minutesAdd . 'minutes');
                    } else {
                        //exit($hoursAdd);
                        $hoursAdd = $hoursAdd * -1;
                        $minutesAdd = $minutesAdd * -1;
                        //Count Days based on working hours
                        if (is_float($hoursAdd / 9) && floor($hoursAdd / 9) > 0) {
                            $dT->modify($todaydateTimeFixedStartHours);
                            //$dT->modify('+' . floor($hoursAdd / 9) + 1 . 'day');
                            //$dT->modify(self::exhi($dT->format('Y-m-d H:i:s')));
                            $rDt = self::exhc(floor($hoursAdd / 9) + 1, $dT->format('Y-m-d H:i:s'),$tBase);
                            $dT->modify(date($rDt));
                            $dT->modify('+'.($hoursAdd % 9). 'hour');
                            $dT->modify('+' . $minutesAdd . 'minutes');
                        } else {
                            $dT->modify($todaydateTimeFixedStartHours);
                            $dT->modify('+ 1 day');
                            $dT->modify(self::exhi($dT->format('Y-m-d H:i:s')));

                            // MB - 12 June, 2016 - Resolve bug when trying to submit at 2:00PM to 2:59PM IST then it was having issue in calculations so added this if condition, before it was having only else portion's code (without if)
                            // Logic is added days if hours are need to add more than 9
                            if($hoursAdd/9>0) {
                                for($i=1;$i<($hoursAdd/9);$i++) {
                                    $dT->modify('+1 day');
                                    $hoursAdd = $hoursAdd - 9;
                                    $dT->modify(self::exhi($dT->format('Y-m-d H:i:s')));
                                }
                                if($hoursAdd>0)
                                $dT->modify('+' . $hoursAdd . 'hour');
                                $dT->modify('+' . $minutesAdd . 'minutes');
                            } else {
                                $dT->modify('+' . $hoursAdd . 'hour');
                                $dT->modify('+' . $minutesAdd . 'minutes');
                            }
                        }
                    }
                    //Add remainer hours for today
                    //$dT->modify('+' . $todayDiffHoursMins . 'hour');
                    //$todayDiffHours = 16 - $dT->format("H");
                    //$todayDiffMi = 16 - $dT->format("H");
                }

                //$dT->modify('+' . $dTOa . ' ' . $dORh);

                /* if ($dT2->format("H") <= 16 && $dT2->format("i") < 30) {
                  $dT2->format('Y-m-d 16:30:00');
                  } */
                //$dT->diff($dT2);
                //print_r($dT);//->hour ;
                //echo "Result " . $dT->y . " years, " . $dT->m." months, ".$dT->d." days ";
            }
        } else {
            if ($dtParam == "") {
                $dtParam = date('Y-m-d H:i:s');
            }

            //$todaydateTimeFixedStartHours = self::exhi(date('Y-m-d 7:30:00'));
            //$todaydateTimeFixedEndHours = self::exhi(date('Y-m-d 16:30:00'));
            $dT = new \DateTime($dtParam);
            //$dT->setTimezone(new \DateTimeZone('Asia/Kolkata'));
            //$dT2 = new \DateTime($dtParam);
            //$dT2->setTimezone(new \DateTimeZone('Asia/Kolkata'));

            if($dTOa == 0){
                return $dT->format('Y-m-d H:i:s');
            }

            //use for practice milestone
            if ($dORh == "days") {
                //$dT->modify('+' . self::exhc($dTOa,$dtParam,$tBase) . ' day');
                //$dT->modify(self::exha($dT->format('Y-m-d H:i:s')));
                $rDt = self::exhc($dTOa,$dtParam,$tBase);
                $dT->modify(date("Y-m-d 16:30:00",strtotime($rDt)));
            }
            //print_r($dtParam);exit;
        }

        return $dT->format('Y-m-d H:i:s');
    }
    
    public static function business_hours($start, $end, $originalDate, $isOverdue){

        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);
        $periodInterval = new \DateInterval( "PT1H" );

        $period = new \DatePeriod( $startDate, $periodInterval, $endDate );
        $count = 0;

        foreach($period as $date)
        {
            $startofday = clone $date;
            $startofday->setTime(7,30); // 07:30 AM

            $endofday = clone $date;
            $endofday->setTime(16,30); // 04:30 PM

            // Need to verify last Saturday logic
            // Last saturday and sudnays are excluded before call this function
            //if($date > $startofday && $date <= $endofday && !in_array($date->format('l'), array('Sunday')) && $date->format("Y-m-d") != date("Y-m-d",strtotime("last sat of ".  date("F Y", $date->getTimestamp())))){
            if($date > $startofday && $date <= $endofday) {
                $count++;
            }
        }

        //Get seconds of Start time
        $start_d = date("Y-m-d H:00:00", strtotime($start));
        $start_d_seconds = strtotime($start_d);
        $start_t_seconds = strtotime($start);
        $start_seconds = $start_t_seconds - $start_d_seconds; //minutes and seconds

        //Get seconds of End time
        $end_d = date("Y-m-d H:00:00", strtotime($end));
        $end_d_seconds = strtotime($end_d);
        $end_t_seconds = strtotime($end);
        $end_seconds = $end_t_seconds - $end_d_seconds;

        $diff = $end_seconds-$start_seconds;

        //if($diff!=0 && $diff < 0):
        if($diff!=0):
            $count--;
        endif;

        //$total_min_sec = date('i:s',$diff);
        //return $count .":".$total_min_sec;

        $total_min_sec1 = date('i',$diff);
        //$total_min_sec2 = date('s',$diff);


        $overdueString="";
        if($count>0) {
            if($count>18) { // if greater than 2 working days then we will display in days otherwise working hours.
                $days = floor($count/9);
                $overdueString = $days." days ";
                $count= $count -($days*9);
            }
            $overdueString .= $count." hours";
        }
        if($total_min_sec1>0) {
            if($overdueString=="")
                $overdueString .= $total_min_sec1." mins";
            else
                $overdueString .= " ".$total_min_sec1." mins";
        }
//        if($total_min_sec2>0) {
//            $overdueString .= " ". $total_min_sec2." secs";
//        }

        if($overdueString == "") {
            $overdueString = '0 min';
        }

        $t = "";
        if($isOverdue == 1) {
            $t = $overdueString;
//            $t = '<a href="javascript:;">';
//            $t .= '<p>Overdue by: '. $overdueString .'</p>';
//            $t.= '<span data-original-title="Overdue by '.$overdueString.'<br/>'. self::convertDateFormat($originalDate, 'd/m/Y H:i:s').'" title="" data-placement="top" style="width:100%" class="pull-left sr-tooltip">
//                <div style="background:#E9544D;height:15px;" class="progress">
//                    <div style="width:100%;background:#E9544D" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-warning progress-bar-striped"> <span class="sr-only">60% Complete (warning)</span> </div>
//            </div>
//            </span>';
//            $t .= '</a>';
        }
        else
        {
            $t = $overdueString;
//            $t = '<a href="javascript:;">';
//            $t .= '<p>'. $overdueString .' left</p>';
//            $t.= '<span data-original-title="'. $overdueString .' left<br/>'. self::convertDateFormat($originalDate, 'd/m/Y H:i:s') .'" title="" data-placement="top" style="width:100%" class="pull-left sr-tooltip">
//                <div style="background:#359210;height:15px;" class="progress">
//                  <div style="width:100%;background:#E5AF0A" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-warning progress-bar-striped"> <span class="sr-only">60% Complete (warning)</span> </div>
//                </div>
//                </span>';
//            $t .= '</a>';
        }

        return $t;
    }
    
    public static function convertDateFormat($date, $format = 'd/m/Y') {
        $date = str_replace("/", "-", $date);
        if (strtotime($date) === false || $date == '' || $date == '0000-00-00 00:00:00' || $date == '0000-00-00')
            return "";
        else
            return date($format, strtotime($date));
    }

    
    public static function generateJobName($jobData) {
        //echo '<pre>';         print_r($jobData);        exit;
        if ($jobData['service_division'] == 4 && $jobData['job_type_id'] == 23) {
            //$showJobname = $jobData->jobName = $jobData['client_name'] . " - Setup In Class Super";
            $showJobname = $jobData['client_name'] . " - Setup In Class Super";
        }
        //else if ($jobData['service_division'] == 5 && in_array ($jobData['job_type_id'], array(29))) {
        else if ($jobData['service_division'] == 5) {
            $showJobname = (isset($jobData['client_name'])) ? $jobData['client_name'] : "";
            $showJobname .=  " - " . date('M/Y') . (isset($jobData['job_type_string']) ?  " - " . $jobData['job_type_string'] : "");
        }
        else if ($jobData['service_division'] == 3 && in_array ($jobData['job_type_id'], array(21))) {
            $showJobname = (isset($jobData['client_name'])) ? $jobData['client_name'] : "";
            $showJobname .= (isset($jobData['subform_name'])) ? " - ".$jobData['subform_name'] : "";
            $showJobname .= (isset($jobData['job_type_string']) ?  " - " . $jobData['job_type_string'] : "");
        }
        else if ($jobData['service_division'] == 13 && in_array ($jobData['job_type_id'], array(7))) {
            $showJobname = (isset($jobData['client_name'])) ? $jobData['client_name'] : "";
            $showJobname .= (isset($jobData['job_type_string']) ?  " - " . $jobData['job_type_string'] : "");
        }
        else if ($jobData['service_division'] == 3 && in_array ($jobData['job_type_id'], array(33))) {
            $showJobname = (isset($jobData['client_name'])) ? $jobData['client_name'] : "";
            $showJobname .= (isset($jobData['job_type_string']) ?  " - " . $jobData['job_type_string'] : "");
            //$showJobname .= (isset($jobData->asic_service_type) ?  " " . $jobData->asic_service_type : "");
        }
        else if ($jobData['service_division'] == 17 && in_array ($jobData['job_type_id'], array(34,35,36,37,38,39))) { // TMS type job
            $showJobname = (isset($jobData['client_name'])) ? $jobData['client_name'] : "";
            $showJobname .= (isset($jobData['job_type_string']) ?  " - " . $jobData['job_type_string'] : "");
            //$showJobname .= (isset($jobData->asic_service_type) ?  " " . $jobData->asic_service_type : "");
        }
        else {
            $showJobname = (isset($jobData['client_name'])) ? $jobData['client_name'] : "";
            $showJobname .=  " - " . $jobData['period'] . (isset($jobData['job_type_string']) ?  " - " . $jobData['job_type_string'] : "");
        }

        return $showJobname;
    }
    
//    public static function searchForId($id, $array) {
//        foreach ($array as $key => $val) {
//            if ($val['id'] === $id) {
//                return $key;
//            }
//        }
//        return null;
//    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $FieldsMain = array();
        
        $FieldsMain[] = " p.company_name,j.service_division,j.job_type_id,c.client_name,j.period, "
                . " tb.is_urgent, tb.is_knockback, tb.is_practice_urgent, j.milestone_date ";
        
        // First Query Created On
        $FieldsMain[] = "(SELECT
                query_milestone_date
              FROM turnaround_buckets
              WHERE job_id = t.job_id
                  AND query_type = \"P\"
                  AND is_active = \"1\"
              ORDER BY id ASC
              LIMIT 1) AS first_query_created_on";
        
        
        //Less Then Query Created        
        $FieldsMain[] = "(CASE "
                            . "WHEN j.milestone_date IS NULL "
                            . "THEN (SELECT query_milestone_date "
                                . "FROM turnaround_buckets "
                                . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) "
                            . "WHEN j.milestone_date = \"0000-00-00 00:00:00\" "
                            . "THEN (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) "
                            . "WHEN (SELECT query_milestone_date FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) < j.milestone_date "
                            . "THEN (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) "
                            . "WHEN (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) > j.milestone_date "
                            . "THEN j.milestone_date "
                            . "WHEN (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) = j.milestone_date "
                            . "THEN j.milestone_date "
                            . "ELSE (SELECT query_milestone_date "
                                    . "FROM turnaround_buckets "
                                    . "WHERE job_id=t.job_id AND query_type=\"P\" AND is_active=\"1\" "
                                    . "ORDER BY id ASC LIMIT 1) "
                            . "END) AS lessDate";
        
        $FieldsMain[] = "(SELECT
                            COUNT(DISTINCT q.id)
                          FROM query_master q
                            JOIN jobs j
                              ON j.job_id = q.job_id
                            LEFT JOIN job_type jt
                              ON jt.id = j.job_type_id
                          WHERE j.practice_id = q.practice_id
                              AND q.query_type = \"P\"
                              AND q.resolved = 0
                              AND q.status = 1
                              AND j.is_active = 1
                              AND j.is_deleted = 0
                              AND q.job_id = t.job_id
                              AND q.is_staff = 1
                              AND jt.display_in_practice = \"yes\"
                              AND (SELECT
                                     COUNT(qr.id)
                                   FROM query_master_reply qr
                                   WHERE qr.query_id = q.id
                                       AND qr.query_reply_type = \"P\") = 0) AS querysentcount";
        
        $FieldsMain[] = "(SELECT
                            COUNT(DISTINCT q.id)
                          FROM query_master q
                            JOIN jobs j
                              ON j.job_id = q.job_id
                            LEFT JOIN job_type jt
                              ON jt.id = j.job_type_id
                          WHERE j.practice_id = q.practice_id
                              AND q.query_type = \"P\"
                              AND q.resolved = 0
                              AND q.status = 1
                              AND j.is_active = 1
                              AND j.is_deleted = 0
                              AND q.job_id = t.job_id
                              AND jt.display_in_practice = \"yes\"
                              AND q.pending_for_practice = 0
                              AND (((SELECT
                                       COUNT(qr.id)
                                     FROM query_master_reply qr
                                     WHERE qr.query_id = q.id
                                         AND qr.query_reply_type = \"P\") = 1
                                    AND q.is_staff = 0)
                                    OR (SELECT
                                          qr.is_staff
                                        FROM query_master_reply qr
                                        WHERE qr.query_id = q.id
                                            AND qr.query_reply_type = \"P\"
                                        ORDER BY qr.id DESC
                                        LIMIT 1) = 0)) AS pendingcount";
        
        $FieldsMain[] = "(SELECT
                            COUNT(DISTINCT q.id)
                          FROM query_master q
                            JOIN jobs j
                              ON j.job_id = q.job_id
                            LEFT JOIN job_type jt
                              ON jt.id = j.job_type_id
                          WHERE j.practice_id = q.practice_id
                              AND q.query_type = \"P\"
                              AND q.resolved = 0
                              AND q.status = 1
                              AND j.is_active = 1
                              AND j.is_deleted = 0
                              AND q.job_id = t.job_id
                              AND jt.display_in_practice = \"yes\"
                              AND ((SELECT
                                      qr.is_staff
                                    FROM query_master_reply qr
                                    WHERE qr.query_id = q.id
                                        AND q.job_id = t.job_id
                                        AND qr.query_reply_type = \"P\"
                                    ORDER BY qr.id DESC
                                    LIMIT 1) = 1)) AS repliedcount1";
        
        $FieldsMain[] = "(SELECT
                            COUNT(DISTINCT q.id)
                          FROM query_master q
                            JOIN jobs j
                              ON j.job_id = q.job_id
                            LEFT JOIN job_type jt
                              ON jt.id = j.job_type_id
                          WHERE j.practice_id = q.practice_id
                              AND q.query_type = \"P\"
                              AND q.resolved = 0
                              AND q.status = 1
                              AND j.is_active = 1
                              AND j.is_deleted = 0
                              AND q.job_id = t.job_id
                              AND jt.display_in_practice = \"yes\"
                              AND ((SELECT
                                      qr.is_staff
                                    FROM query_master_reply qr
                                    WHERE qr.query_id = q.id
                                        AND qr.query_reply_type = \"P\"
                                        AND q.job_id = t.job_id
                                    ORDER BY qr.id DESC
                                    LIMIT 1) = 0
                                   AND q.pending_for_practice = 1)) AS repliedcount2";
        
        $FieldsMain[] = "(SELECT
                            COUNT(DISTINCT q.id)
                          FROM query_master q
                          WHERE q.resolved = 0
                              AND q.job_id = j.job_id
                              AND q.ans_type = 7) AS transactionQueryCount";
        
        $TableJoins = array();
        $TableJoins[] = "INNER JOIN jobs j
                          ON t.job_id = j.job_id
                            AND j.is_active = 1
                            AND j.is_deleted = 0";
                            
        $TableJoins[] = "INNER JOIN clients c
                          ON t.client_id = c.id
                            AND c.is_active = 1
                            AND c.is_deleted = 0";
        
        $TableJoins[] = "INNER JOIN turnaround_buckets tb
                          ON tb.query_id = t.id
                            AND tb.query_type = \"P\"
                            AND tb.is_active = 1";
        
        $TableJoins[] = "INNER JOIN practices p
                          ON t.practice_id = p.id";
        
        $Conditions = "(((j.job_submitted = 'Y')
                              AND (j.job_status_id <> '7'))
                             AND (t.query_type = 'P'))
                          AND (t.resolved = '0')";
        
        $GroupBy    =  "  t.job_id";
        $OrderBy    =  "  tb.is_practice_urgent DESC, "
                            . "tb.is_urgent DESC, tb.is_knockback DESC, "
                            . "lessDate ASC, tb.created_on ASC";
        
        $TableName = "`query_master` `t`";
                
        $SQL = "SELECT "
                    . implode(",", $FieldsMain)
                    . " FROM ".$TableName
                    . implode(" ",$TableJoins)
                        ." WHERE ".$Conditions
                        ." GROUP BY ".$GroupBy
                        ." ORDER BY ".$OrderBy
                ;
        
        
        
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($SQL);
        $result = $command->queryAll();
        
        $SQL = "SELECT t.* FROM job_type t";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($SQL);
        $result2 = $command->queryAll();
        //echo '<pre>';print_r($result2 );exit;
        
        foreach ($result as $key=>$val) {
            //Search Job Type Name
            $key2 = array_search($val['job_type_id'], array_column($result2, 'id'));
            $result[$key]['job_type_string'] = $result2[$key2]['name'];
            $result[$key]['subform_name'] = "";   
            $result[$key]['job_name'] = self::generateJobName($result[$key]);
            //echo '<pre>';print_r(self::generateJobName($result[$key]));
            //exit;
            //echo '<pre>';print_r($result[$key]['lessDate']);
            $result[$key]['first_query_created_on_string'] = self::showOverdueBucket($result[$key]['first_query_created_on']);
            $result[$key]['lessDate_string'] = self::showOverdueBucket($result[$key]['lessDate']);            
            $result[$key]['priority'] = $key+1;            
        }
        //showOverdueBucket
        
        return $result;        
    }
}
