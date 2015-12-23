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
    public $queryPage = 1;
    public $pageCount;
    public $singleItemId;
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
            [['queryText', 'queryCategory', 'queryPage', 'singleItemId'], 'default'],
        ];
    }

    private function initConfig() {
        $this->config = require __DIR__ . '/../../configuration.php';
    }

    private function genMd5Hash() {
        $this->queryHash = md5(strtolower($this->queryText).$this->queryCategory.strtolower($this->queryBrand).$this->queryState.strtolower($this->querySort).$this->queryMaxPrice.$this->queryMinPrice.$this->queryPage);
    }

    private function getItemsFromDB() {
        $h = Hash::findOne(['hash' => $this->queryHash,'page'=>$this->queryPage]);
        if($h==false) {
            return false;
        }
        $this->pageCount=$h->page_count;
        $resp =  $h->items;
        return $resp;
    }

    private function removeOldRecords() {
        $oldHashes = Hash::find()->where('life_time < (NOW() - interval 1 HOUR )')->all();
        foreach($oldHashes as $oldHash) {
            $oldHash->delete();
        }
    }

    public function getItems() {
        $this->genMd5Hash();
        $this->removeOldRecords();
        $items = $this->getItemsFromDB();
        if($items !== false) {
            return $items;
        }
        $service = new Services\FindingService(array(
            'appId' => $this->config['production']['appId'],
            'apiVersion' => $this->config['findingApiVersion'],
            'globalId' => Constants\GlobalIds::US,
        ));
        $request = new Types\FindItemsAdvancedRequest();
        $request->keywords = strtolower($this->queryText);
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
        if(isset($this->queryMinPrice)) {
            $request->itemFilter[] = new Types\ItemFilter(array(
                'name' => 'MinPrice',
                'value' => array($this->queryMinPrice)
            ));
        }
        if(isset($this->queryMaxPrice)) {
            $request->itemFilter[] = new Types\ItemFilter(array(
                'name' => 'MaxPrice',
                'value' => array($this->queryMaxPrice)
            ));
        }
        $request->sortOrder = 'CurrentPriceHighest';
//        if(empty($this->querySort)) {
//            $request->sortOrder = $this->querySort;
//        }
        $request->paginationInput = new Types\PaginationInput();
        $request->paginationInput->entriesPerPage = 100;

            $request->paginationInput->pageNumber = $this->queryPage;

        $response = $service->findItemsAdvanced($request);
        if ($response->ack !== 'Failure') {
            $this->pageCount = (int) $response->paginationOutput->totalPages;
            $arrayresp = $response->toArray();
            $this->addToBD($arrayresp);
            return $this->getItemsFromDB();
        }else{
            return false;
        }
    }

    private function addToBD($ebayResponse) {
        date_default_timezone_set('Europe/Moscow');

        $today = date("YmdHis");
        $hash = new Hash();
        $hash->hash = $this->queryHash;
        $hash->life_time = $today;
        $hash->page_count = $this->pageCount;
        $hash->page = $this->queryPage;
        $hash->save();
        $hashID = $hash->id;
        foreach($ebayResponse['searchResult']['item'] as $itemEbay) {
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
            if(EbayConst::$usePricePolitic) {
                $item->current_price_value = $itemEbay['sellingStatus']['convertedCurrentPrice']['value'] * EbayConst::$currentUSDExchangeRate * EbayConst::$priceCoefficient;
            }else{
                $item->current_price_value  = $itemEbay['sellingStatus']['convertedCurrentPrice']['value'];
            }
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

    public function getSingleItem() {
        if(isset($this->singleItemId)){
            return Item::find()->where(['ebay_item_id' => $this->singleItemId])->asArray()->all();
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
