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
use \DTS\eBaySDK\Shopping\Services as ShopSer;
use \DTS\eBaySDK\Shopping\Types as ShopType;
use \DTS\eBaySDK\Shopping\Enums as ShopEnums;

class EbayForm extends Model
{
    const USD = 840;
    private $cacheTime = 2678400;
    public $emptyResponse = false;
    public $queryText = '';
    public $queryTextShow = '';
    public $queryCategory = null;
    public $queryMinPrice;
    public $queryMaxPrice;
    public $querySort = 2;
    public $querySortShipping;
    public $queryFilterRoot;
    public $queryBrand = null;
    public $queryModel = null;
    public $queryState;
    public $queryPage = 1;
    public $pageCount;
    public $singleItemId;
    private $config;
    private $queryHash;


    public function __construct()
    {
        $this->initConfig();
    }

    /**
     * @param mixed $queryCategory
     */
    public function setQueryCategory($queryCategory)
    {
        $this->queryCategory = $queryCategory;
    }

    /**
     * @param mixed $queryTextShow
     */
    public function setQueryTextShow($queryTextShow)
    {
        $this->queryTextShow = $queryTextShow;
    }

    /**
     * @param mixed $queryText
     */
    public function setQueryText($queryText)
    {
        $this->queryText = trim($queryText);
    }

    /**
     * @param mixed $querySort
     */
    public function setQuerySort($querySort)
    {
        $this->querySort = $querySort;
    }

    /**
     * @param mixed $queryBrand
     */
    public function setQueryBrand($queryBrand)
    {
        $this->queryBrand = trim($queryBrand);
    }

    /**
     * @param mixed $queryModel
     */
    public function setQueryModel($queryModel)
    {
        $this->queryModel = trim($queryModel);
    }

    /**
     * @param int $queryPage
     */
    public function setQueryPage($queryPage)
    {
        $this->queryPage = $queryPage;
    }



    public function rules()
    {
        return [
            [['queryText', 'queryFilterRoot', 'queryBrand', 'queryModel', 'querySort', 'queryCategory', 'queryPage', 'singleItemId'], 'default'],
        ];
    }

    private function initConfig()
    {
        $this->config = require __DIR__ . '/../../configuration.php';
        if(!$this->isPriceConfigInit()) {
            $this->initPriceConfig();
        } else {
            $this->updatePriceConfig();
        }
    }

    private function genMd5Hash()
    {
        $this->queryHash = md5(strtolower($this->queryBrand . $this->queryModel . $this->queryText) . $this->queryCategory . $this->queryState . $this->queryMaxPrice . $this->queryMinPrice . $this->queryPage . $this->querySort);
    }

    private function getItemsFromDB()
    {
        $h = Hash::findOne(['hash' => $this->queryHash]);
        if ($h == false) {
            return false;
        }
        $this->pageCount = $h->page_count;
        switch($this->querySort) {
            case 0: //Включена сортировка по возрастанию
                return $h->itemsWithOrderBy;
            case 1: //Включена сортировка по убыванию
                return $h->getItemsWithOrderBy('price_shipping_sum', SORT_ASC)->all();
        }
        $resp = $h->items;
        return $resp;
    }

    private function removeOldRecords()
    {
        $oldHashes = Hash::find()->where('life_time < (NOW() - interval 1 HOUR )')->all();
        foreach ($oldHashes as $oldHash) {
            $oldHash->delete();
        }
    }

    private function replaseSimbols($query) {
        return htmlspecialchars($query, ENT_QUOTES);
    }

