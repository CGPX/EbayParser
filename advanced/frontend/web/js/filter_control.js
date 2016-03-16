
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
        this.category   = this.getValueFromLS(localStorage.getItem('category'));
        this.page       = this.getValueFromLS(localStorage.getItem('page'));
        this.sort       = this.getValueFromLS(localStorage.getItem('sort'));
        this.brand      = this.getValueFromLS(localStorage.getItem('brand'));
        this.model      = this.getValueFromLS(localStorage.getItem('model'));
        this.text       = this.getValueFromLS(localStorage.getItem('text'));
        if(this.category === '') {
            this.category = 6030;
        }
        if(this.page === '') {
            this.page = 1;
        }
        if(this.sort === '') {
            this.sort = 2;
        }
    },

    getValueFromLS: function(param) {
        if(param == null) {
            return '';
        } else {
          return param;
        }
    },

    setCurrentCategory: function(cat) {
        localStorage.setItem('category', JSON.stringify(+cat));
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
        localStorage.setItem('page', JSON.stringify(+page));
        this.page = +page;
    },

    setQueryText: function(text) {
        localStorage.setItem('text', JSON.stringify(text));
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
