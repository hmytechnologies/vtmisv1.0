$.fn.extend({
    treed: function (o) {
        
        var openedClass = 'glyphicon-folder-open';
        var closedClass = 'glyphicon-folder-open';
        /*
        if (typeof o != 'undefined') {
            if (typeof o.openedClass != 'undefined') {
                openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined') {
                closedClass = o.closedClass;
            }
        };
        */
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
       
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function () {
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
        
        //fire event to open branch if the li contains an anchor instead of text
        /*
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        */
    }
});

//Initialization of treeviews

$('#tree1').treed();



$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});



$("#tree1").click(function(){
$("#hr").click(function (){
    $(".s").show();
});
    $("#pr").click(function () {
        $("s").show();
    });
});



$('#tree1').click(function (e) {
    e.stopPropagation();
});





var hidWidth;
var scrollBarWidths = 40;

var widthOfList = function () {
    var itemsWidth = 0;
    $('.list li').each(function () {
        var itemWidth = $(this).outerWidth();
        itemsWidth += itemWidth;
    });
    return itemsWidth;
};

var widthOfHidden = function () {
    return (($('.wrapper').outerWidth()) - widthOfList() - getLeftPosi()) - scrollBarWidths;
};

var getLeftPosi = function () {
    return $('.list').position().left;
};

var reAdjust = function () {
    if (($('.wrapper').outerWidth()) < widthOfList()) {
        $('.scroller-right').show();
    }
    else {
        $('.scroller-right').hide();
    }

    if (getLeftPosi() < 0) {
        $('.scroller-left').show();
    }
    else {
        $('.item').animate({ left: "-=" + getLeftPosi() + "px" }, 'slow');
        $('.scroller-left').hide();
    }
}

reAdjust();

$(window).on('resize', function (e) {
    reAdjust();
});

$('.scroller-right').click(function () {

    $('.scroller-left').fadeIn('slow');
    $('.scroller-right').fadeOut('slow');

    $('.list').animate({ left: "+=" + widthOfHidden() + "px" }, 'slow', function () {

    });
});

$('.scroller-left').click(function () {

    $('.scroller-right').fadeIn('slow');
    $('.scroller-left').fadeOut('slow');

    $('.list').animate({ left: "-=" + getLeftPosi() + "px" }, 'slow', function () {

    });
});


