<?php
namespace common\models;

use Yii;
use yii\base\Model;
use \DTS\eBaySDK\Parser;
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;
use \DTS\eBaySDK\Trading\Services as TradSer;
use \DTS\eBaySDK\Trading\Types as TradType;
use \DTS\eBaySDK\Trading\Enums as TradEnums;

class EbayForm extends Model
{
    private $cacheTime = 2678400;
    public $queryText;
    public $queryCategory;
    public $queryMinPrice;
    public $queryMaxPrice;
    public $querySort;
    public $queryBrand;
    public $queryState;
    private $config;

    /**
     * EbayForm constructor.
     */
    public function __construct() {
        $this->initConfig();
    }

    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['queryText'], 'required'],
//            // email has to be a valid email address
//            ['email', 'email'],
//            // verifyCode needs to be entered correctly
//            ['verifyCode', 'captcha'],
        ];
    }

    private function initConfig() {
        $this->config = require __DIR__ . '/../../configuration.php';
    }

    public function getItems() {
        $items = Yii::$app->getCache()->get($this->queryText);
        if($items !== false) {
            return $items;
        }
        $service = new Services\FindingService(array(
            'appId' => $this->config['production']['appId'],
            'apiVersion' => $this->config['findingApiVersion'],
            'globalId' => 'EBAY-RU'
        ));
        $request = new Types\FindItemsAdvancedRequest();
        $request->keywords = $this->queryText;
        if(!empty($this->queryCategory)){
            $request->categoryId = array($this->queryCategory);
        }else{
            $request->categoryId = array('6030');
        }
        $itemFilter = new Types\ItemFilter();
        $itemFilter->name = 'ListingType';
        $itemFilter->value[] = 'AuctionWithBIN';
        $itemFilter->value[] = 'FixedPrice';
        $request->itemFilter[] = $itemFilter;
        if($this->queryMinPrice > 0) {
            $request->itemFilter[] = new Types\ItemFilter(array(
                'name' => 'MinPrice',
                'value' => array($this->queryMinPrice)
            ));
        }
        if($this->queryMaxPrice > 0) {
            $request->itemFilter[] = new Types\ItemFilter(array(
                'name' => 'MaxPrice',
                'value' => array($this->queryMaxPrice)
            ));
        }
//        if(empty($this->querySort)) {
//            $request->sortOrder = $this->querySort;
//        }

        $response = $service->findItemsAdvanced($request);

        if ($response->ack !== 'Failure') {
            $arrayresp = $response->toArray();
            Yii::$app->getCache()->set($this->queryText, $arrayresp, 120);
            return $arrayresp;
        }
    }

    public function getCategories() {
        $categories = Yii::$app->getCache()->get('Lolcategory');
        if($categories !== false) {
            return $categories;
        }
        $service = new TradSer\TradingService(array(
            'apiVersion' => $this->config['tradingApiVersion'],
            'siteId' => Constants\SiteIds::US
        ));
        $catconfig = $this->getCategoryConfig();
        foreach ($catconfig as $name => $cat) {
            foreach ($cat as $key => $value) {
                $request = new TradType\GetCategoriesRequestType();
                $request->CategorySiteID = '215';
                $request->CategoryParent = array($value);
                $request->RequesterCredentials = new TradType\CustomSecurityHeaderType();
                $request->RequesterCredentials->eBayAuthToken = $this->config['production']['userToken'];
                $request->DetailLevel = array('ReturnAll');
                $request->OutputSelector = array(
                    'CategoryArray.Category.CategoryID',
                    'CategoryArray.Category.CategoryParentID',
                    'CategoryArray.Category.CategoryLevel',
                    'CategoryArray.Category.CategoryName'
                );
                $catconfig[$name][$key] = $service->getCategories($request);
            }
        }
        $toCache = $this->convertToSimpleArray($catconfig);
        Yii::$app->getCache()->set('Lolcategory', $toCache, $this->cacheTime);
        return $toCache;
    }

    private function convertToSimpleArray($categoryArray) {
        $i = 0;

        $simpleArray = [
            'cats' => [
            ],
            'brands' => [
                ''=>''
            ]
        ];

        foreach($categoryArray as $sections) {
            foreach($sections as $subsection) {
                $i++;
                if($i%2==0) {
                    //Будем перебирать брэнды
                    foreach($subsection->CategoryArray->Category as $category) {
                        array_push($simpleArray['brands'], [$category->CategoryID, $category->CategoryName, $category->CategoryLevel]);
                    }
                }else{
                    //Будем перебирать категории
                    foreach($subsection->CategoryArray->Category as $category) {
                        array_push($simpleArray['cats'], [$category->CategoryID, $category->CategoryName, $category->CategoryLevel]);
                    }
                }
            }
        }
        return $simpleArray;
    }

    private function getCategoryConfig() {
        $categorys = [
            "auto" => [
                "autocat" => "6030",
                "autobrend" => "6001",
            ],
            "moto" => [
                "motocat" => "10063",
                "motobrend" => "6024",
            ],
            "snow" => [
                "snowcat" => "100448",
                "snowbrend" => "42595",
            ],
            "atv" => [
                "atvcat" => "43962",
                "atvbrend" => "6723",
            ],
        ];
        return $categorys;
    }
}
