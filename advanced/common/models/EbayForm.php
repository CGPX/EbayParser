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
    private $queryHash;
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

    private function genMd5Hash() {
        $this->queryHash = md5($this->queryText.$this->queryCategory.$this->queryBrand.$this->queryState.$this->querySort.$this->queryMaxPrice.$this->queryMinPrice);
    }

    private function getItemsFromDB() {
        $oneHash = Hash::findOne([
            'hash' => $this->queryHash,
        ]);
        $h = Hash::findOne($oneHash->id);
        if(empty($h)) {
            return false;
        }
        $resp =  $h->items;
        return $resp;
    }

    public function getItems() {
        $this->genMd5Hash();
        $items = $this->getItemsFromDB();
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
            $this->addToBD($arrayresp);
            return $this->getItemsFromDB();
        }
    }

    private function addToBD($ebayResponse) {
        $today = date("Ymd");
        $hash = new Hash();
        $hash->hash = $this->queryHash;
        $hash->life_time = $today;
        $hash->save();
        $hashID = $hash->id;
        foreach($ebayResponse['searchResult']['item'] as $itemEbay){
            $ebay_item = Item::findOne([
                'ebay_item_id' => $itemEbay['itemId'],
            ]);
            if(!empty($ebay_item->ebay_item_id)){
                continue;
            }
            $item = new Item();
            $item->ebay_item_id         = $itemEbay['itemId'];
            $item->title                = $itemEbay['title'];
            $item->categoryId           = $itemEbay['primaryCategory']['categoryId'];
            $item->categoryName         = $itemEbay['primaryCategory']['categoryName'];
            $item->galleryURL           = $itemEbay['galleryURL'];
            $item->viewItemURL          = $itemEbay['viewItemURL'];
            $item->current_price_value  = $itemEbay['sellingStatus']['currentPrice']['value'];
            $item->sellingState         = $itemEbay['sellingStatus']['sellingState'];
            $item->timeLeft             = $itemEbay['sellingStatus']['timeLeft'];
            $item->save();
            $itemID = $item->id;

            $links = new Links();
            $links->itemId = $itemID;
            $links->hashId = $hashID;
            $links->save();
        }
    }

    public function getCategories() {
        // удаление кеша
        //Yii::$app->getCache()->delete('Lolcategory');

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
                $catconfig[$name][$key] = $service->getCategories($request)->toArray();
            }
        }
        Yii::$app->getCache()->set('Lolcategory', $catconfig, $this->cacheTime);
        return $catconfig;
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
                        array_push($simpleArray['brands'], [$category->CategoryID, $category->CategoryName, $category->CategoryLevel,$category->CategoryParentID]);
                    }
                }else{
                    //Будем перебирать категории
                    foreach($subsection->CategoryArray->Category as $category) {
                        array_push($simpleArray['cats'], [$category->CategoryID, $category->CategoryName, $category->CategoryLevel,$category->CategoryParentID]);
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