    public function getItems()
    {
        $this->genMd5Hash();
        $this->removeOldRecords();
        $items = $this->getItemsFromDB();
        if ($items !== false) {
            return $items;
        }
        $service = new Services\FindingService(array(
            'appId' => $this->config['production']['appId'],
            'apiVersion' => $this->config['findingApiVersion'],
            'globalId' => Constants\GlobalIds::US,
        ));
        $request = new Types\FindItemsAdvancedRequest();
        $request->keywords = $this->replaseSimbols(strtolower($this->queryBrand .' '. $this->queryModel .' '. $this->queryText));
        if (!empty($this->queryCategory)) {
            $request->categoryId = array($this->queryCategory);
        } else {
            $request->categoryId = array('6030');
        }
        $itemFilter = new Types\ItemFilter();
        $itemFilter->name = 'ListingType';
        $itemFilter->value[] = 'AuctionWithBIN';
        $itemFilter->value[] = 'FixedPrice';
        $request->itemFilter[] = $itemFilter;

        if (isset($this->queryMinPrice)) {
            $request->itemFilter[] = new Types\ItemFilter(array(
                'name' => 'MinPrice',
                'value' => array($this->queryMinPrice)
            ));
        }
        if (isset($this->queryMaxPrice)) {
            $request->itemFilter[] = new Types\ItemFilter(array(
                'name' => 'MaxPrice',
                'value' => array($this->queryMaxPrice)
            ));
        }
        switch($this->querySort) {
            case 0:
                $request->sortOrder = 'PricePlusShippingHighest';
                break;
            case 1:
                $request->sortOrder = 'PricePlusShippingLowest';
                break;
            case 2:
                $request->sortOrder = 'BestMatch';
                break;
        }
        $request->paginationInput = new Types\PaginationInput();
        $request->paginationInput->entriesPerPage = 100;
        $request->paginationInput->pageNumber = $this->queryPage;
        $response = $service->findItemsAdvanced($request);
        if ($response->ack !== 'Failure') {
            $this->pageCount = (int)$response->paginationOutput->totalPages;
            $arrayresp = $response->toArray();
            if (!$this->isFill($arrayresp)) {
                $this->emptyResponse = true;
                return false;
            }
            $this->addToBD($arrayresp);

            return $this->getItemsFromDB();
        } else {
            return false;
        }
    }

    private function isFill($arrayresp)
    {
        return !empty($arrayresp['searchResult']['item']);
    }

    private function addToBD($ebayResponse)
    {
        date_default_timezone_set('Europe/Moscow');

        $today = date("YmdHis");
        $hash = new Hash();
        $hash->hash = $this->queryHash;
        $hash->life_time = $today;
        $hash->page_count = $this->pageCount;
        $hash->page = $this->queryPage;
        if($hash->save() == false) {
            $hash = Hash::findOne(['hash' => $this->queryHash]);
            $hash->hash = $this->queryHash;
            $hash->life_time = $today;
            $hash->page_count = $this->pageCount;
            $hash->page = $this->queryPage;
            $hash->update();
        }
        $hashID = $hash->id;
        foreach ($ebayResponse['searchResult']['item'] as $itemEbay) {
            $ebay_item = Item::findOne([
                'ebay_item_id' => $itemEbay['itemId'],
            ]);
            if (!empty($ebay_item->ebay_item_id)) {
                continue;
            }
            $item = new Item();
            $item->ebay_item_id = $itemEbay['itemId'];
            $item->title = $itemEbay['title'];
            $item->categoryId = $itemEbay['primaryCategory']['categoryId'];
            $item->categoryName = $itemEbay['primaryCategory']['categoryName'];
            if (isset($itemEbay['galleryURL'])) {
                $item->galleryURL = $itemEbay['galleryURL'];
            }
            $item->viewItemURL = $itemEbay['viewItemURL'];
            $item->sellingState = $itemEbay['sellingStatus']['sellingState'];
            $item->timeLeft = $itemEbay['sellingStatus']['timeLeft'];
            $item->current_price_value = $itemEbay['sellingStatus']['convertedCurrentPrice']['value'];
            $item->condition_id = $itemEbay['condition']['conditionId'];
            $item->condition_display_name = $itemEbay['condition']['conditionDisplayName'];
            $item->shipping_service_cost = $this->calculateShipping($itemEbay);
            $item->price_shipping_sum = $this->calculateValidPrice($itemEbay, $item->shipping_service_cost);
            $item->save();
            $itemID = $item->id;
            $links = new Links();
            $links->itemId = $itemID;
            $links->hashId = $hashID;
            $links->save();
        }
    }

    public function getSingleItem($ebay_item_id)
    {
        return Item::find()->where(['ebay_item_id' => $ebay_item_id])->asArray()->all();
    }

    public function getItemImages($ebay_item_id) {
        return ImageGallery::find()->where(['ebay_item_id' => $ebay_item_id])->asArray()->all();
    }

    public function getCategories()
    {
        if (EbayCategory::find()->count() > 0){
            return true;
        }
        $service = new TradSer\TradingService(array(
            'apiVersion' => $this->config['tradingApiVersion'],
            'siteId' => Constants\SiteIds::US
        ));
        $catconfig = $this->getCategoryConfig();
        $i = 1;
        foreach ($catconfig as $name => $cat) {
            foreach ($cat as $key => $value) {
                $request = new TradType\GetCategoriesRequestType();
                if($i%2==0) {
                    $request->CategorySiteID = '0';
                }else{
                    $request->CategorySiteID = '215';
                }
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
                $cats = $service->getCategories($request)->toArray();
                $this->addCatsToDB($cats, $value);
                $i++;
            }
        }
    }

