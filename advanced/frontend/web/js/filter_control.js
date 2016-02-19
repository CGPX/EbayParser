
var FilterControl = {
    category: 0,
    page: 1,
    sort: 0,
    brand: '',
    model: '',
    text: '',
    isNeedToChangeCategory: false,
    rootCategory: 0,
    currentCategory: 0,

    getParamsFromHref: function() {
        addressData     = window.location.href;
        this.category   = this.getValueFromArray(addressData.match(/category=([0-9]*)/i));
        this.page       = this.getValueFromArray(addressData.match(/page=([0-9]*)/i));
        this.sort       = this.getValueFromArray(addressData.match(/sort=([0-9])/i));
        this.brand      = this.getValueFromArray(addressData.match(/brand=([-_a-zA-Z0-9\s]+)/i));
        this.model      = this.getValueFromArray(addressData.match(/model=([-_a-zA-Z0-9\s]+)/i));
        this.text       = this.getValueFromArray(addressData.match(/text=([%&-_a-zA-Zа-яА-Я0-9\s.]+)/i));
        if(this.category === '') {
            this.category = 6030;
        }
        if(this.page === '') {
            this.page = 1;
        }
        if(this.sort === '') {
            this.sort = 0;
        }
    },

    getValueFromArray: function(param) {
        if(param == null) {
            return '';
        } else {
          return param[1];
        }
    },

    setCurrentCategory: function(cat) {
        this.currentCategory = +cat;
    },

    resetAllFilters: function() {
        this.brand  = '';
        this.model  = '';
        this.sort   = 0;
    },

    makeURL: function () {
        myLink = '';
        myLink+='/items/category=' + this.category + '&page=' + this.page + '&sort=' + this.sort;
        if(this.brand !== '') {
            myLink += '&brand=' + this.brand;
        }
        if(this.model !== '') {
            myLink += '&model=' + this.model;
        }
        if(this.text !== '') {
            myLink += '&text=' + this.text;
        }
        window.location.href = myLink;
    },

    addFilterParams: function() {
        this.brand = $(".filter_box .filter_brands :selected").text();
        this.model = $(".filter_box .filter_models :selected").text();
        this.sort  = +$(".filter_box .filter_sort :selected").val();
    },

    update: function() {
        this.category = this.currentCategory;
        FilterControl.makeURL();
    },

    setPage: function(page) {
        this.page = +page;
    },

    setQueryText: function(text) {
        this.text = text;
    },

    categoryAction: function() {
        $(".catChange").removeClass("active"),
            $(this).addClass("active");
        categoryTargetId    = +$(this).attr('data-target').substring(1);
        categoryRootId      = +$(this).attr('data-root');
        rootIdFromAddress = +$("[data-target=#"+FilterControl.category+"]").data("root");

        if(categoryRootId !== rootIdFromAddress) {
            FilterControl.resetAllFilters();
            FilterControl.setCurrentCategory(categoryTargetId);
            FilterControl.update();
        } else {
            FilterControl.setCurrentCategory(categoryTargetId);
            FilterControl.update();
        }
    },

    filterAction: function() {
        rootIdFromAddress = $("[data-target=#"+FilterControl.category+"]").data("root");
        rootIdFromFilter = $(".filter_box .filter_ts :selected").data("root");

        if(rootIdFromAddress !== rootIdFromFilter) {
            FilterControl.resetAllFilters();
            FilterControl.setCurrentCategory(rootIdFromFilter);
            FilterControl.update();
        }else{
            FilterControl.addFilterParams();
            FilterControl.setCurrentCategory(FilterControl.category);
            FilterControl.update();
        }
    },

    pageAction: function() {
       FilterControl.setPage($(this).attr('data-target'));
        FilterControl.makeURL();
    },

    queryTextAction: function() {
        FilterControl.setQueryText($("#ebayform-querytext").val());
        FilterControl.makeURL();
    }

};
FilterControl.getParamsFromHref();
