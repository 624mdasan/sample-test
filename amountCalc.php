#!/usr/bin/php
<?php
require 'vendor/autoload.php';

class Calc {
    
    // 大人の人数
    public $adultCount;
    // 子どもの人数
    public $childCount;
    // シニアの人数
    public $seniorCount;
    // 今日の日時（DateTime）
    public $today;
    // 今年の日本の祝日
    public $holidays;
    
    // 大人
    const ADULT = 'adult';
    // 子ども
    const CHILD = 'child';
    // シニア
    const SENIOR = 'senior'; 
    
    // 大人金額
    const ADULT_AMOUNT = 1000;
    // 子ども金額
    const CHILD_AMOUNT = 500;
    // シニア金額
    const SENIOR_AMOUNT = 800;
    
    // 夕方割引金額
    const EVENING_DISCOUNT_AMOUNT = 300;
    // 団体割引
    const GROUP_DISCOUNT_RATE = 0.1;
    // 土日祝日割増し
    const HOLIDAY_RAISE_PRICE_RATE = 0.15;

    public function __construct() {
        global $argv;
        
        if ($argv[1] != self::ADULT || $argv[3] != self::CHILD || $argv[5] != self::SENIOR) {
            echo '第1引数には「adult」、第3引数には「child」、題5引数には「senior」を入力してください';
            return;
        }

        if (!(is_numeric($argv[2])) || !(is_numeric($argv[4])) || !(is_numeric($argv[6]))) {
            echo '第2,4,6引数には人数（数）を入力してください';
            return;
        }

        $this->adultCount = $argv[2];
        $this->childCount = $argv[4];
        $this->seniorCount = $argv[6];

        $this->today = new DateTime();
        $this->today->setTimeZone(new DateTimeZone('Asia/Tokyo'));

        $this->holidays = Yasumi\Yasumi::create('Japan', $this->today->format('Y'));
    }
    
    public function calc() {
        $adultAmountTotal = self::ADULT_AMOUNT * $this->adultCount;
        $childAmountTotal = self::CHILD_AMOUNT * $this->childCount;
        $seniorAmountTotal = self::SENIOR_AMOUNT * $this->seniorCount;
        
        // 合計金額を計算する
        $totalAmount = $this->calcTotalPrice($adultAmountTotal, $childAmountTotal, $seniorAmountTotal);
        
        // 団体の場合、合計金額に割引を適用する
        if ($this->isGroup()) {
            $totalAmount = round($totalAmount - ($totalAmount * self::GROUP_DISCOUNT_RATE));
            
            $isGropuDiscount = true;
            echo '団体割引' . self::GROUP_DISCOUNT_RATE * 100 . '%を適用します' . "\n";
        }
        
        echo '大人' . $this->adultCount . '名 ' . '子ども' . $this->childCount . '名' . ' シニア' . $this->seniorCount .  '名 の合計金額は' . $totalAmount . '円です' . "\n";
    }

    /**
     * 夕方割引を適用するかを判定する
     * 
     * @return bool
     */
    private function isEvening() {
        return $this->today->format('H') >= 17;
    }
    
    /**
     * 土日祝日割引を適用するか判定する
     * 
     * @return bool
     */
    private function isHoliday() {
        return $this->today->format('l') === 'Saturday'
            || $this->today->format('l') === 'Sunday'
            || $this->holidays->isHoliday($this->today);
    }
    
    /**
     * 団体割引を適用するかを判定する（子供は0.5人換算とする）
     * 
     * @return bool
     */
    private function isGroup() {
        return ($this->adultCount + ($this->childCount * 0.5) + $this->seniorCount) >= 10;
    }

    /**
     * 合計金額を計算する
     * 
     * @param int $adultAmountTotal 大人の合計金額
     * @param int $childAmountTotal 子どもの合計金額
     * @param int $seniorAmountTotal シニアの合計金額
     * @return int トータルの合計金額
     */
    private function calcTotalPrice($adultAmountTotal, $childAmountTotal, $seniorAmountTotal) {
        
        // 平日夕方料金
        if (!$this->isHoliday() && $this->isEvening()) {
            $adultAmount = $adultAmount - self::EVENING_DISCOUNT_AMOUNT * $this->adultCount;
            $childAmount = $childAmount - self::EVENING_DISCOUNT_AMOUNT * $this->childCount;
            $seniorAmount = $seniorAmount - self::EVENING_DISCOUNT_AMOUNT * $this->seniorCount;

            echo '夕方割引 -' . self::EVENING_DISCOUNT_AMOUNT. '円を適用します' . "\n";
        }

        // 休日料金
        if ($this->isHoliday()) {
            $adultAmount = $adultAmount + round(self::ADULT_AMOUNT * self::HOLIDAY_RAISE_PRICE_RATE) * $this->adultCount;
            $childAmount = $childAmount + round(self::CHILD_AMOUNT * self::HOLIDAY_RAISE_PRICE_RATE) * $this->childCount;
            $seniorAmount = $seniorAmount + round(self::SENIOR_AMOUNT * self::HOLIDAY_RAISE_PRICE_RATE) * $this->seniorCount;

            echo '休日割増' . self::HOLIDAY_RAISE_PRICE_RATE * 100 . '%を適用します' . "\n";
        }

        return (int)($adultAmountTotal + $childAmountTotal + $seniorAmountTotal);
    }
}

    
$calc = new Calc();
$calc->calc();
    



