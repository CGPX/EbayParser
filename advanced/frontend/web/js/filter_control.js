
var FilterControl = {
    category: 0,
    page: 1,
    sort: 0,
    brand: "",
    model: '',
    text: '',


    getParamsFromHref: function() {
      this.brandSelect = $("select#ebayform-queryfilterroot");
        FilterControl.getDataMotherFucka(this.brandSelect);
    },

    getDataMotherFucka: function(sel) {
        hui = $("select#ebayform-querybrand");
        $.post("/getCats/"+sel.val(), function(data) {
            hui.html(data);
            hui.prepend('<option value="">Выберите марку</option>');
            $("select#ebayform-querybrand option").filter('[value="Audi"]').attr("selected", "selected")
    });
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
        linkParams = '';

        if(this.brand !== '') {
            linkParams += this.brand + '/';
        }
        if(this.model !== '') {
            linkParams += this.model + '/';
        }
        if(this.text !== '') {
            linkParams += '&text=' + this.text;
        }
        myLink+='/category/' + this.category + '/' + linkParams ;//+ '&page=' + this.page + '&sort=' + this.sort;
        window.location.href = myLink;
    },

    addFilterParams: function() {
        this.brand = $(".filter_box .filter_brands :selected").text();
        localStorage.setItem('brand', JSON.stringify(this.brand));
        this.model = $(".filter_box .filter_models :selected").text();
        localStorage.setItem('model', JSON.stringify(this.model));
        this.sort  = +$(".filter_box .filter_sort :selected").val();
        localStorage.setItem('sort', JSON.stringify(this.sort));
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
    setRootCategory: function(id) {
        this.rootCategory = id;
    },

    getRootCategory: function(catId) {
        jQuery.ajax({
            url: '/root', type: 'POST', dataType: 'json', cache: false, async: false,
            data: {catId: catId},
            beforeSend: function(jqXHR, settings) {  },
            success: function(data, textStatus, jqXHR) {
                if (typeof(data.success) !== 'undefined') {
                    FilterControl.setRootCategory(+data.category)
            } },
            complete: function(jqXHR, textStatus) { },
            error: function(jqXHR, textStatus, errorThrown) {  }
        });

    },

    categoryAction: function() {
        $(".catChange").removeClass("active"),
            $(this).addClass("active");
        categoryTargetId    = +FilterControl.getRootCategory(6030);
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
        FilterControl.getRootCategory(+FilterControl.category);

        rootIdFromAddress = FilterControl.rootCategory;
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
window.onload = FilterControl.getParamsFromHref;