    private function getCategoryConfig()
    {
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

    private function addCatsToDB($cats, $rootParent)
    {
        foreach ($cats['CategoryArray']['Category'] as $row) {
            $ebay_cat = EbayCategory::findOne([
                'category_id' => $row['CategoryID'],
            ]);
            if (!empty($ebay_cat->category_id)) {
                continue;
            }
            $ebaycategory = new EbayCategory();
            $ebaycategory->category_id = $row['CategoryID'];
            $ebaycategory->category_parent_id = $row['CategoryParentID'][0];
            $ebaycategory->category_level = $row['CategoryLevel'];
            $ebaycategory->category_name = $row['CategoryName'];
            $ebaycategory->category_root_parent = $rootParent;
            $ebaycategory->save();
        }
    }

    private function calculateValidPrice($itemEbay, $shipping)
    {
        $priceConfig = PriceConfig::findOne(1);
        if ($priceConfig->use_price_politic) {
            $price = ($itemEbay['sellingStatus']['convertedCurrentPrice']['value'] + $shipping) * $priceConfig->price_percent * $priceConfig->current_usd_exchange_rate  * $priceConfig->price_coefficient ;
            return ceil($price / 10) * 10;
        } else {
            $price = $itemEbay['sellingStatus']['convertedCurrentPrice']['value'] + $shipping;
            return ceil($price / 10) * 10;
        }
    }

    private function calculateShipping($itemEbay)
    {
        if (array_key_exists('shippingServiceCost', $itemEbay['shippingInfo'])) {
            return $itemEbay['shippingInfo']['shippingServiceCost']['value'];
        } else {
            return 0;
        }
    }

    public function checkDataAboutSingleItem($ebayitemid)
   {
       $this->getSingleItemFromEbay($ebayitemid);
    }

    private function getSingleItemFromEbay($ebayitemid)
    {
        $this->queryText = $ebayitemid;
        $this->getItems();
        if(!ImageGallery::find()->where(['ebay_item_id'=>$ebayitemid])->exists()) {
            $service = new ShopSer\ShoppingService(array(
                'apiVersion' => $this->config['shoppingApiVersion'],
                'appId' => $this->config['production']['appId']
            ));
            $request = new ShopType\GetSingleItemRequestType();
            $request->ItemID = $ebayitemid;
            $request->IncludeSelector = 'Details';
            $response = $service->getSingleItem($request);
            if ($response->Ack !== 'Failure') {
                $res = $response->toArray();
                $this->addImagesToDB($res, $ebayitemid);
            }
        }
    }

    public function addImagesToDB($item, $ebayitemid)
    {
        if (array_key_exists('PictureURL', $item['Item'])) {
            foreach ($item['Item']['PictureURL'] as $field) {
                $image = new ImageGallery();
                $image->ebay_item_id = $ebayitemid;
                $image->image_url = $field;
                $image->save();
            }
        }
    }

    private function getCurrentRate($int)
    {
        $content = $this->get_content();
        $value = '';
        $pattern = "#<Valute ID=\"([^\"]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)#i";
        preg_match_all($pattern, $content, $out, PREG_SET_ORDER);
        foreach($out as $cur)
        {
            if($cur[2] == $int) return (double)str_replace(",",".",$cur[4]);
        }
    }

    private function get_content() {
        $date = date("d/m/Y");
        $link = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=$date";
        $fd = fopen($link, "r");
        $text="";
        if (!$fd) echo "Запрашиваемая страница не найдена";
        else {
            while (!feof ($fd)) $text .= fgets($fd, 4096);
        }
        fclose ($fd);
        return $text;
    }

    private function isPriceConfigInit() {
        return PriceConfig::find()->where(['id' => 1])->exists();
    }

    private function initPriceConfig() {
        $priceConfig = new PriceConfig();
        $priceConfig->use_price_politic = true;
        $priceConfig->price_coefficient = EbayConst::$priceCoefficient;
        $priceConfig->price_percent = EbayConst::$pricePercent;
        $priceConfig->current_usd_exchange_rate = $this->getCurrentRate($this::USD);
        $priceConfig->last_update_time = date("Ymd");
        $priceConfig->save();
    }

    private function updatePriceConfig() {
        $oldRecords = PriceConfig::find()->where('last_update_time < (NOW() - interval 1 DAY )')->all();
        foreach ($oldRecords as $oldRecord) {
            $oldRecord->current_usd_exchange_rate = $this->getCurrentRate($this::USD);
            $oldRecord->last_update_time = date("Ymd");
            $oldRecord->update();
        }
    }
}